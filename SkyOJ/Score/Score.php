<?php namespace SkyOJ\Score;

abstract class ScoreMode
{
    const VERSION = 'abstract';
    const NAME = 'abstract';
    const DESCRIPTION = 'abstract';
    const COPYRIGHT = 'abstract';
    
    abstract public static function patten():string;
    abstract public static function is_match(string $scoretype):bool;

    public static function installForm($oldprofile = null):array
    {
        return [];
    }
    public static function checkProfile($post, &$msg)
    {
        $json = [];
        $msg = "SUCC";
        return json_encode($json);
    }

    abstract public static function calculate(string $scoretype,$res);
}

class Score
{
    function __construct()
    {
        self::pluginInit();
    }
    static $plugins = [];
    static function pluginInit()
    {
        global $_E;
        static $loaded = false;

        if( $loaded ) return;
        $loaded = true;

        $class_files = ScoreModeEnum::getConstants();
        unset( $class_files[ScoreModeEnum::str(ScoreModeEnum::None)] );
        foreach($class_files as $cl)
        {
            $base = ScoreModeEnum::str($cl);
            $classname = '\\SkyOJ\\Score\\Plugin\\'.$base;
            self::$plugins[$base] = new $classname;
        }
    }
    public function score($mode, $res, $scoretype)
    {
        if(!isset(self::$plugins[$mode]))
        {
            throw new \Exception("No such ScoreMode $mode!");
        }
        return self::$plugins[$mode]::calculate($scoretype, $res);
    }
}