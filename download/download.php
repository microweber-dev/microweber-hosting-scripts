#!/usr/bin/php
<?php
$cmd = "rm -rf microweber-latest.zip";
$output = exec($cmd);

$cmd = "rm -rf microweber-update.zip";
$output = exec($cmd);



$cmd = "wget https://microweber.com/download.php -O microweber-latest.zip";
$output = exec($cmd);

$cmd = "wget https://microweber.com/download.php?update -O microweber-update.zip";
$output = exec($cmd);

$cmd = "unzip microweber-latest.zip";
$output = exec($cmd);

$cmd = "unzip microweber-update.zip";
$output = exec($cmd);