#!/usr/bash

step=$1

case $step in
    install)
        echo "Installing...";
        composer self-update;
        composer install
    ;;
    script)
        echo "Run tests...";
        phpunit --verbose --testsuite=unit
        phpunit --verbose --testsuite=functional
    ;;
esac