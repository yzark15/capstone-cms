<?php
// start a session to record data to be used by all pages
session_start();

// load classes
function __autoload($name){
	require $name . '.php';
}

// initialize database for duration of script
function dbInit(){
	// return global database PDO if already set
	if(isset($GLOBALS['db'])){
		return $GLOBALS['db'];
	}
	global $DBVARS;  // make $DBVARS a global variable
	
	// create and initialize PDO object for database functionality
	$db = new PDO(
		'mysql:host='.$DBVARS['hostname'].';
		dbname='.$DBVARS['db_name'],
		$DBVARS['username'],
		$DBVARS['password']
	);
	
	$db->query('SET NAMES utf8');	// set the character set
	$db->num_queries = 0;
	$GLOBALS['db'] = $db;	// set global db variable
	return $db;
}
// function to query the database
function dbQuery($query){
	$db = dbInit();		// initialize the database 
	$q = $db->query($query);	// query the database 
	$db->num_queries++;		
	return $q;		// return query results 
}

function dbRow($query){
	$q = dbQuery($query);
	return $q->fetch(PDO::FETCH_ASSOC);
}

// db function for building and returning an array of results
function dbAll($query,$key=''){
	$q = dbQuery($query);
	$results = array();
	while($r = $q->fetch(PDO::FETCH_ASSOC)){
		$results[] = $r;
	}
	if(!$key){
		return $results;
	}
	$arr=array();
	
	foreach($results as $r){
		$arr[$r[$key]] = $r;
	}
	return $arr;
}

// db function for returning a single record
function dbOne($query, $field=''){
	$r = dbRow($query);
	return $r[$field];
}

function dbLastInsertedId(){
	return dbOne('select last_insert_id() as id','id');
}

// define constant for web root
define('SCRIPTBASE', $_SERVER['DOCUMENT_ROOT']);
require SCRIPTBASE . '.private/config.php';
//require 'c:/xampp/htdocs/capstone/.private/config.php';

// define a constant for config file
if(!defined('CONFIG_FILE')){
	define('CONFIG_FILE', SCRIPTBASE . '.private/config.php');
}

// include php classes for duration of script
set_include_path(SCRIPTBASE.'cap.php_classes');

// set THEME_DIR constant if $DBVARS['theme_dir'] is set
if(isset($DBVARS['theme_dir'])){
	define('THEME_DIR',$DBVARS['theme_dir']);
}else{
	define('THEME_DIR',SCRIPTBASE.'cap.templates');		// otherwise set THEME_DIR to templated directory
}
// set THEME constant 
if(isset($DBVARS['theme']) && $DBVARS['theme']){
	define('THEME',$DBVARS['theme']);
}else{
	$dir = new DirectoryIterator(THEME_DIR);
	$DBVARS['theme'] = '.default';
	foreach($dir as $file){
		if($file->isDot()){
			continue;
		}
		$DBVARS['theme'] = $file->getFilename();
		break;
	}
	define('THEME',$DBVARS['theme']);
}
// rewrite $DBVARS array with changed template
function config_rewrite(){
	global $DBVARS;
	$tmparr = $DBVARS;
	$tmparr2 = array();
	foreach($tmparr as $name => $val){
		$tmparr2[] = '\''.addslashes($name).'\'=>\''.addslashes($val).'\'';
	}
	
	$config = "<?php\n\$DBVARS=array(\n ".join(",\n ",$tmparr2)."\n);";
	file_put_contents(CONFIG_FILE,$config); 	// rewrite config.php $DBVARS array with new theme
}
// function to clear the cache whenever a new theme is chosen
function cache_clear($type){
	if(!is_dir(SCRIPTBASE.'/cap.cache/'.$type)){
		return;
	}
	$d = new DirectoryIterator(SCRIPTBASE.'/cap.cache/'.$type);
	foreach($d as $f){
		$f = $f->getFilename();
		if($f == '.' || $f == '..'){
			continue;
		}
		unlink(SCRIPTBASE.'/cap.cache/'.$type.'/'.$f);
	}
}
?>