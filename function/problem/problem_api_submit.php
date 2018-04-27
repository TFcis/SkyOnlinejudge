<?php namespace SKYOJ\Problem;

function problem_api_submitHandle()
{
    global $SkyOJ,$_G,$_E;
    $CODE_LIMIT_LEN = 100000;

    $pid = \SKYOJ\safe_post('pid');
    $compiler = \SKYOJ\safe_post('compiler');
    $code = \SKYOJ\safe_post('code');

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

        $problem = new \SkyOJ\Problem\Container();
        if( !$problem->load($pid) )
            throw new \Exception('problem error');

        //$judge = null;
        //$judgename = $problem->GetJudge();
        //if( \Plugin::loadClassFileInstalled('judge',$judgename)!==false )
        //    $judge = new $judgename;

        
    
        #題目權限
        if( !$problem->isAllowSubmit($SkyOJ->User) )
            throw new \Exception('Access denied');

        //TODO Check compiler code
        $lang = 0; //this can be calc by judge, but we not implementation

        if( !\SKYOJ\is_utf8($code) )
            throw new \Exception('This is not a utf-8 encoding file!');

        $cid = \SkyOJ\Challenge\Container::create($SkyOJ->User->uid, $code, $pid, $lang, $compiler);
        \SKYOJ\throwjson('SUCC',$cid);
        $SkyOJ->throwjson_keep('SUCC',$cid);
    }catch(\Exception $e){
        \SKYOJ\throwjson('error',$e->getMessage());
    }

    //Flushed! run on back round
    /*try{
        $data = new \SKYOJ\Challenge\Challenge($cid);
        $res = $data->run_judge();

        if( $res === false )
        {
            //Give JE for this
            throw new \Exception('run_judge error');
        }
    }catch(\Exception $e){
        \Log::msg(\Level::Error,'judge error:'.$e->getMessage());
    }*/
}