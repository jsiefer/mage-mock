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
 * Interface ClassNameResolverInterface
 *
 * @package JSiefer\MageMock
 */
interface ClassNameResolverInterface
{
    const TYPE_MODEL = 'Model';
    const TYPE_RESOURCE = 'Model_Resource';
    const TYPE_BLOCK = 'Block';
    const TYPE_HELPER = 'Helper';

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
    public function resolve($type, $name);
}
