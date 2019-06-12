FROM php:7.1-alpine

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN apk add --update --no-cache \
    php7-pear \
    php7-dev \
    openssh \
    $PHPIZE_DEPS \
    php7-bcmath \
    php7-openssl \
    gmp \
    gmp-dev \
    libmcrypt \
    libmcrypt-dev \
    git
RUN pecl install xdebug
RUN docker-php-ext-configure mcrypt --with-mcrypt
RUN docker-php-ext-configure bcmath --enable-bcmath
RUN docker-php-ext-install bcmath gmp mcrypt
RUN docker-php-ext-enable mcrypt xdebug

RUN echo "memory_limit = -1" > /usr/local/etc/php/conf.d/memory.ini

COPY . /var/www/html
WORKDIR /var/www/html

RUN composer install --prefer-dist --optimize-autoloader
