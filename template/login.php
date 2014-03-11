<?php

echo '<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Signin - '.$GLOBALS['bot_name'].' Webpanel</title>
	<link href="template/css/bootstrap.css" rel="stylesheet">
	<link href="template/css/login.css" rel="stylesheet">
</head>
<body style="padding:40px 0;background-color:#eee;">
	<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
		<div class="container">
			<div class="navbar-header">
				<a class="navbar-brand" href="index.php">'.$GLOBALS['bot_name'].'</a>
			</div>
			<div class="collapse navbar-collapse">
				<ul class="nav navbar-nav"><li class="active"><a href="index.php">Login</a></li></ul>
			</div>
		</div>
	</div>
	<div class="container">
		<div style="padding:40px 15px;text-align:center;">
			<form class="form-signin" action="'.$_SERVER['SCRIPT_NAME'].'" method="POST" role="form">
				<h2 class="form-signin-heading">'.$GLOBALS['bot_name'].' Webpanel</h2><br/>
				<input type="text" class="form-control" placeholder="Username" name="user" required autofocus>
				<input type="password" class="form-control" placeholder="Password" name="pass" required>
				'.(isset($TEMPLATE['alert']) ? $TEMPLATE['alert'] : '').'
				<br/><button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
			</form>
		</div> 
	</div>
</body>
</html>';

?>
