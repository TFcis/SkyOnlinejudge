<?php namespace SKYOJ\Problem;

use \SkyOJ\Challenge\LanguageCode;
use \SkyOJ\Judge\Judge;

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

        $judge = Judge::getJudgeReference($problem->judge_profile);
        //Get Compiler info

        /* compiler should be an array include such tuple
            ( index,LANGCODE,Descrption )
            ex [0, LanguageCode::CPP, "g++ -std=c++11"]
            index : let judge know which one user select
            LANGCODE : defined in \SKYOJ\Chellenge\LanguageCode
            Descrption : maybe LANGCODE with flag information
        */
        $info = $judge->getCompilerInfo();

        $_E['template']['problem'] = $problem;
        $_E['template']['compiler'] = $info;
        $_E['template']['jscallback'] = 'location.href="'.$SkyOJ->uri('chal','result').'/"+res.data;';
        \Render::render('problem_submit','problem');
    }catch(\Exception $e){
        \Render::errormessage($e->getMessage(),'Problem closed');
        \Render::render('nonedefined');
    }
}