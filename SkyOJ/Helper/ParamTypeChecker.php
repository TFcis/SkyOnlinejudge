<?php namespace SkyOJ\Helper;

final class ParamTypeChecker 
{
    function __construct(){die();}

    static public function check($type, $val)
    {
        if( self::isString($type) )
        {
            switch($type)
            {
                case 'int':    return self::isInt($val);
                case 'string': return self::isString($val);
                case 'json': return self::isJson($val);
            }
        }
        else if( self::isArray($type) )
        {
            if( !self::isArray($val) )
            {
                return false;
            }
            return self::checkArray($type[0], $val);
        }
        else if( self::isObject($type) )
        {
            if( !self::isObject($val) )
            {
                return false;
            }
            return self::checkObject($type, $val);
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

    static private function isJson($v):bool
    {
        if( json_decode($v)===NULL )
        {
            return false;
        }
        return true;
    }

    static private function isArray($v):bool
    {
        return is_array($v);
    }

    static private function checkArray($type, $v):bool
    {
        $check=true;
        foreach($v as $e)
        {
            if( !self::check($type, $e) )
            {
                $check=false;
            }
        }
        return $check;
    }

    static private function isObject($v):bool
    {
        return is_object($v);
    }

    static private function checkObject($type, $v):bool
    {
        $check=true;
        foreach($type as $key => $te)
        {
            if( !property_exists($v, $key) )
            {
                return false;
            }
            if( !self::check($te, $v->$key) )
            {
                $check=false;
            }
        }
        return $check;
    }
}