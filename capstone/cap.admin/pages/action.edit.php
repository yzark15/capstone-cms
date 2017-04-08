<?php
// function to check for duplicate page names
function pages_setup_name($id,$pid){
	$name = trim($_REQUEST['name']);
	
	$result = dbOne('select id from pages where name="'.addslashes($name).'" 
				and parent="'.$pid.'" and id !='.$id,'id');
	
	if(isset($result)){
		$i = 2;
		
		// search for a valid page name
		while(dbOne('select id from pages where 
						name="'.addslashes($name.$i).'" and parent='.$pid.' 
						and id!='.$id,'id')){
			$i++;
		}
		// notify user of page name amendment
		echo '<em>A page named "'.htmlspecialchars($name).'" already exists.
				Page name amended to "'.htmlspecialchars($name.$i).'".</em>';
		$name = $name.$i;
	}
	return $name;		// return alternate page name
}
// build up the specials field according special form input selections for insertion into db
function pages_setup_specials($id = 0){
	$special = 0;
	
	if(isset($_REQUEST['special'])){
		$specials = $_REQUEST['special'];
	}else{
		$specials = array();
	}
	
	foreach($specials as $a=>$b){
		$special += pow(2,$a);
	}
	
	$homes = dbOne("select count(id) as ids from pages where (special&1) and id !=$id",'ids');
	
	if($special&1){
		if($homes != 0){
			dbQuery("update pages set special=special-1 where special&1");
		}
	}else{
		if($homes == 0){
			$special += 1;
			echo '<em>This page has been marked as the site\'s Home Page,
					because there must always be one.</em>';
		}
	}
	return $special;
}

// setup variables for page insert or update variables
if(isset($_REQUEST['id'])){
	$id = (int)$_REQUEST['id'];
}else{
	$id = -1;
}
$pid = (int)$_REQUEST['parent'];
if(isset($_REQUEST['keywords'])) $keywords = $_REQUEST['keywords'];
if(isset($_REQUEST['description'])) $description = $_REQUEST['description'];
$associated_date = $_REQUEST['associated_date'];
if(isset($_REQUEST['title'])) $title = $_REQUEST['title'];
if(isset($_REQUEST['body'])) $body = str_replace('&quot;','"',$_REQUEST['body']);
$name = pages_setup_name($id,$pid);
if(isset($_REQUEST['id'])){
	$special = pages_setup_specials($id);
} else{
	$special = 0;
}
if(isset($_REQUEST['page_vars'])){
	$vars = json_encode($_REQUEST['page_vars']);
}else{
	$vars = '[]';
}

// build up sql query for page details insertion or update
$q = 'edate=now()';
if(isset($_REQUEST['template'])) $q .= ',template="'.addslashes($_REQUEST['template']).'"';
$q .= ',type="'.$_REQUEST['type'].'"';
$q .= ',associated_date="'.addslashes($associated_date).'"';
if(isset($_REQUEST['keywords'])) $q .= ',keywords="'.addslashes($keywords).'"';
if(isset($_REQUEST['description'])) $q .= ',description="'.addslashes($description).'"';
$q .= ',name="'.addslashes($name).'"';
if(isset($_REQUEST['title'])) $q .= ',title="'.addslashes($title).'"';
if(isset($_REQUEST['body'])) $q .= ',body="'.addslashes($body).'"';
$q .= ',parent="'.$pid.'"';
$q .= ',special="'.$special.'"';
$q .= ',vars="'.addslashes($vars).'"';

if($_REQUEST['action'] == 'Update Page Details'){
	$q = "update pages set $q where id=$id";
	dbQuery($q);		// update page details
}else{
	$q = "insert into pages set cdate=now(),$q";
	dbQuery($q);		// insert new page details
	$_REQUEST['id'] = dbLastInsertedId();	// set id to display page in page editor
}
echo '<em>Page Saved</em>';
cache_clear('pages');	// clear cache everytime a page is changed
?>