<?php
$f = "";
if(isset($_GET["f"])){
	$u = $_GET["f"];
} else {
    header("location: http://secure-savannah-9905.herokuapp.com/");
    exit();
}
$f = $_GET['f'];
$pdf_viewer = '<object data=".'$f'."></object>';
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>NoteShare PDF Viwere</title>
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
