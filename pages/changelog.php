<?php

if(!isset($GLOBALS['in_script'])) die("Direct access not allowed!");

$TEMPLATE['js'] = "";
$TEMPLATE['site'] = "Updates";

$url = "https://github.com/IRET0x00/Baila/commits/master";

if (function_exists("curl_init"))
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // fix issuer err
	
	$response = curl_exec($ch);
	
	if($response == false)
		$TEMPLATE['text'] = infoMsg(curl_error($ch)." from ".dbgTrace().'.');
	
	curl_close($ch);
}
else
{
	$response = file_get_contents($url);
	
	if($response == false)
		$TEMPLATE['text'] = infoMsg("Unknown error while connecting to GitHub.");
}

if($response)
{
	$dom = new DOMDocument;
	if (@$dom->loadHTML($response))
	{
		$div = null;
		$divs = $dom->getElementsByTagName('div');
		
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
		
		$TEMPLATE['text'] = '<table class="table table-hover table-vcenter" style="margin-left:auto;margin-right:auto;width:auto;"><tr><th>Date</th><th style="padding-left:40px;">Description</th></tr>';
		
		for ($i = 0; $i < $heads->length; $i++)
		{
			$lis = $ols->item($i)->getElementsByTagName('li');
			$TEMPLATE['text'] .= '<tr><td>'.$heads->item($i)->textContent.'</td><td><ul style="list-style:none;">';
			
			for ($j = 0; $j < $lis->length; $j++)
			{
				$a = $lis->item($j)->getElementsByTagName('p')->item(0)->getElementsByTagName('a')->item(0);
				$TEMPLATE['text'] .= '<li>'.$a->textContent.'</li>';
			}
			
			$TEMPLATE['text'] .= '</ul></td></tr>';
		}
		
		$TEMPLATE['text'] .= '</table>';
	}
}

?>