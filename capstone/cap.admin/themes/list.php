<?php
// check to see if a theme was chosen
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'set_theme'){
	if(is_dir(THEME_DIR.'/'.$_REQUEST['theme'])){
		$DBVARS['theme'] = $_REQUEST['theme'];	// change theme
		config_rewrite();		// rewrite config.php with changed theme
		cache_clear('pages');	// clear cache for new theme
	}
}
$dir = new DirectoryIterator(THEME_DIR);
$themes_found = 0;

// list all the themes 
foreach($dir as $file){
	if($file->isDot()){
		continue;
	}
	if(!file_exists(THEME_DIR.'/'.$file.'/screenshot.png')){	// if theme doesn't have img, skip
		continue;
	}
	$themes_found++;
	echo '<div id="set-theme" style="';
	if($file == $DBVARS['theme']){		// if theme is the current theme, highlight it in darkgray
		echo 'background:darkgray;';
	}
	echo '"><form method="post" action="./themes.php">
			<input type="hidden" name="page" value="themes" />
			<input type="hidden" name="action" value="set_theme" />';
	echo '<input type="hidden" name="theme" value="'.htmlspecialchars($file).'" />';
	$size = getimagesize('../cap.templates/'.$file.'/screenshot.png');
	$w = $size[0];
	$h = $size[1];
	if($w > 240){
		$w = $w*(240/$w);
		$h = $h*(240/$w);
	}
	if($h > 172){
		$w = $w*(172/$h);
		$h = $h*(172/$h);
	}
	echo '<img src="/cap.templates/'.htmlspecialchars($file).'/screenshot.png" width="'.
			(floor($w)).'" height="'.(floor($h)).'" /><br />';
	echo '<strong>'.htmlspecialchars($file).'</strong><br />';
	echo '<input type="submit" value="set theme" /></form></div>';
}
if($themes_found == 0){
	echo '<em>No themes found. Create a theme and place it into the /cap.templates directory.</em>';
}
?>