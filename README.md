Bitrix in Docker for manao-team
================================

## Короткое описание
Данный проект предоставляет возможность разворачивать проект на локальном компьютере
в докер контейнерах.

### Отличительные черты:
- Выбор версии php
- Выбор версии mysql

### Описание работы:
1. Для начала работы с проекты необходимо переименовать файл .env.bitrix.example
 -> .env.bitrix. В файле .env.bitrix необходимо распологать все переменные, которые
 необходимо пробрасывать в контейнер с php и mysql. Обязательные поля:
```
MYSQL_DATABASE=
MYSQL_USER=
MYSQL_PASSWORD=
```
2. Переименовываем файл .env.stage в .env. В данном файле перечислены следующие переменные:
```
PROJECT=first(anything) - название проекта
PHP_VERSION=5.6/7.0/7.1/7.2/7.3/7.4/8.0 - выбор версии php
MYSQL_VERSION=5.6/5.7/8.0 - выбор версии mysql
EXT_PORT=8080(or any another) - выбор локального порта по которому будем работать(т.е. в браузере будем вводить 127.0.0.1:8080)
MYSQL_ROOT_PASSWORD= - пароль рутовый для базы данных
MYSQL_ALLOW_EMPTY_PASSWORD=1
```

3. Создаем директорию htdocs. В директории htdocs размещяем код проекта.
4. Если есть база данных. Дамп базы размещаем в директории проекта(htdocs/dump.sql).
В файле mysql/Dockerfile-5.6/5.7/8.0(версия выбранная в файле .env) перед строкой
`EXPOSE 3306` добавляем строку `COPY dump.sql /docker-entrypoint-initdb.d/ `

### Добавление разного ПО в образ:
1. В случае добавления optpng и др. по в блоке php/Dockerfile-VERSION вместо pacage_name вставляем название пакета. Пакет искать для OS alpine.
```
RUN \
  apk update && apk upgrade && apk add --no-cache $PHPIZE_DEPS \
  libmemcached-dev zlib-dev package_name && \
```
2. Для добавления php-libs -
```
RUN \
  apk add --no-cache freetype-dev libjpeg-turbo-dev libpng-dev package_name-dev && \
  docker-php-ext-configure gd --with-freetype --with-jpeg &&\
  docker-php-ext-install -j$(($(nproc) + 2)) gd
  docker-php-ext-configure lib_name --with-ext
  docker-php-ext-install -j$(($(nproc) + 2)) lib_name
```

### Запуску проекта:

1. В директории с docker-compose.yml выполняем следующие комманды

+ bash ./run.sh -n URL -скачает архив и распакует его, создаст каталоги для логов, для дампа БД.
+ bash ./run.sh -c -создаст директории для логов, для дампа БД, htdocs.
+ bash ./run.sh -u -распаковать архив, если нет рспакованных файлов проекта, иначе ничего не делает.
+ bash ./run.sh -d -удалит каталоги с логами и создть их заново.
+ bash ./run.sh -s SITENAME -добавить сайт со ссылками на bitrix, local и upload.
+ пример: ./run.sh -ud

+ docker-compose build
+ docker-compose up -d


