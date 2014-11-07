<?php
if(!defined('IN_TEMPLATE'))
{
  exit('Access denied');
}
?>
<div id = "image-bar"></div>
<div class="container">
    <div>
        <div class="page-header">
            <h1><?=htmlspecialchars($_E['template']['title'])?> <small>Statistics
            <?php if(userControl::getpermission($_E['template']['owner'])): ?>
            <a class = "icon-bttn" href='rank.php?mod=cbedit&id=<?=$_E['template']['id'];?>'>
            <span class="pointer glyphicon glyphicon-pencil"  title="編輯"></span>
            </a>
            <?php endif; ?>
                </small>
            </h1>
            <div class='container-fluid'>
                <div class="row">
                    <div class="col-xs-4 col-md-4 text-left">
                        <a href="rank.php?mod=commonboard&id=<?=$_E['template']['leftid']?>" class="btn btn-primary btn-sm active" <?php if(!$_E['template']['leftid'])echo('disabled="disabled"');?>>
                        <span class="glyphicon glyphicon-arrow-left"></span>
                        </a>
                    </div>
                    <div class="col-xs-4 col-md-4 text-center">
                        <a href="rank.php?mod=list&page=<?=$_E['template']['homeid']?>" class="btn btn-primary btn-sm active">
                        <span class="glyphicon glyphicon-home"></span>
                        </a>
                    </div>
                    <div class="col-xs-4 col-md-4 text-right">
                        <a href="rank.php?mod=commonboard&id=<?=$_E['template']['rightid']?>" class="btn btn-primary btn-sm active" <?php if(!$_E['template']['rightid'])echo('disabled="disabled"');?>>
                        <span class="glyphicon glyphicon-arrow-right"></span>
                        </a>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
    
    <div>
        <div>
        <table>
                <thead>
                    <tr>
                        <th style="padding: 4px;width: 160px;"></th>
                        <?php foreach($_E['template']['plist'] as $prob ){?>
                            <th class="text-center" style="padding: 4px;width: 40px;"><?=$prob['show']?></th>
                        <?php }?>
                        <th></th>
                    </tr>
                </thead>
                
                </tbody>
                    <?php foreach($_E['template']['user'] as $uid){?>
                    <tr>
                        <td style = "text-align: right"><a style="color:white;" href=<?="user.php?mod=view&id=$uid"?>><?=$_E['nickname'][$uid]?></a></td>
                        <?php foreach($_E['template']['plist'] as $prob ){?>
                            <td class = "text-center <?=$_E['template']['s'][$uid][$prob['name']]?>">●</td>
                        <?php }?>
                        <td>
                        </td>
                    </tr>
                    <?php }?>
                </tbody>
        </table>
        </div>
    </div>
    
    <div style = "color: #666666; text-align: right; padding-right: 20px">Until next update: FOREVER</div>
    
    <div class="row">
        <h1>DEBUG</h1>
        <p><?=$_E['template']['dbg']?></p>
    </div>
    

</div>

<!--<iframe src="http://www.tfcis.org/ECHO_STATS/#board" width="90%" height="550"></iframe>-->

