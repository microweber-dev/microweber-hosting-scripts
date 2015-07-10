# Hosting scripts for deploying on Direct Admin


Follow those steps to have your Virtualmin server deploy new Microweber installation on new account creation. 

Make sure you cover the [server requirements](https://github.com/microweber/microweber#general-requirements "") and you can install Microweber normally, before employing those automated methods.


Install PHP requirements
```
yum install php-mcrypt*
```


## Download latest version of this repository and Microweber

```sh
cd /usr/share/
git clone https://github.com/microweber/microweber-hosting-scripts.git
chmod +x /usr/share/microweber-hosting-scripts/common/download.php
chmod +x /usr/share/microweber-hosting-scripts/directadmin/install.php
php /usr/share/microweber-hosting-scripts/directadmin/download.php

```
These scripts will download and unzip the Microweber source in `/usr/share/microweber-latest/`


### Automate install

You must edit the user_create_post.sh file

Edit the file

`
nano /usr/local/directadmin/scripts/custom/user_create_post.sh
`

```sh
if [ "$package" = "microweber" ]; then
        /usr/share/microweber-hosting-scripts/directadmin/mysql_create.sh
        php -f /usr/share/microweber-hosting-scripts/directadmin/install.php
fi
```


### Update all sites to the latest version

If you want to update all sites to the current MW version. Just run the update scripts with: 

```sh 
php /usr/share/microweber-hosting-scripts/common/update.php
```

