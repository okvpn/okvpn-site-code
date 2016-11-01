#!/bin/bash

STEP=$1
TEST=$2
DB_NAME=okvpn;
ARRAY=( "var/openssl/pa1" "var/openssl/uk1" )
export CI_BUILD_NAME;
export PGPASSWORD;

case "$STEP" in
    install)
        if [ "$CI_BUILD_NAME" = "deploy_job" ]; then
            echo "Skip install...";
            echo "Exit with code 0";
            exit 0;
        fi
        echo "Installing...";
        # remove vendor dir
        if [ -d vendor ]; then
            rm -r vendor;
        fi

        # install all dependency use composer.lock
        composer install
        cp application/phinx.yml.dist application/phinx.yml
        cp application/config/parameters.php.dist application/config/parameters.php
        sed -i "s/name"\:".*/name"\:" $DB_NAME/g" application/phinx.yml;
        sed -i "s/pass"\:".*/pass"\:" $PGPASSWORD/g" application/phinx.yml;
        sed -i "s/user"\:".*/user"\:" okvpn/g" application/phinx.yml;
        sed -i "s/'username'\s"\=\>".*/'username' "\=\>" 'okvpn',/g" application/config/parameters.php;
        sed -i "s/'password'\s"\=\>".*/'password' "\=\>" '$PGPASSWORD',/g" application/config/parameters.php;
        sed -i "s/'database'\s"\=\>".*/'database' "\=\>" '$DB_NAME',/g" application/config/parameters.php;

        # install database
        psql -U okvpn -h 127.0.0.1 -c "DROP SCHEMA IF EXISTS public CASCADE";
        psql -U okvpn -h 127.0.0.1 -c "CREATE SCHEMA public";
        cd application;
        php ../vendor/bin/phinx migrate
        php ../vendor/bin/phinx seed:run
        cd -

        # install pki
        for dir in "${ARRAY[@]}"
        do
            if [ -d "$dir" ]; then
                rm -r "$dir";
            fi

            mkdir "$dir";
            echo "Creating directory $dir ...";
            cp -r var/openssl/example/* "$dir";
            cd "$dir";
            echo "yes" | ./easyrsa.sh init-pki
            echo "" | ./easyrsa.sh build-ca nopass
            cd -
        done
    ;;
    script)
        echo "Run tests...";
        case "$TEST" in
            unit)
                echo "Run  phpunit --verbose --testsuite=unit...";
                phpunit --verbose --testsuite=unit
            ;;
            functional)
                echo "phpunit --verbose --testsuite=functional...";
                phpunit --verbose --testsuite=functional
            ;;
            phpcs)
                echo "Run phpcs --encoding=utf-8 --extensions=php --standard=psr2 src/ -p...";
                phpcs --encoding=utf-8 --extensions=php --standard=psr2 src/ -p
            ;;
        esac
    ;;
esac
