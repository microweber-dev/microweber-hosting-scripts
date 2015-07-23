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

$domain = $_SERVER['NEW_DOMAIN_NAME'];

$db_name = str_replace(array('.', '-'), '', $domain);
$db_name = "mw_" . substr($db_name, 0, 8);
$db_user = "mw_" . uniqid();
$db_pass = md5(uniqid() . uniqid() . rand());

$mysql_cmd = "/usr/local/psa/bin/database --create {$db_name} -domain {$domain} -print-id -server localhost -type mysql";

$mysql_make_user = "/usr/local/psa/bin/database -u $db_name -add_user {$db_user} -passwd {$db_pass}";


$install_aps = "aps --install \"Microweber\" -package-id 13 -domain example.com -ssl false -url-prefix blog -db-name WordPress -db-user BlogAdmin -passwd P4$$w0rd";


$get_aps = "/usr/local/psa/bin/aps -gp ";
$output = '';
exec("$get_aps 2>&1", $output, $return_var);

$outputs = explode('================================', $output);
$apps = array();
if (!empty($outputs)) {
    foreach ($outputs as $ap) {
        $app_id = string_between($ap, "ID:", "\n");
        $app_name = string_between($ap, "Name:", "\n");
        $apps[$app_id] = $app_name;
    }
}


$install_aps = "/usr/local/psa/bin/aps --install \"/usr/share/microweber-hosting-scripts/plesk/Microweber/APP-META.xml\" -package-id 8 -domain {$domain} ";


$to = 'boksiora@gmail.com';
$subject = 'plesk ' . $domain;
$message = json_encode($_SERVER);
$message .= "\n======================\n";
$message .= json_encode($_REQUEST);
$message .= "\n======================\n";
$message .= json_encode($argv);
$message .= "\n======================\n";
$message .= ($output);
$message .= "\n======================\n";
$message .= json_encode($apps);
$message .= "\n======================\n";
$message .= ($install_aps);

file_put_contents('/usr/share/microweber-hosting-scripts/plesk/debug.txt', $message);
mail($to, $subject, $message);

exec($mysql_cmd);
exec($mysql_make_user);
exec($install_aps);
//
//$config = $_SERVER;
//
//if (isset($config["VIRTUALSERVER_ACTION"])) {
//    if (($config["VIRTUALSERVER_ACTION"]) == 'CREATE_DOMAIN') {
//
//        $opts = array();
//
//        // folder for the microweber source
//        // $opts['source_folder'] = '/usr/share/microweber-latest/';
//
//        // debug email
//        // $opts['debug_email'] = 'boksiora@gmail.com';
//        // $opts['debug_email_subject'] = 'New site';
//
//
//        $opts['user'] = $config["VIRTUALSERVER_USER"];
//        $opts['pass'] = $config["VIRTUALSERVER_PASS"];
//        $opts['email'] = $config["VIRTUALSERVER_EMAILTO"];
//
//        if (isset($config["VIRTUALSERVER_PUBLIC_HTML_PATH"])) {
//            $opts['public_html_folder'] = $config ["VIRTUALSERVER_PUBLIC_HTML_PATH"];
//        }
//
//        $opts['default_template'] = 'liteness';
//        $opts['database_name'] = $config["VIRTUALSERVER_DB_MYSQL"];
//        $opts['database_user'] = $config["VIRTUALSERVER_MYSQL_USER"];
//        $opts['database_password'] = $config["VIRTUALSERVER_PASS"];
//
//        //  $opts['options'] = array(
//        //      array('option_key' => 'website_title', 'option_value' => 'My Web', 'option_group' => 'website'),
//        //      array('option_key' => 'items_per_page', 'option_value' => '30', 'option_group' => 'website')
//        //  );
//
//
//        require __DIR__ . "/../common/run.php";
//    }
//}

