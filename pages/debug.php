<?php

if(!isset($GLOBALS['in_script'])) die("Direct access not allowed!");
					
$TEMPLATE['js'] = '';
$TEMPLATE['site'] = "Debug";

if (getRole() > getPageRole("Debug")) $TEMPLATE['text'] = infoMsg("You don't have permission to see this page.");
else
{
	$TEMPLATE['text'] = '';
	
	if (isset($_GET['a']) && $_GET['a'] == "clear")
	{
		db_query("TRUNCATE TABLE `error`");
		header("Location: index.php?p=debug");
	}
	
	$stmt = db_query("SELECT * FROM `error` ORDER BY `id` DESC");
	
	$TEMPLATE['text'] .= '<table style="table-layout:fixed;word-wrap:break-word;" class="table table-hover table-vcenter"><tr><th style="width: 10%;">#</th><th>Error</th><th>Task</th><th>Bot</th><th>Date</th></tr>';
										
	while ($row = $stmt->fetch())
		$TEMPLATE['text'] .= '<tr><td>'.$row['id'].'</td><td>'.$row['err'].'</td><td>'.$row['tid'].'</td><td>'.$row['hwid'].'</td><td>'.$row['date'].'</td></tr>';
	
	$TEMPLATE['text'] .= '</table><a style="width:91px;" href="?p=debug&a=clear" class="btn btn-danger pull-right nicesize"><i class="glyphicon glyphicon-trash"></i> Clear log</a>';
}

?>