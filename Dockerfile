FROM php:8.2-fpm-alpine # Usando fpm-alpine, que é mais adequado para produção

# Instala as bibliotecas de desenvolvimento do cliente PostgreSQL
RUN apk add --no-cache postgresql-dev \
    && docker-php-ext-install pdo_pgsql \
    && docker-php-ext-install pgsql \
    && rm -rf /tmp/* /var/cache/apk/*

# Define o diretório de trabalho dentro do contêiner para /app
WORKDIR /app

# Copia todo o conteúdo do seu repositório para o diretório /app dentro do contêiner.
# Isso significa que a pasta 'TrabalhoWEB' estará em /app/TrabalhoWEB
COPY . /app

# DEBUG: Lista o conteúdo do diretório TrabalhoWEB para verificar se os arquivos estão lá
RUN ls -l /app/TrabalhoWEB

EXPOSE 8000 # A porta 8000 é a que você especificou para o servidor embutido

# Inicia o servidor web embutido do PHP na porta 8000,
# explicitamente definindo o diretório raiz do documento como /app/TrabalhoWEB
CMD ["php", "-S", "0.0.0.0:8000", "-t", "/app/TrabalhoWEB"]