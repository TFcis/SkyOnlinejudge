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
        echo "<input type='$type' class='form-control textinput' name='$id' placeholder='$help' value='$oldvalue' required>";
    }
    else
    {
        echo "<input type='$type' class='form-control textinput' name='$id' placeholder='$help' required>";
    }
    echo '</div>';
    echo "<div class='col-sm-1' id=e_$id></div>";
    echo '</div>';
}
?>
<div class="container">
        <center>
        <div id="signin">
        
            <h3><?php echo($_E['site']['name']);?><br><small>User Registry</small></h3>
            
            <div>
                <?php if(isset($_E['template']['reg'])):?>
                <p><i><small><?php echo($_E['template']['reg']);?></small></i></p>
                <?php else:?>
                <p><i><small>-Coding is like poetry</small></i></p>
                <?php endif;?>
            </div>
            
            <div>
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
                        
                        <br>
                        
                        <div class="form-group">
                            <button type="submit" class="btn-blu btn-large btn-wide">Register</button>
                        </div>
                    </form>
                </div>
            </div>
            
        </div>
        </center>
</div>