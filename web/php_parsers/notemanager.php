<?php
include_once("../php_includes/db_conx.php");
include_once("../php_includes/check_login_status.php");
require ('../../vendor/autoload.php');// this will simply read AWS_ACCESS_KEY_ID and AWS_SECRET_ACCESS_KEY from env vars

$s3 = Aws\S3\S3Client::factory();
$bucket = getenv('S3_BUCKET')?: die('No "S3_BUCKET" config var in found in env!');
?><?php
if($user_ok != true || $log_username == "") {
	exit();
}?><?php
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['userfile']) && $_FILES['userfile']['error'] == UPLOAD_ERR_OK && is_uploaded_file($_FILES['userfile']['tmp_name'])) {
    // FIXME: add more validation, e.g. using ext/fileinfo
    // FIXME: do not use 'name' for upload (that's the original filename from the user's computer)
		$note_name = $_POST['note_name'];
		$class = $_POST['class'];
    $upload = $s3->upload($bucket, $log_username.'_'.$class.'_'.$note_name.'.pdf', fopen($_FILES['userfile']['tmp_name'], 'rb'), 'public-read');

    $url = $upload->get('ObjectURL');
    $sql = "INSERT IGNORE INTO notes(user, class, url, uploaddate) VALUES ('$log_username','$class','$url',now())";
    // $sql = "UPDATE users SET avatar='$url' WHERE username='$log_username' LIMIT 1";
    $query = mysqli_query($db_conx, $sql);
    mysqli_close($db_conx);
    header("location: ../notes.php?u=$log_username");
    exit();
  }
?><?php
if (isset($_POST["show"]) && $_POST["show"] == "classnotes"){
	$picstring = "";
	$class = preg_replace('#[^a-z 0-9,]#i', '', $_POST["class"]);
	$user = preg_replace('#[^a-z0-9]#i', '', $_POST["user"]);
	$sql = "SELECT * FROM notes WHERE user='$user' AND class='$class' ORDER BY uploaddate ASC";
	$query = mysqli_query($db_conx, $sql);
	while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
		$id = $row["id"];
		$url = $row["url"];
		$description = $row["description"];
		$uploaddate = $row["uploaddate"];
		$picstring .= "$id|$url|$description|$uploaddate|||";
    }
	mysqli_close($db_conx);
	$picstring = trim($picstring, "|||");
	echo $picstring;
	exit();
}
?>
