Docker & docker-compose

После того как стянули проект на свой компьютер, укажите путь к своему ларавел проекту (todo-list) в .env (APP_PATH=) файле в docker/.env дополнительно можете там настроить порт и тд

Пропишите эти команды в папке докера где docker-compose.yml файл:
`docker-compose build`
`docker-compose ps`
`docker-compose up -d`


Проверьте по адрессу http://127.0.0.1:8080 или http://localhost:8080
Убедитесь, что все работает

Если, необходимо зайти в контейнер, то пропишите: docker-compose exec php-fpm bash

В .env.dvelopment настроить базу данных (указать порты и тд)

Потом активировать скрипт который активирует каомманды внутри контейнера

`sudo sh install-app.sh`

(Необязательно)

Makefile есть короткие комманды для управления контейнером

Синткасис:
`make <command_name>`