<!-- load stylesheet lhs page tree menu -->
<link rel="stylesheet" type="text/css" href="/cap.javascript/vakata-jstree-9770c67/dist/themes/default/style.css">

<script src="/cap.javascript/vakata-jstree-9770c67/dist/jstree.js"></script><!-- load plugin to create page tree menu -->
<script src="/cap.javascript/vakata-jstree-9770c67/src/jstree.contextmenu.js"></script><!-- load plugin to create context menus -->
<script src="/cap.admin/pages/menu.js"></script><!-- load javascript for lhs page tree menu -->
<?php
echo '<div id="pages-wrapper">';
$rs = dbAll('select id, type, name, parent from pages order by ord, name'); // retrieve pages from the database
$pages=array(); // create array for parent page data

// retrieve parent page data
foreach($rs as $r){
	if(!isset($pages[$r['parent']])){
		$pages[$r['parent']]=array();
	}
	$pages[$r['parent']][] = $r;
}
// function for constructing a recursive menu tree
function show_pages($id,$pages){
	if(!isset($pages[$id])){
		return;
	}
	echo '<ul>';
	foreach($pages[$id] as $page){
		echo '<li id="page_'.$page['id'].'">';
		echo '<a href="pages.php?id='.$page['id'].'">';
		echo htmlspecialchars($page['name']);
		echo '</a>';
		show_pages($page['id'],$pages);
		echo '</li>';
	}
	echo '</ul>';
}
show_pages(0,$pages); // create a menu from the top level pages down
echo '</div><br>';
?>
