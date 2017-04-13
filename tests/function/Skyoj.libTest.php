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
}