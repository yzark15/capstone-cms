<?php
// extract page id
if(isset($_REQUEST['id'])){
	$id = (int) $_REQUEST['id'];
}else{
	$id = 0;
}

// return page corresponding to id 
if($id){
	$page = dbRow("SELECT * FROM pages WHERE id=$id");
	
	if($page !== false){
		$page_vars = json_decode($page['vars'],true);
		$edit = true;
	}
}
// obtain default form data if no page id is selected for editing
if(!isset($edit)){
	if(isset($_REQUEST['parent'])){
		$parent = (int) $_REQUEST['parent'];
	}else{
		$parent = 0;
	}
	$special = 0;
	
	if(isset($_REQUEST['hidden'])){
		$special += 2;
	}
	$page = array('parent'=>$parent,'type'=>'0','body'=>'','name'=>'','title'=>'','ord'=>0,
				'description'=>'','id'=>0,'keywords'=>'','special'=>$special,'template'=>'');
				
	$page_vars = array();
	$id = 0;
	$edit = false;
}
// display message if page is not displayed in front end menu
if($page['special']&2){
	echo '<em>NOTE: this page is currently hidden from the front-end navigation.
			Use the "Advanced Options" to un-hide it.</em>';
}
// create page editor form loaded with page or default data
echo '<form id="pages-form" method="post">';
echo '<input type="hidden" name="id" value="'.$id.'" />';

echo '<div class="tabs"><ul>';		// create hook for jqueryui tabs plugin
echo '<li><a href="#tabs-common-details">Common Details</a></li>';	// create common-details tab 
echo '<li><a href="#tabs-advanced-options">Advanced Options</a></li></ul>'; 	// create advanced-options tab 

// Create common-details form 
echo '<div id="tabs-common-details"><table style="clear:right;width:100%;"><tr>';
echo '<th width="5%">name</th><td width="23%">';
echo '<input id="name" name="name" value="'.htmlspecialchars($page['name']).'" /></td>';
echo '<th width="10%">title</th><td width="23%">';
echo '<input name="title" value="'.htmlspecialchars($page['title']).'" /></td>';
echo '<th colspan="2">';
if($edit){
	$u = '/'.str_replace(' ','-',$page['name']);
	echo '<a style="font-weight:bold;color:red" href="'.$u.'" target="_blank">VIEW PAGE</a>';
}else{
	echo '&nbsp;';
}
echo '</th></tr>';
echo '<tr><th>type</th><td><select name="type">';
echo '<option value="0">normal</option>';
echo '</select></td>';

echo '<th>parent</th><td><select name="parent">';
if($page['parent']){
	$parent = Page::getInstance($page['parent']);
	//echo '<option value="'.$parent->id.'">'.htmlspecialchars($parent->name).'</option>';
	echo '<option value="'.$parent->id.'">'.htmlspecialchars($parent->name).'</option>';
}else{
	echo '<option value="0"> -- '.'none'.' -- </option>';
	$q = dbAll('select name, id from pages');
	foreach($q as $r){
		echo '<option value="'.$r['id'].'">'.$r['name'].'</option>';
	}
}
echo '</select>'."\n\n".'</td>';
if(!isset($page['associated_date']) || !preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/',$page['associated_date'])
		|| $page['associated_date'] == '0000-00-00'){
	$page['associated_date'] = date('Y-m-d');
}
echo '<th>Associated Date</th>';
echo '<td><input name="associated_date" class="date-human" value="'.$page['associated_date'].'" /></td></tr>';
echo '<tr><th>body</th><td colspan="5">';
echo ckeditor('body',$page['body']);	// create web page content editor
echo '</td></tr></table></div>';

//Create advanced-options form 
echo '<div id="tabs-advanced-options">';
echo '<table><tr><td>';
echo '<h4>MetaData</h4><table>';
echo '<tr><th>keywords</th><td>';
echo '<input name="keywords" value="'.htmlspecialchars($page['keywords']).'" />';
echo '</td></tr>';
echo '<tr><th>description</th><td>';
echo '<input name="description" value="'.htmlspecialchars($page['description']).'" />';
echo '</td></tr>';

// create template selection drop down
echo '<tr><th>template</th><td>';
$d = array();
if(!file_exists(THEME_DIR.'/'.THEME.'/html/')){
	echo 'SELECTED THEME DOES NOT EXIST<br />Please
			<a href"/cap.admin/siteoptions.php?page=themes">select a theme</a>';
}else{
	$dir = new DirectoryIterator(THEME_DIR.'/'.THEME.'/html/');
	foreach($dir as $f){
		if($f->isDot()){
			continue;
		}
		$n = $f->getFilename();
		if(preg_match('/\.html$/',$n)){
			$d[] = preg_replace('/\.html$/','',$n);
		}
	}
	asort($d);
	if(count($d) > 1){
		echo '<select name="template">';
		foreach($d as $name){
			echo '<option ';
			if($name == $page['template']){
				echo ' selected="selected"';
			}
			echo '>'.$name.'</option>';
		}
		echo '</select>';
	}else{
		echo 'no options available';
	}
}
echo '</td></tr>';

echo '</table></td>';
echo '<td><h4>Special</h4>';

$specials = array('Is Home Page','Does not appear in navigation');

for($i = 0; $i < count($specials); ++$i){
	if($specials[$i] != ''){
		echo '<input type="checkbox" name="'.$specials[$i].'"';
		
		if($page['special']&pow(2,$i)){
			echo ' checked="checked"';
		}
		echo ' />'.$specials[$i].'</input><br />';
	}
}

echo '<h4>Other</h4>';
echo '<table>';
echo '<tr><th>Order of sub-pages</th><td><select name="page_vars[order_of_sub_pages]">';
$arr = array('as shown in admin menu','alphabetically','by associated_date');

foreach($arr as $k => $v){
	echo '<option value="'.$k.'"';
	if(isset($page_vars['order_of_sub_pages']) && $page_vars['order_of_sub_pages'] == $k){
		echo ' selected="selected"';
	}
	echo '>'.$v.'</option>';
}

echo '</select>';
echo '<select name="page_vars[order_of_sub_pages_dir]">';
echo '<option value="0">ascending (a-z, 0-9)</option>';
echo '<option value="1"';
if(isset($page_vars['order_of_sub_pages_dir']) && page_vars['order_of_sub_pages_dir'] == '1'){
	echo ' selected="selected"';
}
echo '>descending (z-a, 0-9)</option></select></td></tr>';
echo '</table>';
echo '</td></tr></table></div>';

// Create form submission button for updating and inserting page data
echo '</div><input type="submit" name="action" value="';
	if($edit){
		echo 'Update Page Details';
	}else{
		echo 'Insert Page Details';
	}
	echo '" /></form>';
	echo '<script>window.currentpageid='.$id.';</script>';
	echo '<script src="/cap.admin/pages/pages.js"></script>';
?>
