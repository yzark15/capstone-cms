<?php
// load common variables and functions
include_once('cap.includes/common.php');

// extract page name variable if it's set
$page=isset($_REQUEST['page']) ? $_REQUEST['page'] : '';

// extract page id variable if it's set
$id=isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : 0;

// if page id is not set, get current page id
if(!$id){
	// get page id from page name
	if($page){
		$r = Page::getInstanceByName($page);
		
		if($r && isset($r->id)){
			$id = $r->id;
		}
		unset($r);
	}
	// get page id from special field
	if(!$id){
		$special = 1;
		if(!$page){
			$r = Page::getInstanceBySpecial($special);
			if($r && isset($r->id)){
				$id = $r->id;
			}
			unset($r);
		}
	}
}

// load page data
if($id){
	$PAGEDATA = (isset($r) && $r) ? $r : Page::getInstance($id);
}else{
	echo 'Error page goes here';
	exit;
}
// set page content to use with templating engine
switch($PAGEDATA->type){
	case '0':
		$pagecontent = $PAGEDATA->render();		// grab page content
		break;
}
// set the page title for template
if($PAGEDATA->title != ''){
	$title = $PAGEDATA->title;
}else{
	$title = str_replace('www.','',$_SERVER['HTTP_HOST']) . ' > '.$PAGEDATA->name;
}
// set javascript and stylesheet links and metadata for template
$metadata = '<title>'.htmlspecialchars($title).'</title>';
$metadata .= '<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">';
$metadata .= '<script src="https://code.jquery.com/jquery-1.12.4.js"></script>'
				.'<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>';
$metadata .= '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';

// if there are keywords set keyword metadata
if($PAGEDATA->keywords){
	$metadata .= '<meta http-equiv="keywords" content="'.htmlspecialchars($PAGEDATA->keywords).'" />';
}
// if description data is set then set description metadata
if($PAGEDATA->description){
	$metadata .= '<meta http-equiv="description" content="'.htmlspecialchars($PAGEDATA->description).'"';
}

// Choose a template to display
if(file_exists(THEME_DIR.'/'.THEME.'/html/'.$PAGEDATA->template.'.html')){
	$template = THEME_DIR.'/'.THEME.'/html/'.$PAGEDATA->template.'.html';	// use database-defined template if it exists
}else if(file_exists(THEME_DIR.'/'.THEME.'/html/_default.html')){
	$template = THEME_DIR.'/'.THEME.'/html/_default.html';		// use _default template if it exists
}else{
	$d = array();
	$dir = new DirectoryIterator(THEME_DIR.'/'.THEME.'/html/');
	foreach($dir as $f){
		if($f->isDot()){
			continue;
		}
		$n = $f->getFilename();
		if(preg_match('/^inc\./',$n)){
			continue;
		}
		if(preg_match('/\.html$/',$n)){
			$d[] = preg_replace('/\.html$/','',$n);
		}
	}
	asort($d);
	$template = THEME_DIR.'/'.THEME.'/html/'.$d[0].'.html';	// use first template listed in theme directory
}
if($template == ''){
	die('no template created. please create a template first');
}

// set up smarty template engine
$smarty = smarty_setup('pages');
$smarty->template_dir = THEME_DIR.'/'.THEME.'/html/';

// set up template variables for display
$smarty->assign('PAGECONTENT',$pagecontent);
$smarty->assign('PAGEDATA',$PAGEDATA);
$smarty->assign('METADATA',$metadata);

// display template on the front end
header('Content-Type: text/html; Charset=utf-8');
$smarty->display($template);
?>