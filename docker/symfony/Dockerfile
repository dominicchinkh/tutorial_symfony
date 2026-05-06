FROM php:8.4-fpm

WORKDIR /var/www/symfony

RUN apt-get update && apt-get install -y \
        libicu-dev \
        libzip-dev \
        unzip \
        procps \
        acl \
    && docker-php-ext-install \
        intl \
        opcache \
        zip \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# See https://symfony.com/doc/current/setup/file_permissions.html
RUN mkdir -p var \
    && HTTPDUSER=$(ps axo user,comm | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx|[c]addy|[f]rankenphp' | grep -v root | head -1 | cut -d\  -f1) \
    && setfacl -dR -m u:"$HTTPDUSER":rwX -m u:$(whoami):rwX var \
    && setfacl -R  -m u:"$HTTPDUSER":rwX -m u:$(whoami):rwX var

EXPOSE 9000