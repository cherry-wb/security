<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>

<?php

	//require '../_scripts/htmldom/simple_html_dom.php';
	//require '/_scripts/htmldom/simple_html_dom.php';
	function osvdbParser($keyword) {
		
		$keyword = str_replace(' ', '+', $keyword);
		
		$url = "http://osvdb.org/search/search?search%5Bvuln_title%5D=$keyword&search%5Btext_type%5D=titles&search%5Bs_date%5D=&search%5Be_date%5D=&search%5Brefid%5D=&search%5Breferencetypes%5D=&search%5Bvendors%5D=&search%5Bcvss_score_from%5D=&search%5Bcvss_score_to%5D=&search%5Bcvss_av%5D=*&search%5Bcvss_ac%5D=*&search%5Bcvss_a%5D=*&search%5Bcvss_ci%5D=*&search%5Bcvss_ii%5D=*&search%5Bcvss_ai%5D=*&kthx=search";
		
		$curl=curl_init();
		curl_setopt($curl,CURLOPT_URL,$url);
		curl_setopt($curl,CURLOPT_CONNECTTIMEOUT,5);
		curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($curl,CURLOPT_HEADER, 0);
		curl_setopt($curl,CURLOPT_PROXY, "proxy.van.sap.corp"); 
		curl_setopt($curl,CURLOPT_PROXYPORT, 8080);
		$buffer = curl_exec($curl);
		curl_close($curl);
		
		if (empty($buffer)) die("Error: cURL buffer empty from: $url");
		
		$html = new simple_html_dom();
		$html->load($buffer);
		
		foreach($html->find('a') as $key => $link)
		{
			$temp = 'http://www.osvdb.org'.$link->href;
			$link->href = $temp;
		}
		
		$odd = $html->find('tr[class="odd"]');

		$orderedOdd = array();
		foreach($odd as $row)
		{
			array_push($orderedOdd, array_pop($odd));
		}
		$even = $html->find('tr[class="even"]');
		$orderedEven = array();
		foreach($even as $row)
		{
			array_push($orderedEven, array_pop($even));
		}
		
		if(empty($orderedOdd)&&empty($orderedEven)) echo 'None Found; Consider broadening your search or removing version numbers if present';
		
		while(!empty($orderedOdd)||!empty($orderedEven))
		{
			echo array_pop($orderedOdd).'<br>';
			echo array_pop($orderedEven).'<br>';	
		}
		
		
		$results = $html->find('td[width="40%"]');
		$number = 0;
		if(!empty($results))
		{
			$number = (string)$results[0];
			
			$last = strpos($number,':');	
			$number = substr($number, $last);
			//echo $number;
		}
		//echo $results[0];
	
	}

//osvdbParser('apache tomcat');
?>

</body>
</html>