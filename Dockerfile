FROM php:8.2-fpm-alpine


RUN apk update && apk add --no-cache \
    nginx \
    postgresql-dev \
    postgresql-client \
    php82-pdo_pgsql \ 
    && rm -rf /var/cache/apk/* 


RUN rm -f /etc/nginx/nginx.conf || true


COPY ./nginx.conf /etc/nginx/nginx.conf


WORKDIR /var/www/html


COPY ./TrabalhoWEB/api/ .
COPY ./TrabalhoWEB/capas/ /var/www/html/capas/


EXPOSE 80

CMD php-fpm -D && nginx -g "daemon off;"
