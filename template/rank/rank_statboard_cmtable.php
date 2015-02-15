<?php
if(!defined('IN_TEMPLATE'))
{
  exit('Access denied');
}
?>
<table id="cbtable">
    <thead>
        <tr>
            <th style="padding: 4px;width: 40px;left:0px;position: absolute;"></th>
            <th style="padding: 4px;width: 120px;left:40px;position: absolute;"><span id="infobox"></span></th>
            <th class="text-center" style="padding: 4px;width: 50px;"><a onclick="change_rate()" id="svchange" title="Change rate/source">score<span></th>
            <?php foreach($tmpl['plist'] as $prob ){?>
                <th class="text-center" style="padding: 4px;width: 40px;">
                    <div class="problemname"><?=$prob['show']?></div>
                </th>
            <?php }?>
            <th></th>
        </tr>
    </thead>
    <tbody sytle="white-space: nowrap;">
        <?php foreach($tmpl['user'] as $uid){?>
        <tr>
            <td style="left:0px;position: absolute;">
                <?php if( $tmpl['owner'] !=-1 && ( userControl::getpermission($tmpl['owner']) || $uid == $_G['uid'] )): ?>
                <a class = "icon-bttn" onclick="build_cb_data('<?=$uid?>')">
                    <span class="pointer glyphicon glyphicon-refresh"  title="重新擷取"></span>
                </a>
                <?php endif;?>
            </td>
            <td class="text-right" style="left:40px;position:absolute;">
                <div class="nickname">
                    <a style="color:white;" href=<?=$_E['SITEROOT']."user.php/view/$uid"?>><?=$_E['nickname'][$uid]?></a>
                </div>
            </td>
    		<?php $AC_count = $tmpl['userdetail'][$uid]['statistics']['90']; ?>
            <td class="text-right">
            <span class="score" onclick="change_rate()"><?=$AC_count?>AC</span>
            <span class="ac_rate" style="display:none" onclick="change_rate()"><?=round($AC_count/count($tmpl['plist'])*100.0)?>%</span>
    
            </td>
<?php foreach($tmpl['plist'] as $prob ){ 
    $vid  = $tmpl['s'][$uid][$prob['name']]["vid"]; 
    $chal = $tmpl['s'][$uid][$prob['name']]["challink"];
    
    ?><td class = "text-center <?=$vid?>"><?php
    if( $chal == '' )
    {
        ?>●<?php
    }
    else
    {
        ?><span onclick = "javascript:window.open('<?=$chal?>')" target="_blank" style="cursor: pointer;">●</span><?php
    }
    
    ?></td><?php
}?>
            <td>
            </td>
        </tr>
        <?php }?>
    </tbody>
</table>
<div style = "color: #666666; text-align: right; padding-right: 20px">Lastest update: <?=$tmpl['buildtime'] ?></div>