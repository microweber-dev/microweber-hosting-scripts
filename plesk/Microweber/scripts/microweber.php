<?php


if (!function_exists('get_table_prefix')) {
    function get_table_prefix($db_modify_hash, $psa_modify_hash, $db_ids=array())
    {
        $config_content = read_file($psa_modify_hash['@@ROOT_DIR@@'] . '/wp-config.php');
        if (preg_match('#^\$table_prefix *= *[\'\"]([^\'\"]*)[\'\"]#m', $config_content, $mres)) {
            $db_modify_hash['@@DB_MAIN_PREFIX@@'] = $mres[1];
        }
        return $db_modify_hash['@@DB_MAIN_PREFIX@@'];
    }
}

if (!function_exists('get_additional_modify_hash')) {
    function get_additional_modify_hash($parameters = array())
    {
        return $parameters;
    }
}