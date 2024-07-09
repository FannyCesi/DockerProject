FROM php:apache

COPY conf/vhost.conf /etc/apache2/sites-available/000-default.conf

RUN a2enmod rewrite

RUN docker-php-ext-install mysqli pdo pdo_mysql

RUN service apache2 restart