<?php
if(!defined('IN_SKYOJSYSTEM'))
{
  exit('Access denied');
}

class Plugin{
    static function install($name,$class)
    {
        $tb = DB::tname('plugin');
        $sql = DB::query("SELECT `id`,`version` FROM $tb WHERE `class` = '$name'");
        if($sql === false)
            return false;
        //format check
        if(!isset($class->version)){
            return false;
        }$version = $class->version;
        
        if( $res = DB::fetch($sql) )
        {
            if( $res['version'] == $version)
            {
                return true;
            }
            elseif (method_exists($class,'upgrade') ) 
            {
                if(!$class->upgrade($res['version']))
                {
                    return false;
                }
            }
            DB::query("UPDATE `$tb` SET `version`= '$version' WHERE `class` = '$name'");
            return true;
        }
        else
        {
            $timestamp = DB::timestamp();
            
            if(method_exists($class,'install'))
            {
                $class->install();
            }
            
            if(DB::query("INSERT INTO `$tb` 
                        (`id`, `class`, `version`, `author`, `timestamp`) VALUES
                        (NULL,'$name','$version','0',NULL)"))
            {
                return true;
            }
        }
        
        return false;
    }
    // path base on ROOT/function/plugins/
    static function loadClassByPluginsFolder($path)
    {
        global $_E;
        $loadedClass = array();
        $pattern = $_E['ROOT'].'/function/plugins/'.$path.'/class_*.php';
        $pname = "/\/(class_[^.\/]*)\.php/";
        $classfile = glob($pattern);
        foreach($classfile as $str)
        {
            require_once($str);
            if( preg_match($pname,$str,$matches) )
            {
                $matches = $matches[1];
                if(class_exists($matches))
                {
                    $loadedClass[$matches] = new $matches;
                    Plugin::install($matches,$loadedClass[$matches]);
                }
            }
        }
        return $loadedClass;
    }
}