<?php
g__loadthis(__FILE__);
use PHPUnit\Framework\TestCase;
class encryptTest extends TestCase
{
    public function testEncryptConst()
    {
        $this->assertEquals(10, strlen(SET_NUM));
        $this->assertEquals(16, strlen(SET_HEX));
        $this->assertEquals(26, strlen(SET_LOWER));
        $this->assertEquals(26, strlen(SET_UPPER));
    }

    public function testDiffieHellman()
    {
        //Constant Test
        $this->assertNotEquals(0, gmp_prob_prime(\SKYOJ\DiffieHellman::DefaultPublicPrime, 100));

        //Function test
        $dh = new \SKYOJ\DiffieHellman();
        $B = '41965498';
        $GB = gmp_strval(gmp_powm($dh->GetG(), $B, $dh->GetPrime()));
        $GAB = gmp_strval(gmp_powm($dh->getGA(), $B, $dh->GetPrime()));
        $this->assertEquals($dh->decode($GB), $GAB);
    }
}
