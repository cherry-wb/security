<?php

class database {

    public function __construct($host, $user, $pass, $dbname) {
	mysql_connect($host, $user, $pass) or die(mysql_error());
	mysql_select_db($dbname) or die(mysql_error());
    }
    public function query($sql) {
	return mysql_query($sql) or die(mysql_error());
    }

}

?>
