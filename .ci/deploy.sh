#!/bin/bash

export UK2_SSH_KEY

chmod 600 ssh.key;
rm ssh.key;

echo "$UK2_SSH_KEY" > ssh.key;
chmod 400 ssh.key;
ssh root@uk2.yandex.ovh -i ssh.key <<'SSH'
mkdir test
ls -l
SSH

chmod 600 ssh.key;
rm ssh.key;
