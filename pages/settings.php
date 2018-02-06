<?php
$TEMPLATE['site'] = $GLOBALS['LANG']['settings'];
if (getRole() > 1)
	$TEMPLATE['text'] = '<div class="alert alert-danger">'.$GLOBALS['LANG']['err_no_permission'].'</div>';
else
{
	$TEMPLATE['text'] = '';
	if ($_POST)
	{
		foreach ($_POST as $key => $val)
		{
			$key = str_replace("_", " ", $key);
			db_query("UPDATE `settings` SET `value` = :1 WHERE `name` = :2", $val, $key);
			$TEMPLATE['text'] .= $GLOBALS['LANG']['updated']." ".htmlentities($key).".<br />";
		}
	}
	$TEMPLATE['text'] .= '<table>
							<form action="index.php?p=settings" method="POST">';
	$stmt = db_query("SELECT * FROM `settings`");
	while ($row = $stmt->fetch())
		$TEMPLATE['text'] .= "	<tr>
									<td>".htmlentities(ucfirst($row['name']))."</td>
									<td style=\"padding-left: 10px;\"><input class=\"form-control\" type=\"text\" name=\"".htmlentities($row['name'])."\" value=\"".htmlentities($row['value'])."\" /></td>
								</tr>";
	$TEMPLATE['text'] .= '<tr><td></td><td style="float:right"><input class="btn btn-md btn-primary" type="submit" value="'.$GLOBALS['LANG']['save'].'" /></td></tr>
						</form></table>';
}
$TEMPLATE['js'] = '';
?>