<?php
/**
 * This file is part of ClassMocker.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package  JSiefer\MageMock
 */
namespace JSiefer\MageMock\Varien;


use JSiefer\ClassMocker\next;
use JSiefer\MageMock\StringUtils;


/**
 * Class Varien_Object
 *
 * @pattern Varien_Object
 * @sort 100
 */
trait Varien_Object
{
    /**
     * @var array
     */
    protected $_data = [];

    /**
     * @param string|array $key
     * @param mixed $value
     *
     * @return $this
     */
    public function setData($key, $value = null)
    {
        if (is_array($key) && $value === null) {
            $this->_data = $key;
            return $this;
        }
        $this->_data[$key] = $value;
        return $this;
    }

    /**
     * @param array $data
     *
     * @return $this
     */
    public function addData(array $data)
    {
        $this->_data = array_merge($this->_data, $data);
        return $this;
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function getData($key = null)
    {
        if (!$key) {
            return $this->_data;
        }
        if (isset($this->_data[$key])) {
            return $this->_data[$key];
        }

        return null;
    }

    /**
     * @param string $name
     * @param array $arguments
     *
     * @return next|$this
     */
    public function ___call($name, $arguments)
    {
        if (strpos($name, 'set') === 0) {
            $field = StringUtils::underscore(substr($name, 3));
            $this->$field = $arguments[0];
            $this->setData($field, $arguments[0]);
            return $this;
        }
        if (strpos($name, 'get') === 0) {
            $field = StringUtils::underscore(substr($name, 3));
            return $this->getData($field);
        }

        return next::parent();
    }

    public function offsetExists($offset)
    {
        return isset($this->_data[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->getData($offset);
    }

    public function offsetSet($offset, $value)
    {
        $this->setData($offset, $value);
    }

    public function offsetUnset($offset)
    {
        unset($this->_data[$offset]);
    }

    /**
     * @return string
     */
    public function toJson()
    {
        $json = json_encode($this->_data);

        return $json;
    }

}
