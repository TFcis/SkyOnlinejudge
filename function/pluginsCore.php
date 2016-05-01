<?php

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}
require_once 'function/plugins/pluguns.base.php';
class Plugin
{
    public static function install($name, $class)
    {
        $tb = DB::tname('plugin');
        $sql = DB::query("SELECT `id`,`version` FROM $tb WHERE `class` = '$name'");
        if ($sql === false) {
            return false;
        }
        //format check
        if (!isset($class->version)) {
            return false;
        }
        $version = $class->version;

        if ($res = DB::fetch($sql)) {
            if ($res['version'] == $version) {
                return true;
            } elseif (method_exists($class, 'upgrade')) {
                if (!$class->upgrade($res['version'])) {
                    return false;
                }
            }
            DB::query("UPDATE `$tb` SET `version`= '$version' WHERE `class` = '$name'");

            return true;
        } else {
            $timestamp = DB::timestamp();

            if (method_exists($class, 'install')) {
                $class->install();
            }

            if (DB::query("INSERT INTO `$tb` 
                        (`id`, `class`, `version`, `author`, `timestamp`) VALUES
                        (NULL,'$name','$version','0',NULL)")) {
                return true;
            }
        }

        return false;
    }

    //path base on ROOT/function/plugins/
    public static function listClassFileByFolder(string $path)
    {
        global $_E;
        $pattern = $_E['ROOT'].'/function/plugins/'.$path.'/class_*.php';
        $classfile = glob($pattern);
        if ($classfile === false) {
            Log::msg(Level::Wraning, 'listClassFileByFolder() Fail');

            return [];
        }

        return $classfile;
    }

    public static function getClassName(string $fullpath)
    {
        static $pname = "/\/(class_[^.\/]*)\.php/";
        if (preg_match($pname, $fullpath, $matches)) {
            return $matches[1];
        }

        return false;
    }

    /**
     * return value
     * false : some error
     * array() : store information name=>info.
     */
    public static function checkInstall($classname)
    {
        // type check
        if (is_string($classname)) {
            $classname = [$classname];
        }
        if (!is_array($classname)) {
            return false;
        }
        if (empty($classname)) {
            return false;
        }

        $rev = [];
        foreach ($classname as $name) {
            if (!is_string($name)) {
                return false;
            }
            $rev[$name] = false;
        }

        $q = DB::genQuestListSign(count($classname));
        $tb = DB::tname('plugin');
        $data = DB::fetchAll("SELECT * FROM `$tb` WHERE `class` IN($q)", $classname);

        if ($data === false) {
            return false;
        }
        foreach ($data as $row) {
            $rev[$data['class']] = $row;
        }

        return $rev;
    }

    /**
     * return value
     * array of classname(string)
     * false : fail to load class.
     */
    public static function loadClassFileByFolder(string $path)
    {
        $classes = [];

        $files = self::listClassFileByFolder($path);
        if ($files === false) {
            return false;
        }

        try {
            foreach ($files as $file) {
                require_once $file;
                $classname = self::getClassName($file);
                if (class_exists($classname)) {
                    $classes[] = $classname;
                }
            }
        } catch (Exception $e) {
            Log::msg(Level::Error, 'loadClassByFolder Exception:', $e->getMessage());

            return false;
        }

        return $classes;
    }

    // path base on ROOT/function/plugins/
    public static function loadClassByPluginsFolder($path)
    {
        Log::msg(Level::Notice, 'loadClassByPluginsFolder() is an old function!');
        global $_E;
        $loadedClass = [];
        $pattern = $_E['ROOT'].'/function/plugins/'.$path.'/class_*.php';
        $pname = "/\/(class_[^.\/]*)\.php/";
        $classfile = glob($pattern);
        foreach ($classfile as $str) {
            require_once $str;
            if (preg_match($pname, $str, $matches)) {
                $matches = $matches[1];
                if (class_exists($matches)) {
                    $loadedClass[$matches] = new $matches();
                    self::install($matches, $loadedClass[$matches]);
                }
            }
        }

        return $loadedClass;
    }
}
