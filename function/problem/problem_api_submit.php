<?php namespace SKYOJ\Problem;

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

require_once($_E['ROOT'].'/function/challenge/challenge.lib.php');
function problem_api_submitHandle()
{
    global $SkyOJ,$_G,$_E;
    $CODE_LIMIT_LEN = 100000;

    //TODO : 題目權限
    if( !$_G['uid'] )
        \SKYOJ\throwjson('error', 'Access denied');

    $pid = \SKYOJ\safe_post('pid');
    $compiler = \SKYOJ\safe_post('compiler');
    $code = \SKYOJ\safe_post('code');
    $uid = $_G['uid'];
    try{
        if( isset($_FILES['codefile']) )
        {
            //User use file to upload! which instead of POST['code']
            if( $_FILES['codefile']['error'] === UPLOAD_ERR_OK )
            {
                if( $_FILES['codefile']['size']>$CODE_LIMIT_LEN )
                    throw new \Exception('code length more than limit');
                $code = file_get_contents($_FILES['codefile']['tmp_name']);
            }
            else if( $_FILES['codefile']['error'] !== UPLOAD_ERR_NO_FILE )
            {
                throw new \Exception('Upload code error! #'.$_FILES['codefile']['error'] );
            }
        }

        if( !isset($pid,$compiler,$code) )
            throw new \Exception('param error');

        $problem = new \SKYOJ\Problem($pid);
        $pid = $problem->pid();

        $judge = null;
        $judgename = $problem->GetJudge();
        if( \Plugin::loadClassFileInstalled('judge',$judgename)!==false )
            $judge = new $judgename;

        if( $problem->pid()===null )
            throw new \Exception('Problem data load fail!');

        #題目權限
        if( !$problem->hasSubmitAccess($_G['uid']) )
            throw new \Exception('Access denied');
        
        //TODO
        if( $judge ){
            $compilers = $judge->get_compiler();
            if( !\array_key_exists($compiler,$compilers) )
                throw new \Exception('NoSuchJudge');
        }else if( !empty($compiler) ){
            throw new \Exception('NoSuchJudge');
        }


        if( strlen($code)>$CODE_LIMIT_LEN )
            throw new \Exception('code length more than limit');
        if( !\SKYOJ\is_utf8($code) )
            throw new \Exception('This is not a utf-8 encoding file!');

        $cid = \SKYOJ\Challenge\Challenge::create($uid,$pid,$code,$compiler);
        if( $cid===null )
            throw new \Exception('SQL Error');

        $SkyOJ->throwjson_keep('SUCC',$cid);
    }catch(\Exception $e){
        \SKYOJ\throwjson('error',$e->getMessage());
    }

    //Flushed! run on back round
    try{
        $data = new \SKYOJ\Challenge\Challenge($cid);
        $res = $data->run_judge();

        if( $res === false )
        {
            //Give JE for this
            throw new \Exception('run_judge error');
        }
    }catch(\Exception $e){
        \Log::msg(\Level::Error,'judge error:'.$e->getMessage());
    }
}