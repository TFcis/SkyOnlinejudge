<?php namespace SkyOJ\Helper;

/**
 * Copy From PHP.net 
 * http://php.net/manual/en/class.splenum.php
 */
abstract class Enum 
{
    private static $constCacheArray  = [];
    private static $constCacheRArray = [];

    public static function getConstants()
    {
        return static::_getConstants();
    }

    //Todo rename this
    public static function getRConstants()
    {
        return static::_getRConstants();
    }

    public static function isValidName($name, $strict = false)
    {
        return static::_isValidName($name, $strict);
    }

    public static function isValidValue($value, $strict = true)
    {
        return static::_isValidValue($value, $strict);
    }

    public static function str($value, $strict=false):string
    {
        return static::_str($value, $strict);
    }

    protected static function _getConstants()
    {
        $calledClass = get_called_class();
        if (!array_key_exists($calledClass, self::$constCacheArray)) {
            $reflect = new \ReflectionClass($calledClass);
            self::$constCacheArray[$calledClass] = $reflect->getConstants();
        }
        return self::$constCacheArray[$calledClass];
    }

    protected static function _getRConstants()
    {
        $calledClass = get_called_class();
        if( !array_key_exists($calledClass, self::$constCacheRArray) )
        {
            $data = self::getConstants();
            self::$constCacheRArray[$calledClass] = [];
            foreach($data as $name => $val)
                self::$constCacheRArray[$calledClass][$val] = $name;
        }
        return self::$constCacheRArray[$calledClass];
    }

    protected static function _isValidName($name, $strict = false)
    {
        $constants = self::getConstants();

        if( $strict )
        {
            return array_key_exists($name, $constants);
        }

        $keys = array_map('strtolower', array_keys($constants));
        return in_array(strtolower($name), $keys);
    }

    protected static function _isValidValue($value, $strict = true)
    {
        $values = array_values(self::getConstants());
        return in_array($value, $values, $strict);
    }

    protected static function _str($value, $strict=false):string
    {
        if( !self::isValidValue($value, $strict) )
        {
            return "[Unknown EnumVal]";
        }
        $d = self::getRConstants();
        return $d[$value];
    }
}