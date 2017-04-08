<?php
require '../admin_libs.php';

// script to hand page menu tree drag and drop event

$id = (int)$_REQUEST['id'];		// grab page id
$to = (int)$_REQUEST['parent_id'];	// grab new parent id
$order = explode(',',$_REQUEST['order']);	// grab page order of child pages of new parent

dbQuery('update pages set parent='.$to.' where id='.$id);	// update page with new parent page 

// create new page order for parent page
for($i=0; $i<count($order); ++$i){
	$pid=(int)$order[$i];
	dbQuery("update pages set ord=$i where id=$pid");
	echo "update pages set ord=$i where id=$pid\n";
}
?>