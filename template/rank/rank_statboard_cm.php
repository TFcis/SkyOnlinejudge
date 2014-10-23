<?php
if(!defined('IN_TEMPLATE'))
{
  exit('Access denied');
}
?>
<!-- show all load plugins -->
<div id = "image-bar"></div>
<div class="container">

    <div>
        <div class="page-header">
            <h1><?=htmlspecialchars($_E['template']['title'])?> <small>Statistics
            <?php if(userControl::getpermission($_E['template']['owner'])): ?>
            <span class="pointer glyphicon glyphicon-pencil" onclick="location.href='rank.php?mod=cbedit&id=<?=$_E['template']['id'];?>'" title="編輯"></span>
            <?php endif; ?>
                </small>
            </h1>
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
                    <?php foreach($_E['template']['user'] as $uid => $name){?>
                    <tr>
                        <td style = "text-align: right"><a style="color:white;" href=<?="user.php?mod=view&id=$uid"?>><?=$name['nickname']?></a></td>
                        <?php foreach($_E['template']['plist'] as $prob ){?>
                            <td class = "<?=$_E['template']['s'][$uid][$prob['name']]?>">●</td>
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

