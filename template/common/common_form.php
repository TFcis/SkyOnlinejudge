<?php
if(!defined('IN_TEMPLATE'))
{
  exit('Access denied');
}
?>
<?php
    $_fi = $tmpl['_formInfo'];
?>
<div clas="container-fluid">
    <form class="<?=$_fi->style()?>" role="form" id="<?=$tmpl['_id']?>">
        <?php foreach($_fi->inputs() as $e){?>
            <?php if( $_fi->style()===FormInfo::STYLE_HORZIONTAL): ?>
            <div class="form-group">
                <label class="col-xs-3 control-label"><?=$e->title()?></label>
            <?php endif; ?>
                <div class="col-xs-9">
                    <input type="<?=$e->type()?>" class="form-control" id="<?=$e->id()?>" name="<?=$e->name()?>">
                </div>
            <?php if( $_fi->style()===FormInfo::STYLE_HORZIONTAL): ?>
            </div>
            <?php endif; ?>
        <?php }?>
    </form>
</div>
