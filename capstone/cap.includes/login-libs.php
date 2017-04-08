<?php
require 'basics.php'; 

$url='/';
$err=0;

// function for redirecting
function login_redirect($url, $msg='success'){
	if($msg){
		$url.='?login_msg=' . $msg;
	}
	
	header('Location: ' . $url);
	echo '<a href="' . htmlspecialchars($url) . '">redirect</a>';
	exit;
}

if(isset($_REQUEST['redirect'])){
	$url = preg_replace('/[\?\&].*/','',$_REQUEST['redirect']);		// remove query string parameters
	
	if($url == ''){
		$url = '/';
	}
}

function login_check_is_email_provided(){	// function to check if email is provided and valid

	if(!isset($_REQUEST['email']) || $_REQUEST['email'] == '' || !filter_var($_REQUEST['email'], FILTER_VALIDATE_EMAIL)){
		login_redirect($GLOBALS['url'],'noemail');
	}
}

?>