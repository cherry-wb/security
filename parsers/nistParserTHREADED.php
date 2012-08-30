<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>NIST Parser | Threaded</title>
    </head>

    <body>

	<?php
	ini_set('memory_limit', '512M'); // 512 Megabyte memory usage 
	ini_set('max_execution_time', 300); //300 seconds timeout time


	$con = mysql_connect('localhost', 'root', '');
	mysql_select_db('security_r2', $con) or die(mysql_error());

	function getScoreFromFid($fiid) {
	    $sql = "SELECT " .
		    "* " .
		    "FROM " .
		    "feed_vulns " .
		    "WHERE " .
		    "fiid = '$fiid' " .
		    "ORDER BY " .
		    "date DESC " .
		    "LIMIT 0, 200";
	    $query = mysql_query($sql) or die(mysql_error());

	    $curls = array();
	    while ($row = mysql_fetch_object($query)) {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $row->url);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_PROXY, "proxy.van.sap.corp");
		curl_setopt($curl, CURLOPT_PROXYPORT, 8080);
		$curls[$row->fvid] = $curl;
	    }
	    $mh = curl_multi_init();

	    foreach ($curls as $key => $curl)
		curl_multi_add_handle($mh, $curl);

	    $running = NULL;
	    do
		curl_multi_exec($mh, $running); while ($running > 0);

	    $data = array();

	    foreach ($curls as $key => $curl)
		$data[$key] = curl_multi_getcontent($curl);

	    curl_multi_close($mh);

	    foreach ($data as $key => $html) {
		$dom = new DOMDocument;
		$dom->loadHTML($html);
		$items = $dom->getElementsByTagName('a');
		$score = trim($items->item(40)->nodeValue);
		$data[$key] = $score;
	    }

	    var_dump($data);
	}

	getScoreFromFid(15);
	?>
    </body>
</html>