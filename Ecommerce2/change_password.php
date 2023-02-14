<?php
require('./includes/config.inc.php');
redirect_invalid_user( );
$page_title = "Change Password";
include('./includes/header.html');
require(MYSQL);

$pass_errors = array();

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	if(!empty($_POST['current'])){
		$current = mysqli_real_escape_string($conn, $_POST['current']);
	}else{
		$pass_errors['current'] = 'Please enter your current password!';
	}
	if (preg_match ('/^(\w*(?=\w*\d)(?=\w*[a-z])(?=\w*[A-Z])\w*){6,20}$/', $_POST['pass1']) ) {
		if ($_POST['pass1'] == $_POST['pass2']) {
			$p = mysqli_real_escape_string ($dbc, $_POST['pass1']);
		}else{
			$pass_errors['pass2'] = 'Your password did not match the confirmed password!';
		}
	}else{
		$pass_errors['pass1'] = 'Please enter a valid password!';
	}
	if (preg_match ('/^(\w*(?=\w*\d)(?=\w*[a-z])(?=\w*[A-Z])\w*){6,20}$/', $_POST['pass1']) ) {
		if($_POST['pass1'] == $_POST['pass2']){
			$p = mysqli_real_escape_string($conn, $_POST['pass1']);
		}else{
			$pass_errors['pass2'] = 'Your passwords do not match!' ;
		}
	}else{
		$pass_errors['pass1'] = 'Please enter a valid password!';
	}
	if(empty($pass_errors)){
		$q = "SELECT id FROM users WHERE pass='". get_password_hash($current).
		"' AND  id={$_SESSION['user_id']}";
		$r = mysqli_query($conn, $q);
		if(mysqli_num_rows($r) == 1){ //correct
			$q = "UPDATE users SET pass='" .get_password_hash($p).
			"'WHERE id={$_SESSION['user_id']} LIMIT 1";
			if($r = mysqli_query($conn, $q)){
				echo '<h3>Your password has been changed.</h3>';
				include('./includes/footer.html');
				exit();
			}else{
				trigger_error('Your password could not be changed due to a system error. 
				We apologize for the inconvenience.');
			}
		}else{
			$pass_errors['current'] = 'Your current password is incorrect!';
		}
	}
}

require('./includes/form_functions.inc.php');
?>
<h3>Change Password</h3>
<p>Use the form below to change password</p>
<form action = "htmlspecialchars($_SERVER['PHP_SELF'])" method = 'post' accept-charset = 'utf-8'>
	<p><label for = 'pass1'><strong>Current Password</strong></label><br />
	<?php create_form_input('current', 'password', $pass_errors);?>
	</p>

	<p><label for = "pass1"><strong>New Password</strong></label><br />
	<?php create_form_input('pass1', 'password', $pass_errors);?>
	<small>Must be between 6 and 20 characters, with at least one lowercase, 
	one uppercase and one letter.</small></p>

	<p><label for = 'pass2'><strong>Confirm New Password</strong></label><br />
	<?php create_form_input('pass2', 'password', $pass_errors);?>
	</p>

	<input type = "submit" name = "submit_button" value = "Change Password" id = "submit_button" 
	class = "formbutton" />

</form>

<?php
include('./includes/footer.html');
?>

			