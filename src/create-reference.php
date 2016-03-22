<?php

use JSiefer\ClassMocker\Footprint\FootprintGenerator;

$vendorDir = dirname(__DIR__) . '/vendor/';

include $vendorDir . 'autoload.php';

ini_set('memory_limit', '4G');


$magentoPath = $vendorDir . 'firegento/magento/';

$generator = new FootprintGenerator();
$generator->addDirectory($magentoPath . 'app/code');
$generator->addDirectory($magentoPath . 'lib/Varien');

$reference = $generator->generate();

file_put_contents('mage.ref.json', $reference);

echo "done";
