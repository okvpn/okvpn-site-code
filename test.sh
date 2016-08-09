#!/bin/bash
DOCROOT="/var/www/html/okvpn.org"
PGPASS="ppkt731415926"
FILE=$(date +%Y-%m-%d)

NEW_expration_DATE=$(date -d "-16 days" +%d)
echo $NEW_expration_DATE
if  ! (($NEW_expration_DATE % 15)); then
	echo "teste"
fi
