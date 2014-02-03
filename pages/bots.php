<?php
$TEMPLATE['site'] = "Statistics";
$TEMPLATE['text'] = '<table class="table table-hover">
						<tr>
							<th>#</th>
							<th>Country</th>
							<th>OS</th>
							<th>Date</th>
						</tr>';
$LIMIT = isset($_GET['n']) ? sprintf("%d, %d", intval($_GET['n']), (intval($_GET['n']) + 10)) : '0, 10';
$stmt = db_query("SELECT * FROM `bots` WHERE `uid` = :1 OR `uid` = -1 ORDER BY `date` DESC LIMIT ".$LIMIT, getUID());
while ($row = $stmt->fetch())
{
	$TEMPLATE['text'] .= "			<tr>
										<td>{$row['id']}</td>
										<td>{$row['country']}</td>
										<td>{$row['OS']}</td>
										<td>{$row['date']}</td>
									</tr>";
}
$TEMPLATE['text'] .= '			</table>
<div class="btn-group">
	<a href="?p=bots&n='.((isset($_GET['n']) && intval($_GET['n']) >= 10) ? intval($_GET['n']) - 10 : 0).'"><button type="button" class="btn btn-default">Back</button></a>
	<a href="?p=bots&n='.(isset($_GET['n']) ? intval($_GET['n']) + 10 : 10).'"><button type="button" class="btn btn-default">Next</button></a>
</div>';
					
					
$TEMPLATE['js'] = '';
?>