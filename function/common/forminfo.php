<?php

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

interface HTML_Element
{
    public function make_html(array $style):string;
    public function __construct(array $setting);
}

abstract class HTML_INPUT_TEXT_HELPER
{
    static private function spilt_setting(string $tag,array &$real,array &$res,$default = null):bool
    {
        if( isset($real[$tag]) ){
            $res[$tag] = $real[$tag];
            unset($res[$tag]);
            return true;
        } else {
            $res[$tag] = $default;
            return false;
        }
    }
    static protected function deal_common_setting(array &$setting):array
    {
        $res = [];
        HTML_INPUT_TEXT_HELPER::spilt_setting('name',$setting,$res);
        HTML_INPUT_TEXT_HELPER::spilt_setting('id'  ,$setting,$res);
        return $res;
    }
}

class HTML_INPUT_TEXT extends HTML_INPUT_TEXT_HELPER implements HTML_Element 
{
    /**
     *  name / id 
     */
    private $setting;
    function __construct(array $setting)
    {
        $this->setting = HTML_INPUT_TEXT::deal_common_setting($setting);
        Log::msg(Level::Debug, '', $this->setting);
    }

    public function make_html(array $style):string
    {
        return "";
    }
}

class HTML_HR implements HTML_Element 
{
    function __construct(array $setting=[]){}
    public function make_html(array $style):string
    {
        return "<hr>";
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
                if( $row instanceof HTML_Element ) {
                    Log::msg(Level::Debug, '', $row);
                    $this->elements[] = $row;
                } else {
                    Log::msg(Level::Debug, 'FormInfo reject a object:', $row);
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
