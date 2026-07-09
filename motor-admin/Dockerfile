FROM php:8.2-apache
COPY . /var/www/html/
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf
RUN sed -i 's|/var/www/html|/var/www/html/PAGES|g' /etc/apache2/sites-available/000-default.conf
EXPOSE 80