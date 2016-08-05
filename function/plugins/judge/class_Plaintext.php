<?php

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

class class_Plaintext extends Judge
{
    const VERSION = '0.1-alpha';
    const NAME = 'Plain text compare judge';
    const DESCRIPTION = 'Just a plain text compare judge';
    const COPYRIGHT = 'SKY Online Judge 2016';
    public static function requiredFunctions():array
    {
        return ['strlen'];
    }

    public static function licence_tmpl():array
    {
        return ['plugin_default_licence', 'common'];
    }

    public static function installForm():array
    {
        return [
            'data' => [

            ]
        ];
    }
}