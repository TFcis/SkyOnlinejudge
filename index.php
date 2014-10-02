<?php
$_E = array();
$_E['ROOT'] = __DIR__;
$_E['style']='';
function _renderSingleTemplate($pagename)
{
  global $_E;
  if( $_E['style'] && file_exists( "$_E[ROOT]/style/$_E[style]/template/$pagename.php" ) )
  {
    @require("$_E[ROOT]/style/$_E[style]/template/$pagename.php");
    return true;
  }
  elseif(file_exists("$_E[ROOT]/template/$pagename.php"))
  {
    @require("$_E[ROOT]/template/$pagename.php");
    return true;
  }
  return false;
}

function render($pagename)
{
  define('IN_TEMPLATE',1);
  _renderSingleTemplate('common/common_header');
  echo '</head>';
  echo '<body>';
  _renderSingleTemplate($pagename);
  _renderSingleTemplate('common/common_footer');
}


//require_once('template/common/common_header.php');
//it may be need a template

render('index');
?>
