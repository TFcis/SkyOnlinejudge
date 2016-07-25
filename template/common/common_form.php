<?php
if (!defined('IN_TEMPLATE')) {
    exit('Access denied');
}
?>
<?php
    $_fi = $tmpl['_formInfo'];
    if( !function_exists('BT3_HORZIONTAL') ){
        function BT3_HORZIONTAL($setting,$info){
            $rev = "";
            if( $info['style'] == FormInfo::STYLE_HORZIONTAL ){
                $rev.=<<<TAG
<div class="form-group">
    <label for="{$setting['name']}" class="col-md-3 control-label white-text">{$setting['option']['help_text']}</label>
    <div class="col-md-9">
TAG;
            }
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
            
            if( $info['style'] == FormInfo::STYLE_HORZIONTAL ){
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
            if( $info['style'] == FormInfo::STYLE_HORZIONTAL ){
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
            
            if( $info['style'] == FormInfo::STYLE_HORZIONTAL ){
                $rev.=<<<'TAG'
    </div>
</div>
TAG;
            }
            return $rev;
        }
    }
?>
<div clas="container-fluid">
    <form class="<?=$_fi->style()?>" role="form" id="<?=$tmpl['_id']?>">
        <?php foreach ($_fi->elements() as $e) {
            if( $e instanceof HTML_INPUT_HELPER ) {
                switch(true)
                {
                    case $e instanceof HTML_INPUT_TEXT:
                    case $e instanceof HTML_INPUT_PASSWORD:
                        echo $e->make_html(['style'=>$_fi->style()],'BT3_HORZIONTAL');
                        break;

                    case $e instanceof HTML_INPUT_BUTTOM:
                        echo $e->make_html(['style'=>$_fi->style()],'BT3_HORZIONTAL_BTN');
                        break;

                    case $e instanceof HTML_INPUT_HIDDEN:
                        echo $e->make_html(['style'=>''],'BT3_HORZIONTAL');
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
