<?php
function getFeedInfo($fiid) {
    $sql = "SELECT * ".
	    "FROM feed_info AS fi, source_feed_map AS sfm, feed_types AS ft ".
	    "WHERE '$fiid'=fi.fiid AND fi.mapid=sfm.mapid AND sfm.fid=ft.fid";
    $query = mysql_query($sql) or die(mysql_error());
    
    while($row=mysql_fetch_object($query)){
	
	$feed = $row;
    }

    return $feed;
}

?>
