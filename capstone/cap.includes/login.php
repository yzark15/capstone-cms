<?php
require 'login-libs.php';

login_check_is_email_provided();	// check that email address is provided and valid

if(!isset($_REQUEST['password']) || $_REQUEST['password'] == ''){
	login_redirect($url,'nopassword');		// if no password provided redirect to login page
}

$password = md5($_REQUEST['email'].'|'.$_REQUEST['password']);		// grab username and password hash

// check username and password
$r = dbRow('select * from user_accounts where 
		email="'.addslashes($_REQUEST['email']).'" and 
		password="'.$password.'" and active'
	);

if($r == false){
	login_redirect($url,'loginfailed');		
}

// successful login, set SESSION variables then redirect
$_SESSION['userdata'] = $r;
$groups = json_decode($r['groups']);
echo $groups . '<br>';
$_SESSION['userdata']['groups'] = array();

foreach($groups as $g){
	$_SESSION['userdata']['groups'][$g] = true;
}

login_redirect($url);
?>