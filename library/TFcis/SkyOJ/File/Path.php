<?php namespace SkyOJ\File;

class Path
{
    const DIR_SPILT_CHAR = '/';
    static public function dataBase():string
    {
        global $_E;
        if( !isset($_E['DATADIR']) )
            throw new PathException('DATADIR NOT SET!');
        if( substr($_E['DATADIR'],-1) !== self::DIR_SPILT_CHAR )
            $_E['DATADIR'].=self::DIR_SPILT_CHAR;
        return $_E['DATADIR'];
    }

    static public function idhash(int $id)
    {
        $hex = str_repeat("0",16).dechex($id);
        $hex = substr($hex,-16);
        return $hex;
    }

    static public function id2folder(int $id):string
    {
        $path = implode(self::DIR_SPILT_CHAR,str_split(self::idhash($id),2));
        return $path;
    }
}

class PathException extends \Exception {};