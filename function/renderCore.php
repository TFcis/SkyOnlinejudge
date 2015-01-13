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
            $tmpl = array();
        }
        else
        {
            $tmpl = &$_E['template'];
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
        $path = $_E['ROOT']."/template/$namespace/theme";
        
        if( file_exists($path.'-'.$options.'.css'))
        {
            echo '<style>';
            echo file_get_contents($path.'-'.$options.'.css');
            echo '</style>';
        } else if (file_exists($path.'.css')){
            echo '<style>';
            echo file_get_contents($path.'.css');
            echo '</style>';
        }
        
        if (file_exists('css/index-'.$options.'.css')){
            echo '<link rel="stylesheet" type="text/css" href="css/index-'.$options.'.css">';
        } else if (file_exists('css/index.css')){
            echo '<link rel="stylesheet" type="text/css" href="css/index.css">';    
        }
        return false;
    }
    
    static function setbodyclass($val)
    {
        global $_E;
        if(!isset($_E['template']['_body_class']))
            $_E['template']['_body_class'] = array();
        $_E['template']['_body_class'][]=$val;
    }
    
    static function render($pagename , $namespace = 'common')
    {
        Render::renderSingleTemplate('common_header');
        //Render::renderStylesheetLink($namespace, 'light');
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
    
    static function errormessage($text,$namespace = '')
    {
        global $_E;
        if( !is_string($text) )
        {
            ob_start();
            var_dump($text);
            $text = ob_get_clean();
        }
        $_E['template']['error'][]=array('msg'=>nl2br(htmlspecialchars($text)),'namespace'=>$namespace);
    }
}
$_E['template']['error'] = array();

?>