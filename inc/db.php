<?php
//DB CONNECTION START
$dbhost							= "localhost";
$dbuser							= "root";
$dbpass							= "greenex212";
$dbname							= "polls";

$conn = mysql_connect($dbhost, $dbuser, $dbpass) or die ("Error connecting to mysql");
mysql_select_db($dbname);
//DB CONNECTION END
?>
