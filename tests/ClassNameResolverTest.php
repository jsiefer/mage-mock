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


/**
 * Class ClassNameResolverTest
 *
 * @covers \JSiefer\MageMock\ClassNameResolver
 *
 * @package JSiefer\MageMock
 */
class ClassNameResolverTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test resolving names for different types using default vendor name
     *
     * @return void
     * @test
     */
    public function testResolvingDefaultVendorName()
    {
        $resolver = new ClassNameResolver();
        $resolver->setDefaultVendorNamespace('Sample');

        $result = $resolver->resolve($resolver::TYPE_MODEL, 'customer/address_api');
        $this->assertEquals('Sample_Customer_Model_Address_Api', $result);

        $result = $resolver->resolve($resolver::TYPE_BLOCK, 'customer/address_edit');
        $this->assertEquals('Sample_Customer_Block_Address_Edit', $result);

        $result = $resolver->resolve($resolver::TYPE_HELPER, 'customer/address');
        $this->assertEquals('Sample_Customer_Helper_Address', $result);

        $result = $resolver->resolve($resolver::TYPE_HELPER, 'customer');
        $this->assertEquals('Sample_Customer_Helper_Data', $result);
    }

    /**
     * Test resolving custom namespaces
     *
     * @return void
     * @test
     */
    public function testResolvingCustomNamespace()
    {
        $resolver = new ClassNameResolver();
        $resolver->registerNamespace('foobar', 'JSiefer_FooBar');

        $result = $resolver->resolve($resolver::TYPE_MODEL, 'foobar/address_api');
        $this->assertEquals('JSiefer_FooBar_Model_Address_Api', $result);

        $result = $resolver->resolve($resolver::TYPE_BLOCK, 'foobar/address_edit');
        $this->assertEquals('JSiefer_FooBar_Block_Address_Edit', $result);

        $result = $resolver->resolve($resolver::TYPE_HELPER, 'foobar/address');
        $this->assertEquals('JSiefer_FooBar_Helper_Address', $result);

        $result = $resolver->resolve($resolver::TYPE_HELPER, 'foobar');
        $this->assertEquals('JSiefer_FooBar_Helper_Data', $result);
    }
}
