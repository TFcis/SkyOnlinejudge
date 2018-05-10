<?php namespace SkyOJ\Judge;

use \SkyOJ\Code\Database\DB;

abstract class Judge
{
    private static $m_judges = [];
    private static $null = null;
    public static function &getJudgeReference(int $profile):?Judge
    {
        if( !JudgeProfileEnum::isValidValue($profile) || $profile == JudgeProfileEnum::None )
            return self::$null;

        if( !isset(self::$m_judges[$profile]) )
        {
            $info = JudgeProfileEnum::getRowData($profile);
            $classname = __NAMESPACE__.'\\'.JudgeTypeEnum::str((int)$info['judge']);
            self::$m_judges[$profile] = new $classname($info['profile']);
        }

        return self::$m_judges[$profile];
    }

    abstract public function judge(\SKYOJ\Challenge\Container $chal);
}

class JudgeException extends \Exception { }