FROM docker.io/composer:lts as deps

WORKDIR /app
COPY --from=docker.io/composer /usr/bin/composer /usr/bin/composer

RUN --mount=type=bind,source=composer.json,target=composer.json \
    --mount=type=bind,source=composer.lock,target=composer.lock \
    --mount=type=cache,target=/tmp/cache \
    composer install --no-dev --no-interaction --ignore-platform-req=ext-apcu

FROM docker.io/php:8.2-fpm as final

RUN pecl install apcu
RUN docker-php-ext-enable apcu

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

COPY --from=0 --chown=www-data /app/vendor/ /var/www/html/vendor
COPY --chown=www-data . /var/www/html
COPY conf/opcache.ini "$PHP_INI_DIR/conf.d/opcache.ini"

USER www-data
