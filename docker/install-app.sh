#!/bin/bash
sudo docker-compose exec -T php-fpm chmod -R 777 storage/ bootstrap/
sudo docker-compose exec -T php-fpm cp .env.development .env
sudo docker-compose exec -T php-fpm composer install
sudo docker-compose exec -T php-fpm composer update --lock
sudo docker-compose exec -T php-fpm php artisan optimize:clear
sudo docker-compose exec -T php-fpm php artisan key:generate
sudo docker-compose exec -T php-fpm php artisan config:cache
sudo docker-compose exec -T php-fpm php artisan migrate
sudo docker-compose exec -T php-fpm php artisan db:seed