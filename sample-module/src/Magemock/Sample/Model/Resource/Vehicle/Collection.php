<?php


/**
 * Class Magemock_Sample_Model_Resource_Vehicle_Collection
 *
 * Sample collection to test
 */
class Magemock_Sample_Model_Resource_Vehicle_Collection
    extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    /**
     * Reverse collection
     *
     * @return $this
     */
    public function reverse()
    {
        $this->load();
        $this->_items = array_reverse($this->_items, true);

        return $this;
    }

    /**
     * Filter items that have not been changed since
     *
     * @param string|DateTime|Zend_Date $since
     * @return $this
     */
    public function addChangedSinceFilter($since)
    {
        if ($since instanceof DateTime) {
            $since = $since->format('Y-m-d H:i:s');
        }
        if ($since instanceof Zend_Date) {
            $since = Varien_Date::formatDate($since);
        }
        $this->addFieldToFilter('updated_at', array('gt' => $since));

        return $this;
    }

    /**
     * Add collection filters by identifiers
     *
     * @param string $id
     * @param boolean $exclude
     * @return $this
     */
    public function addIdFilter($id, $exclude = false)
    {
        if (empty($id)) {
            $this->_setIsLoaded(true);
            return $this;
        }
        if (is_array($id)) {
            if ($exclude) {
                $condition = array('nin' => $id);
            } else {
                $condition = array('in' => $id);
            }
        } else {
            if ($exclude) {
                $condition = array('neq' => $id);
            } else {
                $condition = $id;
            }
        }
        $this->addFieldToFilter($this->getIdFieldName(), $condition);

        return $this;
    }

    /**
     * Check if any item has data changes
     *
     * @return bool
     */
    public function hasDataChanges()
    {
        if(!$this->isLoaded()) {
            return false;
        }

        /* @var $item Mage_Core_Model_Abstract */
        foreach ($this as $item) {
            if ($item->hasDataChanges()) {
                return true;
            }
        }
        return false;
    }

    /**
     * Filter items that are deleted
     *
     * @param bool $exclude
     *
     * @return $this
     */
    public function addDeletedFilter($exclude = true)
    {
        $filter = $exclude
            ? array('null' => true)
            : array('notnull' => true);

        $this->addFieldToFilter('deleted_at', $filter);

        return $this;
    }

    /**
     * Add column 'new_message_count' to result entity
     * showing how many new messages have been send by the customer
     *
     * @return $this
     */
    public function joinOwnerName()
    {
        $table = array('owner' => $this->getTable('vehicle/vehicle_owner'));
        $cond = '`main_table`.`owner_id` = `owner`.`owner_id`';
        $cols = array(
            'owner_name' => new Zend_Db_Expr('CONCAT_WS(" ", `owner`.`firstname`, `owner`.`lastname`)')
        );

        $this->getSelect()
            ->joinLeft($table, $cond, $cols)
            ->group('main_table.vehicle_id');

        return $this;
    }
}
