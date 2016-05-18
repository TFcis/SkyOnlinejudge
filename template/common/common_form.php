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
            <?php if( $e->type()=='hr'){ ?>
                <hr>
            <?php continue; } ?>

            <?php if( $_fi->style()===FormInfo::STYLE_HORZIONTAL): ?>
            <div class="form-group">
                <label class="col-xs-3 control-label"><?=$e->title()?></label>
            <?php endif; ?>
                <div class="col-xs-9">
                <?php if( $e->type()=='submit'): ?>
                    <button type="submit" id="<?=$e->id()?>" class="btn btn-success text-right">送出</button>
                    <?php if( isset($e->option()['info']) ): ?>
                        <small><span id="<?=$e->id()?>-show"></span></small>
                    <?php endif; ?>
                <?php else: ?>
                    <input type="<?=$e->type()?>" class="form-control" id="<?=$e->id()?>" name="<?=$e->name()?>">
                <?php endif; ?>
                </div>
            <?php if( $_fi->style()===FormInfo::STYLE_HORZIONTAL): ?>
            </div>
            <?php endif; ?>
        <?php }?>
    </form>
</div>
