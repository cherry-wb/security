<html>
    <head>
	<meta http-equiv="refresh" content="1">
	<title>jQuery | Parser | SecurityDatabase</title>
	<script type='text/javascript' src='../_scripts/jquery.js'></script>
	<script type="text/javascript">
	    $(document).ready(function() {
		
		values = $('td[style="width:120px; text-align:left;white-space:nowrap;"]');
		fvid = $('#fvid').contents()[0].data;
		
		
		//document.write('<div id="callback"></div>');
		if (values.length == 0) {
		    $(this).load("visitedCallback.php?fvid="+fvid);
		} else {
		    score = values[1].innerText;
		    $(this).load("insertCallback.php?fvid="+fvid+"&score="+score);
		}
		


	    });
	    
	</script>

    <body>

	<?php
	$con = mysql_connect('localhost', 'root', '');
	mysql_select_db('security_r2', $con) or die(mysql_error());

	$sql = "SELECT 
		    * 
		FROM 
		    feed_vulns 
		WHERE 
		    fiid='11' AND 
		    score IS NULL AND 
		    visited='0' 
		LIMIT 1";

	$query = mysql_query($sql);
	while ($row = mysql_fetch_object($query)) {

	    $fiid = $row->fiid;
	    $fvid = $row->fvid;
	    $url = $row->url;
	    var_dump($fvid);
	    var_dump($url);
	}
	if(!isset($url)) die('DONE');
	


	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_HEADER, 0);
	curl_setopt($curl, CURLOPT_PROXY, "proxy.van.sap.corp");
	curl_setopt($curl, CURLOPT_PROXYPORT, 8080);
	curl_setopt($curl, CURLOPT_AUTOREFERER, true);
	curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
	$buffer = curl_exec($curl);
	curl_close($curl);

	echo "<div id='callback'></div>";
	echo "<div id='url'>$url</div>";
	echo "<div id='fiid'>$fiid</div>";
	echo "<div id='fvid'>$fvid</div>";


	echo $buffer;
	?>


    </body
</head>
</html>