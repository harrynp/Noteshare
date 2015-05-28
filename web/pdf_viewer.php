<?php
if(isset($_GET["url"])){
	$url = $_GET["url"];
  // $url = substr($_GET["url"], 39);
  // if (!preg_match('#^http(s)?://#', $url)) {
    // $pdf_viewer = $url;
  //   $url = 'https://securesavanah.s3.amazonaws.com/' . urlencode($url);
  // }
} else {
    header("location: http://secure-savannah-9905.herokuapp.com/");
    exit();
}
// $pdf_viewer = '<embed src="'.$url.'" type="application/pdf"></object>';
$pdf_viewer = '<iframe src="https://docs.google.com/gview?url='.$url.'&embedded=true" style="width:100%; height:100%;" frameborder="0"></iframe>';
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>NoteShare PDF Viewer</title>
<link rel="icon" href="favicon.ico" type="image/x-icon">
<link rel="stylesheet" href="style/style.css">
</head>
<body>
<?php include_once("template_pageTop.php"); ?>
<div id="pageMiddle">
  <?php echo $pdf_viewer; ?>
</div>
<?php include_once("template_pageBottom.php"); ?>
</body>
</html>
