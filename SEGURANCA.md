# Segurança — Guru do Desconto

## Já implementado no projeto

| Proteção | Onde |
|----------|------|
| Credenciais no `.env` (fora do Git) | `.env` + `wp-config.php` |
| Bloqueio de edição de código no painel | `DISALLOW_FILE_EDIT` |
| Limite de tentativas de login | `mu-plugins/guru-security.php` |
| XML-RPC desativado | `mu-plugins/guru-security.php` + `.htaccess` |
| Bloqueio `?author=1` (enumeração) | `mu-plugins/guru-security.php` |
| Headers de segurança (HSTS, X-Frame…) | `mu-plugins/guru-security.php` |
| PHP bloqueado em uploads | `wp-content/uploads/.htaccess` |
| `.env` e `wp-config.php` inacessíveis via web | `.htaccess` |

## Checklist Hostinger (faça agora)

### 1. SSL/HTTPS
- hPanel → **SSL** → ativar certificado gratuito
- No `.env` de produção: `WP_FORCE_SSL_ADMIN=true`
- Instalar plugin **Really Simple SSL**

### 2. Senhas fortes
- Admin WordPress: 16+ caracteres, única
- MySQL: senha gerada pelo hPanel
- Ativar **2FA** no hPanel da Hostinger

### 3. Atualizações automáticas
- Manter WordPress, temas e plugins atualizados
- hPanel → **WordPress → Segurança** (se disponível)

### 4. Backups
- hPanel → **Backups** → ativar backups automáticos
- Antes de cada alteração grande, baixe backup manual

### 5. Plugins recomendados
- **Wordfence** ou **Solid Security** — firewall + scan de malware
- **LiteSpeed Cache** — cache + proteção básica (Hostinger LiteSpeed)
- **UpdraftPlus** — backups agendados

### 6. Conta admin
- Não use usuário `admin` em produção (crie outro e delete o antigo)
- Use e-mail real para recuperação de senha
- Mínimo de editores — só quem precisa

### 7. Monitoramento
- Google Search Console — alertas de problemas no site
- Wordfence — alertas de login suspeito por e-mail

## O que NÃO fazer

- Não commitar `.env` com senhas
- Não instalar plugins/temas piratas (nulled)
- Não usar `admin` / `123456` como senha
- Não desativar SSL em produção
- Não deixar `WP_DEBUG=true` na Hostinger

## Em caso de invasão

1. Altere **todas** as senhas (WordPress, MySQL, FTP, hPanel)
2. Restaure backup limpo no hPanel
3. Escaneie com Wordfence
4. Regenere salts em https://api.wordpress.org/secret-key/1.1/salt/ e cole no `wp-config.php`

## Google Site Kit — "not a valid JSON response"

Causa comum: REST API (`/wp-json/`) retornando HTML em vez de JSON.

**Correção:**
1. **Configurações → Links permanentes** → escolha **Nome do post** → **Salvar**
2. Confirme que `.htaccess` contém as regras de rewrite do WordPress (veja raiz do projeto)
3. Teste no navegador: `https://seudominio.com.br/wp-json/` — deve mostrar JSON
4. No Site Kit: **Configurações → Reconectar** os serviços Google

A mensagem *"Search Console is gathering data"* é normal nas primeiras 48h.
