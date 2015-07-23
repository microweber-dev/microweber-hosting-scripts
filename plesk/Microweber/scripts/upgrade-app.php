<?php
require_once('app-util.php');
require_once('file-util.php');

function upgrade_app($from_ver, $from_rel, $config_files, $db_ids, $psa_modify_hash, $db_modify_hash, $settings_modify_hash, $crypt_settings_modify_hash, $settings_enum_modify_hash, $additional_modify_hash)
{
    $upgrade_schema_files = array();
    configure($config_files, $upgrade_schema_files, $db_ids, $psa_modify_hash, $db_modify_hash, $settings_modify_hash, $crypt_settings_modify_hash, $settings_enum_modify_hash, $additional_modify_hash);


    return 0;
}

?>
