<?php

if (getRole() > 1)
	$TEMPLATE['text'] = '<div class="alert alert-danger">'.$GLOBALS['LANG']['err_no_permission'].'</div>';
else
{
	$TEMPLATE['site'] = $GLOBALS['LANG']['updates'];
	$url = 'https://api.github.com/repos/DavidHaintz/Baila/commits';
    $response = false;
    
	if (function_exists('curl_init'))
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_STDERR, $curl_log);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_USERAGENT, "Baila Webpanel");
		$response = curl_exec($ch);
        $error = curl_error($ch);
		curl_close($ch);
	}
	else
		$TEMPLATE['text'] = $GLOBALS['LANG']['err_curl_not_installed'];
	
    if ($response !== true && function_exists("file_get_contents")) {
        // curl failed => try file_gets_contents
        $opts = array(
          'http'=>array(
            'user_agent' => 'My company name',
            'method'=>"GET",
            'header'=> implode("\r\n", array(
              'Content-type: text/plain;'
            ))
          )
        );

        $context = stream_context_create($opts);
        $response = file_get_contents($url, false, $context);
    }
    
	if ($response && !empty($response))
	{
        try {
            $data = json_decode($response);
            if (count($data) > 0)
                $worked = true;
        } catch (Exception $e) {
            $TEMPLATE['text'] = $GLOBALS['LANG']['err_curl_connect_github'];
        }
        
        if ($worked) {
            $TEMPLATE['text'] = '<table class="table table-hover">
                                    <tr>
                                        <th>'.$GLOBALS['LANG']['date'].'</th>
                                        <th>'.$GLOBALS['LANG']['description'].'</th>
                                    </tr>';
            foreach ($data as $commit)
            {
                $TEMPLATE['text'] .= "<tr>
                                        <td>".date("d.m.Y H:i", strtotime($commit->commit->author->date))."</td>
                                        <td><a href=\"{$commit->html_url}\" target=\"_blank\">".htmlentities($commit->commit->message)."</a></td>
                                    </tr>";
            }
            $TEMPLATE['text'] .= '</table>';
        }
        else
            $TEMPLATE['text'] = $GLOBALS['LANG']['err_curl_connect_github'];
	}
	else {
		//$TEMPLATE['text'] = $GLOBALS['LANG']['err_curl_connect_github']."<br /><br />Error: $error<br /><br />Log:<br />$curl_log<br />";
		$TEMPLATE['text'] = $GLOBALS['LANG']['err_curl_connect_github'];
    }
}
					
$TEMPLATE['js'] = '';
?>