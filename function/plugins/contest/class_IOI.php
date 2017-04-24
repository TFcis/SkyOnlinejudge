<?php

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

class class_IOI extends ContestManger
{
    const VERSION = '0.1-alpha';
    const NAME = 'IOI';
    const DESCRIPTION = 'IOI Style Contest';
    const COPYRIGHT = 'Sylveon';
    private $contest;

    public static function requiredFunctions():array
    {
        return [];
    }

    public static function licence_tmpl():array
    {
        return ['mit_license', 'user'];
    }

    public static function installForm():array
    {
        return [];
    }

    public static function install(&$msg):bool
    {
        return true;
    }

    public function compare(\SKYOJ\UserBlock $a,\SKYOJ\UserBlock $b)
    {
        return $b->score <=> $a->score;
    }

    public function scoreboard_template():array
    {
        return ['view_scoreboard_ioi','contest'];
    }
}
