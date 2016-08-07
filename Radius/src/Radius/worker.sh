#!/bin/bash
export PATH="/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin:/usr/games:/usr/local/games"
DIR='/var/www/src/Radius'
FILE="$DIR/logfile.txt"
killall -9 tcpdump
php "$DIR/radius.php"
rm $FILE
tcpdump -i tun0 > $FILE &
