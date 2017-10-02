Установка и запуск
-
1. Предполагается что в системе 
    * установлены nodejs, npm, redis-server, composer
    * сгенерированы ssl сертификаты и подключены к сайту
1. git clone git@github.com:Error-free/postswall.git postswall
1. cd postswall
1. composer install
1. npm install
1. sudo npm -g install laravel-echo-server --unsafe-perm
1. Создание базы данных (mysqladmin -uroot create postswall)
1. sudo chgrp -R www-data storage bootstrap/cache
1. sudo chmod -R ug+rwx storage bootstrap/cache
1. cp .env.example .env
1. php artisan key:generate
1. Изменить в .env

        DB_DATABASE=postswall
        DB_USERNAME=root
        DB_PASSWORD=

        BROADCAST_DRIVER=redis

1. npm run dev
1. php artisan migrate
1. php artisan db:seed
1. laravel-echo-server init

        ? Do you want to run this server in development mode? Yes
        ? Which port would you like to serve from? 6001
        ? Which database would you like to use to store presence channel members? redis
        ? Enter the host of your Laravel authentication server. postswall.laravel
        ? Will you be serving on http or https? https
        ? Enter the path to your SSL cert file. /etc/nginx/ssl/quickstart.wss/server.crt
        ? Enter the path to your SSL key file. /etc/nginx/ssl/quickstart.wss/server.key
        ? Do you want to generate a client ID/Key for HTTP API? No

1. Установить ключи в laravel-echo-server.json

        "clients": [
            {
                "appId": "b78f3471a25207df",
                "key": "a88fc0ab3e98df408689e6ee38cd0339"
            }
        ],

1. laravel-echo-server start

Разное
-
* Сам сайт тоже должен работать на https
* Фаерфокс ругается на самоподписанные сертификаты. socket.io/socket.io.js надо отдельно открывать и разрешать доступ.

Задача 2
-
* Генерация по шаблону php artisan sequence:generate --template="category brand product property property_value"
* Генерация всех комбинаций php artisan sequence:generate
* Разделитель задается через флаг --delimeter