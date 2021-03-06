<?php
include_once("php_includes/check_login_status.php");
// Make sure the _GET "u" is set, and sanitize it
$u = "";
if(isset($_GET["u"])){
	$u = preg_replace('#[^a-z0-9]#i', '', $_GET['u']);
} else {
    header("location: http://secure-savannah-9905.herokuapp.com/");
    exit();
}
$note_form = "";
// Check to see if the viewer is the account owner
$isOwner = "no";
if($u == $log_username && $user_ok == true){
	$isOwner = "yes";
	$note_form  = '<form id="note_form" enctype="multipart/form-data" method="post" action="php_parsers/notemanager.php">';
	$note_form .=   '<h3>Hi '.$u.', add a new note into one of your classes</h3>';
	$note_form .=   '<b>Class:</b> ';
	$note_form .=   '<input type="text" name="class" required>';
	$note_form .=   '<b>Note name:</b> ';
	$note_form .=   '<input type="text" name="note_name" required>';
	$note_form .=   ' &nbsp; &nbsp; &nbsp; <b>Choose PDF file:</b> ';
	$note_form .=   '<input type="file" name="userfile" accept="application/pdf" required>';
	$note_form .=   '<p><input type="submit" value="Upload Note Now"></p>';
	$note_form .= '</form>';
}
// Select the user notes
$note_list = "";
$sql = "SELECT DISTINCT class FROM notes WHERE user='$u'";
$query = mysqli_query($db_conx, $sql);
if(mysqli_num_rows($query) < 1){
	$note_list = "This user has not uploaded any notes yet.";
} else {
	while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
		$class = $row["class"];
		$countquery = mysqli_query($db_conx, "SELECT COUNT(id) FROM notes WHERE user='$u' AND class='$class'");
		$countrow = mysqli_fetch_row($countquery);
		$count = $countrow[0];
		$filequery = mysqli_query($db_conx, "SELECT url FROM notes WHERE user='$u' AND class='$class' ORDER BY class LIMIT 1");
		$filerow = mysqli_fetch_row($filequery);
		$file = $filerow[0];
		$note_list .= '<div>';
		$note_list .=   '<div onclick="showClass(\''.$class.'\',\''.$u.'\')">';
		$note_list .=     '<img src="images/pdf.png" alt="Class Notes">';
		$note_list .= 	'</div>';
		$note_list .=   '<b>'.$class.'</b> ('.$count.')';
		$note_list .= '</div>';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title><?php echo $u; ?>'s' Notes</title>
<link rel="icon" href="favicon.ico" type="image/x-icon">
<link rel="stylesheet" href="style/style.css">
<style type="text/css">
form#note_form{background:#F3FDD0; border:#AFD80E 1px solid; padding:20px;}
div#classes{}
div#classes > div{float:left; margin:20px; text-align:center; cursor:pointer;}
div#classes > div > div {height:100px; overflow:hidden;}
div#classes > div > div > img{height:100px; width:100px; cursor:pointer;}
div#notes{display:none; border:#666 1px solid; padding:20px;}
/*div#notes > div{float:left; width:125px; height:200px; overflow:hidden; margin:20px;}*/
div#notes > div{float:left; margin:20px; text-align:center; cursor:pointer;}
div#notes > div > div {height:100px; overflow:hidden;}
div#notes > div > div > a > img{height:100px; width:100px; cursor:pointer;}
/*div#notes > div > img{width:125px; cursor:pointer;}*/
</style>
<script src="js/main.js"></script>
<script src="js/ajax.js"></script>
<script>
function showClass(cls,user){
	_("classes").style.display = "none";
	_("section_title").innerHTML = user+'&#39;s '+cls+' notes &nbsp; <button onclick="backToClasses()">Go back to all classes</button>';
	_("notes").style.display = "block";
	_("notes").innerHTML = 'loading notes ...';
	var ajax = ajaxObj("POST", "php_parsers/notemanager.php");
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
			_("notes").innerHTML = '';
			var notes = ajax.responseText.split("|||");
			for (var i = 0; i < notes.length; i++){
				var note = notes[i].split("|");
				_("notes").innerHTML += '<div><div><a href="pdf_viewer.php?url='+note[0]+'"><img src="images/pdf.png" alt="'+note[1]+' height="100px" width="100px""></a></div><b>'+note[1]+'</b></div>';
			}
			_("notes").innerHTML += '<p style="clear:left;"></p>';
		}
	}
	ajax.send("show=classnotes&class="+cls+"&user="+user);
}
function backToClasses(){
	_("notes").style.display = "none";
	_("section_title").innerHTML = "<?php echo $u; ?>&#39;s Notes";
	_("classes").style.display = "block";
}
</script>
</head>
<body>
<?php include_once("template_pageTop.php"); ?>
<div id="pageMiddle">
  <div id="note_form"><?php echo $note_form; ?></div>
  <h2 id="section_title"><?php echo $u; ?>&#39;s Notes</h2>
  <div id="classes"><?php echo $note_list; ?></div>
  <div id="notes"></div>
  <p style="clear:left;">These notes belong to <a href="user.php?u=<?php echo $u; ?>"><?php echo $u; ?></a></p>
</div>
<?php include_once("template_pageBottom.php"); ?>
</body>
</html>
