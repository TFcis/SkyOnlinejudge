<?php

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}
/**
 * @file plugins.base.php
 * @brief define plugin base classes
 *
 * @author LFsWang
 * @copyright 2016 Sky Online Judge Project
 */

//http://stackoverflow.com/questions/10368620/abstract-constants-in-php-force-a-child-class-to-define-a-constant
class Enforcer
{
    public static function __add($class, $c)
    {
        $reflection = new ReflectionClass($class);
        $constantsForced = $reflection->getConstants();
        foreach ($constantsForced as $constant => $value) {
            if (constant("$c::$constant") == 'abstract') {
                throw new Exception("Undefined $constant in ".(string) $c);
            }
        }
    }
}

abstract class PluginBase
{
    /**
     * some constant subclass should set.
     */
    const VERSION = 'abstract';
    const NAME = 'abstract';
    const DESCRIPTION = 'abstract';
    const COPYRIGHT = 'abstract';

    public function __construct()
    {
        Enforcer::__add(__CLASS__, get_called_class());
    }

    /**
     * function requiredFunctions():array;.
     *
     * @return array of strings
     *               like : ['strlen','md5']
     *               it support SKY OJ SYSTEM to check env for install this Plugun.
     */
    abstract public static function requiredFunctions():array;

    /**
     * @return location of pair of licence tmpl file
     */
    public static function licence_tmpl():array
    {
        return ['plugin_default_licence', 'common'];
    }

    /**
     * function installForm():array.
     *
     * @return Render::Form Gen format array
     */
    public static function installForm():array
    {
        return [
            'data' => [
                ['type' => 'text', 'name' => 'A', 'title' => 'URL'],
                ['type' => 'hr'],
                ['type' => 'text', 'name' => 'A', 'title' => 'URL'],
                ['type' => 'text', 'name' => 'A', 'title' => 'URL'],
                ['type' => 'submit', 'id' => 'install', 'option' => ['info' => '']],
            ],
        ];
    }
}

abstract class OnlineJudgeCapture extends PluginBase
{
    const PATTERN = 'abstract';

    public function __construct()
    {
        parent::__construct();
        Enforcer::__add(__CLASS__, get_called_class());
    }
}

abstract class ThirdPartySign extends PluginBase
{
}
