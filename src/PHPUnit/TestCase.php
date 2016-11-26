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


use JSiefer\ClassMocker\Mock\BaseMock;
use JSiefer\MageMock\Mage\MageFacade;

class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * Retrieve current mage super class mock
     *
     * This instance can be treated as mock.
     *
     * @return \Mage|BaseMock
     */
    protected function getMage()
    {
        return MageFacade::getInstance();
    }

    /**
     * Retrieve singleton mock instance
     *
     * @param string $name
     *
     * @return \Mage_Core_Model_Abstract|BaseMock
     */
    protected function getSingleton($name)
    {
        return $this->getMage()->getSingleton($name);
    }

    /**
     * Register a factory class to call when creating a new module
     *
     * @param string $name
     * @param \Closure $factory
     */
    protected function registerModelFactory($name, $factory)
    {
        $mage = $this->getMage();

        $mage->registerModelFactory($name, $factory);
    }
}
