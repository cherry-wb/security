<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Database Importer | Functions</title>
</head>

<body>
<?php
require 'feedDownloadFunctions.php';

function downloadXML($url)
{
	$curl=curl_init();
	curl_setopt($curl,CURLOPT_URL,$url);
	curl_setopt($curl,CURLOPT_CONNECTTIMEOUT,5);
	curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($curl,CURLOPT_HEADER, 0);
	curl_setopt($curl,CURLOPT_PROXY, "proxy.van.sap.corp"); 
	curl_setopt($curl,CURLOPT_PROXYPORT, 8080);
	$buffer = curl_exec($curl);
	curl_close($curl);	
	if (empty($buffer)) 
	{
		die("Error: cURL buffer empty");
	}
	$doc = new DOMDocument;
	$doc-> loadXML($buffer);
	$doc->save('temp.xml') or die('error: save()');
	return true;
}

function xmlToArray($xmlfile)
{
	$xml = simplexml_load_file($xmlfile);
	$json = json_encode($xml);
	$array = json_decode($json, TRUE);
	
	return $array;
}

function getArrayKeys(array $array)
{
    $keys = array();
    foreach ($array as $key => $value) {
        $keys[] = $key;
 
        if (is_array($array[$key])) {
            $keys = array_merge($keys, getArrayKeys($array[$key]));
        }
    }
	foreach ($keys as $key => $val)
	{
		if(is_int($val))
		{
			unset($keys[$key]);
		}
	}
    return(array_unique($keys));
}



function importXMLToDB($tags, $fiid)
{
	$xml = new XMLReader;
	$xml->open('C://wamp/www/security_r2/temp.xml') or die('error');
	$dom = new DOMDocument;
	$existingItems = getExistingItems();	
	
	while($xml->read() && $xml->name!=$tags->item);
	while($xml->name == $tags->item)
	{
		$node = simplexml_import_dom($dom->importNode($xml->expand(), TRUE));
		$item->title = $node->{$tags->title};
		$item->description = $node->{$tags->description};
		$item->link = $node->{$tags->link};
		if(!alreadyExists($item, $existingItems))
			{
				insertItemIntoDatabase($item, $fiid);
				echo "<li>$item->title</li>";
			}
		$xml->next($tags->item);
	}
	$xml->close();
}

function echoTagOptions($tags) 
{
	foreach($tags as $key => $tag)
	{
		echo "<option value=$tag>$tag</option>";
	}
	echo "<option value="."''".">N/A</option>";
}
function echoSources()
{
	$sql =	"SELECT ".
				"* ".
			"FROM ".
				"sources ";	
	$query = mysql_query($sql) or die(mysql_error());
	while($row = mysql_fetch_object($query))
	{
		echo "<option value=$row->sid>$row->desc</option>";
	}
}
function addSourceFeedMap($sid, $fid)
{
	$sql =	"INSERT INTO ".
				"source_feed_map ".
			"VALUES ".
				"(NULL, $sid, $fid)";
	mysql_query($sql) or die(mysql_error());
}

function addFeedInfo($sid, $url)
{
	$mapid;
	$sql =	"SELECT ".
				"* ".
			"FROM ".
				"source_feed_map ".
			"WHERE ".
				"sid = $sid AND ".
				"fid = 0";
	$query = mysql_query($sql) or die(mysql_error);
	while($row = mysql_fetch_object($query))
	{
			$mapid = $row->mapid;
	}
	$sql =	"INSERT INTO ".
				"feed_info ".
			"VALUES ".
				"(NULL, $mapid, '$url')";
	mysql_query($sql) or die(mysql_error());
}

function getFiid($url)
{
	$fiid;
	$sql =	"SELECT ".
				"fiid ".
			"FROM ".
				"feed_info ".
			"WHERE ".
				"url = '$url'";
	$query = mysql_query($sql) or die(mysql_error());
	while($row = mysql_fetch_object($query))
	{
		$fiid = $row->fiid;
	}
	return $fiid;
}
?>
</body>
</html>