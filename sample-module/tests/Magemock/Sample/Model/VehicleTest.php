<?php


namespace Magemock\Sample\Model;


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
     * You can call protected methods by using the helper function __callProtectedMethod($name, array $args = [])
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
