<?php
/**
 * This file is part of ClassMocker.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package  JSiefer\MageMock
 */
use JSiefer\ClassMocker\ClassMocker;
use JSiefer\MageMock\MagentoMock;
use JSiefer\MageMock\TestClasses\ProductModel;


/**
 * Class MageMockTest
 */
class MageMockTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var ClassMocker
     */
    static $mocker;

    /**
     * Bootstrap ClassMocker
     */
    public static function setUpBeforeClass()
    {
        self::$mocker = new ClassMocker();
        self::$mocker->setGenerationDir('./var/generation/');
        self::$mocker->mockFramework(new MagentoMock());
        self::$mocker->enable();
    }

    /**
     * Disable mocker when done
     */
    public static function tearDownAfterClass()
    {
        self::$mocker->disable();
    }

    /**
     * Simple product catalog model test
     *
     * @return void
     * @test
     */
    public function testModelMocking()
    {
        $product = new Mage_Catalog_Model_Product();
        $product->setId(10);

        $this->assertEquals(10, $product->getId());

        $product->method('getData')->will($this->returnValue(100));
        $this->assertEquals(100, $product->getId());

        $product->method('getId')->will($this->returnValue(1000));
        $this->assertEquals(1000, $product->getId());

    }


    /**
     * Test extend from mage class and
     *
     * @return void
     * @test
     */
    public function testCustomModel()
    {
        $product = new ProductModel();
        $product->setId(10);
        $product->save();

        $this->assertEquals(10, $product->getId());
        $this->assertEquals('foobar', $product->getMyTestAttribute());
    }

    /**
     * Access to PHPUnit method mocking
     *
     * @return void
     * @test
     */
    public function testMethodMocking()
    {
        $product = new ProductModel();
        $product->expects($this->once())->method('foobar')->will($this->returnValue(100));

        $result = $product->foobar();
        $this->assertEquals(100, $result);
    }

    /**
     * Should be able to call and test calls in protected methods
     *
     * @return void
     * @test
     */
    public function testProtectedMethods()
    {
        $product = new ProductModel();
        $product->expects($this->once())->method('_init')->with('do/nothing');

        // call protected method _construct
        $product->PROTECTED__construct();
    }


    /**
     * Any static calls to the singleton class Mage should
     * throw an exception
     *
     * @return void
     * @test
     * @expectedException \BadMethodCallException
     * @expectedExceptionMessage 'Mage::getModel' was called
     */
    public function testMageShouldThrowException()
    {
        Mage::getModel('catalog/product');
    }
}
