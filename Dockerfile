FROM php:8.2-apache-alpine
RUN apk add --no-cache postgresql-dev build-base \
    && rm -rf /var/cache/apk/*
RUN docker-php-ext-install pdo pdo_pgsql
COPY . /var/www/html/
EXPOSE 80
