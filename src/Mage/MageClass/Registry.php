<?php
/**
 * This file is part of ClassMocker.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package  JSiefer\MageMock
 */
namespace JSiefer\MageMock\Mage\MageClass;


/**
 * Class Registry
 *
 * This trait mimics the Mage::registry() functionality.
 *
 * @pattern MageClass
 * @sort 100
 * @package JSiefer\MageMock
 */
trait Registry
{
    /**
     * Registry container
     *
     * @var mixed[]
     */
    private $registry = [];

    /**
     * Register a new variable
     *
     * @param string $key
     * @param mixed $value
     * @param bool $graceful
     *
     * @return void
     * @throws Mage_Core_Exception
     */
    public function register($key, $value, $graceful = false)
    {
        if (isset($this->registry[$key])) {
            if ($graceful) {
                return;
            }
            throw new Mage_Core_Exception('Mage registry key "'.$key.'" already exists');
        }
        $this->registry[$key] = $value;
    }

    /**
     * Unregister a variable from register by key
     *
     * @param string $key
     */
    public function unregister($key)
    {
        if (isset($this->registry[$key])) {
            $value = $this->registry[$key];

            if (is_object($value) && method_exists($value, '__destruct')) {
                $value->__destruct();
            }

            unset($this->registry[$key]);
        }
    }

    /**
     * Retrieve a value from registry by a key
     *
     * @param string $key
     * @return mixed
     */
    public function registry($key)
    {
        if (isset($this->registry[$key])) {
            return $this->registry[$key];
        }

        return null;
    }
}
