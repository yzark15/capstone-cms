<?php
require '../admin_libs.php';

function page_show_pagenames($i=0,$n=1,$s=0,$id=0){
	// get all pages eligible to be a parent page
	$q = dbAll('select name, id from pages where parent="'.$i.'" and id != "'.$id.'" order by ord, name');
	
	// if there are no parent options return
	if(count($q) < 1){
		return;
	}
	foreach($q as $r){		// load potential parents into select box
		if($r['id'] != ''){
			echo '<option value="'.$r['id'].'" title="'.htmlspecialchars($r['name']).'"';
			if($s == $r['id']){
				echo ' selected="selected">';
			}else{
				echo '>';
			}
			for($j = 0; $j < $n; $j++){
				echo '&nbsp;';
			}
			$name = $r['name'];
			if(strlen($name) > 20){
				$name = substr($name, 0, 17).'...';
			}
			echo htmlspecialchars($name).'</option>';
			page_show_pagenames($r['id'],$n+1,$s,$id);		// recursively load child pages into select box
		}
	}
}

if(isset($_REQUEST['selected'])){
	$selected = $_REQUEST['selected']
}else{
	$selected = 0;
}
if(isset($_REQUEST['other_GET_params'])){
	$id = (int)$_REQUEST['other_GET_params']
}else{
	$id = -1;
}
echo '<option value="0"> -- none -- </option>';
page_show_pagenames(0,0,$selected,$id);		// load potential parents into select box
show_pages(0,$pages);		// build new page tree menu
?>