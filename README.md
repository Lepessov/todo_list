Docker & docker-compose

После того как стянули проект на свой компьютер, укажите путь к своему ларавел проекту (admin_panel_vlog) в .env (APP_PATH=) файле в docker/.env дополнительно можете там настроить порт и тд

Пропишите эти команды в папке докера где docker-compose.yml файл: 
````
docker-compose build 
docker-compose ps 
docker-compose up -d
````
Потом активировать скрипт который активирует команды внутри контейнера

sudo sh install-app.sh

Проверьте по адрессу http://127.0.0.1:8080 или http://localhost:8080 Убедитесь, что все работает

Если, необходимо зайти в контейнер, то пропишите: docker-compose exec php-fpm bash

В .env.dvelopment настроить базу данных (указать порты и тд)

(Необязательно)

Makefile есть короткие комманды для управления контейнером

Синткасис: make <command_name>

логин и пароль для админа и модератора:

````
admin@example.com
password
````

````
moderator@example.com
password
````
