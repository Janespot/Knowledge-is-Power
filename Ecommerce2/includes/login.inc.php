<div class = "side">
<div class = 'title'>
<h4>Login</h4>
</div>

<form action = "index.php" method = "post" accept-charset = "utf-8">
<p><?php if(array_key_exists('login', $login_errors)){
			echo '<span class = "error">'.$login_errors['login'].'</span><br />';
         }?>
	<label for="email"><strong>Email Address</strong></label><br />
	<?php create_form_input('email', 'text', $login_errors); ?><br /><br />
	<label for="pass"><strong>Password</strong></label><br />
	<?php create_form_input('pass', 'password', $login_errors); ?><br />
	<a href="forgot_password.php" align="right">Forgot password?</a><br /><br />
	<input type="submit" value="Login &rarr;"></p>
</form>
</div>