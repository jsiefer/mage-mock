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


/**
 * Class Mage_Core_Helper_Abstract
 *
 * @sort 100
 * @pattern Mage_Core_Helper_Abstract
 */
trait Mage_Core_Helper_Abstract
{

    /**
     * @param $text
     * @param $text,...
     *
     * @return string
     */
    public function __($text)
    {
        $args = func_get_args();
        return vsprintf(array_shift($args), $args);
    }

}
