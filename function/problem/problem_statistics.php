<?php namespace SKYOJ\Problem;
if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}
require_once($_E['ROOT'].'/function/challenge/challenge.lib.php');
function statisticsHandle()
{
    global $SkyOJ,$_E,$_G;

    $pid = $SkyOJ->UriParam(2);
    
    try{
        $problem = new \SkyOJ\Problem\Container();
        if( !$problem->load($pid) )
            throw new \Exception('題目載入失敗');

        if( !$problem->readable($SkyOJ->User) )
        {
            throw new \Exception('權限不足，不開放此題目');
        }

        $_E['template']['problem'] = $problem;
        $_E['template']['rank_chal'] = \SKYOJ\Challenge\get_ranked_chal($pid);

        //count chal
        $t = \DB::tname('challenge');
        $count = \DB::fetchAllEx("SELECT `result`,COUNT(`result`) FROM {$t} WHERE `pid`=? GROUP BY `result`",$pid);
        $info = [];
        foreach( $count as $row )
            $info[$row[0]]=$row[1];

        $SkyOJ->SetTitle($problem->title);
        $chart = [];
        $chart['labels'] = [];
        $chart['datasets'] = [];
        $set['data']=[];
        $set['backgroundColor']=[];
        $set['hoverBackgroundColor']=[];
        foreach(\SKYOJ\RESULTCODE::getConstants() as $name => $id)
        {
            if( isset($info[$id]) ){
                $chart['labels'][] = $name;
                $set['data'][] = (int)$info[$id];
                $set['backgroundColor'][] = \SKYOJ\getresultcolor($id);
            }
        }
        $chart['datasets'][] = $set;
        $_E['template']['chart'] = $chart;
        \Render::render('problem_statistics','problem');
    }catch(\Exception $e){
        \Render::errormessage($e->getMessage());
        \Render::render('nonedefined');
    }
}
