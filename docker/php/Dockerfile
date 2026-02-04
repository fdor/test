FROM php:8.2-fpm
RUN apt-get update

#common
RUN apt-get install -y wget cron nano

#zip
RUN apt-get install -y libzip-dev zip
RUN docker-php-ext-install zip

#gd
RUN apt-get install -y libpng-dev
RUN apt-get update && apt-get install -y zlib1g-dev
RUN docker-php-ext-install gd

#imagick
RUN apt-get install -y libmagickwand-dev --no-install-recommends && rm -rf /var/lib/apt/lists/*
RUN printf "\n" | pecl install imagick
RUN docker-php-ext-enable imagick

#intl
RUN apt-get update && apt-get install -y zlib1g-dev libicu-dev g++
RUN docker-php-ext-configure intl
RUN docker-php-ext-install intl

#git
RUN apt-get install -y git

#composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

#mysql
RUN docker-php-ext-install mysqli pdo pdo_mysql

#xdebug
RUN pecl install xdebug-3.2.1 && docker-php-ext-enable xdebug

#timezone
ENV TZ=Europe/Moscow
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

WORKDIR /app
