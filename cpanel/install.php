#!/usr/bin/php
<?php


$config = $_SERVER;

if (isset($opts['domain'])) {
    if (isset($config["plan"])) {
		if (isset($opts)) {
		     $opts = array();
		}
        // folder for the microweber source
        // $opts['source_folder'] = '/usr/share/microweber-latest/';

        // debug email
        // $opts['debug_email'] = 'boksiora@gmail.com';
        // $opts['debug_email_subject'] = 'New site';
		// $msg = print_r($opts,true);
        // mail("boksiora@gmail.com","debug",$msg);

		
		if(is_file('/home/cpanelscripthelpers/xmlapi.php')){
		require('/home/cpanelscripthelpers/xmlapi.php');
		} else {
		require(__DIR__.'/cpanelscripthelpers/xmlapi.php');
		}

		$auth_user = $opts['user'];
		$auth_pass = $opts['pass'];
		$contact_email = $opts['contactemail'];

		$json_client = new \xmlapi('localhost');
		
		
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
		
		
        require __DIR__ . "/../common/run.php";
    }
}

