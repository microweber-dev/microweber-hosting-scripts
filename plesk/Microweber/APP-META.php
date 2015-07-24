#!/usr/bin/php
<?php

$pass = rand().uniqid();


$xml = '<?xml version="1.0" encoding="UTF-8"?>
<settings>
 <setting>
 <name>admin_email</name>
 <value>johndoe@example.com</value>
 </setting>
 <setting>
 <name>admin_name</name>
 <value>admin</value>
 </setting> <setting>
 <name>admin_password</name>
 <value>'.$pass.'</value>
 </setting>
 <setting>
 <name>site_name</name>
 <value>My site</value>
 </setting>
 <setting>
 <name>locale</name>
 <value>en-US</value>
 </setting>
</settings>';

//print $xml;
$xml_file = "/tmp/mw_" . uniqid() . '.xml';
//print $xml_file;
file_put_contents($xml_file, $xml);

return;


$xml = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . "APP-META.xml");



$dom = new DOMDocument();
$dom->loadXML($xml);
$pass = uniqid();
$admin_user_name = 'admin';
$xpath = new DOMXPath($dom);
$elements = $xpath->query('//*[@id="admin_password"]');

if ($elements->length >= 1) {
    $element = $elements->item(0);

    $element->setAttribute('default-value', $pass);
    $element->setAttribute('value', $pass);
    //  $element->nodeValue = $pass;
}
$elements = $xpath->query('//*[@name="password"]');
if ($elements->length >= 1) {
    $element = $elements->item(0);

    $element->setAttribute('default-value', $pass);
    $element->setAttribute('value', $pass);
    $element->value = "Administrator";

}

$elements = $xpath->query('//*[@name="username"]');
if ($elements->length >= 1) {
    $element = $elements->item(0);

    $element->setAttribute('default-value', $admin_user_name);
    $element->setAttribute('value', $admin_user_name);
    $element->value = "Administrator";

}


$elements = $xpath->query('//*[@id="admin_name"]');
if ($elements->length >= 1) {
    $element = $elements->item(0);
    $element->setAttribute('default-value', "Administrator");
    $element->setAttribute('value', "Administrator");
    $element->value = "Administrator";

}

$elements = $xpath->query('//*[@id="admin_email"]');
if ($elements->length >= 1) {
    $element = $elements->item(0);
    $val = "me@example.com";
    $element->setAttribute('default-value', $val);
    $element->setAttribute('value', $val);
    //$element->value = $val;
}


print $dom->saveXML();
return;
$xml_file = "/tmp/mw_" . uniqid() . '.xml';
//print $xml_file;
file_put_contents($xml_file, $dom->saveXML());