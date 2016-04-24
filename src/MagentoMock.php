<?php
/**
 * This file is part of ClassMocker.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package  JSiefer\MageMock
 */
namespace JSiefer\MageMock;


use JSiefer\ClassMocker\ClassMocker;
use JSiefer\ClassMocker\FrameworkInterface;
use JSiefer\MageMock\Mage\Mage;
use JSiefer\MageMock\Mage\Mage_Core_Helper_Abstract;
use JSiefer\MageMock\Mage\Mage_Core_Model_Abstract;
use JSiefer\MageMock\Mage\Mage_Core_Model_App;
use JSiefer\MageMock\Varien\Varien_Data_Collection;
use JSiefer\MageMock\Varien\Varien_Data_Collection_Db;
use JSiefer\MageMock\Varien\Varien_Object;

/**
 * Class MagentoMock
 */
class MagentoMock implements FrameworkInterface
{
    public function register(ClassMocker $classMocker)
    {
        $classMocker->importFootprints(__DIR__ . '/mage.ref.json');

        $classMocker->registerTrait(Mage_Core_Helper_Abstract::class);
        $classMocker->registerTrait(Mage_Core_Model_Abstract::class);
        $classMocker->registerTrait(Mage_Core_Model_App::class);

        $classMocker->registerTrait(Varien_Object::class, 'Varien_Object', 100);
        $classMocker->registerTrait(Varien_Data_Collection::class, 'Varien_Data_Collection', 100);
        $classMocker->registerTrait(Varien_Data_Collection_Db::class);

        $classMocker->registerBaseClass(Mage::class);

        $classMocker->mock('Mage');
        $classMocker->mock('Mage_*');
        $classMocker->mock('Varien_Object');
        $classMocker->mock('Varien_Data_Collection');
        $classMocker->mock('Varien_Data_Collection_Db');

        // optional mocking if no lib exists
        $classMocker->mock('Varien_', true);
    }
}
