<?php
if( !defined('IN_SKYOJSYSTEM') )
{
    exit('Access denied');
}

if( $_G['uid']==0 && $_E['Codepad']['allowguestsubmit']== false )
{
    Render::ShowMessage('Only for registed user now!');
    exit(0);
}
if( $_G['uid']==0 )
    setcookie('uid','0',time()+3600,$_E['SITEDIR']);
userControl::registertoken('CODEPAD_EDIT',3600);
Render::render("code_$mod",'code');