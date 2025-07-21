FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    nginx \
    postgresql-client \
    libpq-dev \ 
    && rm -rf /var/lib/apt/lists/* 

# Instala e habilita pdo_pgsql usando a ferramenta oficial
RUN docker-php-ext-install pdo_pgsql \
    && docker-php-ext-enable pdo_pgsql

# PASSO DE DIAGNÓSTICO PARA O NAVEGADOR: Cria um arquivo PHP para verificar a extensão
# Este arquivo irá te dizer diretamente no navegador se o pdo_pgsql está carregado.
RUN echo '<?php if (extension_loaded("pdo_pgsql")) { echo "pdo_pgsql está CARREGADO no CONTÊINER!"; } else { echo "pdo_pgsql NÃO está CARREGADO no CONTÊINER!"; } ?>' > /var/www/html/check_pdo.php


RUN rm -f /etc/nginx/sites-enabled/default

# Copia sua configuração nginx.conf para sites-available e habilita
COPY ./nginx.conf /etc/nginx/sites-available/default.conf
RUN ln -s /etc/nginx/sites-available/default.conf /etc/nginx/sites-enabled/default.conf

# Define o diretório de trabalho
WORKDIR /var/www/html

# Copia arquivos da aplicação
COPY ./TrabalhoWEB/api/ .
COPY ./TrabalhoWEB/capas/ /var/www/html/capas/

# Expõe a porta
EXPOSE 80

# Inicia PHP-FPM e Nginx
CMD php-fpm -D && nginx -g "daemon off;"
