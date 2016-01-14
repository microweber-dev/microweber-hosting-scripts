<?php

$source = "/var/www/vhosts/loai.directory/httpdocs/cms/";

$cmd = 'find /var/www  -iname "bootstrap.php" | grep src/Microweber';


$output = shell_exec($cmd);

$dirs = explode("\n", $output);

print_r($dirs);

