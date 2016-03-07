<?php
if( !defined('IN_SKYOJSYSTEM') )
{
    exit('Access denied');
}
//http://stackoverflow.com/questions/10368620/abstract-constants-in-php-force-a-child-class-to-define-a-constant
class Enforcer {
    public static function __add($class, $c) {
        $reflection = new ReflectionClass($class);
        $constantsForced = $reflection->getConstants();
        foreach ($constantsForced as $constant => $value) {
            if (constant("$c::$constant") == "abstract") {
                throw new Exception("Undefined $constant in " . (string) $c);
            }
        }
    }
}

abstract class PlugunBase{
    /**
     * some constant subclass should set.
     */
    const VERSION       = 'abstract';
    const NAME          = 'abstract';
    const DESCRIPTION   = 'abstract';
    const COPYRIGHT     = 'abstract';
    public function __construct(){
        Enforcer::__add(__CLASS__, get_called_class());
    }
    
    /**
     * function requiredFunctions():array;
     * @return array of strings
     *      like : ['strlen','md5']
     *      it support SKY OJ SYSTEM to check env for install this Plugun.
     */
    abstract public function requiredFunctions():array;   
};

abstract class OnlineJudgeCapture extends PlugunBase{
    
};

abstract class ThirdPartySign extends PlugunBase{
    
};