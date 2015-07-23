<?php

function generate_password($length = 12)
{
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()";
    $password = '';
    for ($i = 0; $i < $length; $i++)
        $password .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
    return $password;
}

function fetch_env_var($envvar)
{
    $res = getenv($envvar);
    if ($res === False)
        return NULL;
    return $res;
}

function get_psa_modify_hash($params)
{
    $scheme = fetch_env_var("BASE_URL_SCHEME");
    $host = fetch_env_var("BASE_URL_HOST");
    $port = fetch_env_var("BASE_URL_PORT");
    $path = fetch_env_var("BASE_URL_PATH");

    $full = $scheme . "://" . $host . (($port !== NULL && strlen($port) > 0) ? ":$port" : "") . ($path[0] == "/" ? "" : "/") . $path;

    $parameters = array();
    $parameters["@@" . "BASE_URL_SCHEME" . "@@"] = $scheme;
    if ($scheme == 'http') {
        $parameters["@@" . "SSL_ENABLED" . "@@"] = 0;
        $parameters["@@" . "SSL_ENABLED_YN" . "@@"] = 'n';
    } else if ($scheme == 'https') {
        $parameters["@@" . "SSL_ENABLED" . "@@"] = 1;
        $parameters["@@" . "SSL_ENABLED_YN" . "@@"] = 'y';
    }
    $parameters["@@" . "BASE_URL_HOST" . "@@"] = $host;
    $parameters["@@" . "BASE_URL_PORT" . "@@"] = $port;

    $my_url_path = $path;
    $my_urlwls_path = $path;
    if ($my_url_path == "/") {
        $my_url_path = "";
        $my_urlwls_path = $my_url_path;
    } else if ($my_url_path[strlen($my_url_path) - 1] == "/") {
        $my_url_path = substr($my_url_path, 0, strlen($my_url_path) - 1);
        $my_urlwls_path = "/" . $my_url_path;
    }
    $parameters["@@" . "BASE_URL_PATH" . "@@"] = $my_url_path;
    $parameters["@@" . "INSTALL_PREFIX_WLS" . "@@"] = $my_urlwls_path;

    $my_root_url = $full;
    if ($my_root_url[strlen($my_root_url) - 1] == "/") {
        $my_root_url = substr($my_root_url, 0, strlen($my_root_url) - 1);
    }
    $parameters["@@" . "ROOT_URL" . "@@"] = $my_root_url;

    $my_web_dir = fetch_env_var("WEB___DIR");
    while ($my_web_dir[strlen($my_web_dir) - 1] == "/") {
        $my_web_dir = substr($my_web_dir, 0, strlen($my_web_dir) - 1);
    }
    $parameters["@@" . "ROOT_DIR" . "@@"] = $my_web_dir;
    $parameters["@@" . "SER_ROOT_DIR" . "@@"] = serialize($my_web_dir);
    $parameters["@@" . "SER_TEMP_DIR" . "@@"] = serialize($my_web_dir . DIRECTORY_SEPARATOR . 'tmp');
    $parameters['@@VAR_BASE_URL_HOST@@'] = serialize($parameters['@@BASE_URL_HOST@@']);

    return $parameters;
}

function get_db_type($db_id)
{
    return fetch_env_var("DB_${db_id}_TYPE");
}

function get_db_name($db_id)
{
    return fetch_env_var("DB_${db_id}_NAME");
}

function get_db_login($db_id)
{
    return fetch_env_var("DB_${db_id}_LOGIN");
}

function get_db_password($db_id)
{
    return fetch_env_var("DB_${db_id}_PASSWORD");
}

function get_db_prefix($db_id)
{
    if (fetch_env_var("DB_${db_id}_PREFIX") !== False) {
        return fetch_env_var("DB_${db_id}_PREFIX");
    } else {
        return '';
    }
}

function get_db_address($db_id)
{
    $db_address = fetch_env_var("DB_${db_id}_HOST");
    if (fetch_env_var("DB_${db_id}_PORT") !== False)
        $db_address .= ':' . fetch_env_var("DB_${db_id}_PORT");

    return $db_address;
}

function get_db_modify_hash($db_ids)
{
    $parameters = array();
    foreach ($db_ids as $db_id) {
        $parameters["@@" . "DB_" . strtoupper($db_id) . "_TYPE" . "@@"] = get_db_type($db_id);
        $parameters["@@" . "DB_" . strtoupper($db_id) . "_NAME" . "@@"] = get_db_name($db_id);
        $parameters["@@" . "DB_" . strtoupper($db_id) . "_LOGIN" . "@@"] = get_db_login($db_id);
        $parameters["@@" . "DB_" . strtoupper($db_id) . "_PASSWORD" . "@@"] = get_db_password($db_id);
        $parameters["@@" . "DB_" . strtoupper($db_id) . "_HOST" . "@@"] = fetch_env_var("DB_${db_id}_HOST");
        $parameters["@@" . "DB_" . strtoupper($db_id) . "_VERSION" . "@@"] = fetch_env_var("DB_${db_id}_VERSION");
        $parameters["@@" . "DB_" . strtoupper($db_id) . "_PORT" . "@@"] = fetch_env_var("DB_${db_id}_PORT");
        $parameters["@@" . "DB_" . strtoupper($db_id) . "_PREFIX" . "@@"] = get_db_prefix($db_id);
        $parameters["@@" . "DB_" . strtoupper($db_id) . "_ADDRESS" . "@@"] = get_db_address($db_id);
    }
    return $parameters;
}

function get_web_dir($web_id)
{
    $web_id_parameter = str_replace("/", "_", $web_id);
    return fetch_env_var("WEB_${web_id_parameter}_DIR");
}

function get_web_modify_hash($web_ids)
{
    $parameters = array();
    foreach ($web_ids as $web_id) {
        $web_id_parameter = str_replace("/", "_", $web_id);
        $parameters["@@" . strtoupper($web_id) . "_DIR" . "@@"] = fetch_env_var("WEB_${web_id_parameter}_DIR");
    }

    return $parameters;
}

function get_settings_modify_hash($params)
{
    $parameters = array();
    foreach ($params as $param) {
        $parameters["@@" . strtoupper($param) . "@@"] = fetch_env_var("SETTINGS_${param}");
        $parameters['@@VAR_' . strtoupper($param) . "@@"] = serialize($parameters["@@" . strtoupper($param) . "@@"]);
    }
    $parameters['@@VAR_INSTALLED_TIME@@'] = serialize(time());

    return $parameters;
}

function get_settings_enum_modify_hash($enum_params)
{
    $parameters = array();
    foreach ($enum_params as $param_id => $elements_ids_map) {
        $param_value = fetch_env_var("SETTINGS_${param_id}");
        foreach ($elements_ids_map as $element_id => $value_for_app) {
            if ($element_id == $param_value) {
                $parameters["@@" . strtoupper($param_id) . "@@"] = $value_for_app;
            }
        }
    }

    return $parameters;
}

function get_crypt_settings_modify_hash($crypt_params)
{
    $parameters = array();
    foreach ($crypt_params as $param) {

        $parameters["@@" . strtoupper($param) . "@@"] = (fetch_env_var("SETTINGS_${param}"));
    }

    return $parameters;
}

function is_linux()
{
    $pwd = getcwd();
    if (preg_match("/:/", $pwd)) {
        return 0;
    }
    return 1;
}

?>
