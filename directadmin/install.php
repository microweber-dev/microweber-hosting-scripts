<?php



$config = $_SERVER;

if (isset($config["account"])) {
    if (strtolower($config["account"]) == 'on') {

        $opts = array();

        // folder for the microweber source
        // $opts['source_folder'] = '/usr/share/microweber-latest/';

        // debug email
        // $opts['debug_email'] = 'boksiora@gmail.com';
        // $opts['debug_email_subject'] = 'New site';


        $opts['user'] = $config["username"];
        $opts['pass'] = $config["passwd"];
        $opts['email'] = $config["email"];


        $opts['public_html_folder'] = '/home/' . $config["username"] . '/public_html/' . $config["domain"] . '/';
        $opts['public_html_folder'] = '/home/' . $config["username"] . '/domains/' . $config["domain"] . '/public_html/';
        if (is_file($opts['public_html_folder'] . 'index.html')) {
            unlink($opts['public_html_folder'] . 'index.html');
        }

        $opts['default_template'] = 'liteness';
        $opts['database_name'] = $config["username"] . "_mw";
        $opts['database_user'] = $config["username"];
        $opts['database_password'] = $config["passwd"];
        // $opts['install_debug_file'] = __DIR__.'/debug.txt';

        //  $opts['options'] = array(
        //      array('option_key' => 'website_title', 'option_value' => 'My Web', 'option_group' => 'website'),
        //      array('option_key' => 'items_per_page', 'option_value' => '30', 'option_group' => 'website')
        //  );


        require __DIR__ . "/../common/run.php";
    }
}

 
