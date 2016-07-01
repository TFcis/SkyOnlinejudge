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
        <?php foreach ($_fi->elements() as $e) { 
            echo $e->make_html([]);
        }/*end of foreach*/?>
    </form>
</div>
