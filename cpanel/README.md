# Microweber cPanel script 

If you are a hosting provider and want to deploy Microweber websites with cPanel accounts, these scripts will help you. 

You will need to have root access to your servers and knowledge of you hosting control panel.



## Setup the WHM/Cpanel instance 

 - Install a Linux distribution 
 - Set the hostname of your server with `hostname mydomain.com` aaa
 - Install WHM as described here https://documentation.cpanel.net/display/ALD/Installation+Guide or with command `cd /home && curl -o latest -L https://securedownloads.cpanel.net/latest && sh latest`
 - Set the PHP Version to PHP 7 
 
![img](img/whm1.png "img")




## Setup Microweber scripts


Those scripts will install Microweber from a shared folder. All users will load the system files from single location via symlinks
 
The system files are located in the folder `/usr/share/microweber-latest` and if you update this folder it will automatically update all installations of all users on the server 

To setup the Microweber scipts run do the following 

 - Download the hosing scripts with `wget https://github.com/microweber/microweber-hosting-scripts/archive/master.zip -O /usr/share/microweber-hosting-scripts.zip`
 - Unzip with `unzip /usr/share/microweber-hosting-scripts.zip -d /usr/share/`
 - Rename the unzipped folder `mv /usr/share/microweber-hosting-scripts-master /usr/share/microweber-hosting-scripts`
 - Download Microweber with `php /usr/share/microweber-hosting-scripts/common/download.php` 
 - Configure the automatic install by editing the file `/scripts/postwwwacct`  
 
Execute `nano /scripts/postwwwacct` and enter this text 
 
 ```sh
 #!/usr/bin/php -q
 <?php
 
 // Set up our variables to be usable by PHP
 
 if(isset($_SERVER['argv']) and !empty($_SERVER['argv'])){
     $argv = $_SERVER['argv'];
 }
 
 $opts = array();
 $argv0 = array_shift($argv);
 
 while(count($argv)) {
     $key = array_shift($argv);
     $value = array_shift($argv);
     $opts[$key] = $value;
 }

 
 require('/usr/share/microweber-hosting-scripts/cpanel/shared_install.php');
 
 exit();
 
 ```
 
 

 
 
## Setup WHMCS and connect it to cPanel

Go on Setup -> Products/Services -> Servers and add new server 


![img](img/whmcs1.png "img")


After that choose cPanel server

![img](img/whmcs2.png "img")

Then enter WHM/cPanel by going on the address https://example.com:2087 to get the API Token

![img](img/whmcs3.png "img")

Create new API Token

![img](img/whmcs4.png "img")

Get the token and paste it in WHMCS 

![img](img/whmcs5.png "img")

Test the connection and you should see SUCCESS 

![img](img/whmcs6.png "img")