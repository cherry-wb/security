<html>
    <head>
	<title>Administrator Page | Database Manipulation</title>
	<link rel='stylesheet' type='text/css' href="_css/styles.css"/>
	<link rel='stylesheet' type='text/css' href='_css/admin.css'/>
	<script type='text/javascript' src='_scripts/jquery.js'></script>
	<script type='text/javascript'>
	    $(document).ready(function() {
		$(".source-feeds").hide();
		$(".source-desc").click(function(){
		    $(this).closest('.source').find('.source-feeds').slideToggle();
		});
	    });
        </script>
    </head>
    <body>
	<div id='header'>
	    <div id='sapLogo'> <img src="_images/sap.png" width="258" height="113"> </div>
	    <h1>Welcome Administrator</h1>
	</div>
	<div class='orangeBreak'></div>
	<div id='container-page'>
	    <div id='content'>
		<?php
		require '_functions/adminPageFunctions.php';
		require '_functions/sqlConnect.php';


		$sql = "SELECT " .
			"* " .
			"FROM " .
			"sources";

		$query = mysql_query($sql) or die(mysql_error());

		if (isset($_POST['addSource'])) {
		    addSource($_POST['desc'], $_POST['link'], $_POST['status']) or die("error:addSource()");
		}

		if (isset($_POST['update'])) {
		    $_PRE = array();
		    while ($row = mysql_fetch_object($query)) {
			$_PRE[$row->sid] = $row->desc;
		    }
		    foreach ($_PRE as $sid => $desc) {
			updateSourceStatus($sid, $_POST);
		    }
		}
		?>
		<div id='adminOptions'>
		    <form method='POST' action='adminPage.php' class='add-source'>
			<h2>Insert new source:</h2>
			<input type='text' name='desc' value='Source Description (source name)' style='width:85%'>
			<div style='float:right;'> <b>Enable:</b>
			    <input type='checkbox' name='status' value='1' checked>
			</div>
			<br/>
			<input type='text' name='link' value='Enter source URL (homepage)' style='width:100%;'>
			<br/>
			<input type='submit' name='addSource' value='Submit'>
		    </form>
		    <form method='POST' action='adminPage.php'>
			<div class='source'>
			    <div class='source-desc'><b>Description</b></div>
			    <div class='source-url'><b>URL</b></div>
			    <div class='source-enable'><b>Enable</b></div>
			</div>
			<?php
			echoSourceStatus($query);
			?>
			<input type='submit' name='update' value='Update' style="float:right">
		    </form>
		    <div style='display:inline-block; width:100%;'>
			<div id='manual-feed-download-container'>
			    <form method='POST' action='feedDownload.php'>
				<h2>Manual Feed Download</h2>
				<input type='submit' name='download' value='Download Feeds'>
			    </form>
			</div>
			<div id='onetime-db-importer-container'>
			    <form method="post" action='databaseImport.php'>
				<h2>XML Database Tag-Mapper</h2>
				<input type="submit" name="importdb" value="Import Database">
			    </form>
			</div>
		    </div>
		    <h2>Modify Source Feeds</h2>
		    <form method='get' action="sourceEditor.php">
			<input type="submit" value='Add/Edit Sources'>
		    </form>
		</div>
	    </div>
	</div>
    </body>
</html>
