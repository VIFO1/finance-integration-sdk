<?php

use Modules\Services\VifoServiceFactory;

require 'vendor/autoload.php';

$serviceFactory = new VifoServiceFactory('dev');
$authenticateUser = $serviceFactory->performUserAuthentication('NEWSPACE_sale_demo','newspace@vifo123');
print_r($authenticateUser);