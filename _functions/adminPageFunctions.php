<?php

function addSource($desc, $link, $status)
{
	$sid;
	$sql =	"INSERT INTO " .
				"sources ".
			"VALUES " .
				"(NULL, '$desc', '$link', '$status')";
	mysql_query($sql) or die(mysql_error());
	
	$sql =	"SELECT ".
				"sid ".
			"FROM ".
				"sources ".
			"WHERE ".
				"link = '$link'";
	$query = mysql_query($sql) or die(mysql_error());
	while($row = mysql_fetch_object($query))
	{
		$sid = $row->sid;
	}
	
	$sql =	"INSERT INTO ".
				"source_feed_map ".
			"VALUES ".
				"(NULL, $sid, 1)";
	mysql_query($sql) or die(mysql_error());
	return $sid;
}



function updateSourceStatus($sid, $_POST)
{
	if (array_key_exists($sid, $_POST)) 
	{
		$sql =	"UPDATE " . 
					"sources " .
				"SET " .
					"status=1 " .
				"WHERE " .
					"sid=$sid";
		mysql_query($sql) or die(mysql_error($con));
	}
	else 
	{
		$sql =	"UPDATE " . 
					"sources " .
				"SET " .
					"status=0 " .
				"WHERE " .
					"sid=$sid";
		mysql_query($sql) or die(mysql_error($con));
	}	
}
function echoSourceStatus($query)
{
	$sql = 	"SELECT " .
				"* " .
			"FROM " .
				"sources";
	$query = mysql_query($sql) or die(mysql_error());
	
	$sources = array();
		
	while ($row = mysql_fetch_object($query))
	{
		
		$source->desc = $row->desc;
		$source->link = $row->link;
		$source->status = $row->status;
		$source->sid = $row->sid;
		$source->feeds = array();
		
		$innerSQL =	"SELECT ".
						"url ".
					"FROM ".
						"feed_info AS fi, ".
						"source_feed_map AS sfm ".
					"WHERE ".
						"fi.mapid = sfm.mapid AND ".
						"sfm.sid = '$source->sid'";
		$innerQuery = mysql_query($innerSQL) or die(mysql_error());
		while($row=mysql_fetch_object($innerQuery))
		{
			array_push($source->feeds, $row->url);
		}
		array_push($sources, $source);
		unset($source);
	}
	
	foreach($sources as $key => $s)
	{
		if($s->status =='1')
		{
			$status = "<input type='checkbox' name='$s->sid' value='$s->desc' checked>";
		} 
		else
		{
			$status = "<input type='checkbox' name='$s->sid' value='$s->desc'>";
		}
		echo	"<div class='source'>
					<div class='source-info'>
						<div class='source-desc'>$s->desc</div>
						<div class='source-url'><a href='$s->link'>$s->link</a></div>
						<div class='source-enable'>$status</div>
					</div>
					<div class='source-feeds'>
						<ul>";
		if(empty($s->feeds))
		{
			echo			"<li>No Feeds</li>";	
		}
		foreach($s->feeds as $key => $feed)
		{
			echo 			"<li><a href='$feed'>$feed</a></li>";
		}
		echo 			'</ul>
					</div>
				</div>';
	}
}


?>