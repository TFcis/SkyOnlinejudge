<?php namespace SKYOJ\Code;

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

/*
 * return string if success
 * return bool false if failed
 */
final class CodeType{
    const CODEPAD = 1;
};

function genCodeHash():string
{
    return \GenerateRandomString(8);
}
function checkCodeHash(string $hash):bool
{
    //preg_match is slow?
    return \preg_match('/[A-Za-z0-9]{8}/', $hash);
}

function PutCode(string $code,int $type,int $uid)
{
    global $_G;
    $table = \DB::tname('codepad');
    $times = 10; // Try times
    do {
        $times--;
        $hash = genCodeHash();
        $uid = $_G['uid'];
        $res = \DB::queryEx("INSERT INTO `$table` (`id`,`owner`,`hash`,`type`,`timestamp`,`content`) 
                                    VALUES (NULL,?,?,?,NULL,?)",$uid,$hash,$type,$code);
    } while ( !$res && $times > 0);

    if ($times <= 0) {
        return false;
    }
    return $hash;
}

function GetCode(string $hash,int $type,&$data):bool
{
    global $_G;
    $table = \DB::tname('codepad');
    if( !checkCodeHash($hash) ){
        \Log::msg(\Level::Debug,'Code1',$hash);
        return false;
    }
    $res = \DB::fetchEx("SELECT `owner`,`type`,`timestamp`,`content` FROM `{$table}` WHERE hash =?", $hash);
    if( !$res || (int)$res['type']!==$type  ){
        \Log::msg(\Level::Debug,'Code2',$res);
        return false;
    }
    $data = $res;
    return true;
}