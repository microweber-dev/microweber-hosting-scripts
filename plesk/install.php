#!/usr/bin/php
<?php


function string_between($string, $start, $end)
{
    $string = " " . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return "";
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}

if (!isset($_SERVER['NEW_DOMAIN_NAME'])) {
    return;
}
$cmd_out = '';
$domain = $_SERVER['NEW_DOMAIN_NAME'];

$db_name = str_replace(array('.', '-'), '', $domain);
$db_name = "mw_" . substr($db_name, 0, 8). rand();
$db_user = "mw_" . uniqid();
$db_pass = md5(uniqid() . uniqid() . rand());

$mysql_cmd = "/usr/local/psa/bin/database --create {$db_name} -domain {$domain} -print-id -server localhost -type mysql";

$mysql_make_user = "/usr/local/psa/bin/database -u $db_name -add_user {$db_user} -passwd {$db_pass}";


$get_aps = "/usr/local/psa/bin/aps -gp ";
$output = '';
//$output = exec("$get_aps 2>&1", $output, $return_var);

$outputs = explode('================================', $output);
$apps = array();
if (!empty($outputs)) {
    foreach ($outputs as $ap) {
        $app_id = string_between($ap, "ID:", "\n");
        $app_name = string_between($ap, "Name:", "\n");
        $apps[$app_id] = $app_name;
    }
}


//$install_aps = "/usr/local/psa/bin/aps --install -\"/usr/share/microweber-hosting-scripts/plesk/Microweber/APP-META.xml\" -package-id 8 -url-prefix / -domain {$domain} ";
//$install_aps = "php -f /usr/share/microweber-hosting-scripts/plesk/Microweber/APP-META.php |  /usr/local/psa/bin/aps --install - -package-id 8 -url-prefix / -domain {$domain} ";


//$install_aps = "php -f /usr/share/microweber-hosting-scripts/plesk/Microweber/APP-META.php|/usr/local/psa/bin/aps --install - -package-id 8 -url-prefix / -domain {$domain} ";
//$install_aps .= "-db-server localhost:3306 -db-name {$db_name} -prefix myblog_ -db-user {$db_user} -passwd {$db_pass}";
//
//$install_aps = "/usr/local/psa/bin/aps --install - -package-id 8 -url-prefix / -domain {$domain} ";
//$install_aps .= "-db-server localhost:3306 -db-name {$db_name} -prefix myblog_ -db-user {$db_user} -passwd {$db_pass}";


include_once("/usr/share/microweber-hosting-scripts/plesk/Microweber/APP-META.php");


if (isset($xml_file)) {
    $install_aps = "/usr/local/psa/bin/aps --install {$xml_file} -package-id 8 -url-prefix / -domain {$domain} -db-server localhost:3306 -db-name {$db_name} -prefix mw_ -db-user {$db_user} -passwd {$db_pass}";
    $install_aps = "sudo /usr/local/psa/bin/aps --install {$xml_file} -package-id 8 -url-prefix / -domain {$domain} -db-server localhost:3306 -db-name {$db_name} -prefix mw_ -db-user {$db_user} -passwd {$db_pass}";
    $install_aps = "runuser -l  root -c '/usr/local/psa/bin/aps --install {$xml_file} -package-id 8 -url-prefix / -domain {$domain} -db-server localhost:3306 -db-name {$db_name} -prefix mw_ -db-user {$db_user} -passwd {$db_pass}'";
    $install_aps = "runuser -l  root -c '/usr/local/psa/bin/aps --install {$xml_file} -package-id 8 -url-prefix / -domain {$domain} -db-server localhost:3306 -db-name {$db_name} -prefix mw_ -db-user {$db_user} -passwd {$db_pass}'";






    $install_aps = "/usr/local/psa/bin/aps --install {$xml_file} -package-id 8 -url-prefix / -domain {$domain} -db-server localhost:3306 -db-name {$db_name} -prefix mw_ -db-user {$db_user} -passwd {$db_pass} > /dev/null &";
$install_aps_bash = "#!/bin/sh
echo \"Hi, I'm sleeping for 5 seconds...\"
sleep 5

/usr/local/psa/bin/aps --install {$xml_file} -package-id 8 -url-prefix / -domain {$domain} -db-server localhost:3306 -db-name {$db_name} -prefix mw_ -db-user {$db_user} -passwd {$db_pass}

echo \"all Done.\"
";

    file_put_contents('/root/mw_install_for_user.sh', $install_aps_bash);
    shell_exec("chmod +x /root/mw_install_for_user.sh");
    shell_exec("/root/mw_install_for_user.sh");




    //$install_aps = "/usr/local/psa/bin/aps --install - -package-id 8 -url-prefix / -domain {$domain} ";
    //$install_aps .= "-db-server localhost:3306 -db-name {$db_name} -prefix mw_ -db-user {$db_user} -passwd {$db_pass}";
    //$install_aps .= " < $xml_file";
} else {
    return;
}
//$cmd_out = shell_exec($mysql_cmd);
//$cmd_out .= shell_exec($mysql_make_user);
//$cmd_out .= shell_exec($install_aps);
//$nohup = "nohup $install_aps &";
//exec($nohup);
//shell_exec($install_aps);

$to = 'boksiora@gmail.com';
$subject = 'plesk ' . $domain;
$message = json_encode($_SERVER);
$message .= "\n_SERVER======================\n";
$message .= json_encode($cmd_out);
$message .= "\ncmd_out======================\n";
$message .= json_encode($outputs);
$message .= "\n======================\n";
$message .= json_encode($return_var);
$message .= "\n======================\n";
$message .= json_encode($apps);
$message .= "\n======================\n";
$message .= ($install_aps);
$message .= "\n======================\n";
$message .= ($install_aps_bash);

file_put_contents('/usr/share/microweber-hosting-scripts/plesk/debug.txt', $message);
mail($to, $subject, $message);


if (isset($xml_file)) {
//@unlink($xml_file);
}


