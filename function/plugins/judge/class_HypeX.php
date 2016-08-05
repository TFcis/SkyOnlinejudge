<?php

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

class class_HypeX extends Judge
{
    const VERSION = '0.1-alpha';
    const NAME = 'HypeX Judge Bridge';
    const DESCRIPTION = 'HypeX Judge Bridge';
    const COPYRIGHT = 'HypeX Copyright (C) 2016 PZ Read (MIT License)';
    public static function requiredFunctions():array
    {
        return ['md5'];
    }

    public static function licence_tmpl():array
    {
        return ['mit_license', 'user'];
    }

    public static function installForm():array
    {
        return [
            'data' => [
                new \SKYOJ\HTML_INPUT_TEXT(['name'=>'ssh_path','option'=>['help_text'=>'主機路徑']]),
                new \SKYOJ\HTML_HR(),
            ]
        ];
    }
}