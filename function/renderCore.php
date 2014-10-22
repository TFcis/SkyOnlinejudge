<?php
if(!defined('IN_SKYOJSYSTEM'))
{
  exit('Access denied');
}
define('IN_TEMPLATE',1);

class Render
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
        if( !isset($_E['template']) )
        {
            $_E['template'] = array();
        }
        $path = $_E['ROOT']."/template/$namespace/$pagename.php";
        if( file_exists($path) )
        {
            require($path);
            return true;
        }
        return false;
    }
    
    //work in progress
    static function renderStylesheetLink($namespace = 'common', $options = '') {
        global $_E,$_G;
        if(!isset($_E['template'])) { $_E['template'] = array(); }
        $path = $_E['ROOT']."/template/$namespace/theme.css";
        if( file_exists($path))
        {
            echo '<style>';
            echo file_get_contents($path);
            echo '</style>';
            return true;
        }
        return false;
    }

    
    static function render($pagename , $namespace = 'common')
    {
        Render::renderSingleTemplate('common_header');
        Render::renderStylesheetLink($namespace);
        
        Render::renderSingleTemplate('common_nav');
        if(!Render::renderSingleTemplate($pagename,$namespace))
        {
            Render::renderSingleTemplate('nonedefined');
        }
        Render::renderSingleTemplate('common_footer');
    }
    
    static function ShowMessage($cont)
    {
        global $_E;
        $_E['template']['message'] = $cont;
        Render::render('common_message');
    }
}

//$Render = new RenderCore();
?>