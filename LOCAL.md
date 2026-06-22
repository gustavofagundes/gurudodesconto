# Rodar o site localmente (Docker)

## Pré-requisito

- [Docker Desktop](https://www.docker.com/products/docker-desktop/) instalado e em execução

## Iniciar

Na raiz do projeto:

```bash
docker compose up -d
```

Aguarde ~30 segundos e acesse: **http://localhost:8080**

## Primeira instalação

1. Escolha idioma **Português do Brasil**
2. Preencha:
   - Título: `Guru do Desconto`
   - Usuário, senha e e-mail (anote a senha)
3. Clique em **Instalar o WordPress**
4. Faça login em http://localhost:8080/wp-admin
5. **Aparência → Temas** → ative **Guru do Desconto**
6. **Aparência → Personalizar → Guru do Desconto** → cole o link do WhatsApp

## Parar o ambiente

```bash
docker compose down
```

Para apagar também o banco de dados:

```bash
docker compose down -v
```

## Credenciais do banco (local)

Definidas no arquivo **`.env`** na raiz do projeto:

```env
DB_NAME=wordpress
DB_USER=wordpress
DB_PASSWORD=wordpress
DB_HOST=db
DB_TABLE_PREFIX=wp_
```

O Docker lê o `.env` automaticamente via `docker-compose.yml`.

## Problemas comuns

**Porta 8080 em uso** — altere em `docker-compose.yml`:
```yaml
ports:
  - "8888:80"
```

**Site lento na primeira carga** — normal; o MySQL está inicializando.

**Permissão negada** — no Mac geralmente não ocorre; se ocorrer:
```bash
docker compose exec wordpress chown -R www-data:www-data /var/www/html
```
