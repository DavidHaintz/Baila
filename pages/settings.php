<?php

if(!isset($GLOBALS['in_script'])) die("Direct access not allowed!");

$TEMPLATE['js'] = '';
$TEMPLATE['site'] = "Settings";

if (getRole() > 1) $TEMPLATE['text'] = infoMsg("You don't have permission to see this page.");
else
{
	$TEMPLATE['text'] = '';
	
	/* Edit setting */
	
	if (isset($_GET['a']) && isset($_GET['id']) && $_GET['a'] == "edit")
	{			
		if ($_POST && isset($_POST['name']) && isset($_POST['value']))
		{
			db_query("UPDATE `settings` SET `value` = :1 WHERE `name` = :2", $_POST['value'], $_POST['name']);			
			$TEMPLATE['text'] .= infoMsg('Updated '.$_POST['name'].'.', 'success');
		}
		
		$stmt = db_query("SELECT * FROM `settings` WHERE `id` = :1", $_GET['id']);		
		
		if ($stmt->rowCount() > 0)
		{
			$row = $stmt->fetch();
			$TEMPLATE['text'] .= '<form action="'.$_SERVER['SCRIPT_NAME'].'?p=settings&a=edit&id='.$_GET['id'].'" method="POST" role="form"><input type="text" class="form-control" placeholder="Page" name="name" value="'.$row['name'].'" required readonly><br/><select class="form-control" name="value"><option'.((intval($row['value']) == 1) ? ' selected' : '').' value="1">Admin</option><option'.((intval($row['value']) == 2) ? ' selected' : '').' value="2">User</option><option'.((intval($row['value']) == 3) ? ' selected' : '').' value="3">Guest</option></select><br><button class="btn btn-lg btn-primary btn-block" type="submit">Save changes</button></form>';
		}
		else $TEMPLATE['text'] .= infoMsg("Couldn't find option.");
	}
	
	/* Settings overview */
	
	else
	{
		$TEMPLATE['text'] .= '<table style="table-layout:fixed;word-wrap:break-word;" class="table table-hover table-vcenter"><tr><th style="width: 10%;">#</th><th style="padding-left:5px;">Page</th><th style="padding-left:5px;">Required role</th><th></th></tr>';
		
		$stmt = db_query("SELECT * FROM `settings`");
		
		while ($row = $stmt->fetch())
			$TEMPLATE['text'] .= '<tr><td>'.$row['id'].'</td><td>'.$row['name'].'</td><td>'.(($row['value'] == 1) ? 'Admin' : (($row['value'] == 2) ? 'User' : 'Guest')).'</td><td style="text-align:right;"><a href="?p=settings&a=edit&id='.$row['id'].'" class="btn btn-primary nicesize"><i class="glyphicon glyphicon-wrench"></i></a></td></tr>';
	
		$TEMPLATE['text'] .= '</table>';
	}	
}
?>