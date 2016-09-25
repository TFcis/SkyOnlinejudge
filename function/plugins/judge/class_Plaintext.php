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
                new \SKYOJ\HTML_INPUT_TEXT(['name' => 'dkey','option' => ['help_text' => '序號(Test)']]),
            ]
        ];
    }

    public static function install(&$msg):bool
    {
        $key = \SKYOJ\safe_post('dkey');
        if( $key == 'GGGG' )return true;
        $msg = "E:".$key;
        return false;
    }
}