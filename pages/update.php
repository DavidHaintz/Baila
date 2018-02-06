<?php

if (getRole() > 1)
	$TEMPLATE['text'] = '<div class="alert alert-danger">You don\'t have permission to see this page.</div>';
else
{
	$TEMPLATE['site'] = $GLOBALS['LANG']['updates'];
	$url = 'https://api.github.com/repos/IRET0x00/Baila/commits';
    
	if (function_exists('curl_init'))
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_USERAGENT, "Baila Webpanel");
		$response = curl_exec($ch);
        if (!$response)
		  $error = curl_error($ch);
		curl_close($ch);
	}
	else
		$TEMPLATE['text'] = $GLOBALS['LANG']['err_curl_not_installed'];
	
	
	if ($response)
	{
		$data = json_decode($response);
		$TEMPLATE['text'] = '<table class="table table-hover">
								<tr>
									<th>'.$GLOBALS['LANG']['date'].'</th>
									<th>'.$GLOBALS['LANG']['description'].'</th>
								</tr>';
		foreach ($data as $commit)
		{
			$TEMPLATE['text'] .= "<tr>
									<td>".date("d.m.Y H:i", strtotime($commit->commit->author->date))."</td>
									<td><a href=\"{$commit->html_url}\" target=\"_blank\">{$commit->commit->message}</a></td>
								</tr>";
		}
		$TEMPLATE['text'] .= '</table>';
	}
	else
		$TEMPLATE['text'] = $GLOBALS['LANG']['err_curl_connect_github']."<br /><br />".$error;
}
					
$TEMPLATE['js'] = '';
?>