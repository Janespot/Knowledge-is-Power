<?php
require('./includes/config.inc.php');
$page_titile = 'Forgot Password';
include('./includes/header.html');
require(MYSQL);

$pass_errors = array();

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
		$q = 'SELECT id FROM users WHERE email = "'.mysqli_real_escape_string(
		$conn, $_POST['email']).'"';
		$r = mysqli_query($conn, $q);
		if(mysqli_num_rows($r) == 1){
			list($uid) = mysqli_fetch_array($r, MYSQLI_NUM);
		}else{
			$pass_errors['email'] = 'The submitted email address has not been 
			registered!';
		}
	}else{
		$pass_errors['email'] = 'Please enter a valid email address.';
	}
	if(empty($pass_errors)){
		$p = substr(md5(uniqid(rand(), true)), 10, 15);
		$q = 'UPDATE users SET pass="'. get_password_hash($p).
		'" WHERE id=$uid LIMIT 1';
		$r = mysqli_query($conn, $q);
		if(mysqli_affected_rows($conn) == 1){
			$body = "Your password has temporarily been changed to '$p'. 
			Please login to change your password.";
			mail($_POST['email'], 'Your temporary password.', $body, 
			'From: admin@example.com');
			
			echo '<h3>Your password has been changed.</h3><p>You will 
			receive the new, temporary password via email. Once you 
			are logged in, you can change your password.</p>';
			include('./includes/footer.html');
			exit();
		}else{
			trigger_error('Your password could not be changed due to a system error. 
			We apologize for the inconvenience.');
		}
	}
}

require('./includes/form_functions.inc.php');
?>
<h3>Reset Your Password</h3>
<p>Enter your email address below to reset your password.</p>
<form action = "htmlspecialchars($_SERVER['PHP_SELF'])" method = 'post' accept-charset = 'utf-8'>
	<p><label for = 'email'><strong>Email Address</strong></label><br />
	<?php create_form_input('email', 'text', $pass_errors);?>
	</p>
	
	<input type = 'submit' name = 'submit' value = "Reset Password" id = "submit_button" 
	class = 'formbutton' />
</form>

<?php
include('./includes/footer.html');
?>