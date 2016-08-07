#!/bin/bash
# http://jurasikt.u-host.in/public/okvpn/
mode=$1
client=$2
rcloc='/var/www/crt'
if [[ "$mode" = "new" ]]; then
    cd /etc/openvpn/easy-rsa/
    ./easyrsa build-client-full $client nopass
    cp /etc/openvpn/client-common.txt /var/www/crt/$client.ovpn
    echo "<ca>" >> /var/www/crt/$client.ovpn
    cat /etc/openvpn/easy-rsa/pki/ca.crt >> /var/www/crt/$client.ovpn
    echo "</ca>" >> /var/www/crt/$client.ovpn
    echo "<cert>" >> /var/www/crt/$client.ovpn
    cat /etc/openvpn/easy-rsa/pki/issued/$client.crt >> /var/www/crt/$client.ovpn
    echo "</cert>" >> /var/www/crt/$client.ovpn
    echo "<key>" >> /var/www/crt/$client.ovpn
    cat /etc/openvpn/easy-rsa/pki/private/$client.key >> /var/www/crt/$client.ovpn
    echo "</key>" >> /var/www/crt/$client.ovpn
fi
if [[ "$mode" = "remove" ]]; then

    cd /etc/openvpn/easy-rsa/
    ./easyrsa --batch revoke $client
    ./easyrsa gen-crl
    rm -rf pki/reqs/$client.req
    rm -rf pki/private/$client.key
    rm -rf pki/issued/$client.crt
    service openvpn restart
fi
