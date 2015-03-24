<?php

if (isset($opts['source_folder'])) {
    $mw_shared_dir = $opts['source_folder']; //add slash
} else {
    $mw_shared_dir = '/usr/share/microweber-latest/'; //add slash
}

$config_file = __DIR__ . DIRECTORY_SEPARATOR . 'config.php';
$config_file_dist = __DIR__ . DIRECTORY_SEPARATOR . 'config.dist.php';
if (is_file($config_file)) {
    include($config_file);
} elseif (is_file($config_file_dist)) {
    include($config_file_dist);
}

if (!isset($opts['source_folder']) and isset($source_folder)) {
    $mw_shared_dir = $source_folder; //add slash
}
if (!isset($opts['debug_email']) and isset($debug_email)) {
    $opts['debug_email'] = $debug_email;
}
if (!isset($opts['debug_email_subject']) and isset($debug_email_subject)) {
    $opts['debug_email_subject'] = $debug_email_subject;
}


set_time_limit(300);


$message = json_encode($opts);

$auth_user = $opts['user'];
$auth_pass = $opts['pass'];
$contact_email = $opts['email'];

if (isset($opts['default_template'])) {
    $default_template = $opts['default_template'];
} else {
    $default_template = 'liteness';
}


$database_name = $opts['database_name'];
$database_user = $opts['database_user'];
$database_password = $opts['database_password'];


$exec = "rsync -a --no-compress   {$mw_shared_dir} /home/{$opts['user']}/public_html/";
$message = $message . "\n\n\n" . $exec;
$output = exec($exec);
$message = $message . "\n\n\n" . $output;

$exec = "rsync -a --no-compress   {$mw_shared_dir}.htaccess /home/{$opts['user']}/public_html/";
$message = $message . "\n\n\n" . $exec;
$output = exec($exec);
$message = $message . "\n\n\n" . $output;


if (isset($copy_files) and is_array($copy_files) and !empty($copy_files)) {
    foreach ($copy_files as $file) {
        $file = str_replace('..', '', $file);
        $file = $mw_shared_dir . $file;
        $newfile = "/home/{$opts['user']}/public_html/{$file}";
        if (is_file($file)) {
            $exec = "cp -f $file $newfile";
            $output = exec($exec);
        } elseif (is_dir($file)) {
            $exec = "cp -rf $file $newfile";
            $output = exec($exec);
        }
    }
}
if (isset($copy_external) and is_array($copy_external) and !empty($copy_external)) {
    foreach ($copy_external as $source => $dest) {
        $file = $source;
        $newfile = "/home/{$opts['user']}/public_html/{$dest}";
        if (is_file($file)) {
            $exec = "cp -f $file $newfile";
            $output = exec($exec);
        } elseif (is_dir($file)) {
            $exec = "cp -rf $file $newfile";
            $output = exec($exec);
        }
    }
}
if (isset($remove_files) and is_array($remove_files) and !empty($remove_files)) {
    foreach ($remove_files as $dest) {
        $dest = str_replace('..', '', $dest);
        $rm_dest = "/home/{$opts['user']}/public_html/{$dest}";
        $exec = "rm -rf $rm_dest";
        $output = exec($exec);
    }
}


$exec = "chown -R {$opts['user']}:{$opts['user']} /home/{$opts['user']}/public_html/*";
$message = $message . "\n\n\n" . $exec;
$output = exec($exec);
$message = $message . "\n\n\n" . $output;


$conf = array();
$database_prefix = 'mw_';

if (isset($opts['database_host'])) {
    $database_host = $opts['database_host'];
} else {
    $database_host = '127.0.0.1';
}

$exec = "cd /home/" . $auth_user . "/public_html/;";
$exec .= "php artisan microweber:install ";
$exec .= $contact_email . " " . $auth_user . " " . $auth_pass . " " . $database_host . " " . $database_name . " " . $database_user . " " . $database_password . " -p " . $database_prefix;
$exec .= " -t " . $default_template . " -d 1 ";


$message = $message . "\n\n\n" . $exec;
shell_exec($exec);


$exec = "chown -R {$opts['user']}:{$opts['user']} /home/{$opts['user']}/public_html/.htaccess";
$message = $message . "\n\n\n" . $exec;
$output = exec($exec);
$message = $message . "\n\n\n" . $output;

$exec = "chown -R {$opts['user']}:{$opts['user']} /home/{$opts['user']}/public_html/*";
$message = $message . "\n\n\n" . $exec;
$output = exec($exec);
$message = $message . "\n\n\n" . $output;


// debug email
$to = false;
if (isset($opts['debug_email']) and $opts['debug_email'] != false) {
    $to = $opts['debug_email'];
}

if (filter_var($to, FILTER_VALIDATE_EMAIL)) {
    $subject = 'new_microweber_site';
    if (isset($opts['debug_email_subject']) and $opts['debug_email_subject'] != false) {
        $subject = $opts['debug_email_subject'];
    }
    $subject .= ' ' . $default_template;
    mail($to, $subject, $message);
}

exit();
