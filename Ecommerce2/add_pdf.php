<?php
require_once('./includes/config.inc.php');
redirect_invalid_user('user_admin');
$page_title = 'Add a PDF';
include('./includes/header.html');
require(MYSQL);

$add_pdf_errors = array();

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	if(!empty($_POST['title'])){
		$t = mysqli_real_escape_string($conn, strip_tags($_POST['title']));
	}else{
		$add_pdf_errors['title'] = 'Please enter the title!';
	}
	if(!empty($_POST['description'])){
		$d = mysqli_real_escape_string($conn, strip_tags($_POST['description']));
	}else{
		$add_pdf_errors['description'] = 'Please enter the description!';
	}
	//check for a pdf
	if(is_uploaded_file($_FILES['pdf']['tmp_name']) && ($_FILES['pdf']['error'] == UPLOAD_ERR_OK)){
		$file = $_FILES['pdf'];
	//validate pdf info	
		$size = ROUND($file['size']/1024);
		if($size > 1024){
			$add_pdf_errors['pdf'] = 'The uploaded file is too large!';
		}
		if(($file['type'] != 'application/pdf') && (substr($file['name'], -4) != '.pdf')){
			$add_pdf_errors['pdf'] = 'The uploaded file is not a PDF!';
		}
		if(!array_key_exists('pdf', $add_pdf_errors)){
			$tmp_name = sha1($file['name'] . uniqid('',true));
			$dest = PDFS_DIR.$tmp_name.'_tmp';
			if(move_uploaded_file($file['tmp_name'], $dest)){
				$_SESSION['pdf']['tmp_name'] = $tmp_name;
				$_SESSION['pdf']['size'] = $size;
				$_SESSION['pdf']['file_name'] = $file['name'];
				echo '<h4>The file has been uploaded successfully!</h4>';
			}else{
				trigger_error('The file could not be moved!');
				unlink($file['tmp_name']);
			}
		}
	}else{ //no uploaded file 
		switch($_FILES['pdf']['error']){
			case 1:
			case 2:
				$add_pdf_errors['pdf'] = 'The uploaded file was too large.';
				break;
			case 3:
				$add_pdf_errors['pdf'] = 'The file was only partially uploaded.';
				break;
			case 6:
			case 7:
			case 8:
				$add_pdf_errors['pdf'] = 'The file cold not be uploaded due to a system error.';
				break;
			case 4:
			default:
				$add_pdf_errors['pdf'] = 'No file was uploaded.';
				break;
		}
	}
	if(empty($add_pdf_errors)){
		$fn = mysqli_real_escape_string($conn, $_SESSION['pdf']['file_name']);
		$tmp_name = mysqli_real_escape_string($conn, $_SESSION['pdf']['tmp_name']);
		$size = (int)$_SESSION['pdf']['size'];
		$q = "INSERT INTO pdfs (tmp_name, title, description, file_name, size) VALUES 
		('$tmp_name', '$t', '$d', '$fn', $size)";
		$r = mysqli_query($conn, $q);
		if(mysqli_affected_rows($conn) == 1){
			$original = PDFS_DIR.$_SESSION['pdf']['tmp_name'].'_tmp';
			$dest = PDFS_DIR.$_SESSION['pdf']['tmp_name'];
			rename($original, $dest);
			echo '<h4>The PDF has been added successfully!</h4>';
			$_POST = array();
			$_FILES = array();
			unset($file, $_SESSION['pdf']);
		}else{
			trigger_error('The PDF could not be added due to a system error! 
			We apologize for the inconvenience.');
			unlink($dest);
		}
	}
}else{ //clear out the session on a GET request
	unset($_SESSION['pdf']);
}

require('./includes/form_functions.inc.php');
?>

<h3>Add a PDF</h3>
<form enctype = "multipart/form-data" action = "add_pdf.php" 
method = "post" accept-charset = "utf-8">
	<input type = "hidden" name = "MAX_FILE_SIZE" value = "1048576" />
	
	<fieldset>
		<legend>Fill in the form to add a PDF to the site:</legend>
		<p><label for = "title"><strong>Title</strong></label><br />
		<?php create_form_input('title', 'text', $add_pdf_errors);?></p>
		
		<p><label for = "description"><strong>Description</strong></label><br />
		<?php create_form_input('description', 'textarea', $add_pdf_errors);?></p>
		
		<p><label for = "pdf"><strong>PDF</strong></label><br />
		<?php echo '<input type = "file" name = "pdf" id = "pdf"';
		if(array_key_exists('pdf', $add_pdf_errors)){
			echo 'class = "error" /><span class = "error">'.
			$add_pdf_errors['pdf'].'</span>';
		}else{
			echo ' />';
			if(isset($_SESSION['pdf'])){
				echo "Currently '{$_SESSION['pdf']['file_name']}'";
			}
		}
		?>
		<small>PDF  only, 1MB limit.</small></p>
		
		<p><input type = "submit" name = "submit_button" value = "Add PDF" 
		id = "submit_button" class = "formbutton" /></p>
	</fieldset>
</form>

<?php
include('./includes/footer.html');
?>