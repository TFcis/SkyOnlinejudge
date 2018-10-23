<?php
use PHPUnit\Framework\TestCase;
use \SkyOJ\Score\Plugin;
use \SkyOJ\Challenge;
class scoreGroupMinTest extends TestCase
{
    public function testGroupMin()
    {
        $res = new \SkyOJ\Challenge\Result;
        $res->tasks[] = json_decode('{"score":0,"id":0,"runtime":100,"memory":1024,"result_code":0,"message":""}');
        $res->tasks[] = json_decode('{"score":1,"id":1,"runtime":100,"memory":1024,"result_code":0,"message":""}');
        $res->tasks[] = json_decode('{"score":1,"id":2,"runtime":100,"memory":1024,"result_code":0,"message":""}');
        $res->tasks[] = json_decode('{"score":1,"id":3,"runtime":100,"memory":1024,"result_code":0,"message":""}');
        $res->tasks[] = json_decode('{"score":1,"id":4,"runtime":100,"memory":1024,"result_code":0,"message":""}');
        $res->tasks[] = json_decode('{"score":0,"id":5,"runtime":100,"memory":1024,"result_code":0,"message":""}');
        $res->tasks[] = json_decode('{"score":1,"id":6,"runtime":100,"memory":1024,"result_code":0,"message":""}');
        $res->tasks[] = json_decode('{"score":1,"id":7,"runtime":100,"memory":1024,"result_code":0,"message":""}');
        $res->tasks[] = json_decode('{"score":0,"id":8,"runtime":100,"memory":1024,"result_code":0,"message":""}');
        $res->tasks[] = json_decode('{"score":0,"id":9,"runtime":100,"memory":1024,"result_code":0,"message":""}');
        $scoretype = "[[10,2],[20,3],[30,1],[30,2],[10,2]]";
        $score = new \SkyOJ\Score\Score();
        $this->assertEquals( $score->score("GroupMin",$res,$scoretype) , 50.0);
    }

    public function testGroupMinFloat()
    {
        $res = new \SkyOJ\Challenge\Result;
        $res->tasks[] = json_decode('{"score":0.5,"id":0,"runtime":100,"memory":1024,"result_code":0,"message":""}');
        $res->tasks[] = json_decode('{"score":1,"id":1,"runtime":100,"memory":1024,"result_code":0,"message":""}');
        $res->tasks[] = json_decode('{"score":1,"id":2,"runtime":100,"memory":1024,"result_code":0,"message":""}');
        $res->tasks[] = json_decode('{"score":0.2,"id":3,"runtime":100,"memory":1024,"result_code":0,"message":""}');
        $res->tasks[] = json_decode('{"score":1,"id":4,"runtime":100,"memory":1024,"result_code":0,"message":""}');
        $res->tasks[] = json_decode('{"score":0,"id":5,"runtime":100,"memory":1024,"result_code":0,"message":""}');
        $res->tasks[] = json_decode('{"score":1,"id":6,"runtime":100,"memory":1024,"result_code":0,"message":""}');
        $res->tasks[] = json_decode('{"score":1,"id":7,"runtime":100,"memory":1024,"result_code":0,"message":""}');
        $res->tasks[] = json_decode('{"score":1,"id":8,"runtime":100,"memory":1024,"result_code":0,"message":""}');
        $res->tasks[] = json_decode('{"score":0.1,"id":9,"runtime":100,"memory":1024,"result_code":0,"message":""}');
        $scoretype = "[[10,2],[20,3],[30,1],[30,2],[10,2]]";
        $score = new \SkyOJ\Score\Score();
        $this->assertEquals( $score->score("GroupMin",$res,$scoretype) , 40.0);
    }
}
