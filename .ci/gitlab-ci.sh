#!/bin/bash

STEP=$1
TEST=$2
DB_NAME=okvpn;
DB_USER=okvpn;
ARRAY=( "var/openssl/pa1" "var/openssl/uk1" )

#export gitlab variables
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

        # install all dependency using composer.lock
        composer install
        cp application/phinx.yml.dist application/phinx.yml
        if [ -f application/config/secret.php  ]; then
            echo 'Copy parameters form secret file...';

            cp application/config/secret.php application/config/parameters.php;
            PGPASSWORD=`cat application/config/secret.php | sed -n "s/.*'\(password\).*'\(.*\)'./\2/p"`;
            DB_USER=`cat application/config/secret.php | sed -n "s/.*'\(username\).*'\(.*\)'./\2/p"`;
            DB_NAME=`cat application/config/secret.php | sed -n "s/.*'\(database\).*'\(.*\)'./\2/p"`;
        else
            echo 'Auto-generate parameters form parameters.dist...';
            cp application/config/parameters.php.dist application/config/parameters.php
            sed -i "s/'username'\s"\=\>".*/'username' "\=\>" '$DB_USER',/g" application/config/parameters.php;
            sed -i "s/'password'\s"\=\>".*/'password' "\=\>" '$PGPASSWORD',/g" application/config/parameters.php;
            sed -i "s/'database'\s"\=\>".*/'database' "\=\>" '$DB_NAME',/g" application/config/parameters.php;
        fi

        # configure phinx.yml
        sed -i "s/name"\:".*/name"\:" $DB_NAME/g" application/phinx.yml;
        sed -i "s/pass"\:".*/pass"\:" $PGPASSWORD/g" application/phinx.yml;
        sed -i "s/user"\:".*/user"\:" $DB_USER/g" application/phinx.yml;

        #install database when test_job.
        if [ "$CI_BUILD_NAME" != "migrate_job" ]; then
            # install database
            psql -U "$DB_USER" -h 127.0.0.1 -c "DROP SCHEMA IF EXISTS public CASCADE";
            psql -U "$DB_USER" -h 127.0.0.1 -c "CREATE SCHEMA public";
        fi

        cd application;
        php ../vendor/bin/phinx migrate

        if [ "$CI_BUILD_NAME" != "migrate_job" ]; then
            php ../vendor/bin/phinx seed:run
        fi

        cd -

        # install pki
        if [ "$CI_BUILD_NAME" != "migrate_job" ]; then
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
        fi
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
