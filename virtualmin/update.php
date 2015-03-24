#!/usr/bin/php
<?php


//require __DIR__ . "/download.php";

$path = '/home/';

$results = scandir($path);
$i = 0;
$migrate = array();
foreach ($results as $result) {
    if ($result === '.' or $result === '..') continue;
    $dir = $path . $result;
    if (is_dir($dir)) {
        $is_mw = $dir . '/public_html/src/Microweber/';
        if (is_dir($is_mw)) {
            $user_public_html = $dir . '/public_html/';
            $migrate[$result] = $user_public_html;
            $i++;
        }
    }
}

$config_file = __DIR__ . DIRECTORY_SEPARATOR . 'config.php';
$config_file_dist = __DIR__ . DIRECTORY_SEPARATOR . 'config.dist.php';
if (is_file($config_file)) {
    include($config_file);
} elseif (is_file($config_file_dist)) {
    include($config_file_dist);
}

print "Sites to be migrated " . $i . "\n";

if (isset($update_folder) and is_dir($update_folder)) {
    if (!empty($migrate)) {
        foreach ($migrate as $user => $item) {
            $exec = "rsync -av --no-compress   {$update_folder}* {$item}";
            $output = exec($exec);

            if (isset($copy_external) and is_array($copy_external) and !empty($copy_external)) {
                foreach ($copy_external as $source => $dest) {
                    $file = $source;
                    $newfile = "/home/{$user}/public_html/{$dest}";
                    if (is_file($file)) {
                        $exec = "cp -f $file $newfile";
                        $output = exec($exec);
                    } elseif (is_dir($file)) {
                        $exec = "cp -rf $file $newfile";
                        $output = exec($exec);
                    }
                }
            }
        }
    }
}

print "Done" . "\n";