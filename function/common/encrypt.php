<?php
/**
 * encrypt
 * 2016 Sky Online Judge Project
 * By LFsWang
 * This file support some encrypt function
 */
if(!defined('IN_SKYOJSYSTEM'))
{
    exit('Access denied');
}

//Get a Random String
define('TOKEN_LEN',64);
define('SET_NUM',"0123456789");
define('SET_HEX',"0123456789abcdef");
define('SET_LOWER',"abcdefghijklmnopqrstuvwxyz");
define('SET_UPPER',"ABCDEFGHIJKLMNOPQRSTUVWXYZ");

/**
 * Get a Random String.
 * @link http://stackoverflow.com/questions/4356289/php-random-string-generator/31107425#31107425 source of this function.
 * 
 * @param int $len length of random string.
 * @param string $charset=SET_NUM.SET_LOWER.SET_UPPER charset of random string.
 *
 * @return string a random string
 */
function GenerateRandomString(int $len,string $charset=SET_NUM.SET_LOWER.SET_UPPER):string
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

/**
 * Diffie-Hellman key exchange algorithm
 */
class DiffieHellman
{
    const PublicPrime = "439351292910452432574786963588089477522344331"; //For test
    const PublicG = "2";
    private $GA;
    private $keyA;
    function __construct(){
        $this->keyA = gmp_random(5);
        $this->GA = gmp_powm(DiffieHellman::PublicG,$this->keyA,DiffieHellman::PublicPrime);
    }
    public function getGA(){
        return gmp_strval($this->GA);
    }
    public function decode(string $GB){
        return gmp_strval( gmp_powm($GB,$this->keyA,DiffieHellman::PublicPrime) );
    }
}

