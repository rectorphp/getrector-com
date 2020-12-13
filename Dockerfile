####
## Base stage, to empower cache
####
FROM php:8.0-apache as dev

WORKDIR /var/www/getrector.org

COPY ./.docker/apache/apache.conf /etc/apache2/sites-available/000-default.conf

# Install php extensions
RUN apt-get update && apt-get install -y \
        git \
        unzip \
        g++ \
        default-mysql-client \
        zlib1g-dev \
        libicu-dev \
        libzip-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl \
    && docker-php-ext-install pdo_mysql \
    && pecl -q install \
        zip \
    && docker-php-ext-enable zip # opcache

# Installing composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

ENV COMPOSER_ALLOW_SUPERUSER=1 COMPOSER_MEMORY_LIMIT=-1

# Entrypoint
COPY ./.docker/docker-php-entrypoint /usr/local/bin/docker-php-entrypoint
COPY ./.docker/docker-dev-php-entrypoint /usr/local/bin/docker-dev-php-entrypoint

# this is always run "docker run/docker-compose ..."
RUN chmod 777 /usr/local/bin/docker-*entrypoint

####
## Build js+css assets
####
FROM node:10.15.3 as node-build

WORKDIR /build

COPY package.json yarn.* webpack.config.js ./
RUN yarn install

COPY ./assets ./assets

RUN yarn run build


####
## Build app itself
####
FROM dev as production

COPY composer.json composer.lock phpunit.xml ./

RUN composer install --prefer-dist --no-dev --no-autoloader --no-scripts --no-progress --ignore-platform-req php \
    && composer clear-cache

COPY --from=node-build /build/public/build ./public/build

RUN mkdir -p ./var/cache \
    ./var/log \
    ./var/sessions \
    ./var/demo \
        && composer dump-autoload -o --no-dev \
        && chown -R www-data ./var

# To track releases
ARG commitHash=""
ENV SENTRY_RELEASE=${commitHash}

COPY . .

####
## Local build + xdebug - we do not need COPY files because we will get them from volume
####
FROM production as dev-xdebug

COPY ./.docker/php/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

RUN pecl -q install xdebug \
    && docker-php-ext-enable xdebug
