<html>
    <head><title>test</title></head>
    <script type='text/javascript' src="../_scripts/jquery.js"></script>
    <script type='text/javascript'>

    </script>
    <body>
	<?php

	//require '../_scripts/htmldom/simple_html_dom.php';
//	require '/_scripts/htmldom/simple_html_dom.php';
	function ibmxforceParser($keyword) {

	    //$keyword = 'apache struts';
	    $keyword = str_replace(' ', '+', $keyword);

	    $url = "http://webapp.iss.net/Search.do?keyword=$keyword&searchType=vuln&start=0&sort=date%3aD%3aS%3ad1";
	    $urlrel = "http://webapp.iss.net/Search.do?keyword=$keyword&searchType=vuln&x=0&y=0";

	    $curl = curl_init();
	    curl_setopt($curl, CURLOPT_URL, $url);
	    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($curl, CURLOPT_HEADER, 0);
	    curl_setopt($curl, CURLOPT_PROXY, "proxy.van.sap.corp");
	    curl_setopt($curl, CURLOPT_PROXYPORT, 8080);
	    $buffer = curl_exec($curl);
	    curl_close($curl);

	    if (empty($buffer)) {
		die("Error: cURL buffer empty from: $url");
	    }


	    $html = new simple_html_dom();
	    $html->load($buffer);
	    //var_dump($html);

	    $tables = $html->find('table[width="560"]') or die('none found');
	    //echo $tables[1];
	    if (empty($tables[1]))
		echo ('None Found; Consider broadening your search or removing version numbers if present');
	    else {

		$entries = $tables[1]->find('div[style="margin-bottom: 1.5em; "]');
		//foreach($entries as $key => $entry) echo $entry;
		foreach ($entries as $key => $entry) {
		    $title = $entry->find('span[style="font-size: 1.1em; font-weight: bold"]');
		    echo $title[0] . '<br>';
		    $description = $entry->find('div[style="margin-left: 2em; padding-bottom: 10px;"]');
		    echo $description[0] . '<br>';
		    //$date = substr((string)$description[0], -50);
		    //$date = substr($date, -10);
		    //echo $date;
		}


		$results = $html->find('div[style="font-family: Arial, Helvetica, sans-serif; font-size: 10pt; background-color: #efefef; padding-left:2px;padding-top: 5px; padding-bottom:5px; padding-right:2px; width: 100%;"]') or die('none found');
		//if($results[0]->children[1]) echo $results[0]->children[1]; //total results
	    }
	}
	?>
    </body>
</html>