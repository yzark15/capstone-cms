<?php
$url = '/';
session_start();

if(isset($_REQUEST['redirect'])){
	$url = preg_replace('/[\?\&].*/','',$_REQUEST['redirect']);
	
	if($url == ''){
		$url = '/';
	}
	
	unset($_SESSION['userdata']);	// log user out by destroying session variable
	
	header('Location: '.$url);		// redirect to login page
	echo '<a href="'.htmlspecialchars($url).'">redirect</a>';
}
?>