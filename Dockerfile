FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    nginx \
    postgresql-client \
    php-pgsql \
    && rm -rf /var/lib/apt/lists/*

RUN rm -f /etc/nginx/sites-enabled/default

COPY ./nginx.conf /etc/nginx/sites-available/default.conf
RUN ln -s /etc/nginx/sites-available/default.conf /etc/nginx/sites-enabled/default.conf

WORKDIR /var/www/html

COPY ./TrabalhoWEB/api/ .
COPY ./TrabalhoWEB/capas/ /var/www/html/capas/

EXPOSE 80

CMD service php8.2-fpm start && nginx -g "daemon off;"
