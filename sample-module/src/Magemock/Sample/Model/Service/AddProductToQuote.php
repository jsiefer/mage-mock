<?php


use Mage_Sales_Model_Quote as Quote;
use Mage_Catalog_Model_Product as Product;


/**
 * Class AddProductToQuote
 */
class Magemock_Sample_Model_Service_AddProductToQuote
{

    /**
     * @var Mage_Catalog_Model_Product_Type_Configurable
     */
    protected $_configurableTypeInstance;

    /**
     * AddProductToQuote constructor.
     *
     * Setup depdendencies
     */
    public function __construct()
    {
        $this->_configurableTypeInstance = Mage::getSingleton('catalog/product_type_configurable');
    }


    public function execute(Quote $quote, Product $product)
    {
        $ids = $this->_configurableTypeInstance->getParentIdsByChild($product->getId());

        if (empty($ids)) {
            return $quote->addProductAdvanced($product);
        }

        if (count($ids) > 1) {
            throw new Exception("too many parents defined");
        }

        $parent = $this->createProduct();
        $parent->load($ids[0]);

        ///....



    }

    /**
     * Create new product instance
     *
     * @return Product
     */
    protected function createProduct()
    {
        return Mage::getModel('catalog/model');
    }

}
