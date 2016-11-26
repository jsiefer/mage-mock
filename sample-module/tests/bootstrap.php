<?php

/**
 * Optional add magento lib classes
 * @var \Composer\Autoload\ClassLoader $loader
 */
$loader = require '../../vendor/autoload.php';
$loader->add('', '../../vendor/firegento/magento/lib');


use JSiefer\ClassMocker\ClassMocker;
use JSiefer\MageMock\Mage\MageFacade;
use JSiefer\MageMock\MagentoMock;

$magentoFramework = new MagentoMock();

$classMocker = new ClassMocker();
$classMocker->setGenerationDir('./var/generation');
$classMocker->mockFramework($magentoFramework);
$classMocker->enable();


$nameResolver = new \JSiefer\MageMock\ClassNameResolver();
$nameResolver->registerNamespace('sample', 'Magemock_Sample');

MageFacade::setNameResolver($nameResolver);
