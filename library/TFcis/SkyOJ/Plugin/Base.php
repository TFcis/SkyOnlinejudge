<?php namespace SkyOJ\Plugin\Base;

abstract class Base
{
    /**
     * some constant subclass should set.
     */
    const VERSION = 'abstract';
    const NAME = 'abstract';
    const DESCRIPTION = 'abstract';
    const COPYRIGHT = 'abstract';

    public function __construct()
    {
        Enforcer::__add(__CLASS__, get_called_class());
    }
    
    static $_valtmp = [];
    static private function _getSysValT()
    {
        static $t;
        if(isset($t))return $t;
        return $t = \DB::tname('sysvalue'); 
    }

    static protected function getval(string $name)
    {
        $t =self::_getSysValT();
        $name = get_called_class()."$".$name;
        
        if( isset(self::$_valtmp[$name]) )
        {
            return self::$_valtmp[$name];
        }

        $data = \DB::fetchEx("SELECT `var` FROM `{$t}` WHERE `name` = ?",$name);
        if( $data && isset($data['var']) )
        {
            return self::$_valtmp[$name] = $data['var'];
        }
        return null;
    }
    
    static protected function setval(string $name,$val)
    {
        $t = self::_getSysValT();
        $name = get_called_class()."$".$name;
        return false!==\DB::queryEx("INSERT INTO `{$t}` (`name`,`var`)
                                   VALUES (?,?)
                                   ON DUPLICATE KEY UPDATE
                                    `var` = VALUES(`var`)",$name,$val);
    }
    /**
     * function requiredFunctions():array;.
     *
     * @return array of strings
     *               like : ['strlen','md5']
     *               it support SKY OJ SYSTEM to check env for install this PlugIn.
     */
    abstract public static function requiredFunctions():array;

    /**
     * @return location of pair of licence tmpl file
     */
    public static function licence_tmpl():array
    {
        return ['plugin_default_licence', 'common'];
    }

    /**
     * function installForm():array.
     *
     * @return Render::Form Gen format array
     */
    public static function installForm():array
    {
        return [];
    }

    /**
     * function install(&$error_msg):bool
     *
     * @param &$error_msg OUTPUT return error message
     * @return bool is insatlled
     */
     public static function install(&$error_msg):bool
     {
         return true;
     }

     public static function uninstall(&$error_msg):bool
     {
         return true;
     }
}