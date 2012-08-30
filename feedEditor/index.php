<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Administrator | Feed Editor</title>
	<script type='text/javascript' src="../_scripts/jquery.js"></script>
    </head>
    <body>
	<?php
	require 'functions.php';
	$con = mysql_connect('localhost', 'root', '');
	mysql_select_db('security_r2', $con) or die(mysql_error());
	
	$feed = getFeedInfo(15);
	var_dump($feed);
	?>
	
	<h1>Feed Editor</h1>
	<form>
	    <input type="text" name="url" value="<?php echo $feed->url;?>" style="width:20%;"></input>
	    <textarea name="test" value="" col="40" row="10"></textarea>
	</form>
    </body>
</html>
