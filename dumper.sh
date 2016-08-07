#!/bin/bash
DOCROOT="/var/www/html/okvpn.org"
PGPASS="ppkt731415926"
FILE=$(date +%Y-%m-%d)
OLD_DATE=$(date -d "-15 days" +%d)
OLD_FILE=$(date -d "-15 days" +%Y-%m-%d)

cd $DOCROOT
PGPASSWORD="$PGPASS" pg_dump -U postgres -h 127.0.0.1 okvpn | gzip > "okvpn-$FILE.sql.gz"
tar -zcvf "okvpn_site-$FILE.tar.gz" *

if  (($OLD_DATE % 15)); then 
	sshpass -p "UKNWLDWJrMcb" sftp ih128779@dump.okvpn.org << EOF
		cd dump/okvpn
		put "okvpn_site-$FILE.tar.gz"
		put "okvpn-$FILE.sql.gz"
		rm "okvpn-$OLD_FILE.sql.gz"
		rm "okvpn_site-$OLD_FILE.tar.gz"
		exit
EOF
else
	sshpass -p "UKNWLDWJrMcb" sftp ih128779@dump.okvpn.org << EOF
		cd dump/okvpn
		put "okvpn_site-$FILE.tar.gz"
		put "okvpn-$FILE.sql.gz"
		exit
EOF
fi
rm "okvpn-$FILE.sql.gz"
rm "okvpn_site-$FILE.tar.gz"
