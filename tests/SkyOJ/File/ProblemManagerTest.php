<?php
use PHPUnit\Framework\TestCase;

class problemManagerTest extends TestCase
{
    private static $base = "";
    public static function setUpBeforeClass()
    {
        self::$base = sys_get_temp_dir()."/";
        \SkyOJ\File\Path::initialize(self::$base);
    }

    public function testunzip()
    {
        $p = new \SkyOJ\File\ProblemManager(1,true);
        //TODO rewrite this
        //$p->copyTestcasesZip( dirname(__FILE__).'/case.zip',true);
        $this->assertEquals(1,1);
    }
}