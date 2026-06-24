# Guru do Desconto — Instalação na Hostinger

Site WordPress para reviews de afiliados e promoções do Mercado Livre, Shopee e Amazon.

## Estrutura

```
public_html/                    ← Raiz do site (upload na Hostinger)
├── content/
│   └── reviews/                  ← Arquivos .html dos reviews (OBRIGATÓRIO)
├── wp-content/
│   ├── themes/guru-do-desconto/  ← Tema personalizado
│   └── mu-plugins/guru-seo-boost.php ← SEO automático
├── .htaccess                   ← Cache, GZIP e rewrite
└── robots.txt                  ← Referência para crawlers
```

## Passo a passo

### 1. Banco de dados

No **hPanel** da Hostinger:

1. Vá em **Bancos de Dados MySQL** → crie um banco e um usuário
2. Anote: nome do banco, usuário, senha e host (geralmente `localhost`)

### 2. Upload dos arquivos

**Opção A — File Manager**

1. Compacte a pasta `public_html` em `.zip`
2. No hPanel → **Gerenciador de Arquivos** → pasta `public_html`
3. Envie o `.zip` e extraia

**Opção B — FTP**

1. Use FileZilla com credenciais FTP da Hostinger
2. Envie todo o conteúdo de `public_html/` para a raiz do domínio

### 3. Configurar variáveis de ambiente (IMPORTANTE — evita erro de conexão)

O `wp-config.php` lê as credenciais do banco do arquivo **`.env`** ou das variáveis do sistema.

**Na Hostinger:**

1. No hPanel: **Websites → Dashboard → Databases → Management**
2. Anote: nome do banco, usuário, senha (host geralmente é `localhost`)
3. No **Gerenciador de Arquivos** → raiz do site (`public_html`)
4. Copie `.env.example` para `.env`
5. Edite `.env` com os dados reais:

```env
DB_NAME=u123456789_seu_banco
DB_USER=u123456789_seu_usuario
DB_PASSWORD=sua_senha
DB_HOST=localhost
DB_TABLE_PREFIX=wp_
```

6. Salve e recarregue o site

> **Segurança:** o `.env` não vai para o Git. Nunca commite senhas.

Guia oficial Hostinger: [Como corrigir "Error establishing a database connection"](https://www.hostinger.com/tutorials/how-to-fix-error-establishing-a-database-connection-in-wordpress)

### 4. Instalar WordPress

1. Acesse `https://seudominio.com.br`
2. Complete a instalação (título: **Guru do Desconto**)
3. Faça login no painel `/wp-admin`

### 5. Ativar o tema

1. **Aparência → Temas** → ative **Guru do Desconto**
2. O tema cria automaticamente a página inicial

### 5b. Publicar reviews (content/reviews/*.html)

Os reviews **não** são criados manualmente no painel — vêm de arquivos HTML no repositório.

1. Envie a pasta **`content/reviews/`** para a **raiz do site** (mesmo nível de `wp-config.php`), com os arquivos `.html`
2. A sincronização é **automática**:
   - Na **próxima visita** ao site (admin ou público), se os arquivos mudaram
   - A cada **15 minutos** via cron do WordPress
   - Imediata via **webhook** (recomendado com n8n — ver abaixo)
3. Opcional: **Reviews → Sincronizar** força importação na hora
4. Acesse `/reviews/` no site para validar

**Webhook (n8n / automação)** — após gravar o `.html` na Hostinger:

```http
POST https://seudominio.com.br/wp-json/guru/v1/sync-reviews
X-Guru-Sync-Token: SEU_TOKEN_SECRETO
```

Defina no `.env`:
```env
GURU_REVIEW_SYNC_SECRET=um-token-longo-e-aleatorio
```

> Se aparecer "Nenhum review encontrado", a pasta `content/reviews/` provavelmente não foi enviada ou está no lugar errado.

### 6. Configurar WhatsApp e Google Site Kit

1. **Aparência → Personalizar → Guru do Desconto** → cole o **link do grupo WhatsApp**
2. **Plugins** → confirme que **Site Kit by Google** está ativo (`wp-content/plugins/google-site-kit/`)
3. Menu **Site Kit** → **Iniciar configuração** → conecte conta Google
4. Conecte **Search Console** e **Google Analytics (GA4)** pelo assistente do Site Kit

> Erro *"not a valid JSON response"*? Vá em **Configurações → Links permanentes → Nome do post → Salvar**.

### 7. SSL e URLs

1. hPanel → **SSL** → ative certificado gratuito
2. **Configurações → Gerais** → URLs com `https://`
3. Instale **Really Simple SSL** (recomendado)

### 8. Google Search Console (via Site Kit)

O Site Kit conecta o Search Console automaticamente. Depois:

1. No Site Kit → verifique se o Search Console está conectado
2. Confirme o sitemap: `https://seudominio.com.br/wp-sitemap.xml`

## Adicionar reviews

1. Crie ou edite um arquivo em `content/reviews/meu-review.html`
2. Faça deploy para a Hostinger (pasta `content/reviews/` na raiz)
3. Sincronização automática na próxima visita, em até 15 min, ou via webhook POST `/wp-json/guru/v1/sync-reviews`

## SEO incluído

- Schema.org (Organization, Product, Review)
- Open Graph e Twitter Cards
- Sitemap XML com reviews
- Meta descriptions por página
- Links afiliados com `nofollow sponsored`
- GZIP, cache e preload de imagens

## Plugins incluídos

- **Site Kit by Google** — Analytics, Search Console, AdSense (já no projeto)
- **LiteSpeed Cache** (Hostinger LiteSpeed) — recomendado na produção
- **Really Simple SSL** — recomendado na produção
