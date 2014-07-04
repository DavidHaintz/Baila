<?php

if (getRole() > 1)
	$TEMPLATE['text'] = '<div class="alert alert-danger">You don\'t have permission to see this page.</div>';
else
{
	$TEMPLATE['site'] = "Updates";
	$url = 'https://github.com/IRET0x00/Baila/commits/master';
	if (function_exists('curl_init'))
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		$response = curl_exec($ch);
		curl_close($ch);
	}
	else
		$response = file_get_contents($url);
	
	
	$dom = new DOMDocument;
	if ($response && @$dom->loadHTML($response))
	{
		$divs = $dom->getElementsByTagName('div');
		$div = null;
		for ($i = 0; $i < $divs->length; $i++)
		{
			if (@$divs->item($i)->attributes->getNamedItem('class')->value == "js-navigation-container js-active-navigation-container")
			{
				$div = $divs->item($i);
				break;
			}
		}
		$heads = $div->getElementsByTagName('h3');
		$ols = $div->getElementsByTagName('ol');
		
		$TEMPLATE['text'] = '<table class="table table-hover">
								<tr>
									<th>Date</th>
									<th>Description</th>
								</tr>';
		for ($i = 0; $i < $heads->length; $i++)
		{
			$lis = $ols->item($i)->getElementsByTagName('li');
			$TEMPLATE['text'] .= '<tr>
									<td>'.$heads->item($i)->textContent.'</td>
									<td>
										<ul style="list-style: none;">';
			for ($j = 0; $j < $lis->length; $j++)
			{
				$a = $lis->item($j)->getElementsByTagName('p')->item(0)->getElementsByTagName('a')->item(0);
				$TEMPLATE['text'] .= '		<li>'.$a->textContent.'</li>';
			}
			$TEMPLATE['text'] .= '		</ul>
									</td>
								  </tr>';
		}
		$TEMPLATE['text'] .= '</table>';
	}
	else
		$TEMPLATE['text'] = "Error connecting to GitHub.";
}
					
$TEMPLATE['js'] = '';
?>