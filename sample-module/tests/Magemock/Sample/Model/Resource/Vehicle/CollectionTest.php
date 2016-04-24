<?php
/**
 * Created by PhpStorm.
 * User: jsiefer
 * Date: 18/04/16
 * Time: 13:33
 */

namespace Magemock\Sample\Model\Resource\Vehicle;

use JSiefer\ClassMocker\Mock\BaseMock;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

class CollectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test order revers method
     *
     * @test
     * @throws \Exception
     */
    public function testReverseOrder()
    {
        $a = new \Varien_Object();
        $a->setId(1);

        $b = new \Varien_Object();
        $b->setId(2);

        $collection = new \Magemock_Sample_Model_Resource_Vehicle_Collection();
        $collection->addItem($a);
        $collection->addItem($b);
        $collection->reverse();
        $items = $collection->getItems();

        $this->assertSame(1, $items[1]->getId());
        $this->assertSame(2, $items[2]->getId());

        $this->assertSame(2, $collection->getFirstItem()->getId());
        $this->assertSame(1, $collection->getLastItem()->getId());
    }

    /**
     * Test addChangedSince filter method
     *
     * @return void
     * @test
     */
    public function testChangedSinceFilter()
    {
        /** @var MockObject|\Magemock_Sample_Model_Resource_Vehicle_Collection $collection */
        $collection = new \Magemock_Sample_Model_Resource_Vehicle_Collection();
        $collection->expects($this->exactly(3))
                   ->method('addFieldToFilter')
                   ->with($this->equalTo('updated_at'), $this->equalTo(['gt' => '2016-01-01 10:00:00']));

        // test with DateTime
        $date = new \DateTime();
        $date->setDate(2016, 1, 1);
        $date->setTime(10, 0, 0);
        $collection->addChangedSinceFilter($date);

        // test with Zend_Date
        $date = new \Zend_Date('2016-01-01 10:00:00');
        $collection->addChangedSinceFilter($date);

        // test with string
        $collection->addChangedSinceFilter('2016-01-01 10:00:00');
    }


    /**
     * @param mixed $expect
     * @param mixed $filter
     * @param boolean $exclude
     *
     * @test
     * @dataProvider getIdFilterDataProvider
     */
    public function testIdFilter($expect, $filter, $exclude)
    {
        /** @var BaseMock|\Magemock_Sample_Model_Resource_Vehicle_Collection $collection */
        $collection = new \Magemock_Sample_Model_Resource_Vehicle_Collection();
        $collection->method('getIdFieldName')->will($this->returnValue('id'));

        $collection->expects($this->once())
            ->method('addFieldToFilter')
            ->with($this->equalTo('id'), $this->equalTo($expect));

        $collection->addIdFilter($filter, $exclude);
    }

    /**
     * @return array
     */
    public function getIdFilterDataProvider()
    {
        return [
            'filter by ID'   => [1,                1,     false],
            'filter out ID'  => [['neq' => 1],     1,     true ],
            'filter by IDs'  => [['in' => [1,2]],  [1,2], false],
            'filter out IDs' => [['nin' => [1,2]], [1,2], true ],
        ];
    }


}
