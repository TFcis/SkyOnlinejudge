<?php
use PHPUnit\Framework\TestCase;

class pathTest extends TestCase
{
    private static $base = "";//sys_get_temp_dir();
    public static function setUpBeforeClass()
    {
        self::$base = sys_get_temp_dir()."/";
        \SkyOJ\File\Path::initialize(self::$base);
    }

    public function testinitialize()
    {
        \SkyOJ\File\Path::initialize("D:\\");
        $this->assertEquals( "D:/", \SkyOJ\File\Path::base() );
        \SkyOJ\File\Path::initialize("D:\\test\\");
        $this->assertEquals( "D:\\test/", \SkyOJ\File\Path::base() );
        //Recover
        \SkyOJ\File\Path::initialize(self::$base);
    }
    public function testidhash()
    {
        $this->assertEquals( 8, strlen(\SkyOJ\File\Path::idhash(0)) );
        $this->assertEquals( "00000000", \SkyOJ\File\Path::idhash(0) );
        $this->assertEquals( "00000001", \SkyOJ\File\Path::idhash(1) );
        $this->assertEquals( "0000000F", \SkyOJ\File\Path::idhash(15) );
    }

    public function testid2folder()
    {
        $this->assertEquals( 12, strlen(\SkyOJ\File\Path::id2folder(0)) );
        $this->assertEquals( "00/00/00/00/", \SkyOJ\File\Path::id2folder(0) );
        $this->assertEquals( "00/00/00/01/", \SkyOJ\File\Path::id2folder(1) );
    }

    public function testProblemDataManager()
    {
        global $_E;
        $p = new \SkyOJ\File\ProblemDataManager(1,true);
        $this->assertEquals($p->base(),self::$base."problem/00/00/00/01/");
    }
}