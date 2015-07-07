#!/usr/bin/php
<?php


$config = $_SERVER;
var_dump($config);
if (isset($config["VIRTUALSERVER_ACTION"])) {
    if (($config["VIRTUALSERVER_ACTION"]) == 'CREATE_DOMAIN') {

        $opts = array();

        // folder for the microweber source
        // $opts['source_folder'] = '/usr/share/microweber-latest/';

        // debug email
        $opts['debug_email'] = 'boksiora@gmail.com';
        $opts['debug_email_subject'] = 'New site';


        $opts['user'] = $config["VIRTUALSERVER_USER"];
        $opts['pass'] = $config["VIRTUALSERVER_PASS"];
        $opts['email'] = $config["VIRTUALSERVER_EMAILTO"];
		
		if(isset($config["VIRTUALSERVER_PUBLIC_HTML_PATH"])){
			$opts['public_html_folder'] = $config ["VIRTUALSERVER_PUBLIC_HTML_PATH"];
		}
		
        $opts['default_template'] = 'liteness';
        $opts['database_name'] = $config["VIRTUALSERVER_DB_MYSQL"];
        $opts['database_user'] = $config["VIRTUALSERVER_MYSQL_USER"];
        $opts['database_password'] = $config["VIRTUALSERVER_PASS"];

        //  $opts['options'] = array(
        //      array('option_key' => 'website_title', 'option_value' => 'My Web', 'option_group' => 'website'),
        //      array('option_key' => 'items_per_page', 'option_value' => '30', 'option_group' => 'website')
        //  );
		
		
        require __DIR__ . "/../common/run.php";
    }
}

