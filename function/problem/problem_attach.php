<?php namespace SKYOJ\Problem;

function attachHandle()
{
    global $SkyOJ,$_E,$_G;

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

        if( !$problem->isAllowEdit($SkyOJ->User) )
            throw new \Exception('權限不足，不開放此題目');
        
        $files = $problem->getFileManager()->getAttachFiles();
        $attachs = [];
        foreach( $files as $file )
        {
            $attachs[] = [pathinfo($file,PATHINFO_BASENAME),
                            filesize($file),
                            date('Y-m-d H:i:s',filemtime($file))
                        ];
        }
        $_E['template']['attachs'] = $attachs;
        $_E['template']['problem'] = $problem;
        $SkyOJ->SetTitle( '上傳附件: '.$problem->title );

        \Render::render('problem_attach','problem');
    }catch(\Exception $e){
        \Render::errormessage($e->getMessage());
        \Render::render('nonedefined');
    }
}
