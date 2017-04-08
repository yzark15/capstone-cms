<?php
header('Content-type: text/html; Charset-utf-8');		// set HTTP header information
require 'admin_libs.php';		// check if user is an administrator
?>
<html>
	<head>
		<!-- javascript, google CDN and css file links -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
		<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
		<script src="/cap.javascript/ckeditor/ckeditor.js"></script>
		<script src="/cap.javascript/fileman/js/main.js"></script>
		<script src="/cap.javascript/remoteselectoptions.js"></script>
		<script src="/cap.admin/javascript/admin.js"></script>
		<script src="/cap.admin/login/login.js"></script>
		<link rel="stylesheet" type="text/css" href="/cap.admin/themes/admin.css" />
	</head>
	<body>
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
		<div id="wrapper">