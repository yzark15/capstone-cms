<?php
require dirname(__FILE__).'/basics.php';

require_once SCRIPTBASE . 'cap.includes/Smarty-2.6.30/libs/Smarty.class.php';
//require_once 'c:/xampp/htdocs/capstone/cap.includes/Smarty-2.6.30/libs/Smarty.class.php';

// 
function smarty_setup($cdir){
	$smarty = new Smarty;	// create new Smarty object
	
	// create a compile directory
	if(!file_exists(SCRIPTBASE.'cap.cache/'.$cdir)){
		if(!mkdir(SCRIPTBASE.'cap.cache/'.$cdir)){
			die(SCRIPTBASE.'cap.cache/'.$cdir.' not created.<br />
				please make sure that '.USERBASE.'cap.cache is writable by the web-server');
		}
	}
	$smarty->compile_dir = SCRIPTBASE.'cap.cache/'.$cdir;	// set smarty compile_dir variable
	$smarty->left_delimiter = '{{';
	$smarty->right_delimiter = '}}';
	
	$smarty->register_function('MENU','menu_show_fg');	// register menu_show_fg function with MENU tag
	
	return $smarty;
}

function menu_show_fg($opts){
	$c = '';
	$c .= '<script src="/cap.javascript/menu.js"></script>';
	
	// set default values for options
	$options = array(
		'direction' => 0,
		'parent' => 0,
		'background' => '',
		'columns' => 1,
		'opacity' => 0
	);
	
	// override default values with values from $opts
	foreach($opts as $k => $v){
		if(isset($options[$k])){
			$options[$k] = $v;
		}
	}
	if(!is_numeric($options['parent'])){
		$r = Page::getInstanceByName($options['parent']);
		if($r){
			$options['parent'] = $r->id;
		}
	}
	if(is_numeric($options['direction'])){
		if($options['direction'] == '0'){
			$options['direction'] = 'horizontal';
		}else{
			$options['direction'] = 'vertical';
		}
	}
	
	// build front end menu 
	$menuid = $GLOBALS['fg_menus']++;
	$c .= '<div class="menu_fg menu-fg-'.$options['direction'].'" id="menu-fg-'.$menuid.'">'
			.menu_build_fg($options['parent'],0,$options).'</div>';
	return $c;
}
$fg_menus = 0;

// function to build menu <ul> tree
function menu_build_fg($parentid,$depth,$options){
	$PARENTDATA = Page::getInstance($parentid);		// get data about the parent page
	$order = 'ord,name';
	
	// figure out sorting order for parents subpages
	if(isset($PARENTDATA->vars->order_of_sub_pages)){
		switch($PARENTDATA->vars->order_of_sub_pages){
			case 1:
				$order = 'name';
				if($PARENTDATA->vars->order_of_sub_pages_dir){
					$order .= ' desc';
				}
				break;
			case 2:
				$order = 'associated_date';
				if($PARENTDATA->vars->order_of_sub_pages_dir){
					$order .= ',name';
				}
				break;
			case 3:
				$order = 'ord';
				if($PARENTDATA->vars->order_of_sub_pages_dir){
					$order .= ',name';
				}
				break;
		}
	}
	$rs = dbAll("select id, name, type from pages where parent='"
			.$parentid."' and !(special&2) order by $order");
			
	// if no pages found return empty string
	if($rs === false || !count($rs)){
		return '';
	}
	
	// build a list of links with db result set
	$items = array();
	foreach($rs as $r){
		$item = '<li>';
		$page = Page::getInstance($r['id']);
		$item .= '<a href="'.$page->getRelativeUrl().'">'.htmlspecialchars($page->name).'</a>';
		//$item .= '<a href="'.htmlspecialchars($page->name).'">'.htmlspecialchars($page->name).'</a>';
		$item .= menu_build_fg($r['id'],$depth+1,$options);	// recursively build next level of child pages
		$item .= '</li>';
		$items[] = $item;
	}
	$options['columns'] = (int)$options['columns'];
	
	if(!$depth){
		return '<ul id="site-menu">'.join('',$items).'</ul>';
	}
	
	// return sub menu
	if($options['columns'] < 2){
		return '<ul>'.join('',$items).'</ul>';
	}
	
	$items_count = count($items);
	$items_per_column = ceil($items_count/$options['columns']);
	$c = '<table><tr><td><ul>';
	for($i = 1;$i < $items_count+1;++$i){
		$c .= $items[$i-1];
		if($i != $items_count && !($i % $items_per_column)){
			$c .= '</ul></td><td><ul>';
		}
	}
	$c .= '</ul></td></tr></table>';
	return $c;
}
?>