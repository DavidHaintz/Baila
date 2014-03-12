<?php

if(!isset($GLOBALS['in_script']) && !isset($GLOBALS['in_gate'])) die("Direct access not allowed!");

function db_query($sql)
{ 
    $args = func_get_args(); 
    $dbc = $GLOBALS['db_conn']; 
    $stmt = $dbc->prepare($sql); 
	
    for ($i = 1; $i < func_num_args(); $i++)
        $stmt->bindParam(":".$i, $args[$i]); 
	
    try { 
		$stmt->execute(); 
	} catch(PDOException $e) {
		die(infoMsg($e->getMessage().' from statement: "'.$sql.'" in '.dbgTrace().'.', 'danger', true));
	} 
	
    return $stmt;
}

function db_table_exists($table)
{
	$stmt = db_query("SHOW TABLES LIKE :1", $table);
	return ($stmt->rowCount() > 0);
}

function db_table_empty($table)
{	
	$stmt = db_query("SELECT 1 FROM " . $table);
	return ($stmt->rowCount() == 0);
}

?>