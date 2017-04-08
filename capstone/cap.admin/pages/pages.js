$(function(){
	$('.tabs').tabs();	// set up page editor tabs in forms.php
	
	// get the rest of the pages for parent select box
	$('#pages_form select[name=parent]').remoteselectoptions({
		url:'/cap.admin/pages/get_parents.php',
		other_GET_params:currentpageid
	});
});