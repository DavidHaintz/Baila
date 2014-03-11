<?php

if(!isset($GLOBALS['in_script'])) die("Direct access not allowed!");

$TEMPLATE['js'] = '';
$TEMPLATE['site'] = "Bots";

if (getRole() > getPageRole("Bots")) $TEMPLATE['text'] = infoMsg("You don't have permission to see this page.");
else
{	
	$TEMPLATE['text'] = '';
	$pages = intval((db_query("SELECT COUNT(`id`) AS `cnt` FROM `bots`")->fetch()['cnt'] + 9) / 10);
	
	if(isset($_GET['s']) && intval($_GET['s']) > 1)
		$s = intval($_GET['s']);
	else
		$s = 1;
		
	$LIMIT = sprintf("%u, 10", (($s-1)*10));	
	$stmt = db_query("SELECT *, (SELECT `bots`.`date` >= CURRENT_TIMESTAMP - INTERVAL :2 MINUTE) as `online`, (SELECT `bots`.`date` < CURRENT_TIMESTAMP - INTERVAL :3 DAY) as `dead` FROM `bots` WHERE `uid` = :1 OR `uid` IS NULL ORDER BY `date` DESC, `id` ASC LIMIT ".$LIMIT, getUID(), $GLOBALS['conn_int'], $GLOBALS['dead_int']);
		
	$TEMPLATE['text'] .= '<table style="table-layout:fixed;word-wrap:break-word;" class="table table-hover table-vcenter"><tr><th style="width: 10%;">#</th><th>HWID</th><th>Country</th><th>OS</th><th>Last seen</th></tr>';
	
	if($pages > 0)
	{	
		while ($row = $stmt->fetch())
			$TEMPLATE['text'] .= '<tr style="color:'.($row['online'] ? "green" : ($row['dead'] ? "gray" : "red")).';"><td>'.$row['id'].'</td><td>'.$row['hwid'].'</td><td>'.$row['country'].'</td><td>'.$row['os'].'</td><td>'.$row['date'].'</td></tr>';
	
		$TEMPLATE['text'] .= '</table>';
		
		if($pages > 1)
			$TEMPLATE['text'] .= '<div style="margin-top: 30px;" class="btn-group nicesize"><a '.($s > 1 ? 'href="?p=bots&s='.($s-1).'"' : '').'><button '.($s <= 1 ? 'disabled="true"' : '').' style="padding:4px 5px 4px 4px;" type="button" class="btn btn-primary">Back</button></a>&nbsp;&nbsp;<a '.($s < $pages ? 'href="?p=bots&s='.($s+1).'"' : '').'"><button '.($s >= $pages ? 'disabled="true"' : '').' style="padding:4px 6px 4px 4px;" type="button" class="btn btn-primary">Next</button></a></div>';
	}
	else $TEMPLATE['text'] .= '</table>';
}
	
?>