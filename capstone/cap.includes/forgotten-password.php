<?php
require 'login-libs.php';

login_check_is_email_provided();

// check that a verification code was provided
if(!isset($_REQUEST['verification_code']) || $_REQUEST['verification_code'] == ''){
	login_redirect($url, 'novalidation');
}

// check the email/verification code matches a row in user table
$password=md5($_REQUEST['email'].'|'.$_REQUEST['password']);
$result=dbRow('
	select * from user_accounts where
	email="'.addslashes($_REQUEST['email']).'" and
	verification_code="'.$_REQUEST['verification_code'].'" and active
');
if($result == false){
	login_redirect($url, 'validationfailed');
}

// set the session variable, clear code from database, redirect
dbQuery('
	update user_accounts set verification_code="" where
	email="'.addslashes($_REQUEST['email']).'"
');
$_SESSION['userdata']=$result;
$groups=json_decode($result['groups']);
$_SESSION['userdata']['groups']=array();
foreach($groups as $g){
	$_SESSION['userdata']['groups']['g']=true;
}
if($result['extras']==''){
	$result['extras']='[]';
}
$_SESSION['userdata']['extras']=json_decode($result['extras']);
login_redirect($url, 'verified');
?>