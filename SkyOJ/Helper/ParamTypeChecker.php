<?php namespace SkyOJ\Helper;

final class ParamTypeChecker 
{
    function __construct(){die();}

    static public function check(string $type,$val)
    {
        switch($type)
        {
            case 'int':    return self::isInt($val);
            case 'string': return self::isString($val);
        }
        return false;
    }

    static private function isInt($v):bool
    {
        if( is_int($v) || $v === "0" ) return true;
        if( !is_string($v) || strlen($v)==0 || $v[0]==='0' ) return false;
        if( $v[0]=='-' ) $v = substr($v, 1);
        return ctype_digit($v);
    }

    static private function isString($v):bool
    {
        return is_string($v);
    }
}