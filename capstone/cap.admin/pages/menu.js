$(function(){
	// load from the menu the selected page into the page editor
	$('#pages-wrapper').on('select_node.jstree',function(e, data){
		document.location='pages.php?action=edit&id='+data.node.id.replace(/.*_/,'');
	}).on('move_node.jstree',function(e,data){ // configure drag and drop events for moving pages around 
		var p = data.parent;
		var new_order = [];
		var nodes = data.new_instance._model.data[p].children;
		
		for(var i = 0; i < nodes.length; ++i){
			new_order.push(nodes[i]);
		}
		$.getJSON('/cap.admin/pages/move_page.php?id='
			+data.node.id.replace(/.*_/,'')+'&parent_id='
			+(p==-1?0:p.replace(/.*_/,''))
			+'&order='+new_order); // send reordered page data back to server for processing
	}).jstree({
		'core' : {
			'check_callback' : true
		},
		"plugins" : [ "dnd",	// enable drag and drop event for menu tree
						"contextmenu" ],	// enable context menus for menu tree
		"contextmenu" : {	// configure context menu for menu tree 
			"select_node" : false,
			"items" : {
				"Create" : {	// configure "Create Page" menu item for menu tree
					"label" : "Create Page",
					"action" : function(obj){
						pages_add_subpage(obj);	// function for adding a child page
					},
					"separator_after" : true
				},
				"Delete" : {	// configure "Delete Page" menu item for the menu tree
					"label" : "Delete Page",
					"action" : function(obj){
						pages_delete(obj);	// function for deleting a page 
					}
				}
			} 
		}
	});
	
	var div=$('<div id="right-click"><i>right-click for options</i><br /><br /></div>');	// right click signifier for menu tree
	
	$('<button>add main page</button>').click(pages_add_main_page).appendTo(div);	// create button to add a main page
	div.appendTo('#pages-wrapper');		// append right click signifier and "add main page" button to menu tree
});
function pages_add_main_page(){
	pages_new(0);	// create a new top level page
}
function pages_new(p){	// function for creating a new with a parent paramenter
	var test = '<div>tsting</div>';
	
	// form for creating basic new page
	var form = '<form id="newpage_dialog" action="/cap.admin/pages.php" method="post">' +
			'<input type="hidden" name="action" value="Insert Page Details" />' +
			'<input type="hidden" name="special[1]" value="1" />' +
			'<input type="hidden" name="parent" value="'+p+'" />' +
			'<table>' +
				'<tr><th>Name</th><td><input name="name" /></td></tr>' +
				'<tr><th>Page Type</th><td><select name="type"><option value="0">normal</option></select></td></tr>' +
				'<tr><th>Associated </th><td><input name="associated_date" class="date-human" id="newpage_date" /></td></tr>' +
			'</table>' +
			'</form>'
	$(form).dialog({	// create a page creation dialog box
		modal:true,
		width: 350,
		buttons: [	// configure dialog buttons
		{
			text: "Create Page",
			click: function(){
				$('#newpage_dialog').submit();	// submit new page form data
			}
		},
		{
			text: "Cancel",			// create a cancel button
			click: function(){
				$(this).dialog('destroy');
				$(this).remove();
			}
		}
		]
	});
	
	$('#newpage_date').each(convert_date_to_human_readable);	// configure dates for readability and DB processing
	return false;
}
function pages_add_subpage(obj){	// function for creating a child page
	var p = obj.reference[0].attributes.href.value;
	p = p.replace(/.*=/,'')
	pages_new(p);
}
function pages_delete(obj){		// function for deleting a page
	var id = obj.reference[0].parentElement.id;		// obtain page id for deleting
	
	if(!confirm("Are you sure you want to delete this page?")){		// confirm page deletion
		return;
	}
	
	$.getJSON('/cap.admin/pages/delete.php?id='+id.replace(/.*_/,''),function(){	// send to deletion script
		document.location = document.location.toString();
	});
}