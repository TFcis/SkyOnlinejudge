<?php namespace SKYOJ\Problem;

use \SkyOJ\Problem\Container;

function newHandle()
{
    global $SkyOJ;
    if( !Container::creatable($SkyOJ->User) )
    {
        \Render::errormessage('Only User who admin allowed can use this!');
        \Render::render('nonedefined');
        exit(0);
    }
    \Render::render('problem_new','problem');
}