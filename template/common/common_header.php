<?php
	if(!defined('IN_TEMPLATE'))
    {
      exit('Access denied');
    }
?>
<!DOCTYPE html>
<head>
    <meta charset='utf-8'>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo($_E['site']['name']); ?></title>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300,400,700,600' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    
    <link rel="stylesheet" type="text/css" href="<?=$_E['SITEROOT']?>css/index.css">
    <?php if($_E['EnableMathJax']): ?>
      	<script type="text/x-mathjax-config">
      		MathJax.Hub.Config(
            {	tex2jax:
               	{
                   	inlineMath: [['$','$'], ['\\(','\\)']],
                    processEscapes: true
                }
            });
    	</script>
        <?php if($_E['uesLocalMathJaxFile']):?>
        <script type="text/javascript" src=<?=$_E['SITEROOT']?>"js/MathJax/MathJax.js?config=TeX-AMS-MML_HTMLorMML"></script>
        <?php else:?>
        <script type="text/javascript" src="https://cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-AMS-MML_HTMLorMML"></script>
        <?php endif;?>
    <?php endif;?>
    <!-- Latest compiled and minified JavaScript -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    <script src="<?=$_E['SITEROOT']?>js/ace/ace.js" type="text/javascript" charset="utf-8"></script>
    <script src="<?=$_E['SITEROOT']?>js/common.js"></script>
    <script>
    $(".alert").alert();
    siteroot('<?=$_E['SITEROOT']?>');
    </script>