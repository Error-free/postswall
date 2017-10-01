Установка и запуск

* sudo npm -g install laravel-echo-server --unsafe-perm
* sudo apt-get install redis-server
* Cгенерировать сертификат ssl и прописать пути в laravel-echo-server.json
* Запуск сервера вебсокетов laravel-echo-server start


Разное

* Сам сайт тоже должен работать на https
* Фаерфокс ругается на самоподписанные сертификаты. socket.io/socket.io.js надо отдельно открывать и разрешать доступ.