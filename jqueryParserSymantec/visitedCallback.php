<?php

if (isset($_GET['fvid'])) {

    $con = mysql_connect('localhost', 'root', '');
    mysql_select_db('security_r2', $con) or die(mysql_error());

    $fvid = $_GET['fvid'];

    $sql = "UPDATE
		feed_vulns
	    SET
		visited = 1
	    WHERE
		fvid = '$fvid'";
    
    mysql_query($sql) or die(mysql_error());
    echo 'VISTED CALLBACK';
} else echo 'HUH@!#@!@#';
?>
