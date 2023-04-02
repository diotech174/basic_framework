FROM php:8.1-cli

# Instalar o Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php --install-dir=/usr/local/bin --filename=composer
RUN php -r "unlink('composer-setup.php');"

WORKDIR /var/www/html/public/

COPY . /var/www/html/

RUN apt-get update && \
    apt-get install -y zip && \
    apt-get install -y unzip && \
    chmod -R 777 /var/www/html/public/*

RUN composer install --working-dir=/var/www/html/ --no-dev

EXPOSE 8080

CMD [ "php", "-S",  "0.0.0.0:8080" ]