<?php

class user {

    private $uid;
    private $username;
    private $email;

    public function setUid($uid) {
	$this->uid = $uid;
    }

    public function getUid() {
	return $this->uid;
    }

    public function setUsername($username) {
	$this->username = $username;
    }

    public function getUsername() {
	return $this->username;
    }

    public function setEmail($email) {
	$this->email = $email;
    }

    public function getEmail() {
	return $this->email;
    }

    public function set($uid) {
	$con = mysql_connect('localhost', 'root', '');
	mysql_select_db('security_r2', $con) or die(mysql_error());

	$sql = "SELECT 
		    *
		FROM
		    users AS u
		WHERE
		    u.uid = '$uid'";
	$query = mysql_query($sql) or die(mysql_error());

	while ($row = mysql_fetch_object($query)) {
	    $this->setEmail($row->email);
	    $this->setUid($row->uid);
	    $this->setUsername($row->username);
	}
    }

    public function saveUserToDB() {
	$con = mysql_connect('localhost', 'root', '');
	mysql_select_db('security_r2', $con) or die(mysql_error());

	$sql = "INSERT INTO
		    users
		VALUES
		    (NULL, '$this->username', '$this->email'";
	mysql_query($sql) or die(mysql_error());
    }

}

?>
