<?php
/**********************************/
/************* Config *************/
/**********************************/

$db_host	= "localhost";
$db_name	= "webpanel";
$db_user	= "root";
$db_pass	= "";

$bot_pass	= "123456";


/**********************************/
/************** Code **************/
/**********************************/

$bot_name		= "Baila";
$conn_int		= 5;
$dead_int		= 5;
try 
{ 
    $db_conn = new PDO("mysql:host=$db_host;dbname=$db_name",$db_user,$db_pass);
	$db_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} 
catch(PDOException $e) 
{ 
    echo "<div id=\"error\">Error connecting:".$e->getMessage()."</div>"; 
}
?>