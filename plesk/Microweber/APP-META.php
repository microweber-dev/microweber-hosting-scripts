#!/usr/bin/php
<?php
$xml = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . "APP-META.xml");


$dom = new DOMDocument();
$dom->loadXML($xml);
$pass = uniqid();
$xpath = new DOMXPath($dom);
$elements = $xpath->query('//*[@id="admin_password"]');

if ($elements->length >= 1) {
    $element = $elements->item(0);

    $element->setAttribute('default-value', $pass);
    $element->setAttribute('value', $pass);
    $element->nodeValue = $pass;
}
$elements = $xpath->query('//*[@name="username"]');
if ($elements->length >= 1) {
    $element = $elements->item(0);

    $element->setAttribute('default-value', $pass);
    $element->setAttribute('value', $pass);
    $element->nodeValue = $pass;

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
    $element->value = $val;
}


print $dom->saveXML();