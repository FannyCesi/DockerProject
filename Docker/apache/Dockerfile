FROM php:8.2-apache

# Définir le répertoire de travail pour les opérations à venir
WORKDIR /var/www/html

# Copie de la configuration Apache
COPY ./Docker/apache/conf/vhost.conf /etc/apache2/sites-available/000-default.conf

# Activation des modules Apache nécessaires
RUN a2enmod rewrite

# Installation des extensions PHP
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Installation de Composer
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN apt update && apt install libzip-dev -y && docker-php-ext-install zip

COPY ../../ .

RUN composer install

# Redémarrage d'Apache pour prendre en compte les nouveaux paramètres
RUN service apache2 restart
