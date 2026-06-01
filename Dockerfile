FROM dunglas/frankenphp

RUN install-php-extensions pdo_mysql

ENV SERVER_NAME=:8080

COPY . /app/public