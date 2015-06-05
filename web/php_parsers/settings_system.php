<?php
include_once("../php_includes/db_conx.php");
include_once("../php_includes/check_login_status.php");
require ('../../vendor/autoload.php');// this will simply read AWS_ACCESS_KEY_ID and AWS_SECRET_ACCESS_KEY from env var
?>
<?php
if($user_ok != true || $log_username == "") {
	exit();
}?>
<?php
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $p = $_POST['password'];
    $salt = crypt($p);
    $p_hash = hash('sha256', $p.$salt);
    $sql = "UPDATE users SET password_hash='$p_hash' AND salt='$salt' WHERE username='$log_username' LIMIT 1";
    $query = mysqli_query($db_conx, $sql);
  }
mysqli_close($db_conx);
header("location: ../settings.php?u=$log_username");
exit();
?>
