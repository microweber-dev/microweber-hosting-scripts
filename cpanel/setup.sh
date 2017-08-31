#!/bin/bash
cd /tmp
clear
rm -Rvf /usr/share/microweber-hosting-scripts
rm -Rvf /usr/share/microweber-hosting-scripts-master
wget https://github.com/microweber/microweber-hosting-scripts/archive/master.zip -O /usr/share/microweber-hosting-scripts.zip
unzip /usr/share/microweber-hosting-scripts.zip -d /usr/share/
mv /usr/share/microweber-hosting-scripts-master /usr/share/microweber-hosting-scripts
chmod -Rv go+X /usr/share/microweber-hosting-scripts
php /usr/share/microweber-hosting-scripts/common/download.php
echo "Done...!!!"