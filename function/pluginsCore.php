<?php
if(!defined('IN_SKYOJSYSTEM'))
{
  exit('Access denied');
}
/**
 * @file
 * @brief
 *
 * @author LFsWang
 * @copyright 2016 Sky Online Judge Project
 */
require_once('function/plugins/pluguns.base.php');
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

    /**
     * @note path base on ROOT/function/plugins/
     * @return All class_[name].file on $path
     */
    static public function listClassFileByFolder(string $path)
    {
        global $_E;
        $pattern = $_E['ROOT'].'/function/plugins/'.$path.'/class_*.php';
        $classfile = glob($pattern);
        if( $classfile === false )
        {
            Log::msg(Level::Wraning,"listClassFileByFolder() Fail");
            return array();
        }
        return $classfile;
    }
    
    /**
     * @param string $fullpath The path of class file
     * @return stdand class name, return False if failed.
     */
    static public function getClassName(string $fullpath)
    {
        static $pname = "/\/(class_[^.\/]*)\.php/";
        if( preg_match($pname,$fullpath,$matches) )
        {
            return $matches[1];
        }
        return false;
    }
    
    /**
     * isStdClass will check $class is extend from std plugin class
     * @return true false
     */
    static public function isStdClass(string $class):bool
    {
        if( !class_exists($class) ){
            return false;
        }
        //TODO : Remove $test and new if available
        $test = new $class();
        return $test instanceof OnlineJudgeCapture;
    }

    /**
     * return value
     * false : some error
     * array() : store information name=>info
     */
    static public function checkInstall($classname)
    {
        // type check
        if( is_string($classname) ) $classname = [$classname];
        if( !is_array($classname) ) return false;
        if( empty($classname) )return false;
        
        $rev = array();
        foreach( $classname as $name )
        {
            if( !is_string($name) )
                return false;
            $rev[$name] = false;
        }
        
        $q = DB::genQuestListSign(count($classname));
        $tb = DB::tname('plugin');
        $data = DB::fetchAll("SELECT * FROM `$tb` WHERE `class` IN($q)",$classname);
        
        if( $data===false ) return false;
        foreach($data as $row)
        {
            $rev[$data['class']] = $row;
        }
        return $rev;
    }

    /**
     * return value
     * array of std classname(string)
     * false : fail to load class
     */
    static public function loadClassFileByFolder(string $path)
    {
        $classes = [];
        
        $files = Plugin::listClassFileByFolder($path);
        if( $files === false )return false;
        
        try{
            foreach( $files as $file )
            {
                require_once($file);
                $classname = Plugin::getClassName($file);
                if( Plugin::isStdClass($classname) )
                {
                    $classes[] = $classname;
                }
            }
        } catch (Exception $e) {
            Log::msg(Level::Error,"loadClassByFolder Exception:",$e->getMessage());
            return false;
        }
        return $classes;
    }
}
