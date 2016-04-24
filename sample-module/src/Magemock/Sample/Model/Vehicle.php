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
     * Initialize resources
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('sample/vehicle');
    }

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

    /**
     * Load bike by name
     *
     * @param string $name
     *
     * @return $this
     */
    public function loadByName($name)
    {
        $this->load($name, 'name');
        return $this;
    }
}
