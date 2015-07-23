<?php

function print_stderr($message)
{
    fwrite(STDERR, $message);
}

function read_file($file)
{
    return fread(fopen($file, 'r'), filesize($file));
}

function write_file($file, $content)
{
    $fp = fopen($file, 'wb');
    if (!$fp)
    {
        print "Unable to write file $file.\n";
        exit(1);
    }
    fputs($fp, $content, strlen($content));
    fclose($fp);
}

function php_quote($val)
{
    $res_val = str_replace("\\", "\\\\", $val);
    $res_val = str_replace("'", "\\'", $res_val);
    return $res_val;
}

function modify_file($file_source, $file_dest, $modify_hash)
{
    $file_content = read_file($file_source);
    foreach($modify_hash as $param => $val){
        $file_content = str_replace($param, php_quote($val), $file_content);
    }
    write_file($file_dest, $file_content);
}

function modify_content($file_source, $modify_hash)
{
    $file_content = read_file($file_source);
    foreach($modify_hash as $param => $val){
        $file_content = str_replace($param, mysql_real_escape_string($val), $file_content);
    }
    return $file_content;
}

function get_upgrade_schema_files($from_ver, $from_rel)
{
    $upgrade_schema_files;
    foreach (glob("./upgrade-*.sql") as $file) {
    
	$string = $file;
	$tok = strtok($string, "-");
	$tok = strtok("-");
	$f_ver = $tok;
	$tok = strtok("-");
	$tok = strtok($tok, ".");
	$f_rel = $tok;
	
	if($f_ver && $f_rel){
            $upgrade_schema_files[$file] = 'main';
	}
    }
    if($upgrade_schema_files){
        ksort($upgrade_schema_files);
    }
    return $upgrade_schema_files;
}

function delete_directory($dir)
{
    if (!preg_match("/\/$/", $dir)) {
        $dir .= '/';
    }
    if ($handle = @opendir($dir)) {
        while (strlen($file = readdir($handle))) {
            if ($file != '.' && $file != '..') {
                if(is_dir($dir.$file)) {
                    if(!@rmdir($dir.$file)) {
                        delete_directory($dir.$file.'/');
                    }
                } else {
                    @unlink($dir.$file);
                }
            }
        }
    }
    @closedir($handle);
    @rmdir($dir);
}

?>
