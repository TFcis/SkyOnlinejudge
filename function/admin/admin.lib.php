<?php namespace SKYOJ\Admin;

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

function CheckAdminToken(string $token,string $namespace = 'ADMIN_CSRF')
{
    if( \userControl::CheckToken($namespace,$token) )
    {
        return true;
    }
    return false;
}