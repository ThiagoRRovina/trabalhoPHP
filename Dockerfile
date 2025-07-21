FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    nginx \
    postgresql-client \
    libpq-dev \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_pgsql \
    && docker-php-ext-enable pdo_pgsql

RUN rm -f /etc/nginx/sites-enabled/default

COPY ./nginx.conf /etc/nginx/sites-available/default.conf
RUN ln -s /etc/nginx/sites-available/default.conf /etc/nginx/sites-enabled/default.conf

WORKDIR /var/www/html

COPY ./TrabalhoWEB/api/ .
COPY ./TrabalhoWEB/capas/ /var/www/html/capas/

EXPOSE 80

CMD php-fpm -D && nginx -g "daemon off;"
