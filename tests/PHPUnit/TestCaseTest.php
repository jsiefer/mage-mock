<?php
/**
 * This file is part of ClassMocker.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package  JSiefer\MageMock
 */
namespace JSiefer\MageMock\PHPUnit;



use JSiefer\ClassMocker\ClassMocker;
use JSiefer\MageMock\Mage\MageFacade;
use JSiefer\MageMock\MagentoMock;
use Mage_Catalog_Model_Product as Product;


/**
 * Class TestCaseTest
 *
 * Testing parent test case class
 *
 * @covers \JSiefer\MageMock\PHPUnit\TestCase
 * @package JSiefer\MageMock\PHPUnit
 */
class TestCaseTest extends TestCase
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
        self::$mocker->setGenerationDir('../var/generation/');
        self::$mocker->mockFramework(new MagentoMock());
        self::$mocker->enable();
    }

    /**
     * Reset Mage god class
     *
     * @return void
     */
    public function setUp()
    {
        MageFacade::reset();
    }

    /**
     * Test model factory
     *
     * Use Closure function to generate new product mocks
     *
     * @return void
     * @test
     */
    public function testModelFactory()
    {
        $idCounter = 1;

        $this->registerModelFactory(Product::class, function() use (&$idCounter) {
            $instance = new Product();
            $instance->expects($this->any())->method('getId')->will($this->returnValue($idCounter++));

            return $instance;
        });

        $productA = \Mage::getModel('catalog/product');
        $productB = \Mage::getModel('catalog/product');

        $this->assertNotSame($productA, $productB, "Both product should be two different instances");

        $this->assertEquals(1, $productA->getId());
        $this->assertEquals(2, $productB->getId());
    }

    /**
     * Test mocking static Mage methods
     */
    public function testStaticMethodOverwrite()
    {
        $this->getMage()
            ->expects($this->once())
            ->method('getBaseDir')
            ->with($this->equalTo('base'))
            ->willReturn('foobar');

        $baseDir = \Mage::getBaseDir('base');

        $this->assertEquals('foobar', $baseDir);
    }
}
