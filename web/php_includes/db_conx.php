<?php

$url = parse_url(getenv("CLEARDB_DATABASE_URL"));

$server = $url["host"];
$username = $url["user"];
$password = $url["pass"];
$db = substr($url["path"], 1);

//$conn = new mysqli($server, $username, $password, $db);
//$db_conx = mysqli_connect("localhost", "root", "", "social");
$db_conx = mysqli_connect($server, $username, $password, $db);
// Evaluate the connection
// if (mysqli_connect_errno()) {
//     echo mysqli_connect_error();
//     exit();
// } else {
// 	echo "Successful database connection, happy coding!!!";
// }
?>
