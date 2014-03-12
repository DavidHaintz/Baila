<?php

$modules = null;

foreach (scandir('modules') as $dir)
{
	if ($dir != '.' && $dir != '..')
		$modules .= '<li><a href="?p=module&m='.$dir.'">'.$dir.'</a></li>';
}

if($modules == null) $modules = '<li><a>No modules!</a></li>';

echo '<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>'.$TEMPLATE['site'].' - '.$GLOBALS['bot_name'].' Webpanel</title>
	<script type="text/javascript" src="template/js/jquery.min.js"></script>
	<script type="text/javascript" src="template/js/moment.min.js"></script>
	<script type="text/javascript" src="template/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="template/js/bootstrap-datetimepicker.min.js"></script>
	<link href="template/css/bootstrap.css" rel="stylesheet">
	<link href="template/css/bootstrap-edits.css" rel="stylesheet">
	<link href="template/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
	<style type="text/css">tr { text-align:left; }</style>
</head>
<body style="padding-top:50px;">
	<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
		<div class="container">
			<div class="navbar-header">
				<a class="navbar-brand" href="index.php">'.$GLOBALS['bot_name'].'</a>
			</div>
			<div class="collapse navbar-collapse">
				<ul class="nav navbar-nav">
					<li '.((!isset($_GET['p']) || $_GET['p'] == 'stats') ? 'class="active"' : '').'><a href="index.php?p=stats">Stats</a></li>
					<li '.((isset($_GET['p']) && $_GET['p'] == 'bots') ? 'class="active"' : '').'><a href="index.php?p=bots">Bots</a></li>
					<li '.((isset($_GET['p']) && $_GET['p'] == 'tasks') ? 'class="active"' : '').'><a href="index.php?p=tasks">Tasks</a></li>
					<li '.((isset($_GET['p']) && $_GET['p'] == 'debug') ? 'class="active"' : '').'><a href="index.php?p=debug">Debug</a></li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">Modules&nbsp;<b class="caret"></b></a>
						<ul style="min-width:110px;" class="dropdown-menu">
							'.$modules.'
						</ul>
					</li>
					<li '.((isset($_GET['p']) && $_GET['p'] == 'users') ? 'class="active"' : '').'><a href="index.php?p=users">Users</a></li>
					<li '.((isset($_GET['p']) && $_GET['p'] == 'settings') ? 'class="active"' : '').'><a href="index.php?p=settings">Settings</a></li>
					<li '.((isset($_GET['p']) && $_GET['p'] == 'changelog') ? 'class="active"' : '').'><a href="index.php?p=changelog">Changelog</a></li>
					<li '.((isset($_GET['p']) && $_GET['p'] == 'logout') ? 'class="active"' : '').'><a href="index.php?p=logout">Logout</a></li>
				</ul>
			</div>
		</div>
	</div>
	<div class="container">
		<div style="padding:40px 15px;text-align:center;">'.$TEMPLATE['text'].'</div>
		<div style="position:fixed;bottom:10px;text-align:center;width:100%;left:0px;">
			Powered by <a href="https://github.com/IRET0x00/Baila">Baila</a>
		</div>
	</div>
	'.$TEMPLATE['js'].'
</body>
</html>';

?>
