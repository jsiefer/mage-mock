<?php
/**
 * This file is part of ClassMocker.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package  JSiefer\MageMock
 */
namespace JSiefer\MageMock;


/**
 * Class ClassNameResolver
 *
 * @package JSiefer\MageMock
 */
class ClassNameResolver implements ClassNameResolverInterface
{
    /**
     * Default vendor namespace to use
     *
     * @var string
     */
    private $defaultVendorNamespace = 'Mage';

    /**
     * Custom registered namespaces
     *
     * @var string[]
     */
    private $namespaces = [];

    /**
     * Resolve class name by type and instance name
     *
     * (model, customer/session) => Mage_Customer_Model_Session
     * (block, core/template_abstract) => Mage_Core_Template_Abstract
     *
     * @param string $type
     * @param string $name
     *
     * @return string
     */
    public function resolve($type, $name)
    {
        // Auto append /data name for helpers
        if ($type === self::TYPE_HELPER && strpos($name, '/') === false) {
            $name .= '/data';
        }

        $namespace = self::getPsr0NamespacePrefix($name);

        $name = ltrim(strstr($name, '/'), '/');
        $name = ucwords($name, '_');

        $className = [$namespace, $type, $name];
        $className = implode('_', $className);

        return $className;
    }

    /**
     * Retrieve namespace (PSR-0 Prefix) from model signiture.
     *
     * e.g.
     * customer/session => 'Mage_Customer_;
     *
     * @param string $name
     *
     * @return string
     */
    public function getPsr0NamespacePrefix($name)
    {
        $name = strstr($name, '/', true);

        if (isset($this->namespaces[$name])) {
            return $this->namespaces[$name];
        }

        $name = ucfirst($name);
        $name = $this->defaultVendorNamespace . '_' . $name;

        return $name;
    }

    /**
     * Set default vendor namespace (e.g. Mage)
     *
     * @param string $namespace
     *
     * @return void
     */
    public function setDefaultVendorNamespace($namespace)
    {
        $this->defaultVendorNamespace = $namespace;
    }

    /**
     * Register custom namespace.
     *
     * e.g.
     * $resolver->registerNamespace('foobar', 'JSiefer_Foobar');
     * $resolver->resolve('model', 'foobar/example') // JSiefer_Foobar_Model_Example
     *
     * @param string $prefix
     * @param string $namespace
     *
     * @return void
     */
    public function registerNamespace($prefix, $namespace)
    {
        $this->namespaces[$prefix] = $namespace;
    }
}
