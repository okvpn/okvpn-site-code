#!/bin/bash

STEP=$1
TEST=$2
ARRAY=( "var/openssl/pa1" "var/openssl/uk1" );

echo $TEST;
echo $STEP;

case "$STEP" in
    install)

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
        psql -c 'create database okvpn;' -U postgres

        cp application/phinx.yml.dist application/phinx.yml
        sed -i "s/name"\:".*/name"\:" okvpn/g" application/phinx.yml
        sed -i "s/pass"\:".*/pass"\:" /g" application/phinx.yml
        sed -i "s/user"\:".*/user"\:" postgres/g" application/phinx.yml

        cd application
        php ../vendor/bin/phinx migrate
        php ../vendor/bin/phinx seed:run
        cd -
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
    ;;
esac