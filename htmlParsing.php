<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>HTML Parsing</title>
</head>


<?php
$url = 'http://webapp.iss.net/Search.do?keyword=apache&searchType=vuln&x=0&y=0';
$url = 'http://www.onapsis.com/research-advisories.php';

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
	die("Error: cURL buffer empty from: $url");
}
//echo $buffer;

$dom = new DOMDocument;
$dom->loadHTML($buffer);
$table = $dom->getElementsByTagName('li');
var_dump($table->item(1)->nodeValue);

/*$dom = new DOMDocument();
@$dom->loadHTML($buffer);
$table = $dom->getElementsByTagName('table');
$rows = $table->item(0)->getElementsByTagName('tr');
foreach($rows as $row)
{
	$cols = $row->getElementsByTagName('td');
	for($i=0;$i<=$cols->length;$i++)
	{
		echo $cols->item($i)->nodeValue;
	}
}
for($i=0;$i<=$items->length;$i++)
{
	echo $items->item($i)->nodeValue . "<br>";	
}
$test = $items->item(1);
$dom->importNode($test);
$dom->preserveWhiteSpace = FALSE;
$items = $dom->getElementsByTagName('div');
for($i=0;$i<=$items->length;$i++)
{
	echo $items->item($i)->nodeValue;	
}

$entries = $items->item(1)->nodeValue;
$test = explode('...', $entries);
var_dump($test);*/
?>

</html>
