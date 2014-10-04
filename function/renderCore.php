<?php
if(!defined('IN_SKYOJSYSTEM'))
{
  exit('Access denied');
}
define('IN_TEMPLATE',1);

function _renderSingleTemplate($pagename)
{
    global $_E;
    $path = $_E['ROOT']."/template/$pagename.php";
    if( file_exists($path) )
    {
        require($path);
        return true;
    }
    return false;
}

function render($pagename)
{
    _renderSingleTemplate('common/common_header');
    _renderSingleTemplate('common/common_nav');
    _renderSingleTemplate($pagename);
    _renderSingleTemplate('common/common_footer');
}
?>