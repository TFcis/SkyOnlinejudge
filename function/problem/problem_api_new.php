<?php namespace SKYOJ\Problem;

use \SkyOJ\Problem\Container;
use \SkyOJ\Problem\ContainerModifyException;

function problem_api_newHandle()
{
    global $_G,$_E;

    try
    {
        if( !\userControl::isAdmin($_G['uid']) )
            \SKYOJ\throwjson('error', 'Access denied');

        $pid = Container::create($_G['uid']);

        if( !\SKYOJ\Problem::CreateDefault($pid) )
            \SKYOJ\throwjson('error', 'Server DATA Creat Error!');

        \SKYOJ\throwjson('SUCC', $pid);
    }
    catch(ContainerModifyException $e)
    {
        \SKYOJ\throwjson('error',$e->getMessage());
    }
}
    