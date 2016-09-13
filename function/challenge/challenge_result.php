<?php namespace SKYOJ\Challenge;

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

function resultHandle()
{
    global $SkyOJ,$_E;
    $cid = $SkyOJ->UriParam(2);
    try{
        if( !preg_match('/^[1-9][0-9]*$/',$cid) )
        {
            throw new \Exception('cid error');
        }

        $tchallenge = \DB::tname('challenge');
        $data = \DB::fetchEx("SELECT * FROM `{$tchallenge}` WHERE `cid` = ?",$cid);

        if( $data===false )
        {
            throw new \Exception('cid error');
        }
        
        $_E['template']['challenge_result_info'] = $data ? $data : [];

        /*$resultpath = $_E['challenge']['path'].'result/'.$rid.'.json';
        $resultdata = file_read($resultpath);
        $resultdata = json_decode($resultdata);
        $_E['template']['challenge_result_info']['result'] = $resultdata;*/

        
        \Render::render('challenge_result', 'challenge');
    }catch(\Exception $e){
        \Render::errormessage($e->getMessage());
        \Render::render('nonedefined');
    }
}


