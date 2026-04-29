FROM php:8.2-apache
RUN a2enmod rewrite 
RUN docker-php-ext-install pdo pdo_mysql
RUN apt-get update \
    && apt-get install -y --no-install-recommends libzip-dev zlib1g-dev \
    && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-install zip
