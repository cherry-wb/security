<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Source Editor</title>
	<link rel='stylesheet' type="text/css" href="_css/styles.css" />
	<link rel='stylesheet' type="text/css" href="_css/admin.css" />
	<script type='text/javascript' src="_scripts/jquery.js"></script>
	<script type='text/javascript'>
	    $(document).ready(function(){
		$(".source-feeds").hide();
		$(".source-desc").click(function(){
		    $(this).closest(".source").find(".source-feeds").slideToggle();
		});
		//$("#create").load('adminPage.php .add-source');
	    });
	    function confirmFeedDelete(){
		var agree=confirm("Are you sure you wish to delete this feed?"); 
		if (agree)
		    return true ;
		else
		    return false ;
	    }
	    function confirmSourceDelete(){
		var agree=confirm("Are you sure you wish to delete this source?");
		if (agree)
		    finalConfirmSourceDelete() ;
		else
		    return false ;
	    }
	    function finalConfirmSourceDelete() {
		var agree=confirm("Warning: this will delete ALL feeds and vulnerabilities associated with this source");
		if (agree)
		    return true;
		else
		    return false;
	    }
	</script>

    </head>

    <body>
	<?php
	require '_functions/sourceEditorFunctions.php';
	$con = mysql_connect('localhost', 'root', '');
	mysql_select_db('security_test', $con) or die(mysql_error());
	?>
	<div id='header'>
	    <div id="sapLogo"><img src="_images/sap.png" width="258" height="113"/></div>
	    <h1>Source Editor</h1>
	</div>
	<div class='orangeBreak'></div>
	<div id='container-page'>
	    <div id='content'>
		<?php
		if (isset($_GET['addFeed'])) {
		    addRSSFeed($_GET['url'], $_GET['sid']);
		}
		if (isset($_GET['deleteFeed'])) {
		    removeXMLFeed($_GET['fiid']);
		}
		if (isset($_GET['deleteSource'])) {
		    removeSource($_GET['link']);
		}
		if (isset($_GET['editFeed'])) {
		    
		}
		if (!isset($_GET['edit'])) {
		    ?>

    		<div id='sourceContainer' style='width:650px; height:auto; padding:0; margin:auto;'>
    		    <div id="create" style="margin-bottom:2em;"></div>
    		    <div class='source'>
    			<div class='source-desc'><b>Description</b></div>
    			<div class='source-url'><b>URL</b></div>
    		    </div>
			<?php echoSourceStatus() ?> </div>
    	    </div>
    	</div>
	    <?php
	} else {
	    $sid = $_GET['sid'];
	    $feeds = array();

	    $sql = "SELECT " .
		    "* " .
		    "FROM " .
		    "sources AS s, " .
		    "source_feed_map AS sfm, " .
		    "feed_info AS fi " .
		    "WHERE " .
		    "'$sid' = s.sid AND " .
		    "s.sid = sfm.sid AND " .
		    "sfm.mapid = fi.mapid";
	    $query = mysql_query($sql) or die(mysql_error());
	    while ($row = mysql_fetch_object($query)) {
		$desc = $row->desc;
		$link = $row->link;
		$status = $row->status;
		$mapid = $row->mapid;
		if ($row->fid == '1') {
		    $feed;
		    $feed->url = $row->url;
		    $feed->type = 'rss';
		    array_push($feeds, $feed);
		    unset($feed);
		} else if ($row->fid == '2') {
		    $feed;
		    $feed->url = $row->url;
		    $feed->type = 'html';
		    array_push($feeds, $feed);
		    unset($feed);
		} else if ($row->fid == '0') {
		    $feed;
		    $feed->url = $row->url;
		    $feed->type = 'xml';
		    array_push($feeds, $feed);
		    unset($feed);
		}
		$fiid = $row->fiid;
		$url = $row->url;
	    }
	    //var_dump($feeds);
	    //echo $sid;
	    if (!isset($desc) || !isset($link)) {
		$sql = "SELECT * FROM sources WHERE sid = '$sid'";
		$query = mysql_query($sql) or die(mysql_error());
		while ($row = mysql_fetch_object($query)) {
		    $desc = $row->desc;
		    $link = $row->link;
		}
	    }
	    ?>
    	<h1 style="color: #000; width:100%;"><?php echo $desc ?></h1>

    	<div id="addFeed">
    	    <form method="GET" style="float:none; display:block;">
    		<h2>Add a Feed</h2>
    		<input type="hidden" name="sid" value="<?php echo "$sid" ?>"></input>
    		<input type="hidden" name="addFeed" value="add"></input>
    		<input type="text" name="url" value="URL of Feed" style="width:50%;"></input>
    		<input type="submit" value="Add Feed"></input>
    	    </form>
    	</div>
    	<div id="removeFeed">
    	    <form method="GET">
    		<h2>Remove a Feed</h2>
    		<table><?php echoFeedForm($sid); ?></table>

    	    </form>
    	</div>
    	<div id="removeSource" style="float:right; margin-top:2em;">
    	    <form method="GET">
    		<input type="hidden" name="deleteSource" value="deleteSource"></input>
    		<input type="hidden" name="link" value="<?php echo $link ?>"></input>
    		<input type="submit" value="Delete This Source" onclick="return confirmSourceDelete()"></input>
    	    </form>
    	</div>
	    <?php
	}
	?>
    </body>
</html>