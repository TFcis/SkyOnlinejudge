<?php
if (!defined('IN_TEMPLATE')) {
    exit('Access denied');
}
?>
<?php
    $_fi = $tmpl['_formInfo'];
?>
<div clas="container-fluid">
    <form class="<?=$_fi->style()?>" role="form" id="<?=$tmpl['_id']?>">
        <?php foreach ($_fi->elements() as $e) { ?>
            <?php switch( $e->block() ):// https://bugs.php.net/bug.php?id=52775
                case'hr': ?>
                    <hr>
                <?php break; case 'inputs': ?>
                    
                    <?php if ($_fi->style() === FormInfo::STYLE_HORZIONTAL): ?>
                        <div class="form-group">
                            <label class="col-xs-3 control-label"><?="title"?></label>
                    <?php endif; ?>
                        
                    <?php if ($_fi->style() === FormInfo::STYLE_HORZIONTAL): ?>
                        </div>
                    <?php endif;?>
                    
                <?php break; default: ?>
            
            
        <?php endswitch;}/*end of switch foreach*/?>
    </form>
</div>
