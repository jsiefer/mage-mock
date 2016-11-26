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
use JSiefer\MageMock\ClassNameResolver;
use JSiefer\MageMock\ClassNameResolverInterface;

/**
 * Class MageFacade
 *
 * This class will be the static access point to all static Mage calls.
 * However all static mage calls will get delegated to the MageObject class which gets
 * reset after each test.
 *
 * @pattern Mage
 * @sort 100
 * @package JSiefer\Mock\Framework\Magento
 *
 */
class MageFacade extends BaseMock
{
    /**
     * Class name resolver
     *
     * Should be valid for all test, define custom resolver in bootstrap
     * if required.
     *
     * @var ClassNameResolverInterface
     */
    private static $nameResolver;

    /**
     * Current mage instance to call static calls on
     *
     * @var Mage
     */
    private static $currentMage;

    /**
     * Retrieve class name resolver
     *
     * @return ClassNameResolverInterface
     */
    public static function getNameResolver()
    {
        if (!self::$nameResolver) {
            self::$nameResolver = new ClassNameResolver();
        }

        return self::$nameResolver;
    }

    /**
     * Set class name resolver
     *
     * @param ClassNameResolverInterface $nameResolver
     *
     * @return void
     */
    public static function setNameResolver(ClassNameResolverInterface $nameResolver)
    {
        self::$nameResolver = $nameResolver;
    }

    /**
     * Retrieve current mage mock instance
     *
     * This instances gets flushed after each test.
     *
     * @return \MageClass
     */
    public static function getInstance()
    {
        if (!self::$currentMage) {
            self::$currentMage = new \MageClass();
        }

        return self::$currentMage;
    }

    /**
     * Reset Mage god class
     *
     * If PHPUnit listener is setup correct, this should get called
     * at the end of each test.
     *
     * @see \JSiefer\MageMock\PHPUnit\TestListener::endTest()
     *
     * @return void
     */
    public static function reset()
    {
        self::$currentMage = null;
    }

    /**
     * Delegate all static calls to hour mage class instance
     *
     * @param string $name
     * @param array $arguments
     *
     * @return mixed
     */
    public static function __callStatic($name, $arguments)
    {
        $instance = self::getInstance();

        $result = call_user_func_array([$instance, $name], $arguments);

        return $result;
    }
}
