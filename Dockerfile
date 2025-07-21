FROM php:8.2-fpm-alpine

# 1. Atualiza os índices dos pacotes e instala pacotes de sistema
# Inclui postgresql-dev (necessário para compilar pdo_pgsql) e postgresql-client
RUN apk update && apk add --no-cache \
    nginx \
    postgresql-dev \ 
    postgresql-client \
    && rm -rf /var/cache/apk/* 

# 2. Instala e habilita a extensão PDO para PostgreSQL usando a ferramenta oficial
# (Isto deve ser feito DEPOIS que as dependências de sistema como postgresql-dev forem instaladas)
RUN docker-php-ext-install pdo_pgsql \
    && docker-php-ext-enable pdo_pgsql

# Remove o arquivo de configuração padrão do Nginx (se existir)
RUN rm -f /etc/nginx/nginx.conf || true

# Copia seu nginx.conf completo para substituir o arquivo de configuração principal do Nginx
COPY ./nginx.conf /etc/nginx/nginx.conf

# Define o diretório de trabalho
WORKDIR /var/www/html

# Copia arquivos da aplicação
COPY ./TrabalhoWEB/api/ .
COPY ./TrabalhoWEB/capas/ /var/www/html/capas/

# Expõe a porta
EXPOSE 80

# Inicia PHP-FPM e Nginx
CMD php-fpm -D && nginx -g "daemon off;"
