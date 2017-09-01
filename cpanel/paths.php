<?php

if (!isset($mw_shared_dir)) {
    $mw_shared_dir = '/usr/share/microweber-latest/'; //add slash
}
if (!isset($mw_ext_dir)) {
    $mw_ext_dir = '/usr/share/microweber-ext/'; //add slash
}


$remove_paths = array();
$remove_paths[] = 'config.php';
$remove_paths[] = 'bootstrap.php';
$remove_paths[] = 'index.php';
$remove_paths[] = 'src';
$remove_paths[] = 'cpanel';

$remove_paths[] = 'userfiles/modules/';
$remove_paths[] = 'userfiles/removed_modules';
$remove_paths[] = 'userfiles/templates/';
$remove_paths[] = 'userfiles/elements/';


$mkdirs = array();
//$mkdirs[] = 'config';
//$mkdirs[] = 'config/' . $domain;
$mkdirs[] = 'storage';
$mkdirs[] = 'storage/framework';
$mkdirs[] = 'storage/framework/sessions';
$mkdirs[] = 'storage/framework/views';
$mkdirs[] = 'storage/cache';
$mkdirs[] = 'storage/logs';
$mkdirs[] = 'storage/app';

$storage_dirs = $mkdirs;
$mkdirs[] = 'bootstrap';
$mkdirs[] = 'bootstrap/cache';
$mkdirs[] = 'userfiles';
$mkdirs[] = 'userfiles/media';
$mkdirs[] = 'userfiles/modules';
$mkdirs[] = 'userfiles/templates';


$copy_files = array();
$copy_files[] = 'index.php';
$copy_files[] = '.htaccess';
$copy_files[] = 'favicon.ico';

$copy_files[] = 'composer.json';
$copy_files[] = 'artisan';
$copy_files[] = 'config';
$copy_files[] = 'bootstrap/app.php';
$copy_files[] = 'bootstrap/autoload.php';
$copy_files[] = 'storage/database.sqlite';


$link_paths = array();

$link_paths[] = 'vendor';
$link_paths[] = 'database';
$link_paths[] = 'resources';
$link_paths[] = 'app';


$link_paths[] = 'tests';


$link_paths[] = 'src';
$link_paths[] = 'userfiles/modules/admin';

$link_paths[] = 'userfiles/elements';
$link_paths[] = 'userfiles/templates/default';
$link_paths[] = 'userfiles/templates/liteness';


$link_paths[] = 'userfiles/modules/audio';
$link_paths[] = 'userfiles/modules/admin';

// B
$link_paths[] = 'userfiles/modules/btn';
$link_paths[] = 'userfiles/modules/breadcrumb';

// C
$link_paths[] = 'userfiles/modules/content';
$link_paths[] = 'userfiles/modules/categories';
$link_paths[] = 'userfiles/modules/comments';
$link_paths[] = 'userfiles/modules/contact_form';
$link_paths[] = 'userfiles/modules/custom_fields';

// D


// E
$link_paths[] = 'userfiles/modules/embed';
$link_paths[] = 'userfiles/modules/editor';

// F
$link_paths[] = 'userfiles/modules/files';
$link_paths[] = 'userfiles/modules/forms';
//$link_paths[] = 'userfiles/modules/free'.DS;

//G
$link_paths[] = 'userfiles/modules/google_maps';


//H
$link_paths[] = 'userfiles/modules/help';
$link_paths[] = 'userfiles/modules/highlight_code';


//I
$link_paths[] = 'userfiles/modules/ip2country';

//L
$link_paths[] = 'userfiles/modules/layouts';


// M
//$link_paths[] = 'userfiles/modules/media';
//$link_paths[] = 'userfiles/modules/mics';
$link_paths[] = 'userfiles/modules/menu';
$link_paths[] = 'userfiles/modules/microweber';


//N
//$link_paths[] = 'userfiles/modules/nav'.DS;

// added for v1.0.8
$link_paths[] = 'userfiles/modules/newsletter';


//O
$link_paths[] = 'userfiles/modules/options';


//P
$link_paths[] = 'userfiles/modules/picture';
$link_paths[] = 'userfiles/modules/pictures';
$link_paths[] = 'userfiles/modules/posts';
$link_paths[] = 'userfiles/modules/pages';

// added for v1.0.8
$link_paths[] = 'userfiles/modules/pdf';
$link_paths[] = 'userfiles/modules/parallax';


//S
$link_paths[] = 'userfiles/modules/settings';
$link_paths[] = 'userfiles/modules/shop';
$link_paths[] = 'userfiles/modules/search';

$link_paths[] = 'userfiles/modules/site_stats';


// T
$link_paths[] = 'userfiles/modules/text';
$link_paths[] = 'userfiles/modules/title';
// added for v1.0.8
$link_paths[] = 'userfiles/modules/teamcard';
$link_paths[] = 'userfiles/modules/testimonials';
$link_paths[] = 'userfiles/modules/tabs';


// U
$link_paths[] = 'userfiles/modules/users';
$link_paths[] = 'userfiles/modules/updates';
//$link_paths[] = 'userfiles/modules/user_profile';
//$link_paths[] = 'userfiles/modules/user_search';
//$link_paths[] = 'userfiles/modules/users_list';

// V
$link_paths[] = 'userfiles/modules/video';

$link_paths[] = 'userfiles/modules/default.php';
$link_paths[] = 'userfiles/modules/default.png';
$link_paths[] = 'userfiles/modules/non_existing.php';




// link templates

$link_paths[] = 'userfiles/templates/liteness';
$link_paths[] = 'userfiles/templates/default';


// link new modulules

$link_paths[] = 'userfiles/modules/social_links';
$link_paths[] = 'userfiles/modules/logo';
$link_paths[] = 'userfiles/modules/rating';
$link_paths[] = 'userfiles/modules/calendar';
$link_paths[] = 'userfiles/modules/beforeafter';
$link_paths[] = 'userfiles/modules/editor';
$link_paths[] = 'userfiles/modules/bxslider';
$link_paths[] = 'userfiles/modules/sharer';
$link_paths[] = 'userfiles/modules/slickslider';
$link_paths[] = 'userfiles/modules/layouts';
$link_paths[] = 'userfiles/modules/parallax';
$link_paths[] = 'userfiles/modules/testimonials';
$link_paths[] = 'userfiles/modules/pricing_table';
$link_paths[] = 'userfiles/modules/tags';
$link_paths[] = 'userfiles/modules/magicslider';
$link_paths[] = 'userfiles/modules/facebook_page';
$link_paths[] = 'userfiles/modules/pdf';
$link_paths[] = 'userfiles/modules/twitter_feed';

$link_paths = array_unique($link_paths);
