FROM php:8.2-fpm-alpine

# Instala as bibliotecas de desenvolvimento do cliente PostgreSQL
RUN apk add --no-cache postgresql-dev \
    && docker-php-ext-install pdo_pgsql \
    && docker-php-ext-install pgsql \
    && rm -rf /tmp/* /var/cache/apk/*

WORKDIR /app
COPY . /app
RUN ls -l /app/TrabalhoWEB

EXPOSE 8000
CMD ["php", "-S", "0.0.0.0:8000", "-t", "/app/TrabalhoWEB"]