FROM php:8.2-apache

RUN apt-get update && apt-get install -y libpq-dev && \
    docker-php-ext-install pgsql pdo_pgsql

RUN a2enmod rewrite headers

# COPY ./app /var/www/html/app
# COPY ./core /var/www/html/core
# COPY ./config /var/www/html/config
# COPY ./public /var/www/html/public

RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' /etc/apache2/sites-available/000-default.conf

RUN echo '<Directory /var/www/html/public>\n\
    Options Indexes FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' >> /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite

RUN echo 'SetEnv DB_HOST ${DB_HOST}' >> /etc/apache2/conf-enabled/environment.conf && \
    echo 'SetEnv DB_PORT ${DB_PORT}' >> /etc/apache2/conf-enabled/environment.conf && \
    echo 'SetEnv DB_NAME ${DB_NAME}' >> /etc/apache2/conf-enabled/environment.conf && \
    echo 'SetEnv DB_USER ${DB_USER}' >> /etc/apache2/conf-enabled/environment.conf && \
    echo 'SetEnv DB_PASS ${DB_PASS}' >> /etc/apache2/conf-enabled/environment.conf && \
    echo 'SetEnv SITE_URL ${SITE_URL}' >> /etc/apache2/conf-enabled/environment.conf

EXPOSE 80
