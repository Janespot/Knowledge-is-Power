<?php
require('./includes/config.inc.php');
require(MYSQL);
$page_title = 'PDFs';
include('./includes/header.html');
echo '<h3>PDF Guides</h3>';

if(isset($_SESSION['user_id'] && !isset($_SESSION['user_not_expired'])){
	echo '<p class = "error">Thank you for your interest in this content. 
	Unfortunately, your account has expired. Please <a href = "renew.php">
	renew your account</a>in order to view any of the PDFs listed below.</p>';
}elseif(!isset($_SESSION['user_id'])){
	echo '<p class = "error">Thank you for your interest in this content. 
	You must be logged in as a registered user to view any of the PDFs 
	listed below.</p>';
}

$q = 'SELECT tmp_name, title, description, size FROM pdfs ORDER BY date_created 
DESC';
$r = mysqli_query($conn, $q);
if(mysqli_num_rows($r) > 0){
	while($row = mysqli_fetch_array($r, MYSQLI_ASSOC)){
		echo '<div><h4><a href=\"view_pdf.php?id={$row['tmp_name']}\">{$row['title']}</a>
		({$row['size']}kb)</h4><p>{$row['description']}</p></div>\n';
	}
}else{
	echo '<p>There are currently no PDFs available to view. Please try again later!</p>';
}
include('./includes/footer.html');
?>