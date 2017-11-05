<?php namespace SKYOJ\Challenge;

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

function resultHandle()
{
    global $SkyOJ,$_E,$_G;
    $cid = $SkyOJ->UriParam(2);
    try{
        if( !preg_match('/^[1-9][0-9]*$/',$cid) )
        {
            throw new \Exception('cid error');
        }

        $tchallenge = \DB::tname('challenge');
        $tproblem = \DB::tname('problem');
        $data = \DB::fetchEx("SELECT * FROM `{$tchallenge}`
                                
                              WHERE `cid` = ?",$cid);
        
        if( $data===false )
        {
            throw new \Exception('cid error');
        }

        $problem = new \SkyOJ\Problem\Container();
        $problem->load($data['pid']);

        if( !$SkyOJ->User->checkPermission($problem) )
        {
            throw new \Exception('不具有檢視權限，無法觀看');
        }
        
        $_E['template']['problem'] = $problem;
        $_E['template']['challenge_result_info'] = $data ? $data : [];

        \Render::render('challenge_result', 'challenge');
    }catch(\Exception $e){
        \Render::errormessage($e->getMessage());
        \Render::render('nonedefined');
    }
}


