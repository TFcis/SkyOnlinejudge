<?php namespace SkyOJ\Helper;

use \SkyOJ\Core\DataBase\DB;

//TODO: Write a DB operation class to remove DB class
abstract class SqlEnumDBHelper extends \SkyOJ\Core\CommonObject
{
    protected static $table;
    protected static $prime_key;

    static function setTable($table,$prime_key)
    {
        self::$table = $table;
        self::$prime_key = $prime_key;
    }

    static function selectall(string $table)
    {
        $table = DB::tname($table);
        $data = \DB::fetchAll("SELECT * FROM `{$table}` WHERE 1");
        if( $data === false )
            throw new \Exception('sql error');
        return $data;
    }

    static function _insertinto($val)
    {
        return self::insertinto($val);
    }
}

abstract class SqlEnum extends Enum 
{
    protected static $table;
    protected static $prime_key = 'id';
    protected static $text_key = 'text';

    // Array of text => id
    private static  $cacheTextToValue = [];
    // Array of id => text
    private static  $cacheValueToText = [];
    // Array of id => sqldata
    private static  $cacheValueToData = [];


    private static function setCacheFromSqlRows($sql)
    {
        $t2v = [];
        $v2t = [];
        $v2d = [];
        foreach( $sql as $row )
        {
            $t2v[$row[static::$text_key]] = (int)$row[static::$prime_key];
            $v2t[$row[static::$prime_key]] = $row[static::$text_key];
            $v2d[$row[static::$prime_key]] = $row;
        }

        $constants = new \ReflectionClass(get_called_class());
        foreach( $constants->getConstants() as $text => $val )
        {
            if( isset($t2v[$text]) )
                throw new \Exception('duplicate text : '.$text);
            if( isset($v2t[$val]) )
                throw new \Exception('duplicate key : '.$val);
    
            $t2v[$text] = $val;
            $v2t[$val] = $text;
            $v2d[$val] = [
                static::$prime_key => $val,
                static::$text_key => $text
            ];
        }
        
        self::$cacheTextToValue[static::$table] = $t2v;
        self::$cacheValueToText[static::$table] = $v2t;
        self::$cacheValueToData[static::$table] = $v2d;
        //Todo add reflectRef const here
    }

    protected static function _getConstants()
    {
        if( !array_key_exists(static::$table, self::$cacheTextToValue) )
        {
            $data = SqlEnumDBHelper::selectall(static::$table);
            self::setCacheFromSqlRows($data);
        }
        return self::$cacheTextToValue[static::$table];
    }

    protected static function _getRConstants()
    {
        return self::$cacheValueToText[static::$table];
    }

    protected static function _isValidName($name, $strict = false)
    {
        $constants = self::getConstants();

        if ($strict) {
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

    public static function getRowData($value, $strict=false)
    {
        if( !self::isValidValue($value, $strict) )
        {
            return [];
        }
        return self::$cacheValueToData[static::$table][$value]??[];
    }

    protected static function insertinto(array $val)
    {
        if( !isset($val[static::$text_key]) || self::isValidName($val[static::$text_key],false) )
            throw new \Exception('empty or duplicate text');

        SqlEnumDBHelper::setTable(static::$table,static::$prime_key);
        return SqlEnumDBHelper::_insertinto($val);
    }
}