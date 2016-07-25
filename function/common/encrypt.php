<?php

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}
/**
 * @file encrypt.php
 * @brief Support some encrypt function
 *
 * @author LFsWang
 * @copyright 2016 Sky Online Judge Project
 */

define('TOKEN_LEN', 64);
define('SET_NUM', '0123456789');
define('SET_HEX', '0123456789abcdef');
define('SET_LOWER', 'abcdefghijklmnopqrstuvwxyz');
define('SET_UPPER', 'ABCDEFGHIJKLMNOPQRSTUVWXYZ');

/**
 * @brief Get a Random String.
 * <a href="http://stackoverflow.com/questions/4356289/php-random-string-generator/31107425#31107425">
 * source of this function.
 * </a>
 *
 * @param int    $len                                 length of random string.
 * @param string $charset=SET_NUM.SET_LOWER.SET_UPPER charset of random string.
 *
 * @return string a random string
 */
function GenerateRandomString(int $len, string $charset = SET_NUM.SET_LOWER.SET_UPPER):string
{
    $setsize = mb_strlen($charset, '8bit');
    $gen = '';
    if ($len < 0) {
        throw new Exception('GenerateRandomString(): $len < 0');
    }
    for ($i = 0; $i < $len; ++$i) {
        $gen .= $charset[random_int(0, $setsize - 1)];
    }

    return $gen;
}

/**
 * Diffie-Hellman key exchange algorithm.
 *
 * @todo Move DefaultPublicPrime/DefaultPublicG to functions
 */
class DiffieHellman
{
    const DefaultPublicPrime = '439351292910452432574786963588089477522344331'; //For test
    const DefaultPublicG = '2';
    private $Prime; ///< prime for Diffie-Hellman algorithm
    private $G;     ///< exp base for Diffie-Hellman algorithm
    private $keyA;  ///< private key
    private $GA;    ///< the value of G^keyA mod Prime

    public function __construct(string $prime = self::DefaultPublicPrime,
                         string $G = self::DefaultPublicG)
    {
        $this->Prime = $prime;
        $this->G = $G;
        $this->keyA = gmp_random(5);
        $this->GA = gmp_strval(gmp_powm($this->G, $this->keyA, $this->Prime));
    }

    public function getPrime():string
    {
        return $this->Prime;
    }

    public function getG():string
    {
        return $this->G;
    }

    public function getGA():string
    {
        return $this->GA;
    }

    public function decode(string $GB):string
    {
        return gmp_strval(gmp_powm($GB, $this->keyA, $this->Prime));
    }
}
