<?php


use JSiefer\ClassMocker\ClassMocker;
use JSiefer\MageMock\MagentoMock;

$magentoFramework = new MagentoMock();

$classMocker = new ClassMocker();
$classMocker->mockFramework($magentoFramework);
$classMocker->enable();
