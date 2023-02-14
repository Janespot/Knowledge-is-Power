<?php
require('./includes/config.inc.php');
require(MYSQL);
if(isset($_GET['id']) && filter_var($_GET['id'], FILTER_VALIDATE_INT, array('min_range' => 1))){
	$q = 'SELECT title, description, content FROM pages WHERE id = '.
	$_GET['id'];
	$r = mysqli_query($conn, $q);
	if(mysqli_num_rows($r) != 1){
		$page_title = 'Error!';
		include('./includes/header.html');
		echo '<p class = "error">This page has been accessed in error.</p>';
		include('./includes/footer.html');
		exit();
	}
	$row = mysqli_fetch_array($r, MYSQLI_ASSOC);
	$page_title = $row['title'];
	include('./includes/header.html');
	echo "<h3>$page_title</h3>";
	if(isset($_SESSION['user_not_expired'])){
		echo "<div>{$row['content']}</div>";
	}elseif(isset($_SESSION['user_id'])){
		echo '<p class = "error">Thank you for your interest in this content but your account has expired. 
		Please <a href = "renew.php">renew your account</a> in order to view the entire page.</p>';
		echo "<div>{$row['description']}</div>";
	}else{
		echo '<p class = "error">Thank you for your interest in this content. You must be logged in as a 
		registered user in order to view this page.</p>';
		echo "<div>{row['description']}</div>";
	}
	}else{
		$page_title = 'Error!';
		include('./includes/header.html');
		echo '<p class = "error">This page has been accessed in error.</p>';
	}
	
	include('./includes/footer.html');
	?>