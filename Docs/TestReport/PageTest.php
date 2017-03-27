<?php
use PHPUnit\Framework\TestCase;
include 'c:/xampp/htdocs/capstone/cap.includes/common.php';
include 'c:/xampp/htdocs/capstone/cap.php_classes/Page.php';
include 'c:/xampp/htdocs/capstone/.private/config.php';

class PageTest extends TestCase{
	
	public function testGetInstance(){
		// create a page object to test getInstance method
		// test page created for testing with id 19
		$page = Page::getInstance(19);
		
		// testing test page id
		$this->assertEquals(19, $page->id);
		
		// testing test page name
		$this->assertEquals('test page', $page->name);
		
		// testing test page parent
		$this->assertEquals('0', $page->parent);
		
		// testing test page ord
		$this->assertEquals('0', $page->ord);
		
		// testing test page cdate
		$this->assertEquals('2017-03-07 12:49:17', $page->cdate);
		
		// testing test page special
		$this->assertEquals('0', $page->special);
		
		// testing test page edate
		$this->assertEquals('2017-03-19 12:12:21', $page->edate);
		
		// testing test page title
		$this->assertEquals('', $page->title);
		
		// testing test page template
		$this->assertEquals(NULL, $page->template);
		
		// testing test page type
		$this->assertEquals('0', $page->type);
		
		// testing test page keywords
		$this->assertEquals('', $page->keywords);
		
		// testing test page description
		$this->assertEquals('', $page->description);
		
		// testing test page associated_date
		$this->assertEquals('2017-03-07', $page->associated_date);
		
		// testing test page vars
		$this->assertEquals('{"order_of_sub_pages":"0","order_of_sub_pages_dir":"0"}', $page->vars);
		
		// testing test page urlname
		$this->assertEquals('test page', $page->urlname);
	}
	
	public function testGetInstanceByName(){
		// create a page object to test getInstanceByName method
		// test page created for testing with name "test page"
		$page = Page::getInstanceByName("test page");
		
		// testing test page id
		$this->assertEquals(19, $page->id);
		
		// testing test page name
		$this->assertEquals('test page', $page->name);
		
		// testing test page parent
		$this->assertEquals('0', $page->parent);
		
		// testing test page ord
		$this->assertEquals('0', $page->ord);
		
		// testing test page cdate
		$this->assertEquals('2017-03-07 12:49:17', $page->cdate);
		
		// testing test page special
		$this->assertEquals('0', $page->special);
		
		// testing test page edate
		$this->assertEquals('2017-03-19 12:12:21', $page->edate);
		
		// testing test page title
		$this->assertEquals('', $page->title);
		
		// testing test page template
		$this->assertEquals(NULL, $page->template);
		
		// testing test page type
		$this->assertEquals('0', $page->type);
		
		// testing test page keywords
		$this->assertEquals('', $page->keywords);
		
		// testing test page description
		$this->assertEquals('', $page->description);
		
		// testing test page associated_date
		$this->assertEquals('2017-03-07', $page->associated_date);
		
		// testing test page vars
		$this->assertEquals('{"order_of_sub_pages":"0","order_of_sub_pages_dir":"0"}', $page->vars);
		
		// testing test page urlname
		$this->assertEquals('test page', $page->urlname);
	}
	
	public function testGetInstanceBySpecial(){
		// create a page object to test getInstanceBySpecial method
		// home page with special = 1 used for testing
		$page = Page::getInstanceBySpecial(1);
		
		// testing test page id
		$this->assertEquals(1, $page->id);
		
		// testing test page name
		$this->assertEquals('Home', $page->name);
		
		// testing test page parent
		$this->assertEquals('0', $page->parent);
		
		// testing test page ord
		$this->assertEquals('0', $page->ord);
		
		// testing test page cdate
		$this->assertEquals(NULL, $page->cdate);
		
		// testing test page special
		$this->assertEquals('1', $page->special);
		
		// testing test page edate
		$this->assertEquals(NULL, $page->edate);
		
		// testing test page title
		$this->assertEquals('', $page->title);
		
		// testing test page template
		$this->assertEquals(NULL, $page->template);
		
		// testing test page type
		$this->assertEquals('0', $page->type);
		
		// testing test page keywords
		$this->assertEquals('', $page->keywords);
		
		// testing test page description
		$this->assertEquals('', $page->description);
		
		// testing test page associated_date
		$this->assertEquals(NULL, $page->associated_date);
		
		// testing test page vars
		$this->assertEquals(NULL, $page->vars);
		
		// testing test page urlname
		$this->assertEquals('Home', $page->urlname);
	}
	
	public function testGetRelativeURL(){
		// create a child page to test getRelativeURL method
		$page = Page::getInstanceByName('Second Page');
		
		// test getRelativeURL() method
		$this->assertEquals('/Home/Second-Page', $page->getRelativeURL());
		
		// create a child page to test getRelativeURL method
		$page1 = Page::getInstanceByName('testChild0');
		
		// test getRelativeURL() method
		$this->assertEquals('/main-page/testChild0', $page1->getRelativeURL());
		
		// create a child page to test getRelativeURL method
		$page2 = Page::getInstanceByName('testChild1');
		
		// test getRelativeURL() method
		$this->assertEquals('/my-page/testChild1', $page2->getRelativeURL());
		
		// create a child page to test getRelativeURL method
		$page3 = Page::getInstanceByName('testChild2');
		
		// test getRelativeURL() method
		$this->assertEquals('/test-page/testChild2', $page3->getRelativeURL());
	}
	
	public function testGetURLSafeName(){
		// create a page with a space in the page name to test getURLSafeName() method
		$page = Page::getInstanceByName('Second Page');
		
		// test getURLSafeName() method
		$this->assertEquals('Second-Page', $page->getURLSafeName());
	}
}
?>