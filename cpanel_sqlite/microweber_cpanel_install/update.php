<?php


if (!defined("T")) {
    define('T', 1);
}

$mw_shared_dir = '/usr/share/microweber_1_0/'; //add slash

$path = $home_path = '/home/';


$results = scandir($path);
$i = 0;
$for_migration = array();
foreach ($results as $result) {
    if ($result === '.' or $result === '..') continue;
    $dir = $path . $result;
    if (is_dir($dir)) {
        $is_mw = $dir . '/public_html/src/Microweber/functions/bootstrap.php';
        $is_mw_dir = $dir . '/public_html/';
        $is_mw_cfg = $dir . '/public_html/config/app.php';
        $is_mw_bs = $is_mw;
        $is_templates_dir = $dir . '/public_html/userfiles/templates/';

        if (is_dir($is_mw_dir) and is_file($is_mw)) {
            if (is_file($is_mw_cfg) and is_file($is_mw_bs) and is_dir($is_templates_dir) and !is_link($is_templates_dir) and !is_link($is_mw_bs)) {

                $bs_cont = file_get_contents($is_mw);

                if (!stristr($is_mw, 'asdasdasdxxxetermi3')) {
                    if (stristr($bs_cont, 'MW_VERSION') or strstr($bs_cont, '0.912')) {
                        $for_migration[] = $is_mw_dir;
                        $migrate[] = $result;


                        $i++;


                    }
                }

            }
        }
        //code to use if directory
    }
}


print "Sites to be migrated " . $i . "\n";

$link_paths = array();
//$link_paths[] = 'userfiles/templates/liteness';
//$link_paths[] = 'userfiles/templates/shopmag';
//$link_paths[] = 'userfiles/templates/darock';
//
//$link_paths[] = 'userfiles/modules/social_links';
//$link_paths[] = 'userfiles/modules/logo';
$link_paths[] = 'userfiles/modules/editor';
//$migrate = array();


$migrate_old_db = $migrate;


//var_dump($for_migration);
//exit;
if (isset($for_migration) and is_array($for_migration) and !empty($for_migration)) {

    foreach ($for_migration as $item) {


        if (is_dir($item)) {


            $cache = "{$dn}/public_html/cache/mw_cach*";


            foreach ($link_paths as $link) {

                $dn = dirname($item);
                $owner = fileowner($item);
                $owner_group = filegroup($item);
                $dir_base = basename($dn);

                $new_path = "{$dn}/public_html/{$link}";
                $path_to_clean = "{$dn}/public_html/{$link}";
                // print $new_path;


                if (!is_link($path_to_clean)) {


                    $exec = " chown -Rv {$owner}:{$owner_group} {$dn}/public_html/userfiles/* ";
                    print $exec;
                    print "\n";
                   exec($exec);


                    print "Linking new path \n";
                    $exec = " ln -s  {$mw_shared_dir}{$link} $new_path";
                    print $exec;
                     exec($exec);

                    print "\n";

                }


            }


        }
    }
}