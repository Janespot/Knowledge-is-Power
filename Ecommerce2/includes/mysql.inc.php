<?php
DEFINE('DB_USER','root');
DEFINE('DB_PASSWORD','');
DEFINE('DB_HOST','localhost');
DEFINE('DB_NAME','products');

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

mysqli_set_charset($conn, 'utf8');//establishes the character set to be used for communication between PHP scripts and the database

function escape_data($data){
	global $conn;
	if(get_magic_quotes_gpc())$data = stripslashes($data);//strips extra slashes if Magic Quotes is on
	return mysqli_real_escape_string(trim($data), $conn);//returns a trimmed, secure version of the data
}
function get_password_hash($password){ //this is a function that hashes password for security
	global $conn;
	return mysqli_real_escape_string($conn, hash_hmac('sha256', $password, 'c#haRl891', true));
}
