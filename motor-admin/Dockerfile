FROM php:8.2-apache
COPY . /var/www/html/
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf
RUN ls /var/www/html/
EXPOSE 80