<?php
if(!defined('IN_TEMPLATE'))
{
  exit('Access denied');
}
?>
<!-- show all load plugins -->
<br>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <table class="table  table-bordered">
                <thead>
                    <tr>
                        <th>插件名稱</th>
                        <th>作者</th>
                        <th>版本</th>
                        <th>描述</th>
                        <th>格式</th>
                    </tr>
                </thead>
                </tbody>
                    <?php foreach($_E['template']['rank_site'] as $site => $data){?>
                    <tr>
                        <td><?=$data['name']?></td>
                        <td><?=$data['author']?></td>
                        <td><?=$data['version']?></td>
                        <td><?=$data['desc']?></td>
                        <td><?=$data['format']?></td>
                    </tr>
                    <?php }?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <table class="text-center" style="margin-left:auto;margin-right:auto;">
                <thead>
                    <tr>
                        <th style="padding: 4px;width: 40px;"></th>
                        <?php foreach($_E['template']['plist'] as $pname ){?>
                            <th style="padding: 4px;width: 40px;"><?=$pname?></th>
                        <?php }?>
                    </tr>
                </thead>
                </tbody>
                    <?php foreach($_E['template']['id'] as $name){?>
                    <tr>
                        <td><?=$name?></td>
                        <?php foreach($_E['template']['plist'] as $pname ){?>
                            <td class="<?=$_E['template']['s'][$name][$pname]?>">●</td>
                        <?php }?>
                    </tr>
                    <?php }?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?=$_E['template']['dbg']?>
<div align="center">
<iframe src="http://www.tfcis.org/problemlist_test/" width="90%" height="550"></iframe>
</div>
