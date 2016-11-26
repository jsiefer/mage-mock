<?php
/**
 * This file is part of ClassMocker.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package  JSiefer\MageMock
 */
namespace JSiefer\MageMock\PHPUnit;


use JSiefer\MageMock\Mage\MageFacade;
use PHPUnit_Framework_Test as Test;

/**
 * Class TestListener
 *
 * @package JSiefer\MageMock
 */
class TestListener extends \PHPUnit_Framework_BaseTestListener
{
    /**
     * Reset Mage god class after each test
     *
     * @param Test $test
     * @param float $time
     */
    public function endTest(Test $test, $time)
    {
        MageFacade::reset();
    }
}
