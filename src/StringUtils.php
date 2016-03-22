<?php


namespace JSiefer\MageMock;


/**
 * Class StringUtils
 * @package JSiefer\ClassMocker\Utils
 */
class StringUtils
{

    /**
     * Convert string from foo_bar to FooBar
     *
     * @param string $str
     *
     * @return mixed
     */
    public static function camelize($str)
    {
        $result = str_replace(' ', '', ucwords(str_replace('_', ' ', $str)));
        return $result;
    }

    /**
     * @param string $name
     *
     * @return string
     */
    public static function underscore($name)
    {
        $result = strtolower(preg_replace('/(.)([A-Z])/', "$1_$2", $name));
        return $result;
    }

}
