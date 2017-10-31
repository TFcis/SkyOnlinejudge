<?php namespace SkyOJ\Plugin;

abstract class Scoreboard
{
    abstract function patten():string;
    abstract function is_match(string $name):bool;

    //user account
    function uid2ojaccount(int $uid):?string
    {
        static $ojid = null;
        static $cache = [];
        if( array_key_exists($uid,$cache) )
            return $cache[$uid];
        if( !isset($ojid) )
        {
            $table = \DB::tname("ojlist");
            $data = \DB::fetchEx("SELECT `id` FROM $table WHERE `class` = ?",get_called_class());

            if( $data === false )
                throw new \SkyOJ\Core\Exception("CLASS NOT REGISTER!");
            $ojid = $data['id'];
        }
        $table = \DB::tname("userojacct");
        $data = \DB::fetchEx("SELECT `account` FROM $table WHERE uid=? AND id=?",$uid,$ojid);

        if( $data === false )
            return $cache[$uid] = null;
        return $cache[$uid] = $data['account'];
    }
    //problem information
    abstract function get_title(string $name):?string;

    abstract public function prepare($uids,$problems);
    abstract public function query($uid,$problem);
}