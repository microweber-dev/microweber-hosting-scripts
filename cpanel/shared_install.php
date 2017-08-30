<?php

if (isset($_SERVER['argv'])) {
    $argv = $_SERVER['argv'];
}

if (!isset($opts)) {
    $opts = array();
    $argv0 = array_shift($argv);

    while (count($argv)) {
        $key = array_shift($argv);
        $value = array_shift($argv);
        $opts[$key] = $value;
    }
}
$config = $_SERVER;

include(__DIR__ . DIRECTORY_SEPARATOR . 'paths.php');
if (!is_dir($mw_shared_dir)) {
    // download latest
    include(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'common/download.php');
}

if (isset($opts['domain'])) {
    if (isset($opts["plan"])) {

        // ini_set('error_log', __DIR__ . DIRECTORY_SEPARATOR . 'my_file.log');
        // folder for the microweber source
        // $opts['source_folder'] = '/usr/share/microweber-latest/';

        // debug email
        // $opts['debug_email'] = 'email@test.com';
        // $opts['debug_email_subject'] = 'debug';


        $auth_user = $opts['user'];
        $auth_pass = $opts['pass'];
        $contact_email = $opts['contactemail'];


        $database_name = $auth_user . '_' . uniqid() . rand();
        $database_name = substr($database_name, 0, 14);
        $database_user = $database_name;
        $database_password = md5($auth_user . '_' . rand() . uniqid() . rand());


        $opts['user'] = $opts['user'];
        $opts['pass'] = $opts['pass'];
        $opts['email'] = $opts['contactemail'];
        $opts['default_template'] = 'liteness';
        $opts['database_name'] = $database_name;
        $opts['database_user'] = $database_user;
        $opts['database_password'] = $database_password;

        //  $opts['options'] = array(
        //      array('option_key' => 'website_title', 'option_value' => 'My Web', 'option_group' => 'website'),
        //      array('option_key' => 'items_per_page', 'option_value' => '30', 'option_group' => 'website')
        //  );

        if (isset($remove_paths) and $remove_paths) {
            foreach ($remove_paths as $remove) {
                $exec = "rm -rvf /home/{$opts['user']}/public_html/{$remove}*";
                $message = $message . "\n\n\n" . $exec;
                $output = shell_exec($exec);
                $message = $message . "\n\n\n" . $output;
            }
        }

        if (isset($mkdirs) and $mkdirs) {
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
        }

        if (isset($copy_files) and $copy_files) {
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
        }

        if (isset($link_paths) and $link_paths) {
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


        $exec = "chown -R {$opts['user']}:{$opts['user']} /home/{$opts['user']}/public_html/userfiles/media/";
        $message = $message . "\n\n\n" . $exec;
        $output = exec($exec);
        $message = $message . "\n\n\n" . $output;


        $exec = "cd /home/" . $auth_user . "/public_html/;";
        $exec .= "php artisan microweber:install ";
        $exec .= $contact_email . " " . $auth_user . " " . $auth_pass . " " . $database_host . " " . $sqlite_file . " " . $database_user . " " . $database_password . " sqlite -p " . $database_prefix;
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

        if (isset($storage_dirs) and $storage_dirs) {
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
    }
}
 
