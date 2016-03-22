<?php
/**
 * Created by PhpStorm.
 * User: jsiefer
 * Date: 22/03/16
 * Time: 20:58
 */

namespace JSiefer\MageMock\TestClasses;

/**
 * Class ProductModel
 *
 * A test product class can use unit test
 */
class ProductModel extends \Mage_Catalog_Model_Product
{
    /**
     * Initialize resources
     */
    protected function _construct()
    {
        $this->_init('do/nothing');
    }

    /**
     * This method should get called by trait
     *
     * @see \JSiefer\MageMock\Mage\Mage_Core_Model_Abstract::save()
     * @return $this
     */
    protected function _afterSave()
    {
        $this->setMyTestAttribute('foobar');
        return parent::_afterSave();
    }

    /**
     * Some custom test logic
     *
     * @return string
     */
    public function testMethod()
    {
        return 'SKU: ' . $this->getSku();
    }
}
