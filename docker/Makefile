# Сборка всего проекта
build:
	sudo sudo docker-compose build

# Поднятие всего проекта
up:
	sudo docker-compose up -d

# Просмотр активных сервисов
ps:
	sudo docker-compose ps

# Положить все сервисы
down:
	sudo docker-compose down

# Рестартануть все сервисы
restart:
	sudo docker-compose restart

# Пересобрать весь проект
rebuild:
	sudo docker-compose down
	sudo docker-compose build
	sudo docker-compose up -d
	sudo sh install-app.sh

# Поднять только сервис nginx
up-nginx:
	sudo docker-compose up -d nginx --build

up-php-fpm:
	sudo docker-compose up -d php-fpm --build

# Положить только сервис nginx
down-nginx:
	sudo docker-compose down nginx

# Положить только сервис php-fpm
down-php-fpm:
	sudo docker-compose down php-fpm

# Консоль для php-fpm
php-fpm:
	sudo docker-compose exec php-fpm bash
