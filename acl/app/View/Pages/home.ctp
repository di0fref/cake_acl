<?php $cakeDescription = "ljn";?>

<div id="header">

	<div id="logo"><img src="img/google_color.png"></div>
	<div id="search">
		<form action="https://www.google.se/search" metod="get" id="search_form">
			<input type="text" id="gbqfq" name="q" class="gbqfif"/>
			<input type="submit" value="search" id="search_button" name="search_button" style=""/>
		</form>
	</div>
	<div id="login">
		<?php echo $this->element("login");?>
	</div>

</div>
<div id="container">


<div id="columns">
	<ul id="column0" class="column"></ul>
	<ul id="column1" class="column"></ul>
	<ul id="column2" class="column"></ul>
</div>

<div id="dialog" title="Add Widget" style="display: none"></div>
<div class="clear"></div>
