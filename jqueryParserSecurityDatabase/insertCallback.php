<?php

if (isset($_GET['fvid'])) {

    $fvid = $_GET['fvid'];
    $score = $_GET['score'];

    $con = mysql_connect('localhost', 'root', '');
    mysql_select_db('security_r2', $con) or die(mysql_error());

    if (is_numeric($score)) {
	$sql = "UPDATE feed_vulns SET score = '$score', visited=1 WHERE fvid='$fvid'";
	mysql_query($sql) or die(mysql_error());
    } else {
	$sql = "UPDATE feed_vulns SET visited=1 WHERE fvid='$fvid'";
	mysql_query($sql) or die(msyql_error());
    }
}else
    echo 'huh?';
?>
