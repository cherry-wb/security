<?php

var_dump($_GET['fvid']);
if (isset($_GET['fvid'])) {

    $fvid = $_GET['fvid'];
    $score = $_GET['score'];
    var_dump($score);

    $con = mysql_connect('localhost', 'root', '');
    mysql_select_db('security_r2', $con) or die(mysql_error());

    $sql = "UPDATE feed_vulns SET score = '$score', visited=1 WHERE fvid='$fvid'";
    mysql_query($sql) or die(mysql_error());
}else
    echo 'huh?';
?>
