<?php

//header('refresh: 1'); //auto refresh every X seconds
ini_set('memory_limit', '512M'); // 512 Megabyte memory usage 
ini_set('max_execution_time', 300); //300 seconds timeout time
error_reporting(0);

$con = mysql_connect('localhost', 'root', '');
mysql_select_db('security_r2', $con) or die(mysql_error());

function getScoreFromFvids($fvidArray) {

    $curls = array();
    foreach ($fvidArray as $key => $fvid) {
	$sql = "SELECT " .
		"* " .
		"FROM " .
		"feed_vulns " .
		"WHERE " .
		"fvid = '$fvid'";
	$query = mysql_query($sql) or die(mysql_error());

	while ($row = mysql_fetch_object($query)) {
	    $curl = curl_init();
	    curl_setopt($curl, CURLOPT_URL, $row->url);
	    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($curl, CURLOPT_HEADER, 0);
	    curl_setopt($curl, CURLOPT_PROXY, "proxy.van.sap.corp");
	    curl_setopt($curl, CURLOPT_PROXYPORT, 8080);
	    $curlObject;
	    $curlObject->curl = $curl;
	    $curlObject->fiid = $row->fiid;
	    $curls[$row->fvid] = $curlObject;
	    unset($curlObject);
	}
    }


    $mh = curl_multi_init();

    foreach ($curls as $key => $curlObject)
	curl_multi_add_handle($mh, $curlObject->curl);

    $running = NULL;
    do
	curl_multi_exec($mh, $running); while ($running > 0);

    $data = array();

    foreach ($curls as $fvid => $curlObject) {
	$object;
	$object->curl = curl_multi_getcontent($curlObject->curl);
	$object->fiid = $curlObject->fiid;
	$data[$fvid] = $object;
	unset($object);
    }

    curl_multi_close($mh);

    $scores = array();
    $visited = array();
    foreach ($data as $fvid => $object) {
	$sql = "UPDATE feed_vulns SET visited=1 WHERE fvid=$fvid";
	mysql_query($sql) or die(mysql_error());
	array_push($visited, $fvid);

	$dom = new DOMDocument;
	$dom->loadHTML($object->curl);
	switch ($object->fiid) {
	    case 44:
		$tag = 'a';
		$item = 40;
		break;
	    case 13:
		$tag = 'a';
		$item = 40;
		break;
	    case 14:
		$tag = 'a';
		$item = 40;
		break;
	    case 15:
		$tag = 'a';
		$item = 40;
		break;
	    case 16:
		$tag = 'a';
		$item = 40;
		break;
	    case 17:
		$tag = 'a';
		$item = 40;
		break;
	    case 18:
		$tag = 'a';
		$item = 40;
		break;
	    case 19:
		$tag = 'a';
		$item = 40;
		break;
	    case 20:
		$tag = 'a';
		$item = 40;
		break;
	    case 21:
		$tag = 'a';
		$item = 40;
		break;
	    case 22:
		$tag = 'a';
		$item = 40;
		break;
	    case 23:
		$tag = 'a';
		$item = 40;
		break;
	    case 24:
		$tag = 'a';
		$item = 40;
		break;
	    case 25;
		$tag = 'td';
		$item = 28;
		break;
	    case 26:
		$tag = 'div';
		$item = 15;
		break;
	}
	if (isset($tag) && isset($item)) {
	    $items = $dom->getElementsByTagName($tag);
	    $score = trim($items->item($item)->nodeValue);
	    if ($object->fiid == 25) {
		$slashPos = strripos($score, '/');
		$score = substr($score, 0, $slashPos);
	    }
	    if (is_numeric($score)) {
		$scores[$fvid] = $score;
	    }
	}
    }
    var_dump($scores);
    return $scores;
}

function updateScore($returnFromGetScoreFromFvids) {

    foreach ($returnFromGetScoreFromFvids as $fvid => $score) {
	$sql = "UPDATE 
		    feed_vulns
		SET 
		    score = '$score', 
		    visited = 1 
		WHERE 
		    fvid = '$fvid'";
	mysql_query($sql) or die(mysql_error());
    }
}

$nist = array();
$sql = "SELECT 
	    * 
	FROM 
	    feed_vulns 
	WHERE 
	    visited = 0 AND 
	    score IS NULL AND 
	    (fiid = '26' OR 
	    fiid = '44' OR 
	    fiid = '13' OR 
	    fiid = '14' OR 
	    fiid = '15' OR 
	    fiid = '16' OR 
	    fiid = '17' OR 
	    fiid = '18' OR 
	    fiid = '19' OR 
	    fiid = '20' OR 
	    fiid = '21' OR 
	    fiid = '22' OR 
	    fiid = '23' OR 
	    fiid = '24' OR 
	    fiid = '25')
	ORDER BY 
	    date DESC 
	LIMIT 
	    0 , 250";
$query = mysql_query($sql) or die(mysql_error());
while ($row = mysql_fetch_object($query))
    array_push($nist, $row->fvid);


updateScore(getScoreFromFvids($nist));
?>