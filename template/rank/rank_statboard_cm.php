<?php
if(!defined('IN_TEMPLATE'))
{
  exit('Access denied');
}
?>
<!-- show all load plugins -->
<br>
<div class="container">
    <div class="row">
        <div class="page-header">
            <h1><?=$_E['template']['title']?> <small>Statistics</small></h1>
            <p>剩餘時間:FOREVER</p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <table class="text-center">
                <thead>
                    <tr>
                        <th style="padding: 4px;width: 40px;"></th>
                        <?php foreach($_E['template']['plist'] as $prob ){?>
                            <th style="padding: 4px;width: 40px;"><?=$prob['name']?></th>
                        <?php }?>
                    </tr>
                </thead>
                </tbody>
                    <?php foreach($_E['template']['id'] as $uid => $name){?>
                    <tr>
                        <td><?=$name['nickname']?></td>
                        <?php foreach($_E['template']['plist'] as $prob ){?>
                            <td class="<?=$_E['template']['s'][$uid][$prob['name']]?>">●</td>
                        <?php }?>
                    </tr>
                    <?php }?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <h1>DEBUG</h1>
        <p><?=$_E['template']['dbg']?></p>
    </div>
</div>

<!--<iframe src="http://www.tfcis.org/ECHO_STATS/#board" width="90%" height="550"></iframe>-->

