<?php
if ($_POST && isset($_POST['user']) && isset($_POST['pwd']))
{
	$stmt = db_query("SELECT `id` FROM `users` WHERE `user` = :1 AND `pwd` = :2", $_POST['user'], hash('sha256', $_POST['pwd']));
	if ($stmt->rowCount() > 0)
	{
		$row = $stmt->fetch();
		session_start();
		$_SESSION['uid'] = $row['id'];
		header("Location: index.php");
	}
	else
		$TEMPLATE['alert'] = '<div class="alert alert-danger">Wrong username or password!</div>';
}
?>