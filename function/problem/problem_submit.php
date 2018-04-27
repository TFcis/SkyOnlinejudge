<?php namespace SKYOJ\Problem;

use \SkyOJ\Challenge\LanguageCode;

function submitHandle()
{
    global $_G,$_E,$SkyOJ;
    try{
        $pid = $SkyOJ->UriParam(2);

        $problem = new \SkyOJ\Problem\Container();
        if( !$problem->load($pid) )
        {
            throw new \Exception('Access denied');
        }

        if( !$problem->isAllowSubmit($SkyOJ->User) )
        {
            if( !$SkyOJ->User->isLogin() )
                throw new \Exception('請登入後再操作');
            throw new \Exception('沒有權限');
        }

        //TODO Fix me
        /*if( \Plugin::loadClassFileInstalled('judge',$problem->judge)===false )
            throw new \Exception('Judge Not Ready!');
        $judge = new $problem->judge;*/
        //Get Compiler info
         /*
            this is decided by judge plugin, and select which is availible in problem setting
            key : unique id let judge plugin work(named by each judge plugin)
            val : judge info support by judge plugin
        */
        /* compiler should be an array include such tuple
            ( index,LANGCODE,Descrption )
            ex [0, LanguageCode::CPP, "g++ -std=c++11"]
            index : let judge know which one user select
            LANGCODE : defined in \SKYOJ\Chellenge\LanguageCode
            Descrption : maybe LANGCODE with flag information
        */
        $_info = [
            [0, LanguageCode::CPP, "g++ -std=c++11"],
            [1, LanguageCode::CPP, "g++ -std=c++14"]
        ];
        $_E['template']['problem'] = $problem;
        $_E['template']['compiler'] = $_info;
        $_E['template']['jscallback'] = 'location.href="'.$SkyOJ->uri('chal','result').'/"+res.data;';
        \Render::render('problem_submit','problem');
    }catch(\Exception $e){
        \Render::errormessage($e->getMessage(),'Problem closed');
        \Render::render('nonedefined');
    }
}