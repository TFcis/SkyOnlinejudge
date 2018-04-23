<?php namespace SkyOJ\Core\Permission;

class ObjectLevel extends \SkyOJ\Helper\Enum
{
    const EVERYONE = -1;
    const LOGIN = 1;
    const USER = 5;
    const USER_PRIVATE = 6;
    const ADMIN = 7;
    const ADMIN_PRIVATE = 8;
    const ROOT = 99;

    static function allowAccessSameGroup(int $code)
    {
        //exclude root
        if( $code == self::ROOT )
            return $code;
        return ($code|1)-1;
    }

    static function disallowAccessSameGroup(int $code)
    {
        return $code|1;
    }

    static function atLeastAdmin(int $opcode = 0)
    {
        if( $opcode < self::ADMIN )
            return self::ADMIN;
        return $opcode;
    }
}