<?php namespace SKYOJ\Rank;
if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

function newHandle()
{
    global $_G;
    if( !\userControl::isAdmin($_G['uid']) )
    {
        \Render::errormessage('Only User who admin allowed can use this!');
        \Render::render('nonedefined');
        exit(0);
    }
    \Render::render('rank_new','rank');
}