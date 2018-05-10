<?php
use PHPUnit\Framework\TestCase;
use SkyOJ\Core\Permission\UserLevel;

class UserLevelTest extends TestCase
{
    public function testRootConst()
    {
        $this->assertEquals(100,UserLevel::ROOT);
    }

    public function testGuestConst()
    {
        $this->assertEquals(0,UserLevel::GUEST);
    }
}