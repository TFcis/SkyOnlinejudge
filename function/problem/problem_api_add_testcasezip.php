<?php namespace SKYOJ\Problem;

function problem_api_add_testcasezipHandle()
{
    global $_G,$_E,$SkyOJ;

    try{
        if( !$SkyOJ->User->isAdmin() )
            \SKYOJ\throwjson('error', 'Access denied');

        $pid = \SKYOJ\safe_post('pid');
        $problem = new \SkyOJ\Problem\Container();

        if( !$problem->load($pid) )
            \SKYOJ\throwjson('error','param error');
        if( !$problem->writeable($SkyOJ->User) )
            \SKYOJ\throwjson('error', 'Access denied');
        
        $file = $_FILES['file']??['error'=>1];
        if( $file['error'] != \UPLOAD_ERR_OK)
            \SKYOJ\throwjson('error', 'Upload Error : '.$file['error']);
        $problem->admmsg = "wait system deal with it...";
        $problem->save();
        $SkyOJ->throwjson_keep('SUCC',"succ");
    }catch(\Exception $e){
        \SKYOJ\throwjson('error',$e->getMessage());
    }

    //Flushed! run on back round
    try{
        //Unzip data
        $problem->admmsg = "Unzip...";
        $problem->save();
        $problem->getDataManager()->copyTestcasesZip($file['tmp_name']);
        
        
        /*$judgename = $problem->GetJudge();
        if( \Plugin::loadClassFileInstalled('judge',$judgename)!==false )
            $judge = new $judgename;
        
        //TODO
        if( $judge ){
            $compilers = $judge->get_compiler();
            if( !\array_key_exists($compiler,$compilers) )
                throw new \Exception('NoSuchJudge');
        }else if( !empty($compiler) ){
            throw new \Exception('NoSuchJudge');
        }*/
        $problem->admmsg = "ok";
        $problem->save();
    }catch(\Exception $e){
        $problem->admmsg = $e->getMessage();
        $problem->save();
        \Log::msg(\Level::Error,'judge error:'.$e->getMessage());
    }
}