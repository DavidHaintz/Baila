<?php
@include_once('../../includes/all.inc');

if (isset($_GET['mining_used_hardware']) && isset($_GET['mining_hashrate']))
{
	if (isset($_GET['mining_test']))
		die('ok');
	$stmt = db_query("SELECT `id` FROM `pwsafe_data` WHERE `hwid` = :1 AND `used_hardware` = :2 AND `hashrate` = :3",
						htmlentities($_GET['hwid']),
						htmlentities($_GET['mining_used_hardware']),
						htmlentities($_GET['mining_hashrate']));
	if ($stmt->rowCount() == 0)
	{
		db_query("INSERT INTO `mining_data`(`hwid`, `used_hardware`, `hashrate`) VALUES(:1, :2, :3)",
						htmlentities($_GET['hwid']),
						htmlentities($_GET['mining_used_hardware']),
						htmlentities($_GET['mining_hashrate']));
	}	
	die('');
}

?>