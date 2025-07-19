FROM php:8.2-fpm-alpine
RUN apk add --no-cache postgresql-dev \
    # Instala a extensão pdo_pgsql
    && docker-php-ext-install pdo_pgsql \
    # Instala a extensão pgsql \
    && docker-php-ext-install pgsql \
    # Limpa após a instalação para reduzir o tamanho da imagem
    && rm -rf /tmp/* /var/cache/apk/*
WORKDIR /app
COPY . .
EXPOSE 9000
CMD ["php-fpm"]