<?php
/**
 * This file is part of ClassMocker.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package  JSiefer\MageMock
 */
namespace JSiefer\MageMock\Mage;


use Mage_Core_Model_Store as Store;

/**
 * Class Mage_Core_Model_App
 *
 * @pattern Mage_Core_Model_App
 * @package JSiefer\Mock\Framework\Magento
 *
 */
trait Mage_Core_Model_App
{

    /**
     * @var Store[]
     */
    protected $_stores = [];

    /**
     * @var Store
     */
    protected $_currentStore;


    /**
     * @return Store
     */
    public function getStore($store = null)
    {
        if ($store === null) {
            if (!$this->_currentStore) {
                $this->_currentStore = new Store();
                $this->_currentStore->setId(1);
                $this->addStore($this->_currentStore);
            }
            return $this->_currentStore;
        }
        if (is_numeric($store)) {

            if (isset($this->_stores[$store])) {
                $storeObj = $this->_stores[$store];
            } else {
                $storeObj = new Store();
                $storeObj->setId($store);
                $this->addStore($storeObj);
            }
            return $storeObj;
        }

        if ($store instanceof Store) {
            return $store;
        }

        return $this->getStore();
    }


    public function setStore(Store $store)
    {
        $this->addStore($store);
        $this->_currentStore = $store;
        return $this;
    }


    public function addStore(Store $store)
    {
        $this->_stores[$store->getId()] = $store;
        return $this;
    }

}
