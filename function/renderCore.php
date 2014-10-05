<?php
if(!defined('IN_SKYOJSYSTEM'))
{
  exit('Access denied');
}
define('IN_TEMPLATE',1);

class RenderCore
{
    private $head_css;
    private $head_js;
    
    function __construct()
    {
        $head_css = array();
        $head_js = array();
    }
    
    static function renderSingleTemplate( $pagename , $namespace = 'common' )
    {
        global $_E,$_G;
        $path = $_E['ROOT']."/template/$namespace/$pagename.php";
        if( file_exists($path) )
        {
            require($path);
            return true;
        }
        return false;
    }
    
    function render($pagename , $namespace = 'common')
    {
        $this->renderSingleTemplate('common_header');
        $this->renderSingleTemplate('common_nav');
        if(!$this->renderSingleTemplate($pagename,$namespace))
        {
            $this->renderSingleTemplate('nonedefined');
        }
        $this->renderSingleTemplate('common_footer');
    }
}

$Render = new RenderCore();
?>