<?php
require $_SERVER['DOCUMENT_ROOT'].'/cap.includes/basics.php';

// function to test if user is an administrator
function is_admin(){
	
	if(!isset($_SESSION['userdata'])){
		return false;
	}
	// check that the user is an administrator
	if(isset($_SESSION['userdata']['groups']['_administrators'])){
		return true;
	}
	
	if(!isset($_REQUEST['login_msg'])){
		$_REQUEST['login_msg'] = 'permissiondenied';
	}
	
	return false;
}
// function to transform a text area into WYSWYG editor
function ckeditor($name,$value='',$height=250){
	return '<textarea style="width:100%;height:'.$height.'px"
		name="'.addslashes($name).'">'.htmlspecialchars($value)
		.'</textarea><script>$(function(){
			var roxyFileman = \'/cap.javascript/fileman/index.html\'; 
			CKEDITOR.replace("'.addslashes($name).'",{
				filebrowserBrowseUrl:roxyFileman,
				filebrowserImageBrowseUrl:roxyFileman+\'?type=image\',
				removeDialogTabs: \'link:upload;image:upload\'
			});
		});</script>';
}

// if user not an admin, send to login page
if(!is_admin()){
	require SCRIPTBASE . 'cap.admin/login/login.php';
	exit;
}
?>