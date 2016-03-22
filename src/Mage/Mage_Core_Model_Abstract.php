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



/**
 * Class Mage_Core_Model_Abstract
 *
 * @pattern Mage_Core_Model_Abstract
 * @sort 100
 *
 * @method _beforeSave()
 * @method _afterSave()
 * @method _afterSaveCommit()
 * @method _beforeLoad()
 * @method _afterLoad()
 * @method _beforeDelete()
 * @method _afterDelete()
 * @method _afterDeleteCommit()
 */
trait Mage_Core_Model_Abstract
{


    /**
     * Save object data
     *
     * @return $this
     */
    public function save()
    {
        /**
         * Direct deleted items to delete method
         */
        if ($this->isDeleted()) {
            return $this->delete();
        }

        $this->_beforeSave();
        $this->_afterSave();
        $this->_afterSaveCommit();

        return $this;
    }


    /**
     * @return bool
     */
    public function isDeleted()
    {
        return false;
    }



    /**
     * Load object data
     *
     * @param   integer $id
     * @return  $this
     */
    public function load($id, $field=null)
    {
        $this->_beforeLoad($id, $field);
        $this->_afterLoad();
        $this->_hasDataChanges = false;

        return $this;
    }

    /**
     * Delete object from database
     *
     * @return $this
     */
    public function delete()
    {
        $this->_beforeDelete();
        $this->_afterDelete();
        $this->_afterDeleteCommit();

        return $this;
    }
}
