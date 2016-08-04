<?php
    if (!defined('IN_TEMPLATE')) {
        exit('Access denied');
    }
?>
<!DOCTYPE html>
<head>
    <meta charset='utf-8'>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $_E['site']['name']; ?></title>
    <link href='//fonts.googleapis.com/css?family=Open+Sans:300,400,700,600' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="<?=$_E['SITEROOT']?>css/third/bootstrap/css/bootstrap.min.css">
    
    <link rel="stylesheet" type="text/css" href="<?=$_E['SITEROOT']?>css/index.css">
    <?php if ($_E['EnableMathJax']): ?>
      	<script type="text/x-mathjax-config">
      		MathJax.Hub.Config(
            {	tex2jax:
               	{
                   	inlineMath: [['$','$'], ['\\(','\\)']],
                    processEscapes: true
                }
            });
    	</script>
        <?php if ($_E['uesLocalMathJaxFile']):?>
        <script type="text/javascript" src=<?=$_E['SITEROOT']?>"js/MathJax/MathJax.js?config=TeX-AMS-MML_HTMLorMML"></script>
        <?php else:?>
<<<<<<< HEAD
        <script type="text/javascript" src="//cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-AMS-MML_HTMLorMML"></script>
        <?php endif; ?>
    <?php endif; ?>
=======
        <script type="text/javascript" src="https://cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-AMS-MML_HTMLorMML"></script>
        <?php endif;?>
    <?php endif;?>
>>>>>>> refs/remotes/TFcis/master
    <!-- Latest compiled and minified JavaScript -->
<<<<<<< HEAD
    <script src="<?=$_E['SITEROOT']?>js/jquery.min.js"></script>
    <script src="<?=$_E['SITEROOT']?>css/bootstrap/js/bootstrap.js"></script>
    <script src="<?=$_E['SITEROOT']?>js/ace/ace.js" type="text/javascript" charset="utf-8"></script>
=======
    <script src="<?=$_E['SITEROOT']?>js/third/jquery.min.js"></script>
    <script src="<?=$_E['SITEROOT']?>css/third/bootstrap/js/bootstrap.min.js"></script>
    <script src="<?=$_E['SITEROOT']?>js/ace/ace.js"></script>
>>>>>>> b2a53ecc87171492dad23a24fa231e56fe8a75a5
    <script src="<?=$_E['SITEROOT']?>js/common.js"></script>
    <?php if (userControl::isAdmin()):?>
    <script src="<?=$_E['SITEROOT']?>js/admin_check.js"></script>
    <?php endif;?>
    <script>
    $(".alert").alert();
    siteroot('<?=$_E['SITEROOT']?>');
    </script>