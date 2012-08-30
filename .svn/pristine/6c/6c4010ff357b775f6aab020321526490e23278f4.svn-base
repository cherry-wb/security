<?php

class vuln {

    private $fvid;
    private $fiid;
    private $title;
    private $description;
    private $url;

    public function setFvid($fvid) {
	$this->fvid = $fvid;
    }

    public function getFvid() {
	return $this->fvid;
    }

    public function setFiid($fiid) {
	$this->fiid = $fiid;
    }

    public function getFiid() {
	return $this->fiid;
    }

    public function setTitle($title) {
	$this->title = $title;
    }

    public function getTitle() {
	return $this->title;
    }

    public function setDescription($description) {
	$this->description = $description;
    }

    public function getDescription() {
	return $this->description;
    }

    public function setUrl($url) {
	$this->url = $url;
    }

    public function getUrl() {
	return $this->url;
    }

    public function set($fvid) {
	$con = mysql_connect('localhost', 'root', '');
	mysql_select_db('security_r2', $con) or die(mysql_error());

	$sql = "SELECT
		    *
		FROM
		    feed_vulns
		WHERE
		    fvid = '$fvid'";
	$query = mysql_query($sql) or die(mysql_error());

	while ($row = mysql_fetch_object($query)) {
	    $this->setDescription($row->description);
	    $this->setFiid($row->fiid);
	    $this->setUrl($row->url);
	    $this->setTitle($row->title);
	}
	$this->setFvid($fvid);
    }

    public function addToReport($rid) {
	$con = mysql_connect('localhost', 'root', '');
	mysql_select_db('security_r2', $con) or die(mysql_error());

	$sql = "INSERT INTO 
		    report_vuln_map 
		VALUES
		    (NULL, '$rid', '$this->fvid')";
	mysql_query($sql) or die(mysql_error());
    }

    public function removeFromReport($rid) {
	$con = mysql_connect('localhost', 'root', '');
	mysql_select_db('security_r2', $con) or die(mysql_error());

	$sql = "DELETE FROM 
		    report_vuln_map AS r
		WHERE
		    '$rid' = r.rid AND 
		    '$this->fvid' = r.fvid";
	mysql_query($sql) or die(mysql_error());
    }

}

?>
