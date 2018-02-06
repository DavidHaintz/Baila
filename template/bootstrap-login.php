<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<link rel="shortcut icon" href="../../docs-assets/ico/favicon.png">

	<title>Signin - <?=$GLOBALS['bot_name']?> <?=$GLOBALS['LANG']['webpanel']?></title>


	<link href="template/css/bootstrap.css" rel="stylesheet">
	<link href="template/bootstrap-login.css" rel="stylesheet">
</head>
<body>

	<div class="container">

	<form class="form-signin" action="<?=$_SERVER['SCRIPT_NAME']?>" method="POST" role="form">
	<h2 class="form-signin-heading"><?=$GLOBALS['bot_name']?> <?=$GLOBALS['LANG']['webpanel']?></h2>
	<input type="text" class="form-control" placeholder="<?=$GLOBALS['LANG']['username']?>" name="user" required autofocus>
	<input type="password" class="form-control" placeholder="<?=$GLOBALS['LANG']['password']?>" name="pwd" required>
	<?=(isset($TEMPLATE['alert']) ? $TEMPLATE['alert'] : '')?>
	<button class="btn btn-lg btn-primary btn-block" type="submit"><?=$GLOBALS['LANG']['sign_in']?></button>
	</form>

	</div> 
</body>
</html>
