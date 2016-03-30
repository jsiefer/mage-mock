<?php


/**
 * Class Magemock_Sample_Model_Vehicle
 *
 * @method string getId()
 * @method string getName()
 * @method string getNumberOfDoors()
 * @method string getNumberOfSeats()
 * @method string getNumberOfWheels()
 * @method string getTopSpeed()
 */
class Magemock_Sample_Model_Vehicle extends Mage_Core_Model_Abstract
{


    /**
     * @return $this
     */
    protected function _beforeSave()
    {
        if (!$this->getName()) {
            throw new DomainException("Vehicle must have a name");
        }

        return parent::_beforeSave();
    }


    /**
     * Is vehicle a bile
     *
     * @return bool
     */
    public function isBike()
    {
        return $this->getNumberOfWheels() == 2 &&
               $this->getNumberOfDoors() == 0;
    }


}
