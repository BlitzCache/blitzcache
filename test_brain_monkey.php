<?php
require 'vendor/autoload.php';

var_dump('Before loading api.php:', function_exists('Brain\Monkey\setUp'));

if (!function_exists('Brain\Monkey\setUp')) {
    require 'vendor/brain/monkey/inc/api.php';
}

var_dump('After loading api.php:', function_exists('Brain\Monkey\setUp'));

var_dump('Brain\Monkey namespace functions:', get_defined_functions()['user']);
