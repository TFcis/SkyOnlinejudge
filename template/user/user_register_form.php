<?php
if (!defined('IN_TEMPLATE')) {
    exit('Access denied');
}
//For Template
function CreateInputText($id, $type, $help = '', $oldvalue = null)
{
    echo "<div class='form-group'>";
    // it should use language file
    echo "<label for='$id' class='col-sm-3 control-label white-text'>$id</label>";
    echo "<div class='col-sm-8'>";
    if ($oldvalue) {
        echo "<input type='$type' class='form-control textinput' name='$id' placeholder='$help' value='$oldvalue' required>";
    } else {
        echo "<input type='$type' class='form-control textinput' name='$id' placeholder='$help' required>";
    }
    echo '</div>';
    echo "<div class='col-sm-1' id=e_$id></div>";
    echo '</div>';
}
?>
<div class="container">
    <div class="row">
        <div class="col-lg-offset-4 col-lg-4 login_form">
            <center>
            
                <h3><?php echo $_E['site']['name']; ?><br><small class="login_sub_title">User Registry</small></h3>
                
                <div>
                    <?php if (isset($_E['template']['reg'])):?>
                    <p><i><small style='color:red'><?php echo $_E['template']['reg']; ?></small></i></p>
                    <?php else:?>
                    <p><i><small class="login_comment">-Coding is like poetry</small></i></p>
                    <?php endif; ?>
                </div>
                
                <div>
                    <div class="row">
                        <form role="form" action="<?=$SkyOJ->uri('user','register')?>" method="post" class="form-horizontal">
                            <input type='hidden' name='mod' value='register'>
                            <input type='hidden' name='accept' value='reg'>
                            <?php
                                CreateInputText('email', 'email', 'Email');
                                CreateInputText('nickname', 'text', 'Nickname');
                                CreateInputText('password', 'password', 'password');
                                CreateInputText('repeat', 'password', 'Repeat password again');
                            ?>
                            
                            <br>
                            
                            <div class="form-group">
                                <button type="submit" class="btn-blu btn-large btn-wide">Register</button>
                            </div>
                        </form>
                    </div>
                </div>
                <br><br>
            </center>
        </div>
    </div>
</div>