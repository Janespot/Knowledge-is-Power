<?php
$live = false;
$contact_email = 'you@example.com';

define('BASE_URI','../effotlessEcommerce/');
define('BASE_URL','www.example.com/');
define('MYSQL', './includes/mysql.inc.php');
define('PDFS_DIR', BASE_URI.'pdfs/');

session_start();

function my_error_handler($e_number, $e_message, $e_file, $e_line, /*$e_vars*/){//a function that reports errors
	global $live, $contact_email;
	$message = "An error occured in script '$e_file'on line $e_line:\n$e_message\n";//error message
	$message .= "<pre>".print_r(debug_backtrace(),1)."</pre>\n";
	if(!$live){
		echo "<div class = 'error'>".nl2br($message)."</div>";
	}else{
		error_log($message,1,$contact_email,'From:admin@example.com');
		if($e_number!=E_NOTICE){
			echo "<div class = 'error'>A system error occured. We apologize for the inconvenience.</div>";
		}
	}
	return true;
}
set_error_handler('my_error_handler');

if(!headers_sent()){
	function redirect_invalid_user($check = 'user_id', $destination = 'index.php', $protocol = 'http://'){//function to 
	//redirect unauthorised users
		if(!isset($_SESSION[$check])){
			$url = $protocol.BASE_URL.$destination;
			header("Location:$url");
			exit();
		}
	}
}else{
	include_once('./includes/header.html');
	trigger_error('You do not have permission to access this page. Please login and try again.');
	include_once('./includes/footer.html');
}