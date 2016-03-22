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


use JSiefer\ClassMocker\Mock\BaseMock;

/**
 * Class Mage
 *
 * @pattern Mage
 * @sort 100
 * @package JSiefer\Mock\Framework\Magento
 *
 */
class Mage extends BaseMock
{
    /**
     * Mage class should not be used doing testing
     *
     * @param $name
     * @param $arguments
     */
    public static function __callStatic($name, $arguments)
    {
        throw new \BadMethodCallException("'Mage::$name' was called");
    }
}
