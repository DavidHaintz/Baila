<?php

if(!isset($GLOBALS['in_script'])) die("Direct access not allowed!");

$TEMPLATE['js'] = '';
$TEMPLATE['site'] = "Users";

if (getRole() > getPageRole("Users")) $TEMPLATE['text'] = infoMsg("You don't have permission to see this page.");
else
{
	$TEMPLATE['text'] = '';
	
	/* Add user */
	
	if (isset($_GET['a']) && $_GET['a'] == "add")
	{	
		if ($_POST && isset($_POST['user']) && isset($_POST['pass']) && isset($_POST['role']))
		{
			db_query("INSERT INTO `users`(`user`, `pass`, `role`) VALUES(:1, :2, :3)", $_POST['user'], hash('sha256', $_POST['pass']), $_POST['role']);
			$TEMPLATE['text'] .= infoMsg('Added '.$_POST['user'].'.', 'success');
		}
		
		$TEMPLATE['text'] .= '<form action="'.$_SERVER['SCRIPT_NAME'].'?p=users&a=add" method="POST" role="form"><input type="text" class="form-control" placeholder="Username" name="user" required autofocus><br/><input type="text" class="form-control" placeholder="Password" name="pass" required><br/><select class="form-control" name="role"><option value="1">Admin</option><option value="2">User</option><option value="3">Guest</option></select><br/><button class="btn btn-lg btn-primary btn-block" type="submit">Create user</button></form>';
	}
	
	/* Edit user */
	
	elseif (isset($_GET['a']) && isset($_GET['id']) && $_GET['a'] == "edit")
	{		
		if ($_POST && isset($_POST['user']) && isset($_POST['pass']) && isset($_POST['role']))
		{
			if (!empty($_POST['pass']))
				db_query("UPDATE `users` SET `pass` = :1, `role` = :2 WHERE `id` = :3", hash('sha256', $_POST['pass']), $_POST['role'], $_GET['id']);
			else
				db_query("UPDATE `users` SET `role` = :1 WHERE `id` = :2", $_POST['role'], $_GET['id']);
			
			$TEMPLATE['text'] .= infoMsg('Updated '.$_POST['user'].'.', 'success');
		}
		
		$stmt = db_query("SELECT * FROM `users` WHERE `id` = :1", $_GET['id']);		
		
		if ($stmt->rowCount() > 0)
		{
			$row = $stmt->fetch();
			$TEMPLATE['text'] .= '<form action="'.$_SERVER['SCRIPT_NAME'].'?p=users&a=edit&id='.$_GET['id'].'" method="POST" role="form"><input type="text" class="form-control" placeholder="Username" name="user" value="'.$row['user'].'" required readonly><br/><input type="text" class="form-control" placeholder="Password" name="pass" required><br/><select class="form-control" name="role"><option'.(($row['role'] == 1) ? ' selected' : '').' value="1">Admin</option><option'.(($row['role'] == 2) ? ' selected' : '').' value="2">User</option><option'.(($row['role'] == 3) ? ' selected' : '').' value="3">Guest</option></select><br><button class="btn btn-lg btn-primary btn-block" type="submit">Save changes</button></form>';
		}
		else $TEMPLATE['text'] .= infoMsg("Couldn't find user.");
	}
	
	/* User overview */
	
	else
	{		
		if (isset($_GET['a']) && $_GET['a'] == "del" && isset($_GET['id']))
		{
			$stmt = db_query("SELECT `user` FROM `users` WHERE `id` = :1", $_GET['id']);
			
			if ($stmt->rowCount() > 0)
			{
				$row = $stmt->fetch();
				db_query("DELETE FROM `users` WHERE `id` = :1", $_GET['id']);
				$TEMPLATE['text'] .= infoMsg('Deleted '.$row['user'].'.', 'success');
			}
			else $TEMPLATE['text'] .= infoMsg("Couldn't find user.");
		}
		
		$stmt = db_query("SELECT * FROM `users` ORDER BY `role`");
		
		$TEMPLATE['text'] .= '<table style="table-layout:fixed;word-wrap:break-word;" class="table table-hover table-vcenter"><tr><th style="width: 10%;">#</th><th style="padding-left:5px;">User</th><th style="padding-left:5px;">Role</th><th></th></tr>';
		
		while ($row = $stmt->fetch())
			$TEMPLATE['text'] .= '<tr><td>'.$row['id'].'</td><td>'.$row['user'].'</td><td>'.(($row['role'] == 1) ? 'Admin' : (($row['role'] == 2) ? 'User' : 'Guest')).'</td><td style="text-align:right;"><a href="?p=users&a=del&id='.$row['id'].'" class="btn btn-danger nicesize"><i class="glyphicon glyphicon-trash"></i></a>&nbsp;&nbsp;<a href="?p=users&a=edit&id='.$row['id'].'" class="btn btn-primary nicesize"><i class="glyphicon glyphicon-wrench"></i></a></td></tr>';

		$TEMPLATE['text'] .= '</table><a style="width:91px;" href="?p=users&a=add" class="btn btn-success pull-right nicesize"><i class="glyphicon glyphicon-plus"></i>&nbsp;Add user</a>';
	}
}
?>