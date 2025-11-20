FROM php:8.2-apache
RUN docker-php-ext-install pdo pdo_mysql
RUN a2enmod rewrite
RUN docker-php-ext-install mysqli
RUN docker-php-ext-enable mysqli
RUN apt-get update && apt-get install -y librdkafka-dev && rm -rf /var/lib/apt/lists/*
RUN pecl install rdkafka && docker-php-ext-enable rdkafka
RUN apt-get update && apt-get install -y git
WORKDIR /var/www/html
RUN git clone https://github.com/NikolayGrishchenko/reestAPIThroughKAFKA.git .
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf