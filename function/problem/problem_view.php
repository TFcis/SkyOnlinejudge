<?php namespace SKYOJ\Problem;
if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

function viewHandle()
{
    global $SkyOJ,$_E,$_G;

    $pid = $SkyOJ->UriParam(2);

    try{
        $problem = new \SKYOJ\Problem($pid);
        $pid = $problem->pid();

        if( $problem->pid()===null )
            throw new \Exception('題目載入失敗');

        if( !$problem->hasContentAccess($_G['uid']) )
        {
            if( $problem->hasSubmitAccess($_G['uid']) )
            {
                 header("Location:".$SkyOJ->uri('problem','submit',$pid));
                 exit();
            }
            throw new \Exception('權限不足，不開放此題目');
        }
        $_E['template']['problem'] = $problem;
        $SkyOJ->SetTitle($problem->GetTitle());
        \Render::render('problem_view','problem');
    }catch(\Exception $e){
        \Render::errormessage($e->getMessage());
        \Render::render('nonedefined');
    }
}