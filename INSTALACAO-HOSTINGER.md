# Guru do Desconto — Instalação na Hostinger

Site WordPress para reviews de afiliados e promoções do Mercado Livre, Shopee e Amazon.

## Estrutura

```
public_html/                    ← Raiz do site (upload na Hostinger)
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

### 3. Configurar wp-config.php (IMPORTANTE — evita erro de conexão)

O `wp-config.php` detecta automaticamente:
- **Docker local** → usa as credenciais do `docker-compose.yml`
- **Hostinger** → precisa do arquivo `wp-config-db.php`

**Na Hostinger:**

1. No hPanel: **Websites → Dashboard → Databases → Management**
2. Anote: nome do banco, usuário, senha (host geralmente é `localhost`)
3. No **Gerenciador de Arquivos** → raiz do site (`public_html`)
4. Copie `wp-config-db.php.example` para `wp-config-db.php`
5. Edite `wp-config-db.php` com os dados reais:

```php
define( 'DB_NAME', 'u123456789_seu_banco' );
define( 'DB_USER', 'u123456789_seu_usuario' );
define( 'DB_PASSWORD', 'sua_senha' );
define( 'DB_HOST', 'localhost' );
```

6. Salve e recarregue o site

Guia oficial Hostinger: [Como corrigir "Error establishing a database connection"](https://www.hostinger.com/tutorials/how-to-fix-error-establishing-a-database-connection-in-wordpress)

### 4. Instalar WordPress

1. Acesse `https://seudominio.com.br`
2. Complete a instalação (título: **Guru do Desconto**)
3. Faça login no painel `/wp-admin`

### 5. Ativar o tema

1. **Aparência → Temas** → ative **Guru do Desconto**
2. O tema cria automaticamente a página inicial, 3 reviews de exemplo e as categorias de marketplace

### 6. Configurar WhatsApp e SEO

1. **Aparência → Personalizar → Guru do Desconto**
2. Cole o **link do grupo WhatsApp** (`https://chat.whatsapp.com/...`)
3. Adicione o **Google Analytics ID** (GA4: `G-XXXXXXXX`)
4. Adicione o código do **Google Search Console**

### 7. SSL e URLs

1. hPanel → **SSL** → ative certificado gratuito
2. **Configurações → Gerais** → URLs com `https://`
3. Instale **Really Simple SSL** (recomendado)

### 8. Google Search Console

1. https://search.google.com/search-console
2. Verifique via meta tag (código no Personalizar)
3. Envie o sitemap: `https://seudominio.com.br/wp-sitemap.xml`

## Adicionar reviews com link de afiliado

1. **Reviews → Adicionar Novo**
2. Título, conteúdo e imagem destacada
3. Marketplace: Mercado Livre, Shopee ou Amazon
4. **Dados do Produto**: link de afiliado, preços, nota
5. **SEO**: meta description (até 160 caracteres)

## SEO incluído

- Schema.org (Organization, Product, Review)
- Open Graph e Twitter Cards
- Sitemap XML com reviews
- Meta descriptions por página
- Links afiliados com `nofollow sponsored`
- GZIP, cache e preload de imagens

## Plugins recomendados

- **Rank Math SEO** ou **Yoast SEO**
- **LiteSpeed Cache** (Hostinger LiteSpeed)
- **Smush** (imagens)
- **Really Simple SSL**
