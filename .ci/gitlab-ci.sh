#!/bin/bash

STEP=$1
TEST=$2
DB_NAME=okvpn;
DB_USER=okvpn;
ARRAY=( "var/openssl/pa1" "var/openssl/uk1" );
PHPMODDIR=/etc/php/7.0/mods-available/xdebug.ini;

#export gitlab variables
export CI_BUILD_NAME;
export PGPASSWORD;

case "$STEP" in
    install)
        if [ "$CI_BUILD_NAME" = "deploy_job" ] || [ "$CI_BUILD_NAME" = "dump_database_job" ]; then
            echo "Skip install...";
            echo "Exit with code 0";
            exit 0;
        fi
        echo "Installing...";
        # remove vendor dir
        if [ -d vendor ]; then
            rm -r vendor;
        fi

        if [ -d application/cache ]; then
            rm -r application/cache/*;
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


        # install database
        psql -U "$DB_USER" -h 127.0.0.1 -d "$DB_NAME" -c "DROP SCHEMA IF EXISTS public CASCADE";
        psql -U "$DB_USER" -h 127.0.0.1 -d "$DB_NAME" -c "CREATE SCHEMA public";

        if [ "$CI_BUILD_NAME" = "migrate_job" ]; then
            export DUMP_USER;
            export DUMP_PASS;
            export DUMP_HOST;
            echo "$DUMP_HOST";
            sshpass -p "$DUMP_PASS" sftp -o StrictHostKeyChecking=no "$DUMP_USER@$DUMP_HOST" << EOF
                get gitlab-ci.okvpn.sql
                exit
EOF
            psql -U "$DB_USER" -h 127.0.0.1 -d okvpn < gitlab-ci.okvpn.sql
            rm gitlab-ci.okvpn.sql
        fi

        cd application;
        php ../vendor/bin/phinx migrate
        php ../vendor/bin/phinx seed:run
        cd -

        echo "On xdebug...";
        sed -i 's|;zend_extension=xdebug.so|zend_extension=xdebug.so|' "$PHPMODDIR"
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
    after_script)
        echo "Off xdebug..."
        if grep -q ";zend_extension=xdebug.so" "$PHPMODDIR"; then
            exit 0
        fi
        sed -i 's|zend_extension=xdebug.so|;zend_extension=xdebug.so|' "$PHPMODDIR"
    ;;
esac
