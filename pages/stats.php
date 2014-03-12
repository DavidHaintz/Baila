<?php

if(!isset($GLOBALS['in_script'])) die("Direct access not allowed!");

$TEMPLATE['site'] = "Statistics";
$TEMPLATE['js'] = '<script type="text/Javascript">$(\'#myTab a\').click(function(e){e.preventDefault();$(this).tab(\'show\');});</script>';

if (getRole() > getPageRole("Stats")) $TEMPLATE['text'] = infoMsg("You don't have permission to see this page.");
else
{
	$TEMPLATE['text'] = '<ul id="stats" class="nav nav-tabs"><li class="active"><a href="#infects" data-toggle="tab">Last infections</a></li><li><a href="#countries" data-toggle="tab">Countries</a></li><li><a href="#os" data-toggle="tab">OSes</a></li><li><a href="#onoff" data-toggle="tab">Online/Offline</a></li></ul><div id="statsContent" class="tab-content">';
	
	/* Last infections */
	
	$TEMPLATE['text'] .= '<div class="tab-pane fade in active" id="infects"><div style="margin-top: -1px;" class="panel panel-default"><table style="table-layout:fixed;word-wrap:break-word;" class="table table-hover table-vcenter"><tr><th style="width: 10%;">#</th><th style="padding-left:5px;">Country</th><th style="padding-left:5px;">OS</th><th style="padding-left:5px;">Date</th></tr>';
	
	$stmt = db_query("SELECT *, (SELECT `bots`.`date` >= CURRENT_TIMESTAMP - INTERVAL :2 MINUTE) as `online`, (SELECT `bots`.`date` < CURRENT_TIMESTAMP - INTERVAL :3 DAY) as `dead` FROM `bots` WHERE `uid` = :1 OR `uid` IS NULL ORDER BY `date` DESC LIMIT 7", getUID(), $GLOBALS['conn_int'], $GLOBALS['dead_int']);
	
	while ($row = $stmt->fetch())
		$TEMPLATE['text'] .= '<tr style="color: '.($row['online'] ? "green" : ($row['dead'] ? "gray" : "red")).';"><td>'.$row['id'].'</td><td>'.$row['country'].'</td><td>'.$row['os'].'</td><td>'.$row['date'].'</td></tr>';
	
	$TEMPLATE['text'] .= '</table></div></div>';
	
	
	/* Countries */
	
	$TEMPLATE['text'] .= '<div class="tab-pane fade" id="countries"><div style="margin-top: -1px;" class="panel panel-default"><table style="table-layout:fixed;word-wrap:break-word;" class="table table-hover table-vcenter"><tr><th style="width: 10%;">#</th><th style="padding-left:5px;">Country</th><th style="padding-left:5px;">Count</th><th style="padding-left:5px;">%</th></tr>';
	
	$stmt = db_query("SELECT `country`, COUNT(`country`) AS `cnt`, (COUNT(`country`) /  (SELECT COUNT(`id`) FROM `bots` WHERE `uid` = :1 OR `uid` IS NULL) * 100) AS `perc` FROM `bots` WHERE `uid` = :1 OR `uid` IS NULL GROUP BY `country`", getUID());
	
	for($i = 1; $row = $stmt->fetch(); $i++)
		$TEMPLATE['text'] .= '<tr><td>'.$i.'</td><td>'.$row['country'].'</td><td>'.$row['cnt'].'</td><td>'.$row['perc'].'%</td></tr>';
	
	$TEMPLATE['text'] .= '</table></div></div>';
	
	
	/* OSes */
	
	$TEMPLATE['text'] .= '<div class="tab-pane fade" id="os"><div style="margin-top: -1px;" class="panel panel-default"><table style="table-layout:fixed;word-wrap:break-word;" class="table table-hover table-vcenter"><tr><th style="width: 10%;">#</th><th style="padding-left:5px;">OS</th><th style="padding-left:5px;">Count</th><th style="padding-left:5px;">%</th></tr>';
	
	$stmt = db_query("SELECT `os`, COUNT(`os`) AS `cnt`, (COUNT(`os`) / (SELECT COUNT(`id`) FROM `bots` WHERE `uid` = :1 OR `uid` IS NULL) * 100) AS `perc` FROM `bots` WHERE `uid` = :1 OR `uid` IS NULL GROUP BY `os` ORDER BY `perc` DESC", getUID());
	
	for($i = 1; $row = $stmt->fetch(); $i++)
		$TEMPLATE['text'] .= '<tr><td>'.$i.'</td><td>'.$row['os'].'</td><td>'.$row['cnt'].'</td><td>'.$row['perc'].'%</td></tr>';
	
	$TEMPLATE['text'] .= '</table></div></div>';
	
	
	/* Online/Offline */
	
	$TEMPLATE['text'] .= '<div class="tab-pane fade" id="onoff"><div style="margin-top: -1px;" class="panel panel-default"><table style="table-layout:fixed;word-wrap:break-word;" class="table table-hover table-vcenter"><tr><th style="padding-left:5px;">Total</th><th style="padding-left:5px;">Online</th><th style="padding-left:5px;">Offline</th><th style="padding-left:5px;">Dead</th></tr>';
	
	$stmt = db_query("SELECT (SELECT COUNT(`id`) FROM `bots` WHERE (`uid` = :1 OR `uid` IS NULL) AND `date` >= CURRENT_TIMESTAMP - INTERVAL :2 MINUTE) as `online`, (SELECT COUNT(`id`) FROM `bots` WHERE (`uid` = :1 OR `uid` IS NULL) AND `date` < CURRENT_TIMESTAMP - INTERVAL :2 MINUTE AND `date` >= CURRENT_TIMESTAMP - INTERVAL :3 DAY) as `offline`, (SELECT COUNT(`id`) FROM `bots` WHERE (`uid` = :1 OR `uid` IS NULL) AND `date` < CURRENT_TIMESTAMP - INTERVAL :3 DAY) as `dead`", getUID(), $GLOBALS['conn_int'], $GLOBALS['dead_int']);
	$row = $stmt->fetch(); $cnt = $row['online'] + $row['offline'] + $row['dead'];
	
	$TEMPLATE['text'] .= '<tr><td>'.$cnt.'</td><td>'.$row['online'].' ('.($cnt ? ($row['online'] / $cnt * 100) : 0).'%)</td><td>'.$row['offline'].' ('.($cnt ? ($row['online'] / $cnt * 100) : 0).'%)</td><td>'.$row['dead'].' ('.($cnt ? ($row['online'] / $cnt * 100) : 0).'%)</td></tr></table></div></div></div>';
	
	$TEMPLATE['text'] .= '';
}
	
?>