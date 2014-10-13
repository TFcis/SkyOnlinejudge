<?php
if(!defined('IN_SKYOJSYSTEM'))
{
  exit('Access denied');
}

class Plugin{
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
                }
            }
        }
        return $loadedClass;
    }
}