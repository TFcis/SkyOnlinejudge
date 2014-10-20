<?php
if(!defined('IN_SKYOJSYSTEM'))
{
  exit('Access denied');
}

class Plugin{
    static function install($name,$class)
    {
        $tb = DB::tname('plugin');
        if($sql = DB::query("SELECT `id` FROM $tb WHERE `class` LIKE '$name'"))
            if(DB::fetch($sql))
                return true;
        if(method_exists($class,'install'))
            $class->install();
        $timestamp = DB::timestamp();
        if(DB::query("INSERT INTO `$tb` 
                    (`id`, `class`, `version`, `author`, `timestamp`) VALUES
                    (NULL,'$name','0','0','$timestamp')"))
            return true;
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
            include($str);
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