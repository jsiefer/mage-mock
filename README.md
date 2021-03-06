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

Same goes for the the MageMock listener, which will reset the Mage class automatically after each test.

Just add listener to your phpunit.xml
```xml
    <listeners>
        <listener class="JSiefer\ClassMocker\TestListener" />
        <listener class="JSiefer\MageMock\PHPUnit\TestListener" />
    </listeners>
```


## How it works

### The mocked classes

All classes generated by the class-mocker implement the PHPUnit MockObject interface
that lets you alter or assert certain behaviour during runtime as you are familiar 
when using the standard PHPUnit mocks (e.g. ``$moock->expects($this->once())...``).

Additional fixed behaviour, like simple stub logic, valid for the entire test run
can be defined using traits.

MageMock already provides some basic stub logic. So for instance calling ``save()`` method on
a Model will trigger the ``_beforeSave()`` and ``_afterSave()`` method.

Traits are used and registered to Mocks, when calling a Method on any generated Mock,
the trait method is not getting called straight away, instead the call gets handled by
the ``BaseMock`` class which is base class for all generated Mocks. The ``BaseMock`` will then
check for any defined methods in the following order:

1. Check for any mocked method.
   ```php
    $mock->expects($this->once())->method('save');
   ```
1. Check for closure functions.

   ```php
    $mock->save = function() {
        return $this;
    }
   ```
1. Check for trait methods.

    ```php
    /**
     * Class Mage_Core_Model_Abstract
     *
     * @pattern Mage_Core_Model_Abstract
     * @sort 100
     */
    trait My_Mage_Core_Model_Abstract_Stub
    {
        /**
         * Save object data
         *
         * @return $this
         */
        public function save()
        {
         $this->_beforeSave();
         $this->_afterSave();
         $this->_afterSaveCommit();
        
         return $this;
        }
    }
 
    // Then in your bootstrap.php
    $magentoFramework = new MagentoMock();
    
    $classMocker = new ClassMocker();
    $classMocker->registerTrait(My_Mage_Core_Model_Abstract_Stub::class)
    $classMocker->setGenerationDir('./var/generation');
    $classMocker->mockFramework($magentoFramework);
    $classMocker->enable();
    ```

1. Check for and call magic trait methods (e.g. ``__call()``)
   
   The above traits can use ``___call()`` method to listen to any call.
   
1. Default method call (return self)

   Can be changed to return NULL or throw an exception.


### The Mage class problem

One of the main challenges during unit testing in Magento is the Mage class.
Mocking the behavior of the static Mage class for tests is not straight forward.

To solve this issue, two Mage classes are created. One called the MageFacade-class, which is used for
the Mage class to fetch all static calls and delegate it to the MageClass-class, however the MageClass
is not static but as well an auto-generated mock that is re-initialized for each test.

This way, we can not only bind trait stubs to the Mage class but also alter the behavior of the
Mage class on runtime using the same methods described above.

(See ``testMockingMage()`` below)


Another problem is that no configuration XML is available for the unit test, so resolving
model names like ``customer/session`` needs to be handled in a semi-automated way.
 
A name resolver can be set to the MageFacade class which will try its best to resolve any
given name. You can register your own namespaces there, or create your own name resolver.
 
(See ``testSingleton()`` below)




```php

use Mage_Sales_Model_Quote as Quote;
use Mage_Catalog_Model_Product as Product;
use Mage_Customer_Model_Customer as Customer;

class MyTest extends JSiefer\MageMock\PHPUnit\TestCase
{
    /**
     * Test mocking mage
     *
     * @return void
     * @test
     */
    public function testMockingMage()
    {    
        // This is the Mage mock
        $mage = $this->getMage();
        $mage->expects($this->once())->method('getBaseUrl')->willReturn('foobar');
        
        $baseUrl = Mage::getBaseUrl(); // foobar
    }
    
    /**
     * Example for mocking a singleton
     *
     * @return void
     * @test
     */
    public function testSingleton()
    {
        $customer = new Customer();
        $customer->setFirstname('Joe');
    
        // Create customer session mock
        $session = $this->getSingleton('catalog/session');
        $session->expects($this->once())->method('getCustomer')->willReturn($customer);
        
        // Now you can use this session, only valid in this test
        $session = Mage::getSingleton('catalog/session');
    }
    
    /**
     * Creating model mocks on the fly using model factories
     *
     * Supose multiple Product models get initiated and you need them to return
     * a unique id by default.
     *
     * @return void
     * @test
     */
    public function testModelFactory()
    {
        $idCounter = 1;

        // Register a factory closure for creating class instances
        // Factories are registred by the full class name 
        $this->registerModelFactory(Product::class, function() use (&$idCounter) {
            $instance = new Product();
            $instance->expects($this->once())->method('getId')->will($this->returnValue($idCounter++));

            return $instance;
        });

        $productA = Mage::getModel('catalog/product');
        $productB = Mage::getModel('catalog/product');

        $this->assertNotSame($productA, $productB, "Both product should be two different instances");

        $this->assertEquals(1, $productA->getId());
        $this->assertEquals(2, $productB->getId());
    }

}
```

## Further Examples

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
    public function testSaveValidation()
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
If you have any ideas, feedback or issues let me know.

