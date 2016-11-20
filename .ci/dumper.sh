#!/bin/bash

DB_NAME=okvpn;
DB_USER=okvpn;
export PGPASSWORD;
export DUMP_USER;
export DUMP_PASS;
export DUMP_HOST;

psql -U "$DB_USER" -h 127.0.0.1 -d "$DB_NAME" -c "DROP SCHEMA IF EXISTS public CASCADE";
psql -U "$DB_USER" -h 127.0.0.1 -d "$DB_NAME" -c "CREATE SCHEMA public";

echo "Installing...";
# remove vendor dir
if [ -d vendor ]; then
    rm -r vendor;
fi

# install all dependency using composer.lock
composer install
cp application/phinx.yml.dist application/phinx.yml

sed -i "s/name"\:".*/name"\:" $DB_NAME/g" application/phinx.yml;
sed -i "s/pass"\:".*/pass"\:" $PGPASSWORD/g" application/phinx.yml;
sed -i "s/user"\:".*/user"\:" $DB_USER/g" application/phinx.yml;

cd application;
php ../vendor/bin/phinx migrate
cd -

pg_dump -U "$DB_USER" -h 127.0.0.1 "$DB_NAME" > gitlab-ci.okvpn.sql;

sshpass -p "$DUMP_PASS" sftp -o StrictHostKeyChecking=no "$DUMP_USER@$DUMP_HOST" << EOF
    rm gitlab-ci.okvpn.sql
    put gitlab-ci.okvpn.sql
    exit
EOF
