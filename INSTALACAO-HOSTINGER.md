# Guru do Desconto — Instalação na Hostinger

Site WordPress para reviews de afiliados e promoções do Mercado Livre, Shopee e Amazon.

## Estrutura

```
public_html/                    ← Raiz do site (upload na Hostinger)
├── content/
│   └── reviews/                  ← Arquivos .html dos reviews (OBRIGATÓRIO)
├── wp-content/
│   ├── themes/guru-do-desconto/  ← Tema personalizado
│   ├── plugins/pixelyoursite/    ← Meta Pixel + CAPI
│   ├── plugins/google-site-kit/  ← GA4 + Search Console
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

### 6. Configurar WhatsApp, Site Kit e PixelYourSite

1. **Aparência → Personalizar → Guru do Desconto** → cole o **link do grupo WhatsApp**
2. **Plugins** → confirme que estão ativos:
   - **Site Kit by Google** (`wp-content/plugins/google-site-kit/`)
   - **PixelYourSite** (`wp-content/plugins/pixelyoursite/`)
   > Na primeira visita ao `/wp-admin`, o mu-plugin `guru-bundled-plugins.php` ativa ambos automaticamente.
3. **Site Kit** → **Iniciar configuração** → conecte Search Console + GA4
4. **PixelYourSite** → **Dashboard** → cole o **Meta Pixel ID** e ative o Facebook Pixel
   - Opcional: ative **Conversions API (CAPI)** no PYS para melhor atribuição em campanhas Meta
   - **Desative** o Meta Pixel no Personalizar do tema (evita duplicar o código base)
5. Os eventos do tema (`Lead`, `Contact`, `WhatsAppClick` nos cliques) continuam funcionando via `tracking.js` enquanto o PYS carrega o `fbq`

> Erro *"not a valid JSON response"*? Vá em **Configurações → Links permanentes → Nome do post → Salvar**.

### 7. SSL e URLs canônicas (Google Search)

**URL canônica do site:** `https://gurudodesconto.com.br` (HTTPS, **sem** `www`)

1. hPanel → **SSL** → ative certificado gratuito
2. **Configurações → Gerais** → ambos os campos devem ser exatamente:
   - `https://gurudodesconto.com.br`
   - **Não** use `http://` nem `https://www.`
3. O `.htaccess` e o mu-plugin `guru-canonical-url.php` redirecionam automaticamente:
   - `http://` → `https://`
   - `https://www.` → `https://` (sem www)
4. Limpe o cache da Hostinger após o deploy

**Search Console — “Página com redirecionamento”**

É **normal** que estas URLs apareçam como *não indexadas* (elas redirecionam de propósito):

| URL | O que acontece |
|-----|----------------|
| `http://gurudodesconto.com.br/` | Redireciona para `https://gurudodesconto.com.br/` |
| `https://www.gurudodesconto.com.br/` | Redireciona para `https://gurudodesconto.com.br/` |

A página indexada deve ser **`https://gurudodesconto.com.br/`**. Após o deploy:

1. Search Console → o problema → **Validar correção**
2. Inspeção de URL → cole `https://gurudodesconto.com.br/` → **Solicitar indexação**
3. Confirme o sitemap: `https://gurudodesconto.com.br/wp-sitemap.xml`

### 8. Google Search Console (via Site Kit)

O Site Kit conecta o Search Console automaticamente. Depois:

1. No Site Kit → verifique se o Search Console está conectado
2. Confirme o sitemap: `https://seudominio.com.br/wp-sitemap.xml`

## Adicionar reviews

1. Crie ou edite um arquivo em `content/reviews/meu-review.html`
2. Faça deploy para a Hostinger (pasta `content/reviews/` na raiz)
3. Sincronização automática na próxima visita, em até 15 min, ou via webhook POST `/wp-json/guru/v1/sync-reviews`

## SEO incluído

- Schema.org (Organization, Product, Review, FAQPage, ItemList)
- Open Graph e Twitter Cards
- Sitemap XML com reviews
- Meta descriptions por página
- Links afiliados com `nofollow sponsored`
- GZIP, cache e preload de imagens

## Rastreamento e conversões (UTM, GA4, Meta)

### UTMs em links de afiliado

Todos os botões `.btn-affiliate` recebem automaticamente:

| Parâmetro | Padrão | Exemplo com Google Ads |
|-----------|--------|------------------------|
| `utm_source` | `gurudodesconto` | `google` (se o visitante veio de anúncio) |
| `utm_medium` | `review` | `cpc` |
| `utm_campaign` | `review_{slug}` | `review_airfryer` |
| `utm_content` | `sidebar`, `product_1`, `winner_cta`, etc. | conforme campanha |

Quando alguém chega com UTMs na URL (`?utm_source=google&utm_medium=cpc&utm_campaign=review_airfryer`), esses valores são **repassados** aos cliques em links de afiliado por 24h.

Reviews gerados pelo n8n também incluem UTMs no HTML commitado no GitHub.

### Configurar no WordPress

**Aparência → Personalizar → Guru do Desconto:**

| Campo | Uso |
|-------|-----|
| Google Analytics ID (`G-...`) | GA4 + eventos `affiliate_click` e `whatsapp_click` |
| Google Ads ID (`AW-...`) | Tag opcional para vincular Ads ao GA4 |
| Meta Pixel ID | Remarketing Facebook/Instagram |
| UTM padrão source/medium | Fallback quando não há campanha na URL |

> Se usar **Site Kit** para GA4, deixe o campo GA vazio no tema para não duplicar a tag. Os eventos de clique funcionam desde que `gtag` esteja carregado (Site Kit ou tema).

### Marcar conversões no GA4

1. **GA4 → Administrar → Eventos**
2. Aguarde aparecer `affiliate_click` e `whatsapp_click` (ou crie eventos personalizados)
3. Marque ambos como **Conversões**

### Google Ads + GA4

1. No **Google Ads** → Ferramentas → **Vinculações** → vincule a propriedade GA4
2. Em campanhas, use URL final com UTMs, ex.:
   `https://gurudodesconto.com.br/reviews/melhor-air-fryer-2026/?utm_source=google&utm_medium=cpc&utm_campaign=review_airfryer&utm_content=ad1`
3. No GA4, analise **Aquisição → Aquisição de tráfego** e relatório de conversões por campanha

### Meta Pixel (Facebook / Instagram) — PixelYourSite

O plugin **PixelYourSite** (já incluído no projeto) gerencia o Pixel, PageView e CAPI.

**Pixel ID do site:** `1757914428796151` (já no `.env` como `GURU_META_PIXEL_ID` e nos defaults do plugin).

1. **Plugins → PixelYourSite** → ative se ainda não estiver
2. **PixelYourSite → Dashboard** → confirme o **Meta Pixel ID** `1757914428796151`
3. Ative **Conversions API** e cole o **Access Token** do Events Manager (obrigatório para CAPI server-side)
4. No WordPress: **Aparência → Personalizar → Guru do Desconto** → **desmarque** *Ativar Meta Pixel (tema)* (o PYS injeta o código base)

**Eventos extras do tema** (via `tracking.js`, sem duplicar o Pixel base):

| Evento Meta | Quando |
|-------------|--------|
| `Lead` | Clique em link de afiliado |
| `Contact` | Clique no botão WhatsApp |
| `AffiliateClick` | Evento customizado — clique afiliado |
| `WhatsAppClick` | Evento customizado — clique WhatsApp |

Configure `ViewContent` e `PageView` no painel do **PixelYourSite** conforme sua estratégia.

**Fallback (sem PYS):** use **Aparência → Personalizar → Meta Pixel ID** ou `.env`:
```env
GURU_META_PIXEL_ID=1757914428796151
```

**Validar:** extensão [Meta Pixel Helper](https://chromewebstore.google.com/detail/meta-pixel-helper/fdgfkebogiimcoedlicjlajpkdmockpc) no Chrome.

**Conversões no Ads:** Events Manager → marque `Lead`, `Contact` ou eventos customizados como conversões.

> Admins logados não disparam eventos do tema por padrão. Desmarque *“Não rastrear admins logados”* no Personalizar para testar logado.

### Microsoft Clarity

Heatmaps e gravações de sessão — útil para ver onde o visitante trava antes de entrar no grupo.

1. Crie um projeto em [clarity.microsoft.com](https://clarity.microsoft.com/)
2. **Project ID do site:** `xkd09nsyqn` (já no `.env` como `GURU_CLARITY_ID`)
3. No WordPress: **Aparência → Personalizar → Guru do Desconto** → confirme o **Microsoft Clarity Project ID**
4. Ou no `.env`:
```env
GURU_CLARITY_ID=xkd09nsyqn
```

O tema também envia eventos customizados `whatsapp_click` e `affiliate_click` para o Clarity (filtros no painel).

---

- **Site Kit by Google** — Analytics, Search Console, AdSense (já no projeto)
- **PixelYourSite** — Meta Pixel + CAPI (+ GA4/GTM opcional no plugin)
- **Microsoft Clarity** — heatmaps e gravações (via tema)
- **LiteSpeed Cache** (Hostinger LiteSpeed) — recomendado na produção
- **Really Simple SSL** — recomendado na produção
