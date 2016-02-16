<?php
/*
 * encrypt
 * 2016 Sky Online Judge Project
 * By LFsWang
 * This file support some encrypt function
 */
if(!defined('IN_SKYOJSYSTEM'))
{
    exit('Access denied');
}

//http://stackoverflow.com/questions/4356289/php-random-string-generator/31107425#31107425
//Get a Random String
define('TOKEN_LEN',64);
define('SET_NUM',"0123456789");
define('SET_LOWER',"abcdefghijklmnopqrstuvwxyz");
define('SET_UPPER',"ABCDEFGHIJKLMNOPQRSTUVWXYZ");
function GenerateRandomString(int $len,string $charset=SET_NUM.SET_LOWER.SET_UPPER)
{
    $setsize = mb_strlen($charset,'8bit');
    $gen = '';
    if( $len < 0 )
    {
        throw new Exception('GenerateRandomString(): $len < 0');
    }
    for($i=0;$i<$len;++$i)
    {
        $gen .= $charset[ random_int(0,$setsize-1) ];
    }
    return $gen;
}
