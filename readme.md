Установить

laravel-echo-server (sudo npm -g install laravel-echo-server --unsafe-perm)
redis-server


Cгенерировать сертификат ssl и прописать пути в laravel-echo-server.json
Сам сайт тоже должен работать на https

Запуск сервера широковещания

laravel-echo-server start



Фаерфокс ругается на самоподписанные сертификаты. socket.io/socket.io.js надо отдельно открывать и разрешать доступ.