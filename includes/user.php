<?php
function isLoggedIn()
{
	if(!isset($_SESSION))
		session_start();
	return isset($_SESSION['uid']);
}

function getUID()
{
	if (isLoggedIn())
		return $_SESSION['uid'];
	return -1;
}

function getRole($uid = -1)
{
	if ($uid == -1)
		$uid = getUID();
	$stmt = db_query("SELECT `role` FROM `users` WHERE `id` = :1", $uid);
	if ($stmt->rowCount() > 0)
	{
		$row = $stmt->fetch();
		return $row['role'];
	}
	else
		return -1;
}
?>