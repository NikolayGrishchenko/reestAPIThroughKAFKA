FROM php:8.2-apache
RUN docker-php-ext-install pdo pdo_mysql
RUN a2enmod rewrite
RUN docker-php-ext-install mysqli
RUN docker-php-ext-enable mysqli
RUN apt-get update && apt-get install -y librdkafka-dev && rm -rf /var/lib/apt/lists/*
RUN pecl install rdkafka && docker-php-ext-enable rdkafka
#RUN composer install --no-dev --optimize-autoloader --no-interaction && composer require kwn/php-rdkafka-stubs --dev --no-interaction
#COPY /Users/nikolaygrishchenko/Downloads/localhost/lib /usr/local/src/php-rdkafka-stubs