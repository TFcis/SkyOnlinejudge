<?php namespace SkyOJ\Scoreboard;

abstract class OJCapture
{
    const VERSION = 'abstract';
    const NAME = 'abstract';
    const DESCRIPTION = 'abstract';
    const COPYRIGHT = 'abstract';
    
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
            $data = false;
            foreach( \SkyOJ\Scoreboard\OJCaptureProfileEnum::getConstants() as $key )
            {
                if( $key == \SkyOJ\Scoreboard\OJCaptureProfileEnum::None )
                    continue;
                $res = \SkyOJ\Scoreboard\OJCaptureProfileEnum::getRowData($key);
                $ojcapture = "SkyOJ\\Scoreboard\\Plugin\\".\SkyOJ\Scoreboard\OJCaptureEnum::str($res['ojcapture']);
                if( $ojcapture!=get_called_class() )
                    continue;
                $data = $res;
                break;
            }

            if( $data === false )
                throw new \SkyOJ\Core\Exception("Plugin Not Installed!");
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
    abstract public function query($uid,$problem,$start=null,$end=null):array; //use unix timestamp
    abstract public function verifyAccount(string $acct):bool;
    abstract public function rebuild($uids,$problems);
    public static function installForm($oldprofile = null):array
    {
        return [];
    }
    public static function checkProfile($post, &$msg)
    {
        $json = [];
        $msg = "SUCC";
        return json_encode($json);
    }
    function challink($uid, $prob):string
    {
        return '';
    }
    function problink($prob):string
    {
        return '';
    }
}