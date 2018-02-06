<?php
$TEMPLATE['site'] = "Settings";
if (getRole() > 1)
	$TEMPLATE['text'] = '<div class="alert alert-danger">You don\'t have permission to see this page.</div>';
else
{
	$TEMPLATE['text'] = '';
	if ($_POST)
	{
		foreach ($_POST as $key => $val)
		{
			$key = str_replace("_", " ", $key);
			db_query("UPDATE `settings` SET `value` = :1 WHERE `name` = :2", $val, $key);
			$TEMPLATE['text'] .= "Updated ".htmlentities($key).".<br />";
		}
	}
	$TEMPLATE['text'] .= '<table>
							<form action="index.php?p=settings" method="POST">';
	$stmt = db_query("SELECT * FROM `settings`");
	while ($row = $stmt->fetch())
		$TEMPLATE['text'] .= "	<tr>
									<td>{$row['name']}</td>
									<td style=\"padding-left: 10px;\"><input type=\"text\" name=\"".htmlentities($row['name'])."\" value=\"".htmlentities($row['value'])."\" /></td>
								</tr>";
	$TEMPLATE['text'] .= '<tr><td></td><td style="float:right"><input type="submit" value="Save" /></td></tr>
						</form></table>';
}
$TEMPLATE['js'] = '';
?>