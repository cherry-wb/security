<?php

class report {

    private $rid;
    private $uid;
    private $vulns = array(); //array of vulns
    private $user;

    function __construct() {
	$this->user = new user();
    }

    public function setRid($rid) {
	$this->rid = $rid;
    }

    public function getRid() {
	return $this->rid;
    }

    public function setUid($uid) {
	$this->uid = $uid;
    }

    public function getUid() {
	return $this->uid;
    }

    public function setVuln($vulns) {
	$this->vulns = $vulns;
    }

    public function getVulns() {
	return $this->vulns;
    }

    public function addVuln($fvid) {
	$vuln = new vuln();
	$vuln->set($fvid);
	array_push($this->vulns, $vuln);
    }

    public function removeVuln($fvid) {
	foreach ($this->vulns as $key => $vuln) {
	    if ($vuln->getFvid == $fvid) {
		unset($this->vuln[$key]);
	    }
	}
    }

    public function setUser($uid) {
	$this->user->set($uid);
    }

    public function getUser() {
	return $this->user;
    }

    public function set($rid) {

	$this->setRid($rid);
	$con = mysql_connect('localhost', 'root', '');
	mysql_select_db('security_r2', $con) or die(mysql_error());
	$sql = "SELECT
		    *
		FROM
		    users AS u, 
		    reports AS r, 
		    report_vuln_map AS rvm, 
		    feed_vulns AS fv 
		WHERE
		    u.uid = r.uid AND 
		    $rid = r.rid AND 
		    r.rid = rvm.rid AND 
		    rvm.fvid = fv.fvid";
	if (mysql_num_rows(mysql_query($sql)) == 0) {
	    $sql = "SELECT
			*
		    FROM
			users AS u,
			reports AS r
		    WHERE
			r.rid = '$rid' AND
			r.uid = u.uid";
	    $query = mysql_query($sql) or die(mysql_error());

	    while ($row = mysql_fetch_object($query)) {
		$this->setUid($row->uid);
		$this->user->set($row->uid);
	    }
	} else {

	    $query = mysql_query($sql);

	    while ($row = mysql_fetch_object($query)) {
		$this->uid = $row->uid;
		$this->user->set($row->uid);
		$this->addVuln($row->fvid);
	    }
	}
    }

    public function saveNewReportToDB() {
	$con = mysql_connect('localhost', 'root', '');
	mysql_connect('security_r2', $con) or die(mysql_error());

	$sql = "SELECT 
		    *
		FROM
		    users
		WHERE
		    '$this->uid' = users.uid";
	$query = mysql_query($sql) or die(mysql_error());

	if (mysql_num_rows($query) == 0) {
	    die("User uid not found in DB");
	} else {
	    $sql = "INSERT INTO
			reports 
		    VALUES
			(NULL, '$uid')";
	    mysql_query($sql) or die(mysql_error());

	    while (!empty($this->vulns)) {
		$fvid = array_pop($this->vulns);
		$sql = "INSERT INTO 
			    report_vuln_map
			VALUES
			    (NULL, '$this->rid', '$fvid'";
		mysql_query($sql) or die(mysql_error());
	    }
	}
    }

}

?>
