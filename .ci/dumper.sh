#!/bin/bash

DB_NAME=okvpn;
DB_USER=okvpn;
export PGPASSWORD;
export DUMP_USER;
export DUMP_PASS;
export DUMP_HOST;

pg_dump -U "$DB_USER" -h 127.0.0.1 "$DB_NAME" > gitlab-ci.okvpn.sql;

sshpass -p "$DUMP_PASS" sftp -o StrictHostKeyChecking=no "$DUMP_USER@$DUMP_HOST" << EOF
    rm gitlab-ci.okvpn.sql
    put gitlab-ci.okvpn.sql
    exit
EOF
