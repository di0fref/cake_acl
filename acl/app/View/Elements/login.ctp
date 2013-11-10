<div id="loginContainer">
<?php if($Auth->loggedIn()){
	$links = "<a href='#' id='addWidgetLink'>Add Widget</a>";
	$links .=  " | " . $this->Html->link("Log Out", array("controller" => "users", "action" => "logout"));

	echo "<div id='links'>$links</div>";
}
else { ?>


	<a href="#" id="loginButton"><span>Login</span><em></em></a>
	<div style="clear:both"></div>
	<div id="loginBox">
		<form id="loginForm" action="users/login" method="post">
			<fieldset id="body">
				<fieldset>
					<label for="username">Email Address</label>
					<input type="text" name="data[User][username]" id="email" />
				</fieldset>
				<fieldset>
					<label for="password">Password</label>
					<input type="password" name="data[User][password]" id="password" />
				</fieldset>
				<input type="submit" id="login" value="Sign in" />
				<label for="checkbox"><input type="checkbox" id="checkbox" />Remember me</label>
			</fieldset>
			<span><a href="#">Forgot your password?</a></span>
			<span><?php echo $this->Html->link("Register", array("controller" => "users", "action" => "register"));?></span>
		</form>
	</div>

<? } ?>
</div>