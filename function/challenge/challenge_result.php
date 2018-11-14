<?php namespace SKYOJ\Challenge;

function resultHandle()
{
    global $SkyOJ,$_E,$_G;
    $cid = $SkyOJ->UriParam(2);
    try{
        if( !preg_match('/^[1-9][0-9]*$/',$cid) )
        {
            throw new \Exception('cid error');
        }

        $chal = new \SkyOJ\Challenge\Container();
        if( !$chal->load($cid) )
        {
            throw new \Exception('cid error');
        }

        if( !$chal->readable($SkyOJ->User) )
        {
            throw new \Exception('不具有檢視權限，無法觀看');
        }
        
        $_E['template']['allowCodeview'] = $chal->codereadable($SkyOJ->User);
        $_E['template']['chal'] = $chal;

        \Render::render('challenge_result', 'challenge');
    }catch(\Exception $e){
        \Render::errormessage($e->getMessage());
        \Render::render('nonedefined');
    }
}


