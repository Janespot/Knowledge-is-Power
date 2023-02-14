<?php
require('./includes/config.inc.php');
$page_title = 'Register';
include('./includes/header.html');
require(MYSQL);

$reg_errors = array();

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	if(preg_match('/^[A-Z \'.-]{2,20}$/i',$_POST['first_name'])){
		$fn = mysqli_real_escape_string($conn, $_POST['first_name']);
	}else{
		$reg_errors['first_name'] = 'Please enter your first name!';
	}
	if(preg_match('/^[A-Z \'.-]{2,40}$/i', $_POST['last_name'])) {
		$ln = mysqli_real_escape_string($conn, $_POST['last_name']);
	}else{
		$reg_errors['last_name'] = 'Please enter your last name!';
	}
	if(preg_match ('/^[A-Z0-9]{2,30}$/i', $_POST['username'])) {
		$u = mysqli_real_escape_string($conn, $_POST['username']);
	}else{
		$reg_errors['username'] = 'Please enter a desired username!';
	}
	if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
		$e = mysqli_real_escape_string($conn, $_POST['email']);
	}else{
		$reg_errors['email']  = 'Please enter a valid email address!';
	}
	if(preg_match('/^(\w*(?=\w*\d)(?=\w*[a-z])(?=\w*[A-Z])\w*){6,20}$/', $_POST['pass1']) ){
		if($_POST['pass1'] == $_POST['pass2']){
			$p = mysqli_real_escape_string($conn, $_POST['pass1']);
		}else{
			$reg_errors['pass2'] = "Your passwords don't match!";
		}
	}else{
		$reg_errors['pass1'] = 'Please enter a valid Password!';
	} //The Cracklib PECL extension can be used to test the strength of a password.
	if(empty($reg_errors)){
		$q = "SELECT email, username FROM users WHERE email = '$e' or
		username = '$u'";
		$r = mysqli_query($conn, $q);
		$rows = mysqli_num_rows($r);
		if($rows == 0){ //no problems
			$q = "INSERT INTO users (username, email, pass, first_name, last_name, date_expires) 
			VALUES ('$u', '$e','". get_password_hash($p) ."', '$fn', '$ln', ADDDATE(NOW(), 
			INTERVAL 1 MONTH))";
			$r = mysqli_query($conn, $q);
			if(mysqli_affected_rows($conn) == 1){
				echo '<h3>Thanks!</h3><p>Thank you for registering! You may now 
				login and access the site\'s content.</p>';
				$body = "Thank you for registering at my site.\n\n";
				mail($_POST['email'], 'Registration Confirmation', $body, 'From: 
				admin@example.com');
				include('./includes/footer.html');
				exit();
			}else{
				trigger_error('You could not be registered due to a system error. We apologize 
				for the inconvenience.');
			}
		}else{
			if($rows == 2){ //both are taken
				$reg_errors['email'] == 'This email address has already been registered. If you have 
				forgotten your password, usethe link at right to have your password sent to you.';
				$reg_errors['username'] = 'This username has already been taken. Please try another.';
			}else{  //one or both may be taken
				$row = mysqli_fetch_array($r, MYSQLI_NUM);
				if(($row[0] == $_POST['email']) && ($row[1] == $_POST['username'])){ //both match
					$reg_errors['email'] = 'This email address has already been registered. 
					If you have forgotten your password, use the link at right to have your password sent to you.';
					$reg_errors['username'] = 'This username has already been taken with this email address. If you 
					have forgotten your password, use the link at right to have your password sent to you.';
				}elseif($row[0] == $_POST['email']){ //email match
					$reg_errors['email'] = 'This email address is already registered. Forgotten password? Use the 
					link at right to have it sent to you.';
				}elseif($row[1] == $_POST['username']){ //username match
					$reg_errors['username'] = 'This username has already been taken. Please try another.';
				}
			} //end of $row == 2 else
		} //end of $row == 0 if
	}
}


require('./includes/form_functions.inc.php');
?>
<h3>Register</h3>
<form action = "<?php htmlspecialchars($_SERVER['PHP_SELF']);?>" method = "post" accept-charset = "utf-8" style = "padding-left:100px">
	<p><label for = "first-name"><strong>First Name</strong></label>
	<br />
	<?php create_form_input('first_name', 'text', $reg_errors); ?>
	</p>
	
	<p><label for = "first-name"><strong>Last Name</strong></label>
	<br />
	<?php create_form_input('last_name', 'text', $reg_errors); ?>
	</p>
	
	<p><label for = "username"><strong>Desired Username</strong></label>
	<br />
	<?php create_form_input('username', 'text', $reg_errors); ?>
	<small>Only letters and numbers allowed.</small></p>
	
	<p><label for = "email"><strong>Email Address</strong></label>
	<br />
	<?php create_form_input('email','text',$reg_errors);?></p>
	
	<p><label for = "pass1"><strong>Password</strong></label>
	<br />
	<?php create_form_input('pass1','password',$reg_errors);?>
	<small>Must be between 6 to 20 characters long, with at least one lowercase letter, 
	one uppercase letter and one number.</small></p>
	
	<p><label for = "pass2"><strong>Confirm Passwpord</strong></label>
	<br />
	<?php create_form_input('pass2','password',$reg_errors);?></p>
	
	<input type = "submit" name = "submit_button" value = "Next&rarr;" 
	id = "submit_button" class = "formbutton" />
	
</form>
	
<p>Access to the site's content is available to registered users at a 
cost of $10.00(US) per year. Use the form below to begin the registaration
 process. <strong>Note: All fields are required.</strong> After completing
 this form, you'll be presented with the opportunity to securely pay for your
 yearly subscription via <a href = "http://www.paypal.com">Paypal</a>.</p>
 <?php
 include('./includes/footer.html');
 ?>