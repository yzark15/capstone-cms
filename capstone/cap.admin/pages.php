<?php
// load headers for Page Management System
require 'header.php';
echo '<h1>Page Administration</h1>';

// perform proper database action (CRUD)
if(isset($_REQUEST['action'])){
	if($_REQUEST['action'] == 'Update Page Details' || $_REQUEST['action'] == 'Insert Page Details'){
		require 'pages/action.edit.php'; 	// call update or insert page details script
	}else if($_REQUEST['action'] == 'delete'){
		'pages/action.delete.php';		// call delete page script
	}
}

// load lhs page tree menu
echo '<div class="left-menu">';
require 'pages/menu.php';	// create lhs page menu tree
echo '</div>';

// load page editor forms 
echo '<div class="has-left-menu">';
require 'pages/forms.php';		// load page editor 
echo '</div>';

require 'footer.php';	// load footer
?>