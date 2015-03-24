<?php

// copy internal files from the shared folder as a source
$copy_files = array();
$copy_files[] = 'favicon.ico';

// deletes files or folders in the destination folder
$remove_files = array();
$remove_files[] = 'userfiles/modules/ants';


// copy external files from absolute path as a source
$copy_external = array(); // $source=>$dest
$copy_external['/usr/share/microweber-ext/userfiles/modules'] = 'userfiles/modules';
$copy_external['/usr/share/microweber-ext/userfiles/templates'] = 'userfiles/templates';
$copy_external['/usr/share/microweber-ext/favicon.ico'] = 'favicon.ico';
