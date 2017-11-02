<?php namespace SkyOJ\Core\User;
use \SkyOJ\Core\Permission\UserLevel;
class User extends \SkyOJ\Core\CommonObject
{
    protected static $table = 'account'; 
    protected static $prime_key = 'uid';

    public static function getGuestData(){
        return [
            'username'=>'Guest',
            'uid'=>0,
            'level' => UserLevel::GUEST
        ];
    }

    public function getObjLevel():int
    {
        return $this->level-1;
    }

    function isAdmin()
    {
        return $this->level >= UserLevel::ADMIN;
    }

    function checkPermission(\SkyOJ\Core\CommonObject &$obj)
    {
        $ownLevel = self::fetchColByPrimeID($obj->owner,'level');
        return  $this->level > $ownLevel ||
                $this->level > $obj->getObjLevel() || 
                $this->uid == $obj->owner;
    }
}