<?php
if(!defined('IN_SKYOJSYSTEM'))
{
  exit('Access denied');
}

class plugins{
    // path base on
    function loadClassByPluginsFolder($path)
    {
        global $_E;
        $loadedClass = array();
        $pattern = $_E['ROOT'].'/function/plugins/'.$path.'/class_*.php';
        $pname = "/\/(class_[^.\/]*)\.php/";
        $classfile = glob($pattern);
        foreach($classfile as $str)
        {
            echo $str;
            include($str);
            if( preg_match($pname,$str,$matches) )
            {
                $matches = $matches[1];
                if(class_exists($matches))
                {
                    $classfile[$matches] = new $matches;
                }
            }
        }
    }
}
$plugins = new plugins;