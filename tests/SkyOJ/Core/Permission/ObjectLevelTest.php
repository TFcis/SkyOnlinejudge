<?php
use PHPUnit\Framework\TestCase;
use SkyOJ\Core\Permission\UserLevel;
use SkyOJ\Core\Permission\ObjectLevel;

class ObjectLevelTest extends TestCase
{
    public function testRootConst()
    {
        $this->assertEquals(99,ObjectLevel::ROOT);
        $this->assertLessThan(UserLevel::ROOT,ObjectLevel::ROOT);
    }

    public function testEveryoneConst()
    {
        $this->assertEquals(-1,ObjectLevel::EVERYONE);
        $this->assertLessThan(UserLevel::GUEST,ObjectLevel::EVERYONE);
    }
}