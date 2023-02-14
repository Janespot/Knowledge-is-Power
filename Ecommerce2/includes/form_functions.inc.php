<?php
function create_form_input($name, $type, $errors){
	$value = false;
	if(isset($_POST[$name]))$value = $_POST[$name];
	//if ($value && get_magic_quotes_gpc( )) $value = stripslashes($value);
	if(($type == 'text') || ($type == 'password')){
		echo '<input type = "'.$type.'" name = "'.$name.'" id = "'.$name.'"';
		if($value)echo 'value = "'.htmlspecialchars($value).'"';
		if(array_key_exists($name, $errors)){  //checks for an error
			echo 'class = "error" /><span class = "error">'.$errors[$name].
			'</span>';
		}else{
			echo '/>';
		}
	}elseif($type == 'textarea'){
		if(array_key_exists($name, $errors)) echo '<span class = "error">'.
		$errors[$name].'</span>';
		echo '<textarea name = "'.$name.'" id = "'.$name.'" rows = "5" 
		cols = "75"';
		if(array_key_exists($name, $errors)){
			echo 'class = "error">';
		}else{
			echo '>';
		}
		if($value) echo $value;
		echo '</textarea>';
	}
}
