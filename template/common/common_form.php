<?php
if (!defined('IN_TEMPLATE')) {
    exit('Access denied');
}
?>
<?php
    $_fi = $tmpl['_formInfo'];
    if( !function_exists('BT3_HORZIONTAL_CB') ){
        function BT3_HORZIONTAL_CB($setting,$info){
            echo <<<TAG
<div class="form-group">
    <label class="col-md-3 control-label">{$setting['option']['help_text']}</label>
    <div class="col-md-9">
TAG;
            $info['this']->make_code();
            echo <<<TAG
    </div>
</div>
TAG;
            return '';
        }
    }
    if( !function_exists('BT3_HORZIONTAL') ){
        function BT3_HORZIONTAL($setting,$info){
            $rev = "";
            if( $info['style'] == \SKYOJ\FormInfo::STYLE_HORZIONTAL )
            {
                $for = isset($setting['name'])?"for='{$setting['name']}'":"";
                $rev.=<<<TAG
<div class="form-group">
    <label {$for} class="col-md-3 control-label">{$setting['option']['help_text']}</label>
    <div class="col-md-9">
TAG;
            }

            if( $info['this'] instanceof \SKYOJ\HTML_INPUT_SELECT )
                $rev.='<select class="form-control"';
            elseif( $info['this'] instanceof \SKYOJ\HTML_INPUT_DIV )
                $rev.='<div ';
            else
                $rev.='<input type=\''.htmlentities($setting['type']).'\' class="form-control textinput"';

            foreach( $setting as $tag => $key){
                if( !is_string($tag) || !is_scalar($key) )continue;
                if($tag == 'type')continue;
                $rev .= ' '.htmlentities($tag);
                if( !empty($key) ){
                    $rev .= '="'.htmlentities($key).'"';
                }
            }
            $rev.='>';

            if( $info['this'] instanceof \SKYOJ\HTML_INPUT_SELECT ){
                $default = $setting['default']??null;
                foreach($setting['key-pair'] as $index => $key){
                    if( $key === $default )
                        $rev.="<option value='{$key}' selected>{$index}</option>";
                    else
                        $rev.="<option value='{$key}'>{$index}</option>";
                }
                $rev.="</select>";
            }else if( $info['this'] instanceof \SKYOJ\HTML_INPUT_DIV ){
                if( $setting['option']['row']??false )
                     $rev.= $setting['option']['html']??'';
                else
                    $rev.= htmlentities($setting['option']['html'])??'';
                $rev.="</div>";
            }
            
            if( $info['style'] == \SKYOJ\FormInfo::STYLE_HORZIONTAL ){
                $rev.=<<<'TAG'
    </div>
</div>
TAG;
            }
            return $rev;
        }
    }

    if( !function_exists('BT3_HORZIONTAL_BTN') ){
        function BT3_HORZIONTAL_BTN($setting,$info){
            $rev = "";
            if( $info['style'] == \SKYOJ\FormInfo::STYLE_HORZIONTAL ){
                $rev.=<<<TAG
<div class="form-group">
    <div class="col-md-12 text-right">
TAG;
            }
            if( $setting['option']['help_text']!=false ){
                $rev.="<small><span id='{$setting['name']}-show'></span></small>";
            }
            $rev.='<button class="btn btn-success"';

            foreach( $setting as $tag => $key){
                if( !is_string($tag) || !is_string($key) )continue;
                if($tag == 'title')continue;
                $rev .= ' '.htmlentities($tag);
                if( !empty($key) ){
                    $rev .= '="'.htmlentities($key).'"';
                }
            }
            $rev.='>'.htmlentities($setting['title']).'</button>';
            
            if( $info['style'] == \SKYOJ\FormInfo::STYLE_HORZIONTAL ){
                $rev.=<<<'TAG'
    </div>
</div>
TAG;
            }
            return $rev;
        }
    }
?>
<div class="container-fluid">
    <form class="<?=$_fi->style()?>" role="form" id="<?=$tmpl['_id']?>">
        <?php foreach ($_fi->elements() as $e) {
            if( $e instanceof \SKYOJ\HTML_INPUT_HELPER ) {
                switch(true)
                {
                    case $e instanceof \SKYOJ\HTML_INPUT_TEXT:
                    case $e instanceof \SKYOJ\HTML_INPUT_PASSWORD:
                    case $e instanceof \SKYOJ\HTML_INPUT_SELECT:
                    case $e instanceof \SKYOJ\HTML_INPUT_DIV:
                    
                        echo $e->make_html(['style'=>$_fi->style(),'this'=>$e],'BT3_HORZIONTAL');
                        break;
                    case $e instanceof \SKYOJ\HTML_INPUT_CODEPAD:
                        echo $e->make_html(['style'=>$_fi->style(),'this'=>$e],'BT3_HORZIONTAL_CB');
                        break;
                    case $e instanceof \SKYOJ\HTML_INPUT_BUTTOM:
                        echo $e->make_html(['style'=>$_fi->style(),'this'=>$e],'BT3_HORZIONTAL_BTN');
                        break;
                    case $e instanceof \SKYOJ\HTML_INPUT_HIDDEN:
                        echo $e->make_html(['style'=>'','this'=>$e],'BT3_HORZIONTAL');
                        break;

                    default:
                        echo $e->make_html();
                }
            } else {
                echo $e->make_html();
            }
        }/*end of foreach*/?>
    </form>
</div>
