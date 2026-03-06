#!/bin/bash

#Descargar composer e instalar composer
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Actualizar configuración servidor web
cp /var/www/html/jobportal/deployment/conf_files/nginx.conf /etc/nginx/nginx.conf
cp /var/www/html/jobportal/deployment/conf_files/default /etc/nginx/sites-enabled/default
cp /var/www/html/jobportal/deployment/conf_files/default /etc/nginx/sites-available/default

# Configuración de certificados SSL
cp /var/www/html/jobportal/deployment/conf_files/certificados/* /etc/nginx/ssl/

# Configuración de crontabs
rm -f /var/spool/cron/crontabs/root
cd /var/spool/cron/crontabs/
touch root
cp /var/www/html/jobportal/deployment/conf_files/cron/crontab /var/spool/cron/crontabs/root
sudo chown root:crontab /var/spool/cron/crontabs/root
sudo chmod 600 /var/spool/cron/crontabs/root

# Configuración PHP
cp /var/www/html/jobportal/deployment/conf_files/php.ini /etc/php/8.1/fpm/php.ini
cp /var/www/html/jobportal/deployment/conf_files/php.ini /etc/php/8.1/cli/php.ini

#  Actualizar archivos de configuración
cp /var/www/html/jobportal/deployment/conf_files/init/index.prod.php /var/www/html/jobportal/index.php
cp /var/www/html/jobportal/deployment/conf_files/init/database.prod.php /var/www/html/jobportal/ca_app/config/database.php
cp /var/www/html/jobportal/deployment/conf_files/init/constants.prod.php /var/www/html/jobportal/ca_app/config/constants.php

# Instalar dependecias por composer 
cd /var/www/html/jobportal
composer install --ignore-platform-reqs

#  Asignar permisos de ejecución
chown -R www-data:www-data /var/www/html/jobportal
chmod -R 777 /var/www/html/jobportal

# Reiniciar servicio php-fpm
sudo service php8.1-fpm restart

# Ejecutar migraciones
php /var/www/html/jobportal/index.php migrate

# Reiniciar servidor web
sudo service nginx reload
sudo service nginx restart

# Instalar supervisor
sudo apt install supervisor -y
sudo systemctl enable supervisor
sudo systemctl start supervisor
cp /var/www/html/jobportal/deployment/conf_files/supervisor/app.config /etc/supervisor/conf.d/app.conf
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start app:*