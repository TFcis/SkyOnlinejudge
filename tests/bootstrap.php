<?php
define('IN_SKYOJSYSTEM', 1);
require __DIR__.'/../vendor/autoload.php';
//load real file in ./
function g__loadthis(string $file):void
{
    static $my;
    $filename = basename($file);
    if( !isset($my) )
    {
        $my = strlen(realpath(__DiR__));
    }
    $path = dirname(substr($file,$my));
    $filename = substr($filename,0,-8).'.php';
    $open = realpath('.').$path.'/'.$filename;
    require_once($open);
}
