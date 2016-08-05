<?php

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}
/**
 * @file
 * @brief
 *
 * @author LFsWang
 * @copyright 2016 Sky Online Judge Project
 */
require_once 'function/plugins/plugins.base.php';
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

    public static function getAllFolders()
    {
        return ['rank/board_other_oj', 'user/login','judge'];
    }

    /**
     * @note path base on ROOT/function/plugins/
     *
     * @return All class_[name].file on $path
     */
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

    /**
     * @param string $fullpath The path of class file
     *
     * @return stdand class name, return False if failed.
     */
    public static function getClassName(string $fullpath)
    {
        static $pname = "/\/(class_[^.\/]*)\.php/";
        if (preg_match($pname, $fullpath, $matches)) {
            return $matches[1];
        }

        return false;
    }

    public static function isClassName(string $class)
    {
        static $p = '/^class_[a-zA-Z0-9_]{1,30}$/';

        return preg_match($p, $class);
    }

    /**
     * isStdClass will check $class is extend from std plugin class.
     *
     * @return true false
     */
    public static function isStdClass(string $class):bool
    {
        if (!class_exists($class)) {
            return false;
        }
        //TODO : Remove $test and new if available
        $test = new $class();

        return $test instanceof PluginBase;
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
     * classname(string)
     * false : fail to load class.
     */
    public static function loadClassFile(string $folder, string $class)
    {
        global $_E;
        if (!in_array($folder, self::getAllFolders())) {
            return false;
        }
        if (!self::isClassName($class)) {
            return false;
        }

        $path = $_E['ROOT'].'/function/plugins/'.$folder.'/'.$class.'.php';

        try {
            require_once $path;
        } catch (Exception $e) {
            Log::msg(Level::Error, 'loadClassFile Exception:', $e->getMessage());

            return false;
        }

        if (self::isStdClass($class)) {
            return $class;
        }

        return false;
    }

    /**
     * return value
     * array of std classname(string)
     * false : fail to load class.
     */
    public static function loadClassFileByFolder(string $path)
    {
        $classes = [];

        $files = self::listClassFileByFolder($path);
        if ($files === false) {
            return false;
        }

        foreach ($files as $file) {
            $classname = self::getClassName($file);
            $classname = self::loadClassFile($path, $classname);

            if ($classname !== false) {
                $classes[] = $classname;
            }
        }

        return $classes;
    }
}
