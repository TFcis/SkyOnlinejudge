<?php
if( !defined('IN_SKYOJSYSTEM') )
{
    exit('Access denied');
}

$challenge = new challenge(1,1,"test",'cpp');
//$socket = new socket($_config['socket']['judgehost'],$_config['socket']['judgeport']);
$judge = new judge($challenge);

/*class testtestresult
{
    public $test_idx = 0;
    public $state = 1;
    public $runtime = 1;
    public $peakmem = 512;
    public $verdict = '';
}

class test
{
    public $chal_id;
    public $verdict = '';
    public $result = [];
}

$result= new test();
$result->result[0]=new testtestresult();
$result->chal_id=$challenge->get_id();
$result=json_encode($result);

$judge->getresult($result);*/
$judge->start();
?>