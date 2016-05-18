<?php
if( !defined('IN_SKYOJSYSTEM') )
{
    exit('Access denied');
}

#Base on bootstrap 3
#todo : add confoser
class FormInput
{
    private $_name;
    private $_id;
    private $_type;
    private $_title;
    private $_option;
    function __construct(array $info)
    {
        $this->_name  = isset($info['name'])  ?$info['name']:'';
        $this->_id    = isset($info['id'])    ?$info['id']  :$this->_name;
        $this->_type  = isset($info['type'])  ?$info['type']:'text';
        $this->_title = isset($info['title']) ?$info['title']:'';
        $this->_option= isset($info['option'])?$info['option']:[];
    }

    static private function html5_form_type(string $t)
    {
        switch($t)
        {
            case 'hr':
                return 'hr';
            case 'submit':
                return 'submit';
            default:
                return 'text';
        }
    }

    public function name(){return $this->_name;}
    public function id()  {return $this->_id;  }
    public function type(){return FormInput::html5_form_type($this->_type);}
    public function title() {return $this->_title;}
    public function option() {return $this->_option;}
}

class FormInfo
{
    #As same as class name
    const STYLE_NORMAL     = "form";
    const STYLE_HORZIONTAL = "form-horizontal";

    private $_style;

    private $_inputs = [];

    function __construct(array $FromInfo)
    {
        $this->_style = isset($FromInfo['style'])?$FromInfo['style']:FormInfo::STYLE_HORZIONTAL;
        if( isset($FromInfo['data']) )
        {
            foreach( $FromInfo['data'] as $row )
            {
                Log::msg(Level::Debug,"",$row);
                $this->_inputs[] = new FormInput($row);
            }
        }
    }

    public function style() {return $this->_style;  }
    public function inputs(){return $this->_inputs; }
};
