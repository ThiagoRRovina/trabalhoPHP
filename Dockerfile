FROM php:8.2-fpm-alpine

# 1. Atualiza os índices dos pacotes e instala pacotes de sistema
# Removido postgresql-dev, pois estamos instalando php82-pdo_pgsql diretamente
RUN apk update && apk add --no-cache \
    nginx \
    postgresql-client \
    php82-pdo_pgsql \ 
    && rm -rf /var/cache/apk/*

# As linhas 'docker-php-ext-install' e 'docker-php-ext-enable' foram removidas anteriormente, mantenha-as assim.

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
