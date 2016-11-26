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


use JSiefer\ClassMocker\Mock\BaseMock;
use JSiefer\MageMock\ClassNameResolverInterface;
use JSiefer\MageMock\Mage\MageFacade;


/**
 * Class ModelFactory
 *
 * Mimics the `getModel` functionality from Mage class
 *
 * getModel()
 * getSingleton()
 * getResourceModel()
 * getResourceSingleton()
 *
 * @pattern MageClass
 * @sort 100
 *
 * @package JSiefer\MageMock
 */
trait ModelFactory
{
    /**
     * A collection of closure functions for creating new models
     * when calling getModel().
     *
     * @see registerModelFactory()
     *
     * @var \Closure[]
     */
    private $modelFactories = [];

    /**
     * Retrieve model instance from model name signature
     *
     * @see \Mage::getModel()
     *
     * @param string $modelName
     * @param array $arguments
     *
     * @return mixed|BaseMock
     */
    public function getModel($modelName, array $arguments = [])
    {
        $className = MageFacade::getNameResolver()
            ->resolve(ClassNameResolverInterface::TYPE_MODEL, $modelName);

        // check for model factory closure functions
        if (isset($this->modelFactories[$className])) {
            $factory = $this->modelFactories[$className];
            $instance = $factory($arguments);
        } else {
            $instance = new $className($arguments);
        }

        return $instance;
    }

    /**
     * Retrieve model object singleton
     *
     * @see \Mage::getSingleton()
     *
     * @param   string $modelClass
     * @param   array $arguments
     *
     * @return  mixed|BaseMock
     */
    public function getSingleton($modelClass='', array $arguments = [])
    {
        $registryKey = '_singleton/' . $modelClass;
        if (!$this->registry($registryKey)) {
            $model = $this->getModel($modelClass, $arguments);
            $this->register($registryKey, $model);
        }
        return $this->registry($registryKey);
    }

    /**
     * Retrieve object of resource model
     *
     * @see \Mage::getResourceModel()
     *
     * @param   string $modelName
     * @param   array $arguments
     *
     * @return  mixed|BaseMock
     */
    public function getResourceModel($modelName, $arguments = array())
    {
        $className = MageFacade::getNameResolver()
            ->resolve(ClassNameResolverInterface::TYPE_RESOURCE, $modelName);

        // check for model factory closure functions
        if (isset($this->modelFactories[$className])) {
            $factory = $this->modelFactories[$className];
            $instance = $factory($arguments);
        } else {
            $instance = new $className($arguments);
        }

        return $instance;
    }

    /**
     * Retrieve model object singleton
     *
     * @see \Mage::getResourceSingleton()
     *
     * @param   string $modelName
     * @param   array $arguments
     *
     * @return  mixed|BaseMock
     */
    public function getResourceSingleton($modelName, $arguments = array())
    {
        $registryKey = '_resource_singleton/' . $modelName;
        if (!$this->registry($registryKey)) {
            $model = $this->getModel($modelName, $arguments);
            $this->register($registryKey, $model);
        }
        return $this->registry($registryKey);
    }

    /**
     * Register model factory to call when calling Mage::getModel()
     *
     * @param string $name
     * @param \Closure $factory
     *
     * @return void
     */
    public function registerModelFactory($name, \Closure $factory)
    {
        $this->modelFactories[$name] = $factory;
    }

    /**
     * Register a new variable
     *
     * @param string $key
     * @param mixed $value
     * @param bool $graceful
     *
     * @return void
     */
    abstract public function register($key, $value, $graceful = false);

    /**
     * Retrieve a value from registry by a key
     *
     * @param string $key
     * @return mixed
     */
    abstract public function registry($key);
}
