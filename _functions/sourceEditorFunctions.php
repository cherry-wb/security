<?php

function getUrlType($url) {

    $sql = "SELECT " .
	    "sfm.fid " .
	    "FROM " .
	    "source_feed_map AS sfm, " .
	    "feed_info AS fi " .
	    "WHERE " .
	    "fi.url = '$url' AND " .
	    "sfm.mapid = fi.mapid";
    $query = mysql_query($sql) or die(mysql_error());

    $fid = -1;
    while ($row = mysql_fetch_object($query))
	$fid = $row->fid;

    return $fid;
}

function echoSourceStatus() {
    $sql = "SELECT " .
	    "* " .
	    "FROM " .
	    "sources";
    $query = mysql_query($sql) or die(mysql_error());

    $sources = array();

    while ($row = mysql_fetch_object($query)) {
	$source;
	$source->desc = $row->desc;
	$source->link = $row->link;
	$source->status = $row->status;
	$source->sid = $row->sid;
	$source->feeds = array();

	$innerSQL = "SELECT " .
		"fi.url, sfm.fid " .
		"FROM " .
		"feed_info AS fi, " .
		"source_feed_map AS sfm " .
		"WHERE " .
		"fi.mapid = sfm.mapid AND " .
		"sfm.sid = '$source->sid'";
	$innerQuery = mysql_query($innerSQL) or die(mysql_error());
	while ($row = mysql_fetch_object($innerQuery)) {
	    if ($row->fid == '1') {
		$feed;
		$feed->url = $row->url;
		$feed->type = 'rss';
		array_push($source->feeds, $feed);
		unset($feed);
	    } else if ($row->fid == '2') {
		$feed;
		$feed->url = $row->url;
		$feed->type = 'html';
		array_push($source->feeds, $feed);
		unset($feed);
	    } else if ($row->fid == '0') {
		$feed;
		$feed->url = $row->url;
		$feed->type = 'xml';
		array_push($source->feeds, $feed);
		unset($feed);
	    }
	}
	array_push($sources, $source);
	unset($source);
    }

    foreach ($sources as $key => $s) {
	if ($s->status == '1') {
	    $status = "<input type='checkbox' name='$s->sid' value='$s->desc' checked>";
	} else {
	    $status = "<input type='checkbox' name='$s->sid' value='$s->desc'>";
	}
	echo "	<div class='source'>
		    <div class='source-info'>
			    <div class='source-desc'>$s->desc</div>
			    <div class='source-url'><a href='$s->link'>$s->link</a></div>
			    <form method='get' action='sourceEditor.php'>
				    <input type='hidden' name='sid' value='$s->sid'>
				    <input type='submit' name='edit' value='Edit' style='float:right;'>
			    </form>
		    </div>
		    <div class='source-feeds' style='margin:0 0 1em 0;'>

			<table style='width:97%; float:right;'>";
	if (empty($s->feeds)) {
	    echo "<tr><td><li>No Feeds</li></td></tr>";
	}
	foreach ($s->feeds as $key => $feed) {
	    echo "  <tr><td style='width:92%;'><li><a href='$feed->url'>$feed->url</a></li></td>
			<td style='float:left;'>$feed->type</td></tr>";
	}
	echo '          </table>
		    </div>
		</div>';
    }
}

function echoFeedForm($sid) {

    $sql = "SELECT " .
	    "* " .
	    "FROM " .
	    "sources AS s, " .
	    "source_feed_map AS sfm, " .
	    "feed_info AS fi, " .
	    "feed_types AS ft " .
	    "WHERE " .
	    "s.sid = '$sid' AND " .
	    "'$sid' = sfm.sid AND " .
	    "sfm.fid = ft.fid AND " .
	    "sfm.mapid = fi.mapid";
    $query = mysql_query($sql) or die(mysql_error());
    if (mysql_num_rows($query) == 0)
	echo 'No Feeds Exist';
    while ($row = mysql_fetch_object($query)) {
	$html = "   <tr>
			<td style='width:80% ; padding:0 0 0 20px;'>$row->url</td>
			<td style=''>$row->type</td>
			<td>
			    <form method='GET'>
				<input type='hidden' name='fiid' value=$row->fiid></input>
				<input type='hidden' name='editFeed' value='edit'></input>
				<input type='submit' value='Edit'></input>
			    </form>
			</td>
			<td style='float:right;'>
			    <form method='GET'>
				<input type='hidden' name='fiid' value=$row->fiid></input>
				<input type='hidden' name='deleteFeed' value='delete'></input>
				<input type='submit' value='Delete' onClick='return confirmFeedDelete()'>
			    </form>
			</td>
		    </tr>";
	echo $html;
    }
}

function removeSource($link) {

    $sql = "SELECT " .
	    "* " .
	    "FROM " .
	    "sources " .
	    "WHERE " .
	    "link = '$link'";
    $query = mysql_query($sql) or die(mysql_error());
    while ($row = mysql_fetch_object($query)) {
	$sid = $row->sid;
    }

    $sql = "DELETE " .
	    "FROM " .
	    "sources " .
	    "WHERE " .
	    "sid = '$sid'";
    mysql_query($sql) or die(mysql_error());
}

function addRSSFeed($feedURL, $sid) {

    $sql = "SELECT * FROM source_feed_map WHERE sid = '$sid' AND fid = '1'";
    $query = mysql_query($sql) or die(mysql_error());
    $rows = mysql_num_rows($query);
    if ($rows == 0) {
	$sql = "INSERT INTO source_feed_map VALUES(NULL, '$sid', '1')";
	mysql_query($sql) or die(mysql_error());
    }
    $sql = "SELECT " .
	    "mapid " .
	    "FROM " .
	    "source_feed_map " .
	    "WHERE " .
	    "sid = $sid AND " .
	    "fid = '1'";
    $query = mysql_query($sql) or die(mysql_error());
    while ($row = mysql_fetch_object($query)) {
	$mapid = $row->mapid;
    }
    $sql = "INSERT INTO " .
	    "feed_info " .
	    "VALUES " .
	    "(NULL, $mapid, '$feedURL')";
    mysql_query($sql);
}

function removeXMLFeed($fiid) {
    $sql = "DELETE FROM " .
	    "feed_info " .
	    "WHERE " .
	    "fiid = $fiid";
    mysql_query($sql) or die(mysql_error());
}

function showFeedEditor($fiid) {
    $sql = "SELECT * ".
	    "FROM feed_info AS fi, source_feed_map AS sfm ".
	    "WHERE '$fiid'=fi.fiid AND fi.mapid=sfm.mapid";
    $query = mysql_query($sql) or die(mysql_error());
    
    while($row=mysql_fetch_object($query)){
	
    }

}

?>