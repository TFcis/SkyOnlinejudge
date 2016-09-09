<?php namespace SKYOJ\Problem;
if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

function submitHandle()
{
    global $_G,$_E,$SkyOJ;
    try{
        $pid = $SkyOJ->UriParam(2);

        $problem = new \SKYOJ\Problem($pid);
        $pid = $problem->pid();

        if( $pid===null )
            throw new \Exception('Access denied');

        if( /*problem is not open*/false || $_G['uid'] == 0 )
        {
            throw new \Exception('Access denied');
        }
        $_E['template']['problem'] = $problem;

        //Get Compiler info
         /*
            this is decided by judge plugin, and select which is availible in problem setting
            key : unique id let judge plugin work(named by each judge plugin)
            val : judge info support by judge plugin
        */
        $_E['template']['compiler'] = [
            'cpp11' => 'c++11/gnu c++ compiler 4.8.4 | options: -O2 -std=c++11',
            'cpp14' => 'c++14/gnu c++ compiler 6.1.0 | options: -O2 -std=c++14'
        ];
        \Render::render('problem_submit','problem');
    }catch(\Exception $e){
        \Render::errormessage($e->getMessage(),'Problem closed');
        \Render::render('nonedefined');
    }
}