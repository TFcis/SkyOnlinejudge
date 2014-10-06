<?php
if(!defined('IN_TEMPLATE'))
{
    exit('Access denied');
}
//For Template
function CreateInputText($id ,$type ,$help = '' ,$oldvalue = null)
{
    echo "<div class='form-group'>";
    // it should use language file
    echo "<label for='$id' class='col-sm-2 control-label'>$id</label>";
    echo "<div class='col-sm-9'>";
    if($oldvalue)
    {
        echo "<input type='$type' class='form-control' name='$id' placeholder='$help' value='$oldvalue' required>";
    }
    else
    {
        echo "<input type='$type' class='form-control' name='$id' placeholder='$help' required>";
    }
    echo '</div>';
    echo "<div class='col-sm-1' id=e_$id></div>";
    echo '</div>';
}
?>
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-1" id="signinlogo">
        </div>
        <div class="col-md-4 col-md-offset-1" id="signin">
            <h3><?php echo($_E['site']['name']);?><br><small>帳號資料</small></h3>
            <div class="text-right">
                <?php if(isset($_E['template']['reg'])):?>
                <p><i><small><?php echo($_E['template']['reg']);?></small></i></p>
                <?php else:?>
                <p><i><small>Coding is like poetry</small></i></p>
                <?php endif;?>
            </div>
            <div class=".container-fluid">
                <div class="row">
                    <form role="form" action="user.php" method="post" class="form-horizontal">
                        <input type='hidden' name='mod' value='register'>
                        <input type='hidden' name='accept' value='reg'>
                        <?php
                            CreateInputText('email','email','Email for login');
                            CreateInputText('nickname','text','Nickname');
                            CreateInputText('password','password','password');
                            CreateInputText('repeat','password','Repeat password again');
                        ?>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-success">註冊</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>