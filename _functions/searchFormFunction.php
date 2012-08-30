<?php

function showSearchBar($keyword, $howMany) {
    echo "	<div id='header'>
			  <div id='sapLogo'> <img src='_images/sap.png' width='258' height='113'> </div>
			  <div id='searchOptions'>
				<form method='GET'>
				  <h2>Search criteria:</h2>
				  <input type='text' name='keyword' value='$keyword' size=75>
				  <input type='number' name='howMany' value='$howMany' style='width:4em;'>
				  <input type='submit' name='submit' value='Go'>
				  <br/>
				  <div id='sort'>
					Sort by:
					<select name='sort'>
						<option value='DESC'>Date - Descending</option>
						<option value='ASC'>Date - Ascending</option>
					</select>
				  </div>
				  <div id='match'>
				  	Match:
				  	<select name='match'>
						<option value='loose'>Loose</option>
						<option value='exact'>Exact</option>
					</select>
				  </div>
				  <div id='days'>
				  	Days back?:
					<select name='days'>
						<option value='1'>1</option>
						<option value='5'>5</option>
						<option value='10'>10</option>
						<option value='15'>15</option>
						<option value='20'>20</option>
					</select>
				  </div>
				  <div id='useExternal'>
				  	External Sources?:
				  	<select name='external'>
						<option value='0'>No</option>
						<option value='1'>Yes</option>
					</select>
				  </div>
				</form>
			  </div>
			</div><div class='orangeBreak'></div>";
}

function numOfResults($keyword, $match) {
    $sql = "SELECT " .
	    "fv.title, fv.description, fv.url, fv.date, s.desc " .
	    "FROM " .
	    "feed_vulns AS fv, " .
	    "sources AS s, " .
	    "feed_info AS fi, " .
	    "source_feed_map AS sfm " .
	    "WHERE ";

    if ($match == 'exact') {
	$sql .= "(fv.title LIKE '%$keyword%' " .
		"OR fv.description LIKE '%$keyword%') AND ";
    } else {
	$keywordArray = array_unique(explode(" ", $keyword));
	while (list($key, $val) = each($keywordArray)) {
	    if ($val <> " " and strlen($val) > 0) {
		$sql .= "(fv.title LIKE '%$val%' OR fv.description LIKE '%$val%') AND ";
	    }
	}
	//$sql = substr($sql,0,(strlen($sql)-3));
    }
    $sql .= "fv.fiid = fi.fiid AND " .
	    "fi.mapid = sfm.mapid AND " .
	    "sfm.sid = s.sid AND " .
	    "s.show = '1'" .
	    "ORDER BY " .
	    "date DESC";
    $query = mysql_query($sql) or die(mysql_error());

    return mysql_num_rows($query);
}

function search($keyword, $match, $howMany) {
    $sql = "SELECT " .
	    "fv.title, fv.description, fv.url, fv.date, s.desc, fv.score " .
	    "FROM " .
	    "feed_vulns AS fv, " .
	    "sources AS s, " .
	    "feed_info AS fi, " .
	    "source_feed_map AS sfm " .
	    "WHERE ";

    if ($match == 'exact') {
	$sql .= "(fv.title LIKE '%$keyword%' " .
		"OR fv.description LIKE '%$keyword%') AND ";
    } else {
	$keywordArray = array_unique(explode(" ", $keyword));
	while (list($key, $val) = each($keywordArray)) {
	    if ($val <> " " and strlen($val) > 0) {
		$sql .= "(fv.title LIKE '%$val%' OR fv.description LIKE '%$val%') AND ";
	    }
	}
	//$sql = substr($sql,0,(strlen($sql)-3));
    }
    $sql .= "fv.fiid = fi.fiid AND " .
	    "fi.mapid = sfm.mapid AND " .
	    "sfm.sid = s.sid AND " .
	    "s.show = '1'" .
	    "ORDER BY " .
	    "date DESC " .
	    "LIMIT 0 , " . $howMany;
    $query = mysql_query($sql) or die(mysql_error());

    $count = $howMany;
    while ($row = mysql_fetch_object($query)) {
	echoHTML($row);
    }
}

function echoHTML($entryObject) {

    if (!isset($entryObject->score)) {
	$entryObject->score = 'N/A';
    }

    if ($entryObject->score == "Low")
	$style = "style='border:2px solid green'";
    else if ($entryObject->score == "Medium")
	$style = "style='border:2px solid orange'";
    else if ($entryObject->score == "High")
	$style = "style='border:2px solid red'";
    else if ($entryObject->score >= 0 && $entryObject->score <= 4)
	$style = "style='border:2px solid green'";
    else if ($entryObject->score > 4 && $entryObject->score <= 7)
	$style = "style='border:2px solid orange'";
    else if ($entryObject->score > 7)
	$style = "style='border:2px solid red'";
    else
	$style = '';

    $html = "<p>  
	    <table width='100%'>  
		<tr> 
		    <td width='90%'><h3>$entryObject->title</h3></td><td>$entryObject->date</td> 
		</tr>  
		<tr>  
		    <td><b><i>$entryObject->desc</i></b></td>
		    <td $style>SCORE: $entryObject->score</td>
		</tr> 
		<tr> 
		    <td>$entryObject->description</td> 
		</tr>  
		<tr> 
		    <td><b><a href='$entryObject->url'>$entryObject->url</a><b></td> 
		</tr> 
	    </table>
	    </p>
	    <hr>";

    echo $html;
}

?>