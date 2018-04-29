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
        if( empty($pid) || !is_numeric($pid) )
        {
            header("Location:".$SkyOJ->uri('problem','list'));
            exit(0);
        }
        $problem = new \SkyOJ\Problem\Container();
        if( !$problem->load($pid) )
            throw new \Exception('NO SUCH PROBLEM');

        if( isset($filename) && strlen($filename)>0 )
        {
            viewachieveHandle($problem,$filename);
            exit(0);
        }

        if( !$problem->readable($SkyOJ->User) )
        {
            throw new \Exception('權限不足，不開放此題目');
        }

        

        $_E['template']['problem'] = $problem;
        $SkyOJ->SetTitle( $problem->title );
        \Render::render('problem_view','problem');
    }catch(\Exception $e){
        \Render::errormessage($e->getMessage());
        \Render::render('nonedefined');
    }
}

function viewachieveHandle(\SkyOJ\Problem\Container $problem, string $filename)
{
    global $SkyOJ;
    try{
        if( !$problem->readable($SkyOJ->User) )
        {
            throw new \Exception('403');
        }

        $filepath = $problem->genAttachLocalPath($filename);
        if( !is_file($filepath) )
        {
            throw new \Exception('403');
        }

		header('Content-Length: '.filesize($filepath));
		if( strtolower( pathinfo($filepath, PATHINFO_EXTENSION) ) == 'pdf' )
			header('Content-Type: application/pdf');
		else
			header("Content-Type: ".filetype($filepath)); //TODO:他媽的filetype
		
        readfile($filepath);
        exit(0);
    }catch(\Exception $e){
        http_response_code(403);
        exit(0);
    }
}