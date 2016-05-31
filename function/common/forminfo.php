<?php

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

//Base on bootstrap 3
//todo : add confoser
class HTML_Element
{
    const BLOCK_INVALID = "invalid";
    
    ///html_gen_block
    private $block = "";
    
    ///html tags
    private $tags = [];
    
    ///html tags
    private $option = [];
    
    private $id;
    private $name;

    public function __construct(array $info)
    {
        if( !isset($info['block']) ) {
            Log::msg(Level::Error,'HTML_Element build missing block',$info);
            $this->type = HTML_Element::BLOCK_INVALID;
            return;
        }
        
        $block = strtolower($info['block']);
        switch( $info['block'] ) {
            case 'inputs':
                $this->option['title'] = isset($info['title'])?$info['title']:'';
                break;
            case 'hr':
                break;
            default:
                $this->type = HTML_Element::BLOCK_INVALID;
                Log::msg(Level::Error,'No such block case!',$info);
                return;
        }
        $this->block= $block;
        $this->tags = $info['block'];
        $this->name = isset($info['name'])?$info['name']:false;
        $this->id   = isset($info['id'])?$info['id']:false;
    }

    public function name()
    {
        return $this->name;
    }

    public function id()
    {
        return $this->id;
    }

    public function block()
    {
        return $this->block;
    }

    public function tags()
    {
        return $this->tags;
    }

    public function option()
    {
        return $this->option;
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
        $this->style = isset($FromInfo['style']) ? $FromInfo['style'] : self::STYLE_HORZIONTAL;
        if (isset($FromInfo['data'])) {
            foreach ($FromInfo['data'] as $row) {
                Log::msg(Level::Debug, '', $row);
                $this->elements[] = new HTML_Element($row);
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
