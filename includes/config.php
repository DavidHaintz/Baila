<?php

if(!isset($GLOBALS['in_script']) && !isset($GLOBALS['in_gate'])) die("Direct access not allowed!");

$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "baila";

$bot_pass = "123456";
$bot_name = "S3 Bot";

$conn_int = 5;
$dead_int = 5;

/*** DONT EDIT BELOW THIS LINE ***/

try 
{ 
    $db_conn = new PDO('mysql:host='.$db_host.';dbname=',$db_user, $db_pass); 
	$db_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} 
catch(PDOException $e) 
{ 
	die(errorMsg($e->getMessage(), 'danger', true));
}

?>