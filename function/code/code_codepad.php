<?php
if( !defined('IN_SKYOJSYSTEM') )
{
    exit('Access denied');
}

if( $_G['uid']==0 )
{
    Render::ShowMessage('Only for registed user now!');
    exit(0);
}
userControl::registertoken('CODEPAD_EDIT',3600);
Render::render("code_$mod",'code');