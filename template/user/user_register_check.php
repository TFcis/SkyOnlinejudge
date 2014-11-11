<?php
if(!defined('IN_TEMPLATE'))
{
    exit('Access denied');
}
?>
<div class="container">
        <center>
        <div id="signin">
        
            <h3><?php echo($_E['site']['name']);?><br><small>Terms & Conditions</small></h3>
            <div class=".container-fluid">
            
                <div>
                    <div id="license">
                    <?php Render::renderSingleTemplate('mit_license','user'); ?>
                    </div>
                </div>
                
                <div style = "text-align: right">
                    <button type="button" class="btn-grn btn-large" onclick="location.href='user.php?mod=register&accept=accept'">
                    I accept these terms
                    </button>
                    <button type="button" class="btn-red btn-large" onclick="location.href='index.php'">
                    Deny
                    </button>
                </div>
                
            </div>
            
        </div>
        </center>
</div>