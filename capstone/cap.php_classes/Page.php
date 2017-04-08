<?php
class Page{
	static $instances = array();
	static $instancesByName = array();
	static $instancesBySpecial = array();
	
	function __construct($v,$byField=0,$fromRow=0,$pvq=0){
		# byField: 0=type; 1=Name; 3=special
		
		if(!$byField && is_numeric($v)){
			
			if($fromRow){
				$r = $fromRow;
			}else{
				if($v){
					$r = dbRow("select * from pages where id=$v limit 1");
				}else{
					array();
				}
			}
		}
		else if($byField == 1){		// retrieve page by name
			$name = strtolower(str_replace('-','_',$v));
			$fname = 'page_by_name_'.md5($name);
			
			// retrieve page data from database
			$r = dbRow("select * from pages where name like '".addslashes($name)."' limit 1");
		}
		else if($byField == 3 && is_numeric($v)){
			$fname = 'page_by_special_'.$v;
			
			// retrieve page data from database
			$r = dbRow("select * from pages where special&$v limit 1");
		}
		else return false;
		
		// test if database query returned a result
		if(!count($r || !is_array($r))){
			return false;
		}
		
		// if page id is not set, set it to 0
		if(!isset($r['id'])){
			$r['id'] = 0;
		}
		// if page type is not set, set it to default 0
		if(!isset($r['type'])){
			$r['type'] = 0;
		}
		// if page special field is not set, set it to 0
		if(!isset($r['special'])){
			$r['special'] = 0;
		}
		// if page name is not set, update name field
		if(!isset($r['name'])){
			$r['name'] = 'NO NAME SUPPLIED';
		}
		// create page object
		foreach ($r as $k=>$v){
			$this->{$k} = $v;
		}
		$this->urlname = $r['name'];
		$this->dbVals = $r;
		
		// populate Page property arrays
		self::$instances[$this->id] =& $this;
		self::$instancesByName[preg_replace('/[^a-z0-9]/','-', strtolower($this->urlname))] =& $this;
		self::$instancesBySpecial[$this->special] =& $this;
		
		/*if(!$this->vars){
			$this->vars = '{}';
		}
		$this->vars = json_decode($this->vars);*/
	}// end of constructor
	
	function getInstance($id = 0, $fromRow = false, $pvq = false){
		
		// check to make sure id is numeric
		if(!is_numeric($id)){
			return false;
		}
		
		// check if page already exists, if so return page
		if(!@array_key_exists($id, self::$instances)){
			self::$instances[$id] = new Page($id, 0, $fromRow, $pvq);
		}
		
		return self::$instances[$id];
	}
	function getInstanceByName($name = ''){
		$name = strtolower($name);
		$nameIndex = preg_replace('#[^a-z0-9/]#','-',$name);

		// check if page already exists, if so return page
		if(@array_key_exists($nameIndex, self::$instancesByName)){
			return self::$instancesByName[$nameIndex];
		}
		// create page and store it in $instancesByName array 
		self::$instancesByName[$nameIndex] = new Page($name,1);
		return self::$instancesByName[$nameIndex];
	}
	function getInstanceBySpecial($sp = 0){
		
		// check if special passed parameter is numeric
		if(!is_numeric($sp)){
			return false;
		}
		
		// check if page already exists, if so return page
		if(!@array_key_exists($sp,$instancesBySpecial)){
			$instancesBySpecial[$sp] = new Page($sp,3);
		}
		return $instancesBySpecial[$sp];
	}
	// function to include parent in link url
	function getRelativeURL(){
		if(isset($this->relativeURL)){
			return $this->relativeURL;
		}
		$this->relativeURL = '';
		if($this->parent){
			$p = Page::getInstance($this->parent);
			if($p){
				$this->relativeURL .= $p->getRelativeURL();		// add parent to link URL
			}
		}
		$this->relativeURL .= '/'.$this->getURLSafeName();
		return $this->relativeURL;
	}
	// URL security function 
	function getURLSafeName(){
		if(isset($this->getURLSafeName)){
			return $this->getURLSafeName;
		}
		$r = $this->urlname;
		$r = preg_replace('/[^a-zA-Z0-9,-]/','-',$r);
		$this->getURLSafeName = $r;
		return $r;
	}
	// 
	function render(){
		$smarty = smarty_setup('pages');	// set up smarty 
		$smarty->compile_dir = SCRIPTBASE . '/cap.cache/pages';
		if(!file_exists(SCRIPTBASE.'/cap.cache/pages/template_'.$this->id)){	// check if page body exists 
			file_put_contents(SCRIPTBASE.'/cap.cache/pages/template_'.$this->id, $this->body);	// create page body
		}
		return $smarty->fetch(SCRIPTBASE.'/cap.cache/pages/template_'.$this->id);
	}
}
?>