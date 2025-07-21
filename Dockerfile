FROM php:8.2-fpm-alpine

RUN apk add --no-cache \
    nginx \
    postgresql-dev \
    && docker-php-ext-install pdo_pgsql \
    && docker-php-ext-enable pdo_pgsql


COPY ./nginx.conf /etc/nginx/nginx.conf 

WORKDIR /var/www/html

COPY ./TrabalhoWEB/api/ .

COPY ./TrabalhoWEB/capas/ /var/www/html/capas/

EXPOSE 80

CMD php-fpm -D && nginx -g "daemon off;"
