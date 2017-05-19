<?php

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

define('IN_TEMPLATE', 1);

function getLangDirBase():string
{
    global $_E;

    return $_E['ROOT'].'/language/'.$_E['language'].'/';
}
require_once getLangDirBase().'common_lang.php';

function lang(string $str):string
{
    global $_LG;
    if (array_key_exists($str, $_LG)) {
        return $_LG[$str];
    }

    return $str;
}

class Render
{
    private $head_css;
    private $head_js;

    public function __construct()
    {
        $head_css = [];
        $head_js = [];
    }

    public static function renderSingleTemplate($pagename, $namespace = 'common')
    {
        try {
            global $_E,$_G,$SkyOJ;
            if (!isset($_E['template'])) {
                $_E['template'] = [];
                $tmpl = [];
            } else {
                $tmpl = &$_E['template'];
            }
            $path = $_E['ROOT']."/template/$namespace/$pagename.php";
            $lang = getLangDirBase()."$namespace/$pagename.php";
            if (file_exists($lang)) {
                require_once $lang;
            }
            if (file_exists($path)) {
                require $path;
            } else {
                return false;
            }
        } catch (Exception $e) {
            echo 'Render Exception. Please call admin. Logged';
            Log::msg(Level::Error, 'Render Exception', $pagename, $namespace, $e);
            die(0);
        }

        return true;
    }

    //Genformat : it will use sprintf($url,pid) to gen url!
    //care of any type of injection
    public static function renderForm(\SKYOJ\FormInfo $formInfo, string $id)
    {
        global $_E;
        $_E['template']['_formInfo'] = $formInfo;
        $_E['template']['_id'] = $id;
        self::renderSingleTemplate('common_form');
    }

    //Genformat : it will use sprintf($url,pid) to gen url!
    //care of any type of injection
    public static function renderPagination(\SKYOJ\PageList $p, string $url, int $now, $use_nav = false)
    {
        global $_E;
        $_E['template']['_pagelist'] = $p;
        $_E['template']['_pagelist_now'] = $now;
        $_E['template']['_pagelist_url'] = $url;
        $_E['template']['_use_nav'] = $use_nav;
        self::renderSingleTemplate('common_pagination');
    }
    CONST CODE_SETTING = ['minLines'=>20,'maxLines'=>1E9];

    public static function renderCode(string $code, string $language, string $id = '',array $set = null)
    {
        global $_E;
        if( empty($id) )$id = uniqid("code");
        if( is_null($set) )$set = self::CODE_SETTING;
        $_E['template']['_defaultcode'] = $code;
        $_E['template']['_language'] = $language;
        $_E['template']['_id'] = $id;
        $_E['template']['_set'] = $set;
        self::renderSingleTemplate('common_codepanel');
    }
    //work in progress
    public static function renderStylesheetLink($namespace = 'common', $options = '')
    {
        global $_E,$_G;
        if (!isset($_E['template'])) {
            $_E['template'] = [];
        }
        $path = $_E['ROOT']."/template/$namespace/theme";

        if (file_exists($path.'-'.$options.'.css')) {
            echo '<style>';
            echo file_get_contents($path.'-'.$options.'.css');
            echo '</style>';
        } elseif (file_exists($path.'.css')) {
            echo '<style>';
            echo file_get_contents($path.'.css');
            echo '</style>';
        }

        if (file_exists('css/index-'.$options.'.css')) {
            echo '<link rel="stylesheet" type="text/css" href="css/index-'.$options.'.css">';
        }

        return true;
    }

    public static function setbodyclass($val)
    {
        global $_E;
        if (!isset($_E['template']['_body_class'])) {
            $_E['template']['_body_class'] = [];
        }
        $_E['template']['_body_class'][] = $val;
    }

    public static function render($pagename, $namespace = 'common')
    {
        self::renderSingleTemplate('common_header');
        self::renderStylesheetLink($namespace);

        self::renderSingleTemplate('common_nav');
        if (!self::renderSingleTemplate($pagename, $namespace)) {
            self::renderSingleTemplate('nonedefined');
        }
        self::renderSingleTemplate('common_footer');
    }

    public static function ShowMessage($cont)
    {
        global $_E;
        $_E['template']['message'] = $cont;
        self::render('common_message');
    }

    public static function errormessage($text, $namespace = '')
    {
        global $_E;
        if (!is_string($text)) {
            ob_start();
            var_dump($text);
            $text = ob_get_clean();
        }
        $_E['template']['error'][] = ['msg' => nl2br(htmlspecialchars($text)), 'namespace' => $namespace];
    }

    public static function static_html($pagename, $namespace = 'common')
    {
        ob_start();
        self::renderSingleTemplate($pagename, $namespace);
        $res = ob_get_clean();

        return $res;
    }

    public static function htmlcachefile($name)
    {
        global $_E;

        return $_E['ROOT']."/data/cachehtml/$name.html";
    }

    public static function save_html_cache($name, $res)
    {
        $handle = fopen(self::htmlcachefile($name), 'w');
        if (!$handle) {
            return false;
        }
        fwrite($handle, $res);
        fclose($handle);

        return true;
    }

    public static function html_cache_exists($name)
    {
        return file_exists(self::htmlcachefile($name));
    }

    public static function rendercachehtml($name)
    {
        $fullname = self::htmlcachefile($name);
        if (!file_exists($fullname)) {
            return false;
        }
        require $fullname;
    }
}
$_E['template']['error'] = [];
