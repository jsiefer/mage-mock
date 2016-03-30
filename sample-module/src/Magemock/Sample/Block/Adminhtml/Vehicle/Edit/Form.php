<?php

/**
 * Class Magemock_Sample_Block_Adminhtml_Vehicle_Edit_Form
 *
 * Sample form class with business logic
 */
class Magemock_Sample_Block_Adminhtml_Vehicle_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * @var Magemock_Sample_Model_Vehicle
     */
    protected $_vehicle;

    /**
     * @var Varien_Data_Form
     */
    protected $_form;

    /**
     * @return Mage_Adminhtml_Block_Widget_Form
     */
    protected function _prepareForm()
    {
        $form = $this->getForm();
        $form->setId('edit_form');
        $form->setAction($this->getData('action'));
        $form->setMethod('post');

        $vehicle = $this->getVehicle();

        if ($vehicle->getId()) {
            $form->addField(
                'vehicle_id',
                'hidden',
                array(
                    'name'  => 'vehicle_id',
                    'value' => $vehicle->getId()
                )
            );
        }

        $fieldset = $form->addFieldset(
            'base_fieldset',
            array(
                'legend'   => $this->__('Vehicle')
            )
        );

        $fieldset->addField(
            'name', 'text',
            array(
                'name'     => 'name',
                'required' => true,
                'label'    => $this->__('Vehicle Name'),
            )
        );

        if (!$vehicle->getId()) {
            $fieldset->addField(
                'number_of_wheels', 'text',
                array(
                    'name'     => 'number_of_wheels',
                    'required' => true,
                    'label'    => $this->__('Wheels'),
                )
            );
            $fieldset->addField(
                'number_of_doors', 'text',
                array(
                    'name'     => 'number_of_doors',
                    'required' => true,
                    'label'    => $this->__('Doors'),
                )
            );
        }

        $fieldset->addField(
            'number_of_seats', 'text',
            array(
                'name'     => 'name',
                'required' => true,
                'label'    => $this->__('Seats'),
            )
        );

        $fieldset->addField(
            'top_speed', 'text',
            array(
                'name'     => 'top_speed',
                'required' => true,
                'label'    => $this->__('Top Speed'),
            )
        );

        /**
         * Bikes require a special security warning
         */
        if ($vehicle->isBike()) {
            $bikeFieldset = $form->addFieldset(
                'bike_fieldset',
                array(
                    'legend'   => $this->__('Bike')
                )
            );
            $bikeFieldset->addField(
                'bike_note', 'note',
                array(
                    'name'     => 'bike_note',
                    'label'    => $this->__('Bike Security Warning'),
                )
            );
        }


        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Retrieve vehicle
     *
     * @return Magemock_Sample_Model_Vehicle
     * @codeCoverageIgnore
     */
    public function getVehicle()
    {
        if (!$this->_vehicle) {
            $this->_vehicle = Mage::registry('current_vehicle');
        }
        return $this->_vehicle;
    }

    /**
     * Set vehicle
     *
     * @param Magemock_Sample_Model_Vehicle $vehicle
     *
     * @return $this
     * @codeCoverageIgnore
     */
    public function setVehicle(Magemock_Sample_Model_Vehicle $vehicle)
    {
        $this->_vehicle = $vehicle;
        return $this;
    }

    /**
     * Retrieve Form
     *
     * @return Varien_Data_Form
     * @codeCoverageIgnore
     */
    public function getForm()
    {
        if (!$this->_form) {
            $this->_form = new Varien_Data_Form();
        }
        return $this->_form;
    }

    /**
     * Set Form
     *
     * @param Varien_Data_Form $form
     *
     * @return $this
     * @codeCoverageIgnore
     */
    public function setForm($form)
    {
        $this->_form = $form;
        return $this;
    }
}
