<?php
// set up User Management headers
require 'header.php';
echo '<h1>User Management</h1>';

// create administrative menu 
echo '<div class="left-menu" id="left-menu">';
echo '<ul>';
echo '<li><a href="/cap.admin/pages.php">Pages</a></li>';
echo '<li><a href="/cap.admin/users.php">Users</a></li>';
echo '<li><a href="/cap.admin/themes.php">Themes</a></li>';
echo '</ul>';
echo '</div>';

echo '<div class="has-left-menu" id="user-table">';

// perform actions pertaining to users (CRUD)
if(isset($_REQUEST['action'])){
	require 'users/actions.php';
}
// load form for editing or creating users
if(isset($_REQUEST['id'])){
	require 'users/form.php';
}
// list current users
require 'users/list.php';
echo '</div>';

echo '<script src="/cap.admin/users/users.js"></script>';
require 'footer.php';
?>