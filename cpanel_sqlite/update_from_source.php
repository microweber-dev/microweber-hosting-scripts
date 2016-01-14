<?php

$cmd1  = 'composer create-project microweber/microweber my_site 1.0.x-dev --prefer-dist --no-dev --no-plugins --no-scripts --ignore-platform-reqs';
$cmd  = '';
$cmd .= "composer create-project ";
$cmd .= "microweber/microweber my_site 1.0.x-dev  ";
$cmd .= "--prefer-dist --no-dev --no-plugins --no-scripts --ignore-platform-reqs";

exec($cmd);