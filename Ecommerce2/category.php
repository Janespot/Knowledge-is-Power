<?php
require('./includes/config.inc.php');
require(MYSQL);
if(isset($_GET['id']) &&filter_var($_GET['id'],FILTER_VALIDATE_INT, array('min_range'=>1))){
	$q = 'SELECT category FROM categories WHERE id='.$_GET['id'];
	$r = mysqli_query($conn, $q);
	if(mysqli_num_rows($r) != 1){
		$page_title = 'Error!';
		include('./includes/header.html');
		echo '<p class = "error">This page has been accessed in error.</p>';
		include('./includes/footer.html');
		exit();
	}
	list($page_title) = mysqli_fetch_array($r, MYSQLI_NUM);
	include('./includes/header.html');
	echo "<h3>$page_title</h3>";
	if(isset($_SESSION['user_id']) && !isset($_SESSION['user_not_expired'])){
		echo '<p class = "error">Thank you for your interest in this content. Unfortunately, 
		your account has expired. Please <a href = "renew.php">renew your account</a> in 
		order to access site content.</p>';
	}
	$q = 'SELECT id, title, description FROM pages WHERE category_id = ' .$_GET['id'].
	' ORDER BY date_created DESC';
	$r = mysqli_query($conn, $q);
	if(mysqli_num_rows($r)>0){
		while($row = mysqli_f($r, MYSQLI_ASSOC)){
			echo "<div><h4><a href = \"page.php?id={$row['id']}\">{$row['title']}</a>
			</h4><p>{$row['description']}</p></div>\n";
		}
	}else{
		echo '<p>There are currently no pages of content associated with this category. 
		Try again later!</p>';
	}
}else{
	$page_title = "Error!";
	include('./includes/header.html');
	echo '<p class = "error">This page has been accessed in error.</p>';
}

include('./includes/footer.html');
?>