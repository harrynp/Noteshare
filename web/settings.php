<?php
session_start();
include_once("php_includes/db_conx.php");
include_once("php_includes/check_login_status.php");
require ('../vendor/autoload.php');// this will simply read AWS_ACCESS_KEY_ID and AWS_SECRET_ACCESS_KEY from env vars

$s3 = Aws\S3\S3Client::factory();
$bucket = getenv('S3_BUCKET')?: die('No "S3_BUCKET" config var in found in env!');
?>
<html>
    <head>
      <meta charset="UTF-8">
      <link rel="stylesheet" href="style/style.css">
    </head>
    <body>
      <div id="pageMiddle">
        <?php include_once("template_pageTop.php"); ?>
        <h1>Profile Picture</h1>
<?php
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['userfile']) && $_FILES['userfile']['error'] == UPLOAD_ERR_OK && is_uploaded_file($_FILES['userfile']['tmp_name'])) {
    // FIXME: add more validation, e.g. using ext/fileinfo
    try {
        // FIXME: do not use 'name' for upload (that's the original filename from the user's computer)
        $upload = $s3->upload($bucket, $_FILES['userfile']['name'], fopen($_FILES['userfile']['tmp_name'], 'rb'), 'public-read');
        // $u = "";
        // if(isset($_POST["user"]))
        // {
        //     $u = preg_replace('#[^a-z0-9]#i', '', $_POST['user']);
        // }
        // else {
        //     header("location: #");
        //     exit();
        // }
        $url = $upload->get('ObjectURL');

        $sql = "UPDATE users SET avatar='$url' WHERE username='$log_username' LIMIT 1";
        $query = mysqli_query($db_conx, $sql);
        mysqli_close($db_conx);

?>
        <p>Upload <a href="<?=htmlspecialchars($upload->get('ObjectURL'))?>">successful</a> :)</p>
<?php } catch(Exception $e) { ?>
        <p>Upload error :(</p>
<?php } } ?>
        <h2>Upload a file</h2>
        <form enctype="multipart/form-data" action="<?=$_SERVER['PHP_SELF']?>" method="POST">
            <input name="userfile" type="file"><input type="submit" value="Upload">
        </form>
        <div>
        <?php echo $sql; ?>
        </div>
      </div>
        <?php include_once("template_pageBottom.php"); ?>
    </body>
</html>
