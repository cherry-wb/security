<html>
    <head>
	<title>Reports</title>
	<link rel="stylesheet" type="text/css" href="_css/styles.css"/>
    </head>

    <body>
	<div id="header">
	    <div id="sapLogo" style="display:inline-block;">
		<img src="../_images/sap.png" width="258" height="113">
	    </div>
	    <h1 style="display:inline-block; color:white;">Reports</h1>
	</div>
	<?php
	foreach (glob("_classes/*.php") as $filename) {
	    require "$filename";
	}

	$con = mysql_connect('localhost', 'root', '');
	mysql_select_db('security_r2', $con);

	$sql = "SELECT 
		    *
		FROM 
		    reports AS r,
		    report_vuln_map AS rvm,
		    users AS u,
		    feed_vulns AS fv
		WHERE
		    u.uid = r.uid AND
		    r.rid = rvm.rid AND
		    rvm.fvid = fv.fvid";

	$sql = "SELECT
		    *
		FROM
		    reports";
	echo mysql_num_rows(mysql_query($sql));

	$query = mysql_query($sql) or die(mysql_error());


	while ($row = mysql_fetch_object($query)) {

	    // var_dump($row);

	    $report = new report();
	    $report->set($row->rid);
	    var_dump($report);
	}
	?>

    </body>
</html>
