<?php
use JSiefer\ClassMocker\Mock\BaseMock;


/**
 * Class Magemock_Sample_Block_Adminhtml_Vehicle_Edit_FormTest
 *
 * Sample class for testing a form widget
 *
 * @covers Magemock_Sample_Block_Adminhtml_Vehicle_Edit_Form
 */
class Magemock_Sample_Block_Adminhtml_Vehicle_Edit_FormTest extends PHPUnit_Framework_TestCase
{
    const FIELD_ID = 'vehicle_id';
    const FIELD_NAME = 'name';
    const FIELD_WHEELS = 'number_of_wheels';
    const FIELD_DOORS = 'number_of_doors';
    const FIELD_SEATS = 'number_of_seats';
    const FIELD_TO_SPEED = 'top_speed';

    const FIELDSET_BASE = 'base_fieldset';
    const FIELDSET_BIKE = 'bike_fieldset';


    /**
     * Test form for new vehicles
     *
     * @return void
     * @test
     */
    public function testFormForNewVehicles()
    {
        $vehicle = new Magemock_Sample_Model_Vehicle();

        $form = new Varien_Data_Form();
        /** @var Magemock_Sample_Block_Adminhtml_Vehicle_Edit_Form|BaseMock $formWidget */
        $formWidget = new Magemock_Sample_Block_Adminhtml_Vehicle_Edit_Form();
        $formWidget->setVehicle($vehicle);
        $formWidget->setForm($form);
        $formWidget->__callProtectedMethod('_prepareForm');

        $this->assertFieldNotExist($form, self::FIELD_ID);
        $fieldset = $form->getElement(self::FIELDSET_BASE);

        $this->assertField($fieldset, self::FIELD_NAME);
        $this->assertField($fieldset, self::FIELD_WHEELS);
        $this->assertField($fieldset, self::FIELD_DOORS);
        $this->assertField($fieldset, self::FIELD_SEATS);
        $this->assertField($fieldset, self::FIELD_TO_SPEED);

        $this->assertFieldNotExist($form, 'bike_fieldset');
    }

    /**
     * Test form for existing vehicles
     *
     * @return void
     * @test
     */
    public function testFormForExistingVehicles()
    {
        $vehicle = new Magemock_Sample_Model_Vehicle();
        $vehicle->setId(1);

        $form = new Varien_Data_Form();
        /** @var Magemock_Sample_Block_Adminhtml_Vehicle_Edit_Form|BaseMock $formWidget */
        $formWidget = new Magemock_Sample_Block_Adminhtml_Vehicle_Edit_Form();
        $formWidget->setVehicle($vehicle);
        $formWidget->setForm($form);
        $formWidget->__callProtectedMethod('_prepareForm');

        $this->assertField($form, self::FIELD_ID, 'hidden');
        $fieldset = $form->getElement(self::FIELDSET_BASE);

        $this->assertField($fieldset, self::FIELD_NAME);
        $this->assertFieldNotExist($fieldset, self::FIELD_WHEELS);
        $this->assertFieldNotExist($fieldset, self::FIELD_DOORS);
        $this->assertField($fieldset, self::FIELD_SEATS);
        $this->assertField($fieldset, self::FIELD_TO_SPEED);

        $this->assertFieldNotExist($form, 'bike_fieldset');
    }

    /**
     * Test form for existing bikes
     *
     * @return void
     * @test
     */
    public function testFormForBike()
    {
        $vehicle = new Magemock_Sample_Model_Vehicle();
        $vehicle->setNumberOfWheels(2);
        $vehicle->setNumberOfDoors(0);
        $vehicle->setId(1);

        $this->assertTrue($vehicle->isBike());

        $form = new Varien_Data_Form();
        /** @var Magemock_Sample_Block_Adminhtml_Vehicle_Edit_Form|BaseMock $formWidget */
        $formWidget = new Magemock_Sample_Block_Adminhtml_Vehicle_Edit_Form();
        $formWidget->setVehicle($vehicle);
        $formWidget->setForm($form);
        $formWidget->__callProtectedMethod('_prepareForm');

        $this->assertField($form, self::FIELD_ID, 'hidden');
        $fieldset = $form->getElement(self::FIELDSET_BASE);

        $this->assertField($fieldset, self::FIELD_NAME);
        $this->assertFieldNotExist($fieldset, self::FIELD_WHEELS);
        $this->assertFieldNotExist($fieldset, self::FIELD_DOORS);
        $this->assertField($fieldset, self::FIELD_SEATS);
        $this->assertField($fieldset, self::FIELD_TO_SPEED);

        $bikeFieldset = $form->getElement('bike_fieldset');
        $this->assertField($bikeFieldset, 'bike_note', 'note');
    }

    /**
     * Assert form field
     *
     * @param Varien_Data_Form_Abstract $form
     * @param string $elementId
     * @param string $type
     * @param array $config
     */
    public static function assertField(Varien_Data_Form_Abstract $form, $elementId, $type = 'text', array $config = [])
    {
        $field = $form->getElements()->searchById($elementId);
        if (!$field) {
            self::fail("Element with id '$elementId' not found in form");
        }

        self::assertEquals($type, $field->getType(), "Form element does not match type");
        foreach ($config as $key => $expect) {
            self::assertEquals($expect, $field->getData($key), "Form element config '$key' does not match");
        }
    }

    /**
     * Assert form field not exist
     *
     * @param Varien_Data_Form_Abstract $form
     * @param string $elementId
     */
    public static function assertFieldNotExist(Varien_Data_Form_Abstract $form, $elementId)
    {
        $field = $form->getElements()->searchById($elementId);
        if ($field) {
            self::fail("Element with id '$elementId' was found in form");
        }
    }

}
