<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Importer</title>
        <link rel='stylesheet' type='text/css' href="_css/styles.css" />
        <script src="_scripts/jquery.js" type="text/javascript"></script>
        <script type="text/javascript">
            $(document).ready(function(){
                $(".import").hide();
                $('.import-show-hide').click(function(){
                    $(".import").slideToggle();
                });
                $(".create").hide();
                $(".create").load('adminPage.php .add-source');
                $('.create-show-hide').click(function() {
                    $('.create').slideToggle();
                });
            });
        </script>
    </head>
    <body>
	<?php
//require '_functions/sqlConnect.php';
	$con = mysql_connect('localhost', 'root', '');
	mysql_select_db('security_test', $con) or die(mysql_error());

	require '_functions/databaseImportFunctions.php';
	ini_set('max_execution_time', 300); //300 seconds timeout time
	ini_set('memory_limit', '512M'); // 512 Megabyte memory usage 
	session_start() or die('failed to start session');
	?>
	<div id='header'>
	    <div id='sapLogo'> <img src="_images/sap.png" width="258" height="113"> </div>
	    <h1>XML Database & RSS Importer</h1>
	</div>
	<div class='orangeBreak'></div>
	<div id='container-page'>
	    <div id='content'>
		<?php
		if (!isset($_GET['url_submit']) && !isset($_GET['tag_submit'])) {
		    ?>
    		<h2 class='import-show-hide' style='text-decoration:underline; cursor:pointer;'> "I have already created or their already is an existing source for this database" </h2>
    		<div class="import">
    		    <form method="GET" action='databaseImport.php'>
    			<h2>Enter URL of XML file:</h2>
    			<input type='text' name='url' value='Enter URL' size="100" onfocus="value=''" />
    			<input type='submit' name='url_submit' value='Go' />
    		    </form>
    		</div>
    		<h2 class='create-show-hide' style='text-decoration:underline; cursor:pointer;'>"I have NOT created or their is NOT already an existing source for this database"</h2>
    		<div class='create' style='width:650px;'></div>
		    <?php
		}
		if (isset($_GET['url_submit'])) {
		    $_SESSION['url'] = $_GET['url'];
		    downloadXML($_GET['url']) or die('error: downloadXML');
		    $tags = getArrayKeys(xmlToArray('C://wamp/www/security_r2/temp.xml'));
		    ?>
    		<form method='get' action="databaseImport.php">
    		    <h2>Indicate which tags represent each part of a single vulnerability</h2>
    		    <table width="100%">
    			<tr>
    			    <td>Is this a static database or regularly updated RSS?</td>
    			    <td><select name='type'>
    				    <option value="rss">RSS</option>
    				    <option value="static">Static XML</option>
    				</select></td>
    			</tr>
    			<tr>
    			    <td>Container of single vulnerability: </td>
    			    <td><select name='item'>
					<?php echoTagOptions($tags) ?>
    				</select></td>
    			</tr>
    			<tr>
    			    <td>Title: </td>
    			    <td><select name='title'>
					<?php echoTagOptions($tags) ?>
    				</select></td>
    			</tr>
    			<tr>
    			    <td>Description: </td>
    			    <td><select name='description'>
					<?php echoTagOptions($tags) ?>
    				</select></td>
    			</tr>
    			<tr>
    			    <td>Link: </td>
    			    <td><select name='link'>
					<?php echoTagOptions($tags) ?>
    				</select></td>
    			</tr>
    	<!--				<tr>
    	    <td>Publication Date: (choose 'null' if not applicable)</td>
    	    <td><select name='date'>
			    <?php //echoTagOptions($tags) ?>
    			    <option value='NULL'>null</option>
    		    </select></td>
                </tr>-->
    			<tr>
    			    <td>Select the source which this database originates:</td>
    			    <td><select name='sid'>
					<?php echoSources() ?>
    				</select></td>
    			</tr>
    			<tr>
    			    <td></td>
    			    <td><input type='submit' name='tag_submit' value='Go' /></td>
    			</tr>
    		    </table>
    		</form>
		    <?php
		}
		if (isset($_GET['tag_submit'])) {
		    $tags->item = $_GET['item'];
		    $tags->title = $_GET['title'];
		    $tags->description = $_GET['description'];
		    $tags->link = $_GET['link'];
		    $tags->type = $_GET['type'];
		    $sid = $_GET['sid'];

		    if ($tags->type == 'rss')
			addSourceFeedMap($sid, 1);
		    else
			addSourceFeedMap($sid, 0);


		    addFeedInfo($sid, $tags->link);
		    $fiid = getFiid($tags->link) or die('error: getFiid()');
		    ?>
    		<h2>Inserted Vulnerabilities:</h2>
    		<ul>
			<?php
			importXMLToDB($tags, $fiid);
			?>
    		</ul>
		    <?php
		    unlink('temp.xml');
		}
		?>
	    </div>
	</div>
    </body>
</html>