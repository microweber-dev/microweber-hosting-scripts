<?php
$mw_shared_dir = '/usr/share/microweber_1_0/'; //add slash


set_time_limit(300);
if (!isset($argv) and !isset($opts)) {
    $argv_o = $argv;
// Set up our variables to be usable by PHP
    $opts = array();
    $argv0 = array_shift($argv);

    while (count($argv)) {
        $key = array_shift($argv);
        $value = array_shift($argv);
        $opts[$key] = $value;
    }

}


$shared_install = false;
if (isset($opts['plan']) and $opts['plan'] == 'website') {
    $shared_install = true;

}


$to = 'boksiora@gmail.com';


$subject = 'new_v1_site';
$message = json_encode($opts);
$headers = 'From: admin@microweber.org' . "\r\n" .
    'Reply-To: admin@microweber.org' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();


//set error handling so any warnings are logged
ini_set('error_log', '/usr/local/cpanel/logs/error_log_postwwacct');
ini_set('display_errors', 1);


include_once('/home/cpanelscripthelpers/xmlapi.php');


$json_client = new \xmlapi('localhost');
$auth_user = $opts['user'];
$auth_pass = $opts['pass'];
$contact_email = $opts['contactemail'];

$domain = $opts['domain'];
if($domain == 'petertest16.microweber.com'){
    $mw_shared_dir = '/usr/share/microweber_1_0_TEST/'; //add slash

}

$default_template = 'liteness';


$config_for_domain = "https://members.microweber.com/_sync/site_config/?domain=" . $domain;

$ch = curl_init($config_for_domain);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_HEADER, 0);
$config_for_domain = curl_exec($ch);
curl_close($ch);


//$config_for_domain = file_get_contents($config_for_domain);
if ($config_for_domain) {
    $config_for_domain = @json_decode($config_for_domain, 1);
    if (isset($config_for_domain['template'])) {
        $default_template = $config_for_domain['template'];
    }

}


$subject .= ' ' . $default_template;


$json_client->set_output('json');
//$json_client->set_port(2087);
$json_client->set_port(2083);
$json_client->password_auth($auth_user, $auth_pass);
$json_client->set_debug(1);
$database_name = $auth_user . '_' . uniqid() . rand();
$database_name = substr($database_name, 0, 14);
$database_user = $database_name;
$database_password = md5($auth_user . '_' . rand() . uniqid() . rand());


//create database
$createdb = $json_client->api1_query($auth_user, "Mysql", "adddb", array($database_name));
$usr = $json_client->api1_query($auth_user, "Mysql", "adduser", array($database_user, $database_password));
$addusr = $json_client->api1_query($auth_user, "Mysql", "adduserdb", array($database_name, $database_user, 'all'));
$emailmsg = '';
$result = $ms1 . $message . $emailmsg . "\n\n\n" . $createdb . "\n\n\n" . $createdb . "\n\n\n" . $usr . "\n\n\n" . $addusr . "\n\n\n" . json_encode($opts);


//exec("mkdir /home/{$opts['user']}/public_html/cache/");


$remove_paths = array();
$remove_paths[] = 'config.php';
$remove_paths[] = 'bootstrap.php';
$remove_paths[] = 'index.php';
$remove_paths[] = 'src';
$remove_paths[] = 'cpanel';

$remove_paths[] = 'userfiles/modules/';
$remove_paths[] = 'userfiles/removed_modules';
$remove_paths[] = 'userfiles/templates/';
$remove_paths[] = 'userfiles/elements/';
foreach ($remove_paths as $remove) {
    $exec = "rm -rvf /home/{$opts['user']}/public_html/{$remove}*";
    $message = $message . "\n\n\n" . $exec;
    $output = shell_exec($exec);
    $message = $message . "\n\n\n" . $output;
}

$link_paths = array();

//$link_paths[] = 'components-shared';
$link_paths[] = 'vendor';
////$link_paths[] = 'bootstrap';
$link_paths[] = 'database';
$link_paths[] = 'resources';
$link_paths[] = 'app';


$link_paths[] = 'tests';
//$link_paths[] = 'config/app.php';
//$link_paths[] = 'config/auth.php';
//$link_paths[] = 'config/cache.php';
//$link_paths[] = 'config/compile.php';
//$link_paths[] = 'config/database.php';
//$link_paths[] = 'config/filesystems.php';
//$link_paths[] = 'config/mail.php';
//$link_paths[] = 'config/queue.php';
//$link_paths[] = 'config/services.php';
//$link_paths[] = 'config/session.php';
//$link_paths[] = 'config/view.php';
//$link_paths[] = 'config/workbench.php';


//$link_paths[] = 'components-shared';
//$link_paths[] = 'vendor-shared';

$link_paths[] = 'src';
$link_paths[] = 'userfiles/modules/admin';

$link_paths[] = 'userfiles/elements';
$link_paths[] = 'userfiles/templates/default';
$link_paths[] = 'userfiles/templates/liteness';


$link_paths[] = 'userfiles/modules/audio';
$link_paths[] = 'userfiles/modules/admin';

// B
$link_paths[] = 'userfiles/modules/btn';
$link_paths[] = 'userfiles/modules/breadcrumb';

// C
$link_paths[] = 'userfiles/modules/content';
$link_paths[] = 'userfiles/modules/categories';
$link_paths[] = 'userfiles/modules/comments';
$link_paths[] = 'userfiles/modules/contact_form';
$link_paths[] = 'userfiles/modules/custom_fields';

// D


// E
$link_paths[] = 'userfiles/modules/embed';
$link_paths[] = 'userfiles/modules/editor';

// F
$link_paths[] = 'userfiles/modules/files';
$link_paths[] = 'userfiles/modules/forms';
//$link_paths[] = 'userfiles/modules/free'.DS;

//G
$link_paths[] = 'userfiles/modules/google_maps';


//H
$link_paths[] = 'userfiles/modules/help';
$link_paths[] = 'userfiles/modules/highlight_code';


//I
$link_paths[] = 'userfiles/modules/ip2country';

//L
$link_paths[] = 'userfiles/modules/layout';


// M
$link_paths[] = 'userfiles/modules/media';
$link_paths[] = 'userfiles/modules/mics';
$link_paths[] = 'userfiles/modules/menu';
$link_paths[] = 'userfiles/modules/microweber';


//N
//$link_paths[] = 'userfiles/modules/nav'.DS;
//$link_paths[] = 'userfiles/modules/newsletter'.DS;


//O
$link_paths[] = 'userfiles/modules/options';


//P
$link_paths[] = 'userfiles/modules/picture';
$link_paths[] = 'userfiles/modules/pictures';
$link_paths[] = 'userfiles/modules/posts';
$link_paths[] = 'userfiles/modules/pages';


//S
$link_paths[] = 'userfiles/modules/settings';
$link_paths[] = 'userfiles/modules/shop';
$link_paths[] = 'userfiles/modules/search';

$link_paths[] = 'userfiles/modules/site_stats';


// T
$link_paths[] = 'userfiles/modules/text';
$link_paths[] = 'userfiles/modules/title';


// U
$link_paths[] = 'userfiles/modules/users';
$link_paths[] = 'userfiles/modules/updates';
$link_paths[] = 'userfiles/modules/user_profile';
$link_paths[] = 'userfiles/modules/user_search';
$link_paths[] = 'userfiles/modules/users_list';

// V
$link_paths[] = 'userfiles/modules/video';

$link_paths[] = 'userfiles/modules/default.php';
$link_paths[] = 'userfiles/modules/default.png';
$link_paths[] = 'userfiles/modules/non_existing.php';


// new


$link_paths[] = 'userfiles/templates/liteness';
$link_paths[] = 'userfiles/templates/shopmag';
$link_paths[] = 'userfiles/templates/darock';
$link_paths[] = 'userfiles/templates/photon';
$link_paths[] = 'userfiles/templates/corp';

$link_paths[] = 'userfiles/modules/social_links';
$link_paths[] = 'userfiles/modules/logo';



$copy_files = array();
$copy_files[] = 'index.php';
$copy_files[] = '.htaccess';

$copy_files[] = 'composer.json';
$copy_files[] = 'artisan';
//$copy_files[] = 'app';
$copy_files[] = 'config';

//$copy_files[] = 'config/app.php';
//$copy_files[] = 'config/auth.php';
//$copy_files[] = 'config/cache.php';
//$copy_files[] = 'config/compile.php';
//$copy_files[] = 'config/database.php';
//$copy_files[] = 'config/filesystems.php';
//$copy_files[] = 'config/mail.php';
//$copy_files[] = 'config/queue.php';
//$copy_files[] = 'config/services.php';
//$copy_files[] = 'config/session.php';
//$copy_files[] = 'config/view.php';
//$copy_files[] = 'config/workbench.php';
$copy_files[] = 'bootstrap/app.php';
$copy_files[] = 'bootstrap/autoload.php';


$mkdirs = array();
//$mkdirs[] = 'config';
//$mkdirs[] = 'config/' . $domain;
$mkdirs[] = 'storage';
$mkdirs[] = 'storage/framework';
$mkdirs[] = 'storage/framework/sessions';
$mkdirs[] = 'storage/framework/views';
$mkdirs[] = 'storage/cache';
$mkdirs[] = 'storage/logs';
$mkdirs[] = 'storage/app';

$storage_dirs = $mkdirs;
$mkdirs[] = 'bootstrap';
$mkdirs[] = 'userfiles';
$mkdirs[] = 'userfiles/media';
$mkdirs[] = 'userfiles/modules';
$mkdirs[] = 'userfiles/templates';
foreach ($mkdirs as $link) {
    $mk = "/home/{$opts['user']}/public_html/{$link}";
    if (!is_dir($mk)) {
        $exec = "mkdir {$mk}";
        $message = $message . "\n\n\n" . $exec;
        $output = shell_exec($exec);
        $message = $message . "\n\n\n" . $output;


    }
    $exec = "chown -R {$opts['user']}:{$opts['user']} {$mk}";
    $message = $message . "\n\n\n" . $exec;
    $output = exec($exec);
}
foreach ($copy_files as $link) {


    $file = $mw_shared_dir . $link;
    $newfile = "/home/{$opts['user']}/public_html/{$link}";


    if (is_file($file)) {
        if (!copy($file, $newfile)) {

        }
    } elseif (is_dir($file)) {
        $exec = "cp -R $file $newfile";
        $output = shell_exec($exec);
        $message = $message . "\n\n\n" . $exec;
        $exec = "chown -R {$opts['user']}:{$opts['user']} {$newfile}";
        $output = exec($exec);
    }

}
foreach ($link_paths as $link) {
    $exec = "rm -rvf /home/{$opts['user']}/public_html/{$link}";
    $message = $message . "\n\n\n" . $exec;
    $output = shell_exec($exec);
    $message = $message . "\n\n\n" . $output;

    $exec = " ln -s  {$mw_shared_dir}{$link} /home/{$opts['user']}/public_html/{$link}";

    $message = $message . "\n\n\n" . $exec;
    $output = shell_exec($exec);
    $message = $message . "\n\n\n" . $output;

}


$exec = "chown -R {$opts['user']}:{$opts['user']} /home/{$opts['user']}/public_html/*";
$message = $message . "\n\n\n" . $exec;
$output = exec($exec);
$message = $message . "\n\n\n" . $output;


$conf = array();
$database_prefix = 'mw_';


$conf['db_host'] = $database_host = '127.0.0.1';
$conf['db_user'] = $database_user;
$conf['db_pass'] = $database_password;
$conf['with_default_content'] = 'yes';
$conf['default_template'] = $default_template;


//

$exec = "chown -R {$opts['user']}:{$opts['user']} /home/{$opts['user']}/public_html/userfiles/media/";
$message = $message . "\n\n\n" . $exec;
$output = exec($exec);
$message = $message . "\n\n\n" . $output;


$exec = "cd /home/" . $auth_user . "/public_html/;";
$exec .= "php artisan microweber:install ";
$exec .= $contact_email . " " . $auth_user . " " . $auth_pass . " " . $database_host . " " . $database_name . " " . $database_user . " " . $database_password . " -p " . $database_prefix;
$exec .= " -t " . $default_template . " -d 1 --env={$domain}";


$message = $message . "\n\n\n" . $exec;
shell_exec($exec);


$exec = "chown -R {$opts['user']}:{$opts['user']} /home/{$opts['user']}/public_html/.htaccess";
$message = $message . "\n\n\n" . $exec;
$output = exec($exec);
$message = $message . "\n\n\n" . $output;


$exec = "rm -rvf /home/{$opts['user']}/public_html/storage/framework/cache/*";
$message = $message . "\n\n\n" . $exec;
$output = shell_exec($exec);
$message = $message . "\n\n\n" . $output;


foreach ($storage_dirs as $link) {
    $mk = "/home/{$opts['user']}/public_html/{$link}";
    if (!is_dir($mk)) {
        $exec = "mkdir {$mk}";
        $message = $message . "\n\n\n" . $exec;
        $output = shell_exec($exec);
        $message = $message . "\n\n\n" . $output;


    }
    $exec = "chown -R {$opts['user']}:{$opts['user']} {$mk}";
    $message = $message . "\n\n\n" . $exec;
    $output = exec($exec);
}
$exec = "chown -R {$opts['user']}:{$opts['user']} /home/{$opts['user']}/public_html/config/";
$message = $message . "\n\n\n" . $exec;
$output = exec($exec);
$message = $message . "\n\n\n" . $output;

$exec = "chown -R {$opts['user']}:{$opts['user']} /home/{$opts['user']}/public_html/storage/";
$message = $message . "\n\n\n" . $exec;
$output = exec($exec);
$message = $message . "\n\n\n" . $output;

$exec = "chown -R {$opts['user']}:{$opts['user']} /home/{$opts['user']}/public_html/userfiles/media/";
$message = $message . "\n\n\n" . $exec;
$output = exec($exec);
$message = $message . "\n\n\n" . $output;

mail($to, $subject, $message, $headers);


exit();
