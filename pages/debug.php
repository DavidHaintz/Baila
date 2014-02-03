<?php
$TEMPLATE['site'] = "Debug";
if (getRole() > 1)
	$TEMPLATE['text'] = '<div class="alert alert-danger">You don\'t have permission to see this page.</div>';
else
{
	$TEMPLATE['text'] = '';
	if (isset($_GET['a']) && $_GET['a'] == "clear")
	{
		db_query("TRUNCATE TABLE `error`");
		header("Location: index.php?p=debug");
	}
	$stmt = db_query("SELECT *, COUNT(*) AS `cnt` FROM `error` GROUP BY `err` ORDER BY `id` DESC");
	$TEMPLATE['text'] .= '<a href="?p=debug&a=clear" class="btn btn-danger pull-right"><i class="glyphicon glyphicon-trash"></i> Clear log</a
							<div style="height: 10px;"></div>
									<table class="table table-hover">
										<tr>
											<th>#</th>
											<th>OS</th>
											<th>Error</th>
											<th>Count</th>
											<th>Date</th>
										</tr>';
	while ($row = $stmt->fetch())
	{
		$TEMPLATE['text'] .= "			<tr>
											<td>{$row['id']}</td>
											<td>{$row['os']}</td>
											<td>{$row['err']}</td>
											<td>{$row['cnt']}</td>
											<td>{$row['date']}</td>
										</tr>";
	}
	$TEMPLATE['text'] .= '			</table>';
}
					
$TEMPLATE['js'] = '';
?>