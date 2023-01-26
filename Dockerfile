FROM php:8.1-rc-fpm-alpine

RUN apk update && apk upgrade \
  && apk add curl zlib-dev libpng-dev libjpeg-turbo-dev freetype-dev \
  && apk add php-opcache php-common php-bcmath php-ctype php-json php-mbstring \
  && apk add php-openssl php-pdo php-tokenizer php-xml php-gd php-session php-curl php-zlib

RUN docker-php-ext-install pdo_mysql exif pcntl \
  && docker-php-ext-configure gd --with-freetype --with-jpeg \
  && docker-php-ext-install gd

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN rm -rf /var/cache/apk/*

WORKDIR /var/www

USER www-data

EXPOSE 6001
EXPOSE 9000

CMD ["php-fpm"]
