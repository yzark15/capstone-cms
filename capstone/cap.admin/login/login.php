<!DOCTYPE html>

<html>
	<head>
		<!-- javascript, google CDN and css file links -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
		<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
		
		<script src="/cap.admin/javascript/admin.js"></script>
		<script src="/cap.admin/login/login.js"></script>
		<link rel="stylesheet" type="text/css" href="/cap.admin/themes/admin.css" />
	</head>
	<body>
		<div id="login-wrapper">	<!-- layout wrapper div -->
			<div id="header">
				<h1>CMS Administration</h1>
				<div id="menu-top">		<!-- create administration menu -->
					<ul>
						
						<li><a href="/cap.admin/pages.php">Pages</a></li>
						<li><a href="/cap.admin/users.php">Users</a></li>
						<li><a href="/cap.admin/themes.php">Themes</a></li>
						<li><a href="/cap.includes/logout.php?redirect=/cap.admin/">Log Out</a></li>
					</ul>
				</div>
			</div>
		
<?php

if(isset($_REQUEST['login_msg'])){
	require SCRIPTBASE . 'cap.includes/login-codes.php';
	
	$login_msg = $_REQUEST['login_msg'];
	// display various login feedback depending on the login message
	if(isset($login_msg_codes[$login_msg])){
		echo '<script>$(function(){$("<strong>' . htmlspecialchars($login_msg_codes[$login_msg]) . '</strong>").dialog({modal:true});});</script>';
	}
}
?>
			
			<div class="tabs">		<!-- create div for jquery ui tabs plugin, see login.js -->
				<!-- login menu -->
				<ul>
					<li><a href="#tab1">Login</a></li>
					<li><a href="#tab2">Forgotten Password</a></li>
				</ul>
				
				<!-- login tab div -->
				<div id="tab1">
					
					<!-- login form -->
					<form method="post" action="/cap.includes/login.php?redirect=<?php echo $_SERVER['PHP_SELF']; ?>">
						<table>
							<tr>
								<th>email</th>
								<td><input type="email" name="email" value=""/></td>
							</tr>
							<tr>
								<th>password</th>
								<td><input type="password" name="password" value=""/></td>
							</tr>
							<tr>
								<th colspan="2" align="right">
									<input type="submit" name="action" value="login" class="login"/>
								</th>
							</tr>
						</table>
					</form>	<!-- close login form -->
				</div>	<!-- close tab1 div -->
				
				<!-- recover password tab div -->
				<div id="tab2">
				
					<!-- forgotten password form -->
					<form method="post" action="/cap.includes/password-reminder.php?redirect=<?php echo $_SERVER['PHP_SELF']; ?>">
						<table>
							<tr>
								<th>email</th>
								<td><input id="email" type="text" name="email" /></td>
							</tr>
							<tr>
								<th colspan="2" align="right">
									<input name="action" type="submit" value="resend my password" class="login" />
								</th>
							</tr>
						</table>
					</form>	<!-- close forgotten-password form -->
				</div>	<!-- close tab2 div -->
			</div>	<!-- close tabs div -->
		</div>	<!-- close layout wrapper -->
	</body>
</html>