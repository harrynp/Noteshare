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
if($u == $log_username && $user_ok == true){
	$settings_form  = '<form id="note_form" enctype="multipart/form-data" method="post" action="php_parsers/settings_manager.php">';
  $settings_form .=   '<h3>'.$u.' settings</h3>';
  $settings_form .=   '<b>Profile Picture:</b> ';
  $settings_form .=   '<input type="file" name="userfile" accept="image/*" required>';
  $settings_form .=   '<b>Change password:</b> ';
  $settings_form .=   '<input type="text" name="password">';
  // $settings_form .=   '<b>Confirm password:</b> ';
  // $settings_form .=   '<input type="text" name="confirm_password">';
  $settings_form .=   '<p><input type="submit" value="Apply changes"></p>';
  $settings_form .= '</form>';
}
?>
