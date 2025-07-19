FROM php:8.2-cli-alpine
RUN apk add --no-cache postgresql-dev \
    && docker-php-ext-install pdo_pgsql pgsql \ && rm -rf /tmp/* /var/cache/apk/*

WORKDIR /app
COPY . .
EXPOSE 8000