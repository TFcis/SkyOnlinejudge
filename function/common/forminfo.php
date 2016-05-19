<?php

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

//Base on bootstrap 3
//todo : add confoser
class FormInput
{
    const BLOCK_INVALID = "invalid";
    
    ///html_gen_type
    private $type = "";
    
    ///html tags
    private $tags = [];

    public function __construct(array $info)
    {
        if( !isset($info['block']) ) {
            Log::msg(Level::Error,'FormInput build missing block',$info);
            $this->type = FormInput::TYPE_INVALID;
            return;
        }
        
        $info = strtolower($info);
        switch( $info['block'] ) {
            case 'inputs':
            case 'hr':
            default:
                $this->type = FormInput::TYPE_INVALID;
                Log::msg(Level::Error,'No such block case!',$info);
                return;
        }
        $this->_name = isset($info['name'])  ? $info['name'] : '';
        $this->_id = isset($info['id'])    ? $info['id']  : $this->_name;
        $this->_type = isset($info['type'])  ? $info['type'] : 'text';
        $this->_title = isset($info['title']) ? $info['title'] : '';
        $this->_option = isset($info['option']) ? $info['option'] : [];
    }

    private static function html5_form_type(string $t)
    {
        switch ($t) {
            case 'hr':
                return 'hr';
            case 'submit':
                return 'submit';
            default:
                return 'text';
        }
    }

    public function name()
    {
        return $this->_name;
    }

    public function id()
    {
        return $this->_id;
    }

    public function type()
    {
        return self::html5_form_type($this->_type);
    }

    public function title()
    {
        return $this->_title;
    }

    public function option()
    {
        return $this->_option;
    }
}

class FormInfo
{
    //As same as class name
    const STYLE_NORMAL = 'form';
    const STYLE_HORZIONTAL = 'form-horizontal';

    private $_style;

    private $_inputs = [];

    public function __construct(array $FromInfo)
    {
        $this->_style = isset($FromInfo['style']) ? $FromInfo['style'] : self::STYLE_HORZIONTAL;
        if (isset($FromInfo['data'])) {
            foreach ($FromInfo['data'] as $row) {
                Log::msg(Level::Debug, '', $row);
                $this->_inputs[] = new FormInput($row);
            }
        }
    }

    public function style()
    {
        return $this->_style;
    }

    public function inputs()
    {
        return $this->_inputs;
    }
}
