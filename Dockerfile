FROM node:10.15.3 as node-build

WORKDIR /build

COPY package*.json webpack*.json ./
RUN yarn install

COPY . .

RUN yarn run build


FROM php:7.3-apache as production

WORKDIR /var/www/getrector.org

COPY ./.docker/apache/apache.conf /etc/apache2/sites-available/000-default.conf

# Install php extensions + cleanup
RUN apt-get update && apt-get install -y \
        git \
        unzip \
        g++ \
        default-mysql-client \
        zlib1g-dev \
        libicu-dev \
        libzip-dev \
        libpng-dev \
        libjpeg-dev \
    && docker-php-ext-configure gd --with-png-dir=/usr/include/  --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install gd \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install exif \
    && pecl -q install \
        zip \
    && docker-php-ext-enable zip

# Install docker
RUN apt-get update && apt-get install -y \
        apt-transport-https \
        ca-certificates \
        curl \
        gnupg2 \
        software-properties-common \
    && curl -fsSL https://download.docker.com/linux/$(. /etc/os-release; echo "$ID")/gpg > /tmp/dkey; apt-key add /tmp/dkey \
    && add-apt-repository \
        "deb [arch=amd64] https://download.docker.com/linux/$(. /etc/os-release; echo "$ID") \
        $(lsb_release -cs) \
        stable" \
    && apt-get update && apt-get -y install \
        docker-ce-cli

# Installing composer and prestissimo globally
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

ENV COMPOSER_ALLOW_SUPERUSER 1
RUN composer global require "hirak/prestissimo:^0.3" --prefer-dist --no-progress --no-suggest --classmap-authoritative --no-plugins --no-scripts

# Entrypoint
COPY ./.docker/docker-entrypoint.sh /usr/local/bin/docker-php-entrypoint
RUN chmod +x /usr/local/bin/docker-php-entrypoint

COPY composer.json phpunit.xml.dist ./

RUN COMPOSER_MEMORY_LIMIT=-1 composer install --prefer-dist --no-dev --no-autoloader --no-scripts --no-progress --no-suggest \
    && composer clear-cache

COPY . .

COPY --from=node-build /build/public/build ./public/build

RUN mkdir -p ./var/cache \
    ./var/log \
    ./var/sessions \
    ./var/demo \
        && composer dump-autoload -o --no-dev \
        && chown -R www-data ./var


## Local build
FROM production as dev

## TODO: we might need NPM + NODE in dev + entrypoint with npm install?

## see https://getcomposer.org/doc/articles/troubleshooting.md#memory-limit-errors
RUN COMPOSER_MEMORY_LIMIT=-1 composer install --prefer-dist --no-scripts --no-progress --no-suggest


## Local build + xdebug
FROM dev as dev-xdebug

COPY ./.docker/php/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

RUN pecl -q install xdebug \
    && docker-php-ext-enable xdebug
