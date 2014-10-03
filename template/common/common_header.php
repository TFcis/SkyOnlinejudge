<?php
	if(!defined('IN_TEMPLATE'))
    {
      exit('Access denied');
    }
?>
<!DOCTYPE html>
<head>
    <meta charset='utf-8'>
    <title><?php echo($_E['site']['name']); ?></title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/index.css">
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
    <script type="text/javascript" src="js/MathJax/MathJax.js?config=TeX-AMS-MML_HTMLorMML"></script>
    <?php else:?>
    <script type="text/javascript" src="http://cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-AMS-MML_HTMLorMML"></script>
    <?php endif;?>
  <?php endif; //EnableMathJax?>