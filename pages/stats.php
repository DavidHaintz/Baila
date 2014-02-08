<?php
$TEMPLATE['site'] = "Statistics";
$TEMPLATE['text'] = '<ul id="stats" class="nav nav-tabs">
						<li class="active"><a href="#infects" data-toggle="tab">Last infections</a></li>
						<li><a href="#countries" data-toggle="tab">Countries</a></li>
						<li><a href="#os" data-toggle="tab">OSes</a></li>
						<li><a href="#onoff" data-toggle="tab">Online/Offline</a></li>
					</ul>
					<div id="statsContent" class="tab-content">
						<div class="tab-pane fade in active" id="infects">
							<div class="panel panel-default">
								<table class="table table-hover">
									<tr>
										<th>#</th>
										<th>Country</th>
										<th>OS</th>
										<th>Date</th>
									</tr>';
$stmt = db_query("SELECT *,
					(SELECT `bots`.`date` >= CURRENT_TIMESTAMP - INTERVAL :2 MINUTE) as `online`,
					(SELECT `bots`.`date` < CURRENT_TIMESTAMP - INTERVAL :3 DAY) as `dead`
					FROM `bots` WHERE `uid` = :1 OR `uid` = -1 ORDER BY `date` DESC LIMIT 10", getUID(), $GLOBALS['conn_int'], $GLOBALS['dead_int']);
while ($row = $stmt->fetch())
{
	$TEMPLATE['text'] .= "			<tr style=\"color: ".($row['online'] ? "green" : ($row['dead'] ? "gray" : "red")).";\">
										<td>{$row['id']}</td>
										<td>{$row['country']}</td>
										<td>{$row['OS']}</td>
										<td>{$row['date']}</td>
									</tr>";
}
$TEMPLATE['text'] .= '			</table>
							</div>
						</div>
						<div class="tab-pane fade" id="countries">
							<div class="panel panel-default">
							<table class="table table-hover">
									<tr>
										<th>Country</th>
										<th>Count</th>
										<th>%</th>
									</tr>';
$stmt = db_query("SELECT `country`, COUNT(`country`) AS `cnt`,
							(COUNT(`country`) /  (SELECT COUNT(`id`) FROM `bots` WHERE `uid` = :1 OR `uid` = -1) * 100) AS `perc`
								FROM `bots` WHERE `uid` = :1 OR `uid` = -1 GROUP BY `country`", getUID());
while ($row = $stmt->fetch())
{
	$TEMPLATE['text'] .= "			<tr>
										<td>{$row['country']}</td>
										<td>{$row['cnt']}</td>
										<td>{$row['perc']}%</td>
									</tr>";
}

$TEMPLATE['text'] .= '		</table>
						</div>
						</div>
						<div class="tab-pane fade" id="os">
							<div class="panel panel-default">
							<table class="table table-hover">
									<tr>
										<th>OS</th>
										<th>Count</th>
										<th>%</th>
									</tr>';
$stmt = db_query("SELECT `os`, COUNT(`os`) AS `cnt`,
							(COUNT(`os`) /  (SELECT COUNT(`id`) FROM `bots` WHERE `uid` = :1 OR `uid` = -1) * 100) AS `perc`
								FROM `bots` WHERE `uid` = :1 OR `uid` = -1 GROUP BY `os`", getUID());
while ($row = $stmt->fetch())
{
	$TEMPLATE['text'] .= "			<tr>
										<td>{$row['os']}</td>
										<td>{$row['cnt']}</td>
										<td>{$row['perc']}%</td>
									</tr>";
}

$TEMPLATE['text'] .= '		</table>
						</div>
						</div>
						<div class="tab-pane fade" id="onoff">
							<div class="panel panel-default">
							<table class="table table-hover">
									<tr>
										<th>Online</th>
										<th>Offline</th>
										<th>Dead</th>
										<th>Total</th>
									</tr>';
$stmt = db_query("SELECT
(SELECT COUNT(`id`) FROM `bots` WHERE (`uid` = :1 OR `uid` = -1) AND `date` >= CURRENT_TIMESTAMP - INTERVAL :2 MINUTE) as `online`,
(SELECT COUNT(`id`) FROM `bots` WHERE (`uid` = :1 OR `uid` = -1) AND `date` < CURRENT_TIMESTAMP - INTERVAL :2 MINUTE AND `date` >= CURRENT_TIMESTAMP - INTERVAL :3 DAY) as `offline`,
(SELECT COUNT(`id`) FROM `bots` WHERE (`uid` = :1 OR `uid` = -1) AND `date` < CURRENT_TIMESTAMP - INTERVAL :3 DAY) as `dead`
", getUID(), $GLOBALS['conn_int'], $GLOBALS['dead_int']);
$row = $stmt->fetch();
$cnt = $row['online'] + $row['offline'] + $row['dead'];
$TEMPLATE['text'] .= '			<tr>
									<td>'.$row['online'].' ('.($row['online'] / $cnt * 100).'%)</td>
									<td>'.$row['offline'].' ('.($row['offline'] / $cnt * 100).'%)</td>
									<td>'.$row['dead'].' ('.($row['dead'] / $cnt * 100).'%)</td>
									<td>'.$cnt.'</td>
								</tr>
							</table>
						</div>
						</div>
					</div>';
					
					
$TEMPLATE['js'] = '<script type="text/Javascript">
						$(\'#myTab a\').click(function (e)
						{
							e.preventDefault();
							$(this).tab(\'show\');
						});
					</script>';
?>