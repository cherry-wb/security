<?php

function insertItemIntoDatabase($item, $fiid) {
    $title = mysql_real_escape_string($item->title);
    $desc = mysql_real_escape_string($item->description);
    $link = mysql_real_escape_string($item->link);

    $sql = "INSERT INTO 
		feed_vulns 
	    VALUES 
		(NULL, '$fiid', '$title', '$desc', '$link', CURDATE(), NULL, 0)";

    mysql_query($sql) or die(mysql_error());
}

function getExistingItems($fiid) {
    $existingItems = array();

    $sqlCheck = "SELECT 
		    * 
		 FROM 
		    feed_vulns";

    $query = mysql_query($sqlCheck) or die(mysql_error());

    while ($row = mysql_fetch_object($query)) {
	array_push($existingItems, $row->url);
    }
    return $existingItems;
}

function alreadyExists($item, $existingItems) {
    if (in_array((string) $item->link, $existingItems)) {
	return TRUE;
    } else {
	return FALSE;
    }
}

?>