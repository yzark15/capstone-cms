<?php
// get user info
$id=(int)$_REQUEST['id'];
$groups = array();
$result = dbRow("select * from user_accounts where id=$id");

// create default user values if no result returned
if(!is_array($result) || !count($result)){
	$result = array('id'=>-1,'email'=>'','active'=>0);
}

// create form for creating and updating users
echo '<form action="users.php?id='.$id.'" method="post">';
echo '<input type="hidden" name="id" value="'.$id.'" />';
echo '<table><tr>';
echo '<th>Email</th><td><input name="email" value="'.htmlspecialchars($result['email']).'" /></td></tr>';
echo '<tr><th>Password</th><td><input name="password" type="password" /></td></tr>';
echo '<tr><th>(repeat)</th><td><input name="password2" type="password" /></td></tr>'; 
echo '<tr><th>Groups</th><td class="groups">';
$grs = dbAll('select id, name from groups');
$gms = array();

// list all groups 
foreach($grs as $g){
	$groups[$g['id']] = $g['name'];
}
if($id != -1){
	$grs = json_decode($result['groups']);
}
foreach($groups as $k => $g){
	echo '<input type="checkbox" name="groups['.$k.']"';
	if(in_array($g,$grs)){
		echo ' checked="checked"';
	}
	echo ' />'.htmlspecialchars($g).'</input><br />';
}
echo '</td></tr>';

// select whether user is active or not
echo '<tr><th>Active</th><td><select name="active">';
echo '<option value="0">No</option>';
echo '<option value="1"'.($result['active']?'selected="selected"':'').'>Yes</option></select></td></tr></table>';
echo '<input id="sub-user" type="submit" name="action" value="Save" /></form>';
?>