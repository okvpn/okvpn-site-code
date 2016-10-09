#!/usr/bash

step=$1
DB_NAME=okvpn;

case $step in
    install)
        echo "Installing...";
        composer install
        cp application/phinx.yml.dist application/phinx.yml
        cp application/config/parameters.php.dist application/config/parameters.php
        sed -i "s/name"\:".*/name"\:" $DB_NAME/g" application/phinx.yml;
        sed -i "s/pass"\:".*/pass"\:" 123456/g" application/phinx.yml;
        sed -i "s/user"\:".*/user"\:" okvpn/g" application/phinx.yml;
        sed -i "s/'username'\s"\=\>".*/'username' "\=\>" 'okvpn',/g" application/config/parameters.php;
        sed -i "s/'password'\s"\=\>".*/'password' "\=\>" '123456',/g" application/config/parameters.php;
        sed -i "s/'database'\s"\=\>".*/'database' "\=\>" '$DB_NAME',/g" application/config/parameters.php;
        export PGPASSWORD='123456';
        psql -U okvpn -h 127.0.0.1 -c "DROP SCHEMA IF EXISTS public CASCADE";
        psql -U okvpn -h 127.0.0.1 -c "CREATE SCHEMA public";
        cd application;
        php ../vendor/bin/phinx migrate
        php ../vendor/bin/phinx seed:run 
    ;;
    script)
        echo "Run tests...";
        echo "Run  phpunit --verbose --testsuite=unit...";
        phpunit --verbose --testsuite=unit
        echo "phpunit --verbose --testsuite=functional...";
        phpunit --verbose --testsuite=functional
        echo "Run phpcs --encoding=utf-8 --extensions=php --standard=psr2 src/ -p...";
        phpcs --encoding=utf-8 --extensions=php --standard=psr2 src/ -p
    ;;
esac