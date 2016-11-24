#!/bin/bash

export SSH_KEY;
export DEPLOY_HOST;

echo "$SSH_KEY" > ssh.key;
chmod 400 ssh.key;
ssh -o StrictHostKeyChecking=no "root@$DEPLOY_HOST" -i ssh.key <<'SSH'
cd /var/www/okvpn
git checkout .
git pull origin master
git push github master
rm -r vendor
rm -r application/cache/*
composer install
cd application
php ../vendor/bin/phinx migrate
cd -
chown www-data:www-data -R /var/www/okvpn
service php7.0-fpm restart
SSH

chmod 600 ssh.key;
rm ssh.key;
