<?php
use PHPUnit\Framework\TestCase;

class problemManagerTest extends TestCase
{
    private static $base = "D:/web/tmp/";//sys_get_temp_dir();
    public static function setUpBeforeClass()
    {
        \SkyOJ\File\Path::initialize(self::$base);
    }

    public function testunzip()
    {
        $p = new \SkyOJ\File\ProblemManager(1,true);
        $p->copyTestcasesZip( dirname(__FILE__).'/case.zip',true);
        $this->assertEquals(1,1);
    }
}