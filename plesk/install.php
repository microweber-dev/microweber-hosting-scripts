#!/usr/bin/php
<?php

$the_app_id = 8;


function string_between($string, $start, $end)
{
    $string = " " . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return "";
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}


$get_aps = "/usr/local/psa/bin/aps -gp ";
$output = shell_exec($get_aps);


$outputs = explode('================================', $output);
$apps = array();
if (!empty($outputs)) {
    foreach ($outputs as $ap) {
        $app_id = intval(string_between($ap, "ID:", "\n"));
        $app_name = trim(string_between($ap, "Name:", "\n"));
        if ($app_id > 0) {
            $apps[$app_id] = $app_name;
            if (stristr($app_name, 'microweber')) {
                $the_app_id = $app_id;
            }
        }
    }
}


if (!isset($_SERVER['NEW_DOMAIN_NAME'])) {
    return;
}
$cmd_out = '';
$domain = $_SERVER['NEW_DOMAIN_NAME'];

$db_name = str_replace(array('.', '-'), '', $domain);
$db_name = "mw_" . substr($db_name, 0, 8) . rand();
$db_user = "mw_" . uniqid();
$db_pass = md5(uniqid() . uniqid() . rand());


include_once("/usr/share/microweber-hosting-scripts/plesk/Microweber/APP-META.php");

if (isset($xml_file)) {
    $install_aps = "/usr/local/psa/bin/aps --install {$xml_file} -package-id {$the_app_id} -url-prefix / -domain {$domain} -db-server localhost:3306 -db-name {$db_name} -prefix mw_ -db-user {$db_user} -passwd {$db_pass} > /dev/null &";
    $install_aps_bash = "#!/bin/sh
echo \"Hi, I'm will install in 5 seconds...\"
sleep 5

/usr/local/psa/bin/aps --install {$xml_file} -package-id {$the_app_id} -url-prefix / -domain {$domain} -db-server localhost:3306 -db-name {$db_name} -prefix mw_ -db-user {$db_user} -passwd {$db_pass}

echo \"All Done Folks.\"

rm -f {$xml_file}
rm -f /root/mw_install_for_user.sh


";

    file_put_contents('/root/mw_install_for_user.sh', $install_aps_bash);
    shell_exec("chmod +x /root/mw_install_for_user.sh");
    shell_exec("/root/mw_install_for_user.sh");
} else {
    return;
}
