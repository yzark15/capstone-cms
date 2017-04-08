<?php
require 'login-libs.php';

// check that email input is provided
login_check_is_email_provided();

// check for valid email
$result=dbRow('
	select email from user_accounts where
		email="'.addslashes($_REQUEST['email']).'" and active
');
if($result==false){
	login_redirect($url, 'nosuchemail');
}

// generate a validation email, 
$validation_code=md5(time().'|'.$result['email']);
$email_domain=preg_replace('/^www\./','',$_SERVER['HTTP_HOST']);
dbQuery('
	update user_accounts set activation_key="'.$validation_code.'"
	where email="'.addslashes($result['email']).'"
');
$validation_url='http://'.$_SERVER['HTTP_HOST'].'/cap.includes/forgotten-password.php?
	verification_code='.$validation_code.'&email='.$result['email'].'&redirect_url='.$url;

// set up web server email 
ini_set("SMTP","ssl://smtphm.sympatico.ca");
ini_set("smtp_port","443");

// send a validation email 
mail(
	$result['email'],
	"[$email_domain] forgotten password",
	"Hello!\n\nThe forgotten password form at http://".$_SERVER['HTTP_HOST']
	."/ was submitted. If you did not do this, you can safely discard this email.
	\n\nTo log into your account, please use the link below, and then reset your password.
	\n\n$validation_url",
	"From: no-reply@$email_domain\nReply-to: no-reply@$email_domain"
);
login_redirect($url,'validationsent');
?>