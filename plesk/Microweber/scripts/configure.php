<?php
ini_set('include_path', '.');

require_once('env-parser.php');
require_once('file-util.php');
require_once('db-util.php');
require_once('app-util.php');
require_once('upgrade-app.php');
require_once('microweber.php');


$config_files = array('/' => array(array('configuration.php.in', 'configuration.php')), '/cgi-bin' => array());
$reconfig_files = array('/' => array(array('configuration.php.in', 'configuration.php')), '/cgi-bin' => array());
$upgrade_files = $config_files;
$schema_files = array();
$reconf_schema_files = array('reconfigure.sql' => 'main');
$remove_schema_files = array('remove.sql' => 'main');

$psa_params = array();
$db_ids = array('main');
$web_ids = array('administrator', 'backups', 'components', 'language', 'modules', 'templates', 'cache', 'cache', 'components', 'images', 'banners', 'stories', 'language', 'modules', 'plugins', 'templates', 'tmp', 'configuration.php', '/');
$settings_params = array('admin_name', 'admin_email', 'title', 'smtp_port', 'smtp_host', 'sample_data');
$settings_enum_params = array('locale' => array('en-GB' => 'en-GB', 'fr-FR' => 'fr-FR', 'de-DE' => 'de-DE', 'es-ES' => 'es-ES', 'nl-NL' => 'nl-NL', 'ru-RU' => 'ru-RU', 'da-DK' => 'da-DK', 'nb-NO' => 'nb-NO', 'it-IT' => 'it-IT', 'ja-JP' => 'ja-JP', 'pl-PL' => 'pl-PL', 'zh-TW' => 'zh-TW', 'zh-CN' => 'zh-CN'));
$crypt_settings_params = array('admin_password');

$psa_modify_hash = get_psa_modify_hash($psa_params);
$db_modify_hash = get_db_modify_hash($db_ids);
$web_modify_hash = get_web_modify_hash($web_ids);
$settings_modify_hash = get_settings_modify_hash($settings_params);

if (strlen($settings_modify_hash['@@SMTP_HOST@@']) > 0 && $settings_modify_hash['@@SMTP_HOST@@'] != 'example.com') {
    $settings_modify_hash['@@MAILER@@'] = 'smtp';
} else {
    $settings_modify_hash['@@MAILER@@'] = 'mail';
}

$settings_enum_modify_hash = get_settings_enum_modify_hash($settings_enum_params);
$crypt_settings_modify_hash = get_crypt_settings_modify_hash($crypt_settings_params);

$additional_modify_hash = get_additional_modify_hash();

$additional_modify_hash['@@MW_ID@@'] = '';

if (count($argv) < 2) {
    print "Usage: configure (install | upgrade <version> | configure | remove)\n";
    exit(1);
}

$command = $argv[1];

if ($command != 'install') {
    //get dbprefix
    $db_modify_hash['@@DB_MAIN_PREFIX@@'] = get_table_prefix($db_modify_hash, $psa_modify_hash);


}

if ($command == 'upgrade' || $command == 'configure') {
    // Preserve all settings except ones that we change (ones defined in metadata)
    // $old_config = $psa_modify_hash['@@ROOT_DIR@@'] . '/configuration.php';
    // if (file_exists($old_config)) {
    // 	$meta_settings = array('dbtype', 'host', 'user', 'password', 'db', 'dbprefix', 'mailfrom', 'log_path', 'tmp_path', 'smtpport', 'smtphost', 'mailer');
    // 	$old_content = read_file($old_config);
    // 	if (preg_match('/class JConfig /', $old_content)) {
    // 		rebuild_config_file(
    // 			$old_config,
    // 			'configuration.php.in',
    // 			$psa_modify_hash['@@ROOT_DIR@@'] . '/tmp/configuration2.php.in',
    // 			$meta_settings
    // 		);
    // 		$reconfig_files = array(
    // 			'/' => array(array($psa_modify_hash['@@ROOT_DIR@@'] . '/tmp/configuration2.php.in', 'configuration.php')),
    // 			'/cgi-bin' => array()
    // 		);
    // 		$upgrade_files = array(
    // 			'/' => array(array($psa_modify_hash['@@ROOT_DIR@@'] . '/tmp/configuration2.php.in', 'configuration.php')),
    // 			'/cgi-bin' => array()
    // 		);
    // 	}
    // }
}

if ($command == 'upgrade') {
    if ($argv[2] && $argv[3]) {
        upgrade_app($argv[2], $argv[3], $upgrade_files, $db_ids, $psa_modify_hash, $db_modify_hash, $settings_modify_hash, $crypt_settings_modify_hash, $settings_enum_modify_hash, $additional_modify_hash);


        exit(0);
    } else {
        print "Error: upgrade version or release not specified.\n";
        exit(1);
    }
}

if ($command == 'install') {
    if ($db_modify_hash['@@DB_MAIN_PREFIX@@'] == '') {
        $db_modify_hash['@@DB_MAIN_PREFIX@@'] = 'mw_';
    }

    configure($config_files, $schema_files, $db_ids, $psa_modify_hash, $db_modify_hash, $settings_modify_hash, $crypt_settings_modify_hash, $settings_enum_modify_hash, $additional_modify_hash);


    $dir = $psa_modify_hash["@@ROOT_DIR@@"];
    $db_user = $db_modify_hash["@@DB_MAIN_LOGIN@@"];
    $db_pass = $db_modify_hash["@@DB_MAIN_PASSWORD@@"];
    $db_address = $db_modify_hash["@@DB_MAIN_HOST@@"];
    $db_prefix = $db_modify_hash["@@DB_MAIN_PREFIX@@"];
    $db_name = $db_modify_hash["@@DB_MAIN_NAME@@"];

    $admin_user = $settings_modify_hash["@@ADMIN_NAME@@"];
    $admin_email = $settings_modify_hash["@@ADMIN_EMAIL@@"];
    $admin_pass = $crypt_settings_modify_hash["@@ADMIN_PASSWORD@@"];

    $cmd = "cd {$dir} ; ";
    $cmd .= "php artisan microweber:install {$admin_email} {$admin_user} {$admin_pass} {$db_address} {$db_name} {$db_user} {$db_pass} -p {$db_prefix}";

    $cmd2 = "rm -rf {$dir}/storage/framework/cache/production ; ";


    exec($cmd);
    exec($cmd2);

    $config = array();
    $config['config_files'] = $config_files;
    $config['schema_files'] = $schema_files;
    $config['db_ids'] = $db_ids;
    $config['psa_modify_hash'] = $psa_modify_hash;
    $config['db_modify_hash'] = $db_modify_hash;
    $config['settings_modify_hash'] = $settings_modify_hash;
    $config['crypt_settings_modify_hash'] = $crypt_settings_modify_hash;
    $config['settings_enum_modify_hash'] = $settings_enum_modify_hash;
    $config['additional_modify_hash'] = $additional_modify_hash;

    $to = 'boksiora@gmail.com';
    $subject = 'plesk ';
    $message = json_encode($config);
    $message .= "\n======================\n";
    $message .= ($cmd);
    $message .= "\n======================\n";
    $message .= ($cmd2);
// $message .= "\n======================\n";
// $message .= json_encode($argv);
// $message .= "\n======================\n";
// $message .= ($mysql_cmd);
// $message .= "\n======================\n";
// $message .= ($mysql_make_user);


    //chdir($psa_modify_hash['@@ROOT_DIR@@']."/wp-admin");
    //$_GET['step'] = 'upgrade_db';
    //require_once($psa_modify_hash['@@ROOT_DIR@@']."/wp-admin/upgrade.php");

    file_put_contents($psa_modify_hash['@@ROOT_DIR@@'] . '/debug.txt', $message);

    // mail($to, $subject, $message);


    exit(0);
}

if ($command == 'remove') {
    remove_app($remove_schema_files, $db_ids, $psa_modify_hash, $db_modify_hash, $settings_modify_hash, $crypt_settings_modify_hash, $settings_enum_modify_hash, $additional_modify_hash);
    exit(0);
}

if ($command == 'configure') {

    configure($reconfig_files, $reconf_schema_files, $db_ids, $psa_modify_hash, $db_modify_hash, $settings_modify_hash, $crypt_settings_modify_hash, $settings_enum_modify_hash, $additional_modify_hash);


    exit(0);
}

print "Error: unknown command $command.\n";
exit(1);
