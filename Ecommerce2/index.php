<?php
require("./includes/config.inc.php");
include("./includes/header.html");
require(MYSQL);
?>
<div class = "main">
<h3>Welcome</h3>
<p>Welcome to Knowledge is Power, a site dedicated to keeping you up 
to date on the Web security and programming information you need to 
know. Blah, blah, blah. Yadda, yadda, yadda.</p>
</div>
<?php
$login_errors = array();

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
		$e = mysqli_real_escape_string ($conn, $_POST['email']);
	} else {
		$login_errors['email'] = 'Please enter a valid email address!';
	}
	if (!empty($_POST['pass']) ) {
		$p = mysqli_real_escape_string ($conn, $_POST['pass']);
	} else {
		$login_errors['pass'] = 'Please enter your password!';
	}
	if (empty($login_errors)) {
		$q = "SELECT id, username, type, IF(date_expires > NOW( ), true, 
		false) FROM users WHERE (email='$e' AND pass='" . get_password_hash($p) . "')";
		$r = mysqli_query ($conn, $q);
		if (mysqli_num_rows($r) == 1) {
			$row = mysqli_fetch_array ($r, MYSQLI_NUM);
			if ($row[2] == 'admin'){
				session_regenerate_id(true);
				$_SESSION['user_admin'] = true;
			}
			$_SESSION['user_id'] = $row[0];
			$_SESSION['username'] = $row[1];
			if ($row[3] == 1) $_SESSION['user_not_expired'] = true;
		}else{
			$login_errors['login'] = 'The email address and password do not match those on file.';
		}
	}
}
if(!isset($login_errors))$login_errors = array();
require_once('./includes/form_functions.inc.php');

include('./includes/login.inc.php');


include("./includes/footer.html");
?>