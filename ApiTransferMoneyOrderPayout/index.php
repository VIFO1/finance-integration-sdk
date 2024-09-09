<?php 
use ApiTransferMoneyOrderPayout\Services\VifoServiceFactory;
require 'vendor/autoload.php';
$login = new VifoServiceFactory('dev');
$headersLogin = [
    'Accept: application/json, text/plain, */*',
    'Accept-Encoding: gzip, deflate',
    'Accept-Language: *'
];
$a = $login->login($headersLogin,'NEWSPACE_sale_demo','newspace@vifo123');
print_r($a);