<?php

$cakeDescription = __d('cake_dev', 'CakePHP: the rapid development php framework');
?>
<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $cakeDescription ?>:
		<?php echo $title_for_layout; ?>
	</title>
	<?php
	echo $this->Html->meta('icon');

	//echo $this->Html->css('cake.generic');
	echo $this->Html->css('style');

	echo $this->Html->css('inettuts.js');
	echo $this->Html->css('inettuts');
	echo $this->Html->css('jquery-ui');
	echo $this->Html->css('rss');
	echo $this->Html->css('login');
	echo $this->Html->script('jquery');
	echo $this->Html->script('jquery-ui');
	echo $this->Html->script('iGiggle');
	echo $this->Html->script('jquery.googleSuggest');
	echo $this->Html->script('inettuts');
	echo $this->Html->script('login');
	echo $this->fetch('meta');
	echo $this->fetch('css');
	echo $this->fetch('script');
	?>
</head>
<body>

<div id="content">

	<?php echo $this->Session->flash(); ?>

	<?php echo $this->fetch('content'); ?>
</div>
<div id="footer">
	<div id="footer_wrap">
		<div id="footer_content">
			iGiggle &copy;<a href="http://www.fahlstad.se">Fredrik Fahlstad</a> 2013
		</div>
	</div>
</div>
</div>
</body>
</html>
