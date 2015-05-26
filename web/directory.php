<?php
session_start();
include_once("php_includes/db_conx.php");
include_once("php_includes/check_login_status.php");
require ('../vendor/autoload.php');// this will simply read AWS_ACCESS_KEY_ID and AWS_SECRET_ACCESS_KEY from env vars
?>



<html>
    <head>
      <meta charset="UTF-8">
      <link rel="stylesheet" href="style/style.css">
    </head>


    <body>
    <?php include_once("template_pageTop.php"); ?>

      <div id="pageMiddle">

      <?php 
$sql = "SELECT username, avatar FROM users";
$results = mysqli_query($db_conx, $sql);
//MySqli Select Query
//$results = $mysqli->query("SELECT username, avatar FROM users");

print '<table border="1">';
while($row = $results->fetch_assoc()) {
    print '<tr>';
    print '<td><a href="https://secure-savannah-9905.herokuapp.com/user.php?u='.$row["username"].'">'.$row["username"].'</a></td>';
    print '<td><img src="'.$row["avatar"].'" alt="user"style="width:100px;height:100px;"</td>';
    print '</tr>';
}  
print '</table>';

// Frees the memory associated with a result
$results->free();

mysqli_close($db_conx);
?>
      	
      </div>
    <?php include_once("template_pageBottom.php"); ?>
    </body>
    <a href="link">users</a>

</html>
