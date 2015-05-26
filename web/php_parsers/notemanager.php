<?php
include_once("../php_includes/db_conx.php");
include_once("../php_includes/check_login_status.php");
require ('../../vendor/autoload.php');// this will simply read AWS_ACCESS_KEY_ID and AWS_SECRET_ACCESS_KEY from env vars

$s3 = Aws\S3\S3Client::factory();
$bucket = getenv('S3_BUCKET')?: die('No "S3_BUCKET" config var in found in env!');
?>
<?php
if($user_ok != true || $log_username == "") {
	exit();
}?>
<?php
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['userfile']) && $_FILES['userfile']['error'] == UPLOAD_ERR_OK && is_uploaded_file($_FILES['userfile']['tmp_name'])) {
    // FIXME: add more validation, e.g. using ext/fileinfo
    // FIXME: do not use 'name' for upload (that's the original filename from the user's computer)
    $upload = $s3->upload($bucket, $_FILES['userfile']['name'], fopen($_FILES['userfile']['tmp_name'], 'rb'), 'public-read');

    $url = $upload->get('ObjectURL');
    $sql = "INSERT INTO photos(user, gallery, url, uploaddate) VALUES ('$log_username','$gallery','$url',now())";
    $query = mysqli_query($db_conx, $sql);
    mysqli_close($db_conx);
    header("location: ../photos.php?u=$log_username");
    exit();
  }
?>
