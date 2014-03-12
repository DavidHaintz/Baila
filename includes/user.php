<?php

if(!isset($GLOBALS['in_script']) && !isset($GLOBALS['in_gate'])) die("Direct access not allowed!");

if(!isset($_SESSION)) session_start();

function isLoggedIn()
{
	return isset($_SESSION['uid']);
}

function getUID()
{
	return isLoggedIn() ? $_SESSION['uid'] : 0;
}

function getRole($uid = 0)
{
	if (!$uid) $uid = getUID();
	
	$stmt = db_query("SELECT `role` FROM `users` WHERE `id` = :1", $uid);
	if ($stmt->rowCount() > 0)
	{
		$row = $stmt->fetch();
		return $row['role'];
	}
	
	return 0;
}

function getPageRole($page = null)
{
	if ($page == null) return 0;
	
	$stmt = db_query("SELECT `value` FROM `settings` WHERE `name` = :1", $page);
	if ($stmt->rowCount() > 0)
	{
		$row = $stmt->fetch();
		return intval($row['value']);
	}
	
	return 0;
}

function dbgTrace() 
{ 
	$trace = debug_backtrace();
	
	if(isset($trace[0])) $dbg = $trace[0];
	
	if(isset($trace[1]))
		if(!strstr($trace[1]['function'], "include") && 
			!strstr($trace[1]['function'], "require")) $dbg = $trace[1];
		
	if(isset($trace[2]))
		if(!strstr($trace[2]['function'], "include") && 
			!strstr($trace[2]['function'], "require")) $dbg = $trace[2];
	
	return empty($dbg) ? "" : $dbg['file'].":".$dbg['line'].":".$dbg['function'];
}

function infoMsg($msg, $type = 'danger', $noembbed = false)
{
	$noembeddstyle = 'style="padding:15px 10px 15px 50px;margin:10px 20px;"';
	
	$errbox = '<div '.($noembbed ? $noembeddstyle : '').' class="alert alert-'.$type.'" >'.$msg.'</div>';
	$errpage = '<!DOCTYPE html><html lang="en"><head><meta charset="utf-8"><title>Error - '.$GLOBALS['bot_name'].' Webpanel</title><link href="template/css/bootstrap.css" rel="stylesheet"></head><body>'.$errbox.'</body></html>';
	
	return isset($GLOBALS['in_gate']) ? $msg : ($noembbed ? $errpage : $errbox);
}

?>