<?php
if(!defined('IN_TEMPLATE'))
{
    exit('Access denied');
}
?>
<script>
$(document).ready(function()
{
    $("#ojacct").submit(function(e)
    {
        e.preventDefault();
        $.post("user.php",
            $("#ojacct").serialize(),
            function(data){
                alert("Data Loaded: " + data);
        });
        return true;
    });
})
</script>

<div class="container">

    <div><h2>Come soon...</h2></div>

    
</div>
