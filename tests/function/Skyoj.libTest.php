<?php
use PHPUnit\Framework\TestCase;
g__loadthis(dirname(__FILE__).'/common/emnuTest.php');
g__loadthis(__FILE__);

class skyoj_libTest extends TestCase
{
    public function testNeverReach()
    {
        $this->expectException('Exception');
        \SKYOJ\NeverReach();
    }

    public function testget_timestamp()
    {
        $this->assertEquals(\SKYOJ\get_timestamp(strtotime('2017-08-08 20:17:16')),'2017-08-08 20:17:16');
        //Fix timeformat bug at PR contest
        $this->assertEquals(\SKYOJ\get_timestamp(strtotime('2017-04-12 09:57:16')),'2017-04-12 09:57:16');
    }
}