FROM php:7.4-cli

RUN apt-get update && apt-get install -y \
                                       zlib1g-dev \
                                       libicu-dev g++ \
                                       vim \
                                       libfreetype6-dev \
                                       libjpeg62-turbo-dev \
                                       libmcrypt-dev \
                                       libpng-dev \
                                       zlib1g-dev \
                                       libxml2-dev \
                                       libzip-dev \
                                       libonig-dev \
                                       graphviz \
                                       libcurl4-openssl-dev \
                                       pkg-config \
                                       libpq-dev\
                                       libyaml-dev \
&& docker-php-ext-install pdo pdo_mysql \
&& docker-php-ext-configure intl \
&& docker-php-ext-install intl \
&& docker-php-ext-install pcntl \
&& docker-php-ext-install zip \
&& apt install -y libmagickwand-dev --no-install-recommends \
&& pecl install imagick \
&& docker-php-ext-enable imagick \
&& docker-php-ext-configure gd --with-freetype --with-jpeg \
&& docker-php-ext-install -j$(nproc) gd \
&& docker-php-ext-install exif

RUN  pecl install yaml && docker-php-ext-enable yaml

RUN php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer

ENV COMPOSER_ALLOW_SUPERUSER 1

WORKDIR /var/www/app

