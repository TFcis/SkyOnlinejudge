<?php
use PHPUnit\Framework\TestCase;

class pathTest extends TestCase
{
    public function testidhash()
    {
        $this->assertEquals( 16, strlen(\SkyOJ\File\Path::idhash(0)) );
        $this->assertEquals( "0000000000000000", \SkyOJ\File\Path::idhash(0) );
        $this->assertEquals( "0000000000000001", \SkyOJ\File\Path::idhash(1) );
    }

    public function testid2folder()
    {
        $this->assertEquals( 23, strlen(\SkyOJ\File\Path::id2folder(0)) );
        $this->assertEquals( "00/00/00/00/00/00/00/00", \SkyOJ\File\Path::id2folder(0) );
        $this->assertEquals( "00/00/00/00/00/00/00/01", \SkyOJ\File\Path::id2folder(1) );
    }

    public function testProblemManager()
    {
        global $_E;
        $_E['DATADIR'] = sys_get_temp_dir();
        $p = new \SkyOJ\File\ProblemManager(1);
        $this->assertEquals($p->base(),'');
    }
}