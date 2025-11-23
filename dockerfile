FROM php:8.2-apache

# Habilitar mod_rewrite si es necesario
RUN a2enmod rewrite

WORKDIR /var/www/html

COPY . .

EXPOSE 80

CMD ["apache2-foreground"]
