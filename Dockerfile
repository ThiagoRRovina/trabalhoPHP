# Use uma imagem base com PHP e FPM (FastCGI Process Manager)
FROM php:8.2-fpm-alpine # Escolha uma versão PHP estável (ex: 8.2) e uma imagem leve (alpine)

# Instale dependências do sistema e extensões PHP necessárias
# Ex: pdo_pgsql para PostgreSQL, gd para manipulação de imagem, etc.
RUN apk add --no-cache \
    nginx \
    postgresql-dev \
    && docker-php-ext-install pdo_pgsql \
    && docker-php-ext-enable pdo_pgsql

# Configuração do Nginx
# Remova a configuração padrão
RUN rm /etc/nginx/conf.d/default.conf

# Crie sua própria configuração Nginx no projeto (você precisará criar o arquivo nginx.conf)
# Este arquivo (nginx.conf) será copiado do seu repositório para dentro do container
COPY ./TrabalhoWEB/nginx.conf /etc/nginx/conf.d/default.conf

# Configuração do PHP-FPM (opcional, pode ajustar php.ini)
# COPY ./TrabalhoWEB/php.ini /usr/local/etc/php/conf.d/custom.ini

# Define o diretório de trabalho dentro do contêiner para sua aplicação
# O Render clonará seu repo e construirá a partir da raiz (onde o Dockerfile está)
# Então, aqui estamos dizendo que os arquivos da sua aplicação estarão em /var/www/html
WORKDIR /var/www/html

# Copia o conteúdo da sua pasta 'api' para o diretório de trabalho do Nginx/PHP-FPM
# O "." no final significa copiar para o WORKDIR atual
COPY ./TrabalhoWEB/api/ .

# Copia a pasta 'capas' para o contêiner
COPY ./TrabalhoWEB/capas/ /var/www/html/capas/

# Expõe a porta que o Nginx vai escutar
EXPOSE 80

# Comando para iniciar o PHP-FPM em segundo plano e o Nginx em primeiro plano
CMD php-fpm -D && nginx -g "daemon off;"
