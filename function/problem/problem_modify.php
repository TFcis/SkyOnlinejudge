<?php namespace SKYOJ\Problem;
if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

function modifyHandle()
{
    global $SkyOJ,$_E;

    $pid = $SkyOJ->UriParam(2);

    try{
        if( empty($pid) || !is_numeric($pid) )
        {
            header("Location:".$SkyOJ->uri('problem','list'));
            exit(0);
        }
        $problem = new \SkyOJ\Problem\Container();
        if( !$problem->load($pid) )
            throw new \Exception('NO SUCH PROBLEM');

        if( !$problem->writeable($SkyOJ->User) )
            throw new \Exception('權限不足，不開放此題目');

        //For attachs
        $files = $problem->getDataManager()->getAttachFiles();
        $attachs = [];
        foreach( $files as $file )
        {
            $attachs[] = [pathinfo($file,PATHINFO_BASENAME),
                            filesize($file),
                            date('Y-m-d H:i:s',filemtime($file))
                        ];
        }
        $_E['template']['attachs'] = $attachs;

        //Testdata
        $_E['template']['testdata'] = $problem->getTestdata();

        $judges_info = \Plugin::listInstalledClassFileByFolder('judge');
        $judges = [];

        $judges['empty'] = '';
        if( !empty($problem->judge) && !isset($judges_info[$problem->judge]) )
        {
            $judges['default(Not Availible)'] = $problem->judge;
        }

        foreach( $judges_info as $data )
        {
           $class = $data['class'];
           $judges[$class] = $class;
        }
        //$_E['template']['pjson'] = @file_get_contents($_E['DATADIR']."problem/{$pid}/{$pid}.json");
        $_E['template']['judges'] = $judges;

        $_E['template']['problem'] = $problem;
        $SkyOJ->SetTitle( '修改: '.$problem->title );
        \Render::render_bs4('problem_modify','problem');
    }catch(\Exception $e){
        \Render::errormessage($e->getMessage());
        \Render::render('nonedefined');
    }
    
}