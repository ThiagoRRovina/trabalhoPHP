FROM php:8.2-fpm-alpine

RUN apk update && apk add --no-cache \
    nginx \
    postgresql-dev \
    postgresql-client \
    && rm -rf /var/cache/apk/*

RUN docker-php-ext-install pdo_pgsql

RUN echo "extension=pdo_pgsql.so" > /usr/local/etc/php/conf.d/docker-php-ext-pdo_pgsql.ini

RUN php -m | grep pdo_pgsql

RUN rm -f /etc/nginx/nginx.conf || true

RUN apk add --no-cache ca-certificates && update-ca-certificates

COPY ./nginx.conf /etc/nginx/nginx.conf

WORKDIR /var/www/html

COPY ./TrabalhoWEB/api/ .
COPY ./TrabalhoWEB/capas/ /var/www/html/capas/

EXPOSE 80

CMD php-fpm -D && nginx -g "daemon off;"
