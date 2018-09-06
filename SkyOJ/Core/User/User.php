<?php namespace SkyOJ\Core\User;
use \SkyOJ\Core\Permission\UserLevel;

class User extends \SkyOJ\Core\CommonObject implements \SkyOJ\Core\Permission\Permissible
{
    protected static $table = 'account'; 
    protected static $prime_key = 'uid';

    public static function getGuestData()
    {
        return [
            'username'=>'Guest',
            'uid'=>0,
            'level' => UserLevel::GUEST
        ];
    }

    static public function isEmail(string $email)
    {
        $pattern = '/^[A-z0-9_.]{1,30}@[A-z0-9_.]{1,20}$/';
        return preg_match($pattern, $email);
    }

    static public function create(string $username, string $passhash, string $email):int
    {
        //TODO: check all!!
        if( !self::isEmail($email) )
            return false;

        $default = [
            'email' => $email,
            'passhash' => $passhash,
            'nickname'  => $username,
            'level'=> UserLevel::USER
        ];
        return self::insertInto($default);
    }

    public function afterLoad()
    {
        $this->sqldata['uid'] = (int)$this->uid;
        $this->sqldata['level'] = (int)$this->level;
        return true;
    }

    public function getObjLevel():int
    {
        return $this->level-1;
    }

    function isAdmin()
    {
        return $this->level >= UserLevel::ADMIN;
    }

    function isUser()
    {
        return $this->level >= UserLevel::USER;
    }

    function isLogin()
    {
        return $this->level >= UserLevel::GUEST;
    }

    function testStisfyPermission(int $owner,int $minReqlevel):bool
    {
        $ownLevel = (int)(self::fetchColByPrimeID($owner,'level')[0]);
        return  $this->level > $ownLevel ||
                $this->level > $minReqlevel ||
                $this->uid == $owner;

    }

    public function readable(User $user):bool
    {
        return true;
    }
    public function writeable(User $user):bool
    {
        return testStisfyPermission($user->uid, Level::Admin);
    }
    public static function creatable(User $user):bool
    {
        return true;
    }
}