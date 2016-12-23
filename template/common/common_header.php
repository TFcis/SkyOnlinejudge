<?php
    if (!defined('IN_TEMPLATE')) {
        exit('Access denied');
    }
?>
<!DOCTYPE html>
<head>
    <meta charset='utf-8'>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?=htmlentities($SkyOJ->GetTitle())?></title>
    <link rel='stylesheet' href='//fonts.googleapis.com/css?family=Open+Sans:300,400,700,600'  type='text/css'>
    <link rel="stylesheet" href="<?=$_E['SITEROOT']?>css/third/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.5.0/styles/github-gist.min.css">
    <link rel="stylesheet" href="<?=$_E['SITEROOT']?>css/index.css" type="text/css">
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
        <script type="text/javascript" src="//cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-AMS-MML_HTMLorMML"></script>
        <?php endif; ?>
    <?php endif; ?>
    <!-- Latest compiled and minified JavaScript -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.5.0/highlight.min.js"></script>
    <script src="<?=$_E['SITEROOT']?>js/third/jquery.min.js"></script>
    <script src="<?=$_E['SITEROOT']?>css/third/bootstrap/js/bootstrap.min.js"></script>
    <script src="<?=$_E['SITEROOT']?>js/common.js"></script>
    <script src="<?=$_E['SITEROOT']?>js/timer.js"></script>
    <script src="<?=$_E['SITEROOT']?>js/ace/ace.js"></script>
    <?php if (userControl::isAdmin($_G['uid'])):?>
    <script src="<?=$_E['SITEROOT']?>js/admin_check.js"></script>
    <?php endif;?>
    <script>
    $(document).ready(function(){
        $(".alert").alert();
        siteroot('<?=$_E['SITEROOT']?>');
        hljs.initHighlightingOnLoad();
        $('.dropdown-toggle').dropdown();
    })
    
    </script>