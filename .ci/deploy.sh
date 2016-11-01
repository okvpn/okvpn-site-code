#!/bin/bash

export UK2_SSH_KEY

echo "$UK2_SSH_KEY" > ssh.key;
chmod 400 ssh.key;
ssh -o StrictHostKeyChecking=no root@uk2.yandex.ovh -i ssh.key <<'SSH'
mkdir test
ls -l
SSH

chmod 600 ssh.key;
rm ssh.key;
