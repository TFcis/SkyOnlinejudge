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
                                LEFT JOIN `{$tproblem}`
		                        ON `{$tchallenge}`.`pid` = `{$tproblem}`.`pid`
                              WHERE `cid` = ?",$cid);

        if( $data===false )
        {
            throw new \Exception('cid error');
        }

        if( !\SKYOJ\Problem::hasContentAccess_s($_G['uid'],$data['owner'],$data['content_access'],$data['pid']) )
        {
            throw new \Exception('不具有檢視權限，無法觀看');
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


