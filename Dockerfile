FROM php:7.3-fpm-alpine

LABEL maintainer="khemsisami@gmail.com"

ENV TERM="xterm"
ENV APCU_VERSION 5.1.12

# Minimal packages
RUN apk add --no-cache --virtual .persistent-deps \
	acl \
        bash \
        git \
        icu-libs \
        libxml2-dev \
        openssh-client \
        nodejs \
        yarn
#        zlib

# PHP extensions
RUN set -xe \
	&& apk add --no-cache --virtual .build-deps \
        $PHPIZE_DEPS \
        icu-dev \
        libtool \
        openssl-dev \
#        zlib-dev \
	&& pecl install xdebug \
	&& docker-php-ext-install \
        intl \
        mbstring \
        opcache \
        pdo_mysql \
        soap \
        sockets \
#        zip \
	&& pecl install \
        apcu-${APCU_VERSION} \
    && docker-php-ext-enable \
        apcu \
        opcache \
        xdebug \
	&& apk del .build-deps

# Conf PHP
COPY docker/engine/php.ini /usr/local/etc/php/php.ini

# Composer
COPY --from=composer /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
CMD ["php-fpm"]

ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_MEMORY_LIMIT -1

# Use prestissimo to speed up builds
RUN composer global require "hirak/prestissimo:^0.3" --prefer-dist --no-progress --no-suggest --optimize-autoloader --classmap-authoritative  --no-interaction

# Install vendors
#RUN composer install
