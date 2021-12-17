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
        sudo \
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
## Xdebug
####
FROM dev as xdebug

RUN pecl install xdebug

COPY .docker/php/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini


####
## Build js+css assets
####
FROM node:14-alpine as js-builder

RUN apk add --no-cache python3 make gcc g++
ENV PYTHON="/usr/bin/python3"

WORKDIR /build

# Install npm packages
COPY package.json yarn.* webpack.config.js ./
RUN yarn install

# Production yarn build
COPY ./assets ./assets

RUN yarn run build

####
## Build app itself - containing source codes and is designed to leverage Docker layers caching
####
FROM dev as production

RUN mkdir -p ./var/cache ./var/log ./var/demo

COPY --from=js-builder /build/public ./public

# To track releases
ARG commitHash=""

# Install composer packages
COPY composer.json composer.lock ./
RUN composer install --prefer-dist --no-dev --no-autoloader --no-progress
RUN composer dump-autoload --optimize --no-dev

RUN chmod -R 777 ./var

COPY . .
