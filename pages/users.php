<?php

if (getRole() > 1)
	$TEMPLATE['text'] = '<div class="alert alert-danger">You don\'t have permission to see this page.</div>';
else
{
	if (isset($_GET['a']) && $_GET['a'] == "add")
	{
		$TEMPLATE['text'] = '';
		if ($_POST && isset($_POST['user']) && isset($_POST['pwd']) && isset($_POST['role']))
		{
			db_query("INSERT INTO `users`(`user`, `pwd`, `role`) VALUES(:1, :2, :3)", $_POST['user'], hash('sha256', $_POST['pwd']), $_POST['role']);
			$TEMPLATE['text'] .= '<div class="alert alert-success">Added '.$_POST['user'].'.</div>';
		}
		$TEMPLATE['site'] = "Add user";
		$TEMPLATE['text'] .= '<form action="'.$_SERVER['SCRIPT_NAME'].'?p=users&a=add" method="POST" role="form">
							<input type="text" class="form-control" placeholder="Username" name="user" required autofocus>
							<input type="text" class="form-control" placeholder="Password" name="pwd" required>
							<select class="form-control" name="role">
								<option value="1">Admin</option>
								<option value="2">User</option>
								<option value="3">Guest</option>
							</select>
							<button class="btn btn-lg btn-primary btn-block" type="submit">Create user</button>
							</form>';
	}
	elseif (isset($_GET['a']) && isset($_GET['id']) && $_GET['a'] == "edit")
	{
		$TEMPLATE['text'] = '';
		if ($_POST && isset($_POST['user']) && isset($_POST['pwd']) && isset($_POST['role']))
		{
			if (!empty($_POST['pwd']))
				db_query("UPDATE `users` SET `pwd` = :1, `role` = :2 WHERE `id` = :3", hash('sha256', $_POST['pwd']), $_POST['role'], $_GET['id']);
			else
				db_query("UPDATE `users` SET `role` = :1 WHERE `id` = :2", $_POST['role'], $_GET['id']);
			$TEMPLATE['text'] .= '<div class="alert alert-success">Updated '.$_POST['user'].'.</div>';
		}
		$TEMPLATE['site'] = "Edit user";
		$stmt = db_query("SELECT * FROM `users` WHERE `id` = :1", $_GET['id']);
		if ($stmt->rowCount() > 0)
		{
			$row = $stmt->fetch();
			$TEMPLATE['text'] .= '<form action="'.$_SERVER['SCRIPT_NAME'].'?p=users&a=edit&id='.$_GET['id'].'" method="POST" role="form">
								<input type="text" class="form-control" placeholder="Username" name="user" value="'.$row['user'].'" required readonly>
								<input type="text" class="form-control" placeholder="Password" name="pwd" required>
								<select class="form-control" name="role">
									<option'.(($row['role'] == 1) ? ' selected' : '').' value="1">Admin</option>
									<option'.(($row['role'] == 2) ? ' selected' : '').' value="2">User</option>
									<option'.(($row['role'] == 3) ? ' selected' : '').' value="3">Guest</option>
								</select>
								<button class="btn btn-lg btn-primary btn-block" type="submit">Save changes</button>
								</form>';
		}
		else
			$TEMPLATE['text'] .= '<div class="alert alert-danger">Couldn\'t find user.</div>';
	}
	else
	{
		$TEMPLATE['text'] = '';
		if (isset($_GET['a']) && $_GET['a'] == "del" && isset($_GET['id']))
		{
			$stmt = db_query("SELECT `user` FROM `users` WHERE `id` = :1", $_GET['id']);
			if ($stmt->rowCount() > 0)
			{
				$row = $stmt->fetch();
				db_query("DELETE FROM `users` WHERE `id` = :1", $_GET['id']);
				$TEMPLATE['text'] .= '<div class="alert alert-info">Deleted '.$row['user'].'.</div>';
			}
			else
				$TEMPLATE['text'] .= '<div class="alert alert-danger">Couldn\'t find user.</div>';
		}
		$stmt = db_query("SELECT * FROM `users` ORDER BY `role`");
		$TEMPLATE['site'] = "Users";
		$TEMPLATE['text'] .= '<a href="?p=users&a=add" class="btn btn-success pull-right"><i class="glyphicon glyphicon-plus"></i> Add user</a
							<div style="height: 10px;"></div>
										<table class="table table-hover">
											<tr>
												<th>#</th>
												<th>User</th>
												<th>Role</th>
												<th></th>
											</tr>';
		while ($row = $stmt->fetch())
		{
			$TEMPLATE['text'] .= "			<tr>
												<td>{$row['id']}</td>
												<td>{$row['user']}</td>
												<td>{$row['role']}</td>
												<td style=\"width: 100px;\">
													<a href=\"?p=users&a=del&id={$row['id']}\" class=\"btn btn-danger\"><i class=\"glyphicon glyphicon-trash\"></i></a>
													<a href=\"?p=users&a=edit&id={$row['id']}\" class=\"btn btn-primary\"><i class=\"glyphicon glyphicon-wrench\"></i></a>
												</td>
											</tr>";
		}
		$TEMPLATE['text'] .= '			</table>';
	}
}
					
$TEMPLATE['js'] = '';
?>