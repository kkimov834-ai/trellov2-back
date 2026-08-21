FROM php:8.2-apache

# Həyata keçiriləcək lazımi PHP genişlənmələri (PDO, MySQL və s.)
RUN docker-php-ext-install pdo pdo_mysql

# Kodları serverə kopyalayırıq
COPY . /var/www/html/

# Apache mod_rewrite aktivləşdiririk (route-lar üçün)
RUN a2enmod rewrite

EXPOSE 80