FROM php:8.1.4

RUN docker-php-ext-install pdo pdo_mysql sockets

RUN apt-get update && apt-get -y install git zip unzip

RUN curl -sS https://getcomposer.org/installer | php -- \
     --install-dir=/usr/local/bin --filename=composer

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

RUN composer install
