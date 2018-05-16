<?php
use PHPUnit\Framework\TestCase;
use \SkyOJ\Helper\ParamTypeChecker;
class paramTypeCheckerTest extends TestCase
{
    public function testCheckInt()
    {
        $this->assertTrue( ParamTypeChecker::check('int',123) );
        $this->assertTrue( ParamTypeChecker::check('int',0) );
        $this->assertTrue( ParamTypeChecker::check('int','0') );
        $this->assertTrue( ParamTypeChecker::check('int','123') );
        $this->assertTrue( ParamTypeChecker::check('int','2147483647') );

        $this->assertFalse( ParamTypeChecker::check('int',12.3) );
        $this->assertFalse( ParamTypeChecker::check('int','00') );
        $this->assertFalse( ParamTypeChecker::check('int','0.0') );
        $this->assertFalse( ParamTypeChecker::check('int','087') );
        $this->assertFalse( ParamTypeChecker::check('int','abcd') );
    }
}
