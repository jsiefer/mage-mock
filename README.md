# mage-mock

Creating UnitTests for Magento classes can be hard considering that you 
have to extend from so many classes which in term require a lot of setup 
to get initialised in the first place.

The framework mocker will help you to mock the Magento environment.

The idea for a UnitTest here is that it...

* ...should run quickly (<100ms)
* ...no Magento installation or source is required

All required Magento Classes are generated on the fly by the
[class-mocker lib](https://github.com/jsiefer/class-mocker).


## Setup

Simply load this project using composer:

    composer require jsiefer/mage-mock


Create a PHPUnit ``bootstrap.php`` file and register the MagentMock to 
the ClassMocker and enable the ClassMocker.

```php
<?php

use JSiefer\ClassMocker\ClassMocker;
use JSiefer\MageMock\MagentoMock;

$magentoFramework = new MagentoMock();

$classMocker = new ClassMocker();
// optional cache dir for generated classes
$classMocker->setGenerationDir('./var/generation');
$classMocker->mockFramework($magentoFramework);
$classMocker->enable();
```

It is also recommended to setup the ClassMocker test listener so 
ClassMock object assertions are validated as well.
(e.g. ``$test->expects($this->once())->method('test')``)

Just add listener to your phpunit.xml
```xml
    <listeners>
        <listener class="JSiefer\ClassMocker\TestListener" />
    </listeners>
```

## Example

Suppose you have the following model:

```php

/**
 * Class Magemock_Sample_Model_Vehicle
 *
 * @method string getId()
 * @method string getName()
 * @method string getNumberOfDoors()
 * @method string getNumberOfSeats()
 * @method string getNumberOfWheels()
 * @method string getTopSpeed()
 */
class Magemock_Sample_Model_Vehicle extends Mage_Core_Model_Abstract
{
    /**
     * Initialize resources
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('sample/vehicle');
    }

    /**
     * @return $this
     */
    protected function _beforeSave()
    {
        if (!$this->getName()) {
            throw new DomainException("Vehicle must have a name");
        }

        return parent::_beforeSave();
    }

    /**
     * Is vehicle a bile
     *
     * @return bool
     */
    public function isBike()
    {
        return $this->getNumberOfWheels() == 2 &&
               $this->getNumberOfDoors() == 0;
    }

    /**
     * Load bike by name
     *
     * @param string $name
     *
     * @return $this
     */
    public function loadByName($name)
    {
        $this->load($name, 'name');
        return $this;
    }
}
```

Now lets write a unit test for this class, remember you only want to
test your logic. The magento logic of the parent class is not 
relevant at this point.

The mage-mocker however helps you by implementing some of the most
important classes and methods, like the Varien_Object implementation.

All ``Mage_*`` and ``Varien_*`` classes are created and extended on the 
fly when running the test. They will have no method implementation, 
however they all implement the correct class hierarchies and class 
constants.

e.g:

* ``Magemock_Sample_Model_Vehicle``
* extends: ``Mage_Core_Model_Abstract``
* extends: ``Varien_Object``
* implements: ``ArrayAccess``


Lets look at a simple test for the above example.

```php
/**
 * Simple unit test for Vehicle model
 */
class VehicleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Simple test to see that all classes
     * are initialized during testing without
     * the presents of the magento framework
     *
     * @test
     */
    public function testVehicleClassInstance()
    {
        $vehicle = new \Magemock_Sample_Model_Vehicle();

        $this->assertInstanceOf('Mage_Core_Model_Abstract', $vehicle);
        $this->assertInstanceOf('Varien_Object', $vehicle);
        $this->assertInstanceOf('ArrayAccess', $vehicle);

        // class const example
        $this->assertEquals(
            'COLLECTION_DATA',
            \Mage_Core_Model_Resource_Db_Collection_Abstract::CACHE_TAG
        );
    }

    /**
     * Test _construct() method
     *
     * You can call protected methods by using the helper 
     * function __callProtectedMethod($name, array $args = [])
     *
     * @return void
     * @test
     */
    public function validateConstructMethod()
    {
        /** @var \Magemock_Sample_Model_Vehicle|\JSiefer\ClassMocker\Mock\BaseMock $product */
        $product = new \Magemock_Sample_Model_Vehicle();
        $product->expects($this->once())->method('_init')->with('sample/vehicle');

        // call protected method _construct
        $product->__callProtectedMethod('_construct');
    }

    /**
     * Test before vehicle save validation
     *
     * @return void
     * @test
     */
    public function testBeforeSaveValidation()
    {
        $vehicle = new \Magemock_Sample_Model_Vehicle();
        $vehicle->setName("foobar");
        $vehicle->save();

        $this->assertEquals('foobar', $vehicle->getName());

        try {
            $vehicle = new \Magemock_Sample_Model_Vehicle();
            $vehicle->save();

            $this->fail("Expected validation error for missing name");
        }
        catch(\Exception $e) {
            $this->assertEquals(
                'Vehicle must have a name',
                $e->getMessage(),
                'Caught wrong exception'
            );
        }
    }

    /**
     * Test isBike() method
     *
     * @return void
     * @test
     */
    public function testIsBikeMethod()
    {
        $vehicle = new \Magemock_Sample_Model_Vehicle();

        $this->assertFalse(
            $vehicle->isBike(),
            'Vehicle should not be a bike by default'
        );

        $vehicle->setNumberOfWheels(2);
        $vehicle->setNumberOfDoors(0);

        $this->assertTrue(
            $vehicle->isBike(),
            'Vehicle should be a bike if it has two wheels and no doors'
        );
    }

    /**
     * Test load by name method
     *
     * Since the Magemock_Sample_Model_Vehicle class extends a class-mocker generated
     * class all PHPUnitObjectMock methods are available
     *
     * @return void
     * @test
     */
    public function testLoadByName()
    {
        $vehicle = new \Magemock_Sample_Model_Vehicle();
        $vehicle->expects($this->once())
                ->method('load')
                ->with($this->equalTo('foobar'), $this->equalTo('name'));

        $result = $vehicle->loadByName('foobar');

        $this->assertSame($vehicle, $result, 'loadByName should return $this');
    }
}
```

Since this is a UnitTest only focusing on your class no Magento 
initialization process is required and the test can run in a few 
milliseconds.



## Note
This is still an early release and a proof of concept. It yet needs to be tested if this approach can be of use.

