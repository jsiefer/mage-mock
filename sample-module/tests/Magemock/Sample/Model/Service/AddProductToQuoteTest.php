<?php

namespace Magemock\Sample\Model\Service;

use JSiefer\MageMock\PHPUnit\TestCase;
use Mage_Sales_Model_Quote as Quote;
use Mage_Catalog_Model_Product as Product;


use Mage_Catalog_Model_Product_Type_Configurable as ConfigurableType;

/**
 * Class AddProductToQuote
 *
 * @covers Magemock_Sample_Model_Service_AddProductToQuote
 */
class AddProductToQuoteTest extends TestCase
{
    /**
     * Test service with one parent
     *
     * @return void
     * @test
     */
    public function testService()
    {
        $configType = $this->getSingleton('catalog/product_type_configurable');
        $configType->expects($this->once())->method('getParentIdsByChild')->willReturn([10]);

        // Should load parent product
        $this->registerModelFactory(Product::class, function() {
            $product = new Product();
            $product->expects($this->once())->method('load')->with($this->equalTo(10));

            return $product;
        });

        $quote = new Quote();
        $product = new Product();

        $service = new \Magemock_Sample_Model_Service_AddProductToQuote();
        $service->execute($quote, $product);
    }

}
