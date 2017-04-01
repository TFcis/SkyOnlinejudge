<?php namespace SKYOJ\Problem;
if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

function viewHandle()
{
    global $SkyOJ,$_E,$_G;

    $pid = $SkyOJ->UriParam(2);
    $filename = $SkyOJ->UriParam(3);
    
    try{
        $problem = new \SKYOJ\Problem($pid);
        $pid = $problem->pid();

        if( isset($filename) && strlen($filename)>0 )
        {
            viewachieveHandle($problem,$filename);
            exit(0);
        }

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

function viewachieveHandle(\SKYOJ\Problem $problem,string $filename)
{
    global $_G;
    try{
        $patten = "/^[a-zA-Z0-9\.]{1,64}$/";
        if( $problem->pid() === null || !$problem->hasContentAccess($_G['uid']) )
        {
            throw new \Exception('403');
        }

        if( !preg_match($patten,$filename) )
        {
            throw new \Exception('403');
        }

        $filepath = \SKYOJ\Problem::GetHttpFolder($problem->pid()).$filename;
        if( !is_file($filepath) )
        {
            throw new \Exception('403');
        }

        header("Content-type: ".filetype($filepath));
        readfile($filepath);
    }catch(\Exception $e){
        http_response_code(403);
        exit(0);
    }
}