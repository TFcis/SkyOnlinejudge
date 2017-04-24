<?php namespace SKYOJ;

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}
/**
 * Copy From PHP.net 
 * http://php.net/manual/en/class.splenum.php
 */
abstract class BasicEnum {
    private static $constCacheArray  = [];
    private static $constCacheRArray = [];

    public static function getConstants() {
        $calledClass = get_called_class();
        if (!array_key_exists($calledClass, self::$constCacheArray)) {
            $reflect = new \ReflectionClass($calledClass);
            self::$constCacheArray[$calledClass] = $reflect->getConstants();
        }
        return self::$constCacheArray[$calledClass];
    }

    public static function getRConstants()
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

    public static function isValidName($name, $strict = false) {
        $constants = self::getConstants();

        if ($strict) {
            return array_key_exists($name, $constants);
        }

        $keys = array_map('strtolower', array_keys($constants));
        return in_array(strtolower($name), $keys);
    }

    public static function isValidValue($value, $strict = true) {
        $values = array_values(self::getConstants());
        return in_array($value, $values, $strict);
    }

    public static function str($value,$strict=false):string
    {
        if( !self::isValidValue($value,$strict) )
        {
            return "[Unknown EnumVal]";
        }
        $d = self::getRConstants();
        return $d[$value];
    }
}