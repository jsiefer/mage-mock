<?php

namespace JSiefer\MageMock\Varien;

/**
 * Class Varien_Data_Collection_Db
 *
 * @pattern Varien_Data_Collection_Db
 * @sort 100
 */
trait Varien_Data_Collection_Db
{
    /**
     * @var \Varien_Db_Select
     */
    protected $_select;

    /**
     * @var \Zend_Db_Adapter_Abstract
     */
    protected $_connection;

    /**
     * Get Zend_Db_Select instance
     *
     * @return \Varien_Db_Select
     */
    public function getSelect()
    {
        if (!$this->_select) {
            $this->_select = $this->getConnection()->select();
        }
        return $this->_select;
    }

    /**
     * Set select query
     *
     * @param \Varien_Db_Select $select
     *
     * @return $this
     */
    public function setSelect(\Varien_Db_Select $select)
    {
        $this->_select = $select;
        return $this;
    }

    /**
     * Retrieve connection
     *
     * @return \Zend_Db_Adapter_Abstract
     */
    public function getConnection()
    {
        return $this->_connection;
    }

    /**
     * Set connection
     *
     * @param \Zend_Db_Adapter_Abstract $conn
     * @return $this
     */
    public function setConnection($conn)
    {
        $this->_connection = $conn;
        return $this;
    }


}
