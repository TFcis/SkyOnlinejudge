<?php namespace SKYOJ;

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

interface HTML_Element
{
    //TODO: May be we need a class to store style
    //Call Back Order
    //$make_html_callback > $callback > default 
    public function make_html($setting = null,callable $callback = null):string;
    public function __construct(array $setting,callable $make_html_callback = null);
}

abstract class HTML_INPUT_HELPER
{
    protected $setting;
    protected $used_callback = null;
    static protected function check_setting(string $tag,array &$real,$default = null):bool
    {
        if( !isset($real[$tag]) ){
            $res[$tag] = $default;
            return false;
        }
        return true;
    }

    function __construct(array $setting,callable $make_html_callback = null)
    {
        if( $make_html_callback !== null )
            $this->used_callback = $make_html_callback;
        $this->setting = HTML_INPUT_TEXT::deal_common_setting($setting);
        
        //Log::msg(Level::Debug, '', $this->setting);
    }

    static protected function deal_common_setting(array $setting):array
    {
        HTML_INPUT_HELPER::check_setting('name',$setting);
        HTML_INPUT_HELPER::check_setting('id'  ,$setting);
        if( !isset($setting['option']) || !is_array($setting['option']) )
            $setting['option'] = [];
        if(!isset($setting['option']['help_text']))
            $setting['option']['help_text'] = '';
        return $setting;
    }

    public function make_html($info = null,callable $callback = null):string
    {
        if( $this->used_callback !== null ){
            return $callback($this->setting,$info);
        }
        if( $callback !== null ){
            return $callback($this->setting,$info);
        }
        return "<!--INPUT NOT DEFINED-->";
    }
}

class HTML_INPUT_TEXT extends HTML_INPUT_HELPER implements HTML_Element 
{
    function __construct(array $setting,callable $make_html_callback = null)
    {
        parent::__construct($setting,$make_html_callback);
        $this->setting['type'] = 'text';
    }
}

class HTML_INPUT_PASSWORD extends HTML_INPUT_HELPER implements HTML_Element 
{
    function __construct(array $setting,callable $make_html_callback = null)
    {
        parent::__construct($setting,$make_html_callback);
        $this->setting['type'] = 'password';
    }
}

class HTML_INPUT_HIDDEN extends HTML_INPUT_HELPER implements HTML_Element 
{
    function __construct(array $setting,callable $make_html_callback = null)
    {
        parent::__construct($setting,$make_html_callback);
        $this->setting['type'] = 'hidden';
    }
}

class HTML_INPUT_BUTTOM extends HTML_INPUT_HELPER implements HTML_Element 
{
    function __construct(array $setting,callable $make_html_callback = null)
    {
        HTML_INPUT_HELPER::check_setting('type',$setting,'submit');
        HTML_INPUT_HELPER::check_setting('title',$setting);
        parent::__construct($setting,$make_html_callback);
    }
}

class HTML_INPUT_SELECT extends HTML_INPUT_HELPER implements HTML_Element 
{
    function __construct(array $setting,callable $make_html_callback = null)
    {
        HTML_INPUT_HELPER::check_setting('key-pair',$setting,[]);
        parent::__construct($setting,$make_html_callback);
    }
}

class HTML_INPUT_DIV extends HTML_INPUT_HELPER implements HTML_Element 
{
    function __construct(array $setting,callable $make_html_callback = null)
    {
        HTML_INPUT_HELPER::check_setting('code',$setting,'');
        parent::__construct($setting,$make_html_callback);
    }
}

class HTML_INPUT_CODEPAD extends HTML_INPUT_HELPER implements HTML_Element 
{
    private $c;
    function __construct(array $setting,callable $make_html_callback = null)
    {
        parent::__construct($setting,$make_html_callback);
        HTML_INPUT_HELPER::check_setting('code',$setting['option'],'code');
        HTML_INPUT_HELPER::check_setting('language',$setting['option'],'c_cpp');
        HTML_INPUT_HELPER::check_setting('setting',$setting['option'],\Render::CODE_SETTING);
        $this->c = new HTML_CODEPAD(['code'=>$setting['option']['code']
                                    ,'language'=>$setting['option']['language']
                                    ,'id'=>$setting['option']['id']??null
                                    ,'setting'=>$setting['option']['setting']]);
    }
    function make_code(){
        $this->c->make_html();
    }
}

class HTML_HR implements HTML_Element 
{
    function __construct(array $setting = [],callable $make_html_callback = null){}
    public function make_html($setting = null,callable $callback = null):string
    {
        return "<hr>";
    }
}

class HTML_ROW implements HTML_Element
{
    private $text = '';
    function __construct(array $setting = [],callable $make_html_callback = null)
    {
        $this->text = $setting['html'] ?? '';
    }
    public function make_html($setting = null,callable $callback = null):string
    {
        return $this->text;
    }
}

class HTML_CODEPAD implements HTML_Element 
{
    private $code;
    private $language;
    private $id;
    private $setting;
    function __construct(array $setting,callable $make_html_callback = null)
    {
        $this->code = $setting['code'];
        $this->language = $setting['language'];
        $this->id = $setting['id']??null;
        $this->setting = $setting['setting']??null;
    }

    public function make_html($setting = null,callable $callback = null):string
    {
        \Render::renderCode($this->code,$this->language,$this->id,$this->setting);
        return '';
    }
}
class FormInfo
{
    //As same as class name
    const STYLE_NORMAL = 'form';
    const STYLE_HORZIONTAL = 'form-horizontal';

    private $style;

    private $elements = [];

    public function __construct(array $FromInfo)
    {
        $this->style = $FromInfo['style'] ?? self::STYLE_HORZIONTAL;
        if (isset($FromInfo['data'])) {
            foreach ($FromInfo['data'] as $row) {
                if( $row instanceof \SKYOJ\HTML_Element ) {
                    $this->elements[] = $row;
                } else {
                    \Log::msg(\Level::Debug, 'FormInfo reject a object:', $row);
                }
            }
        }
    }

    public function style()
    {
        return $this->style;
    }

    public function elements()
    {
        return $this->elements;
    }
}
