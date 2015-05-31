<?php
include_once("php_includes/check_login_status.php");
$envelope = '';
$loginLink = '<a href="login.php">Log In</a> &nbsp; | &nbsp; <a href="signup.php">Sign Up</a>';
$user_profile = "";
$settings = "";
$my_notes = "";
$directory = "";
// $note_directory = "";
if($user_ok == true) {
	$sql = "SELECT notescheck FROM users WHERE username='$log_username' LIMIT 1";
	$user_profile = '<a href="user.php?u='.$log_username.'"><img src="images/profile.png" alt="My Profile" title="My Profile" width="30" height="30"/></a>';
	$settings = '<a href="settings.php?u='.$log_username.'"><img src="images/settings-64.png" alt="My Settings" title="My Settings" width="30" height="30"/></a>';
	$my_notes = '<a href="notes.php?u='.$log_username.'"><img src="images/my_notes.jpg" alt="My Notes" title="My Notes" width="30" height="30"/></a>';
	$directory = '<a href="directory.php"><img src="images/directory.svg" alt="User Directory" title="User Directory" width="30" height="30"/></a>';
	// $note_directory = '<a href="notes_by_user.php"><img src="images/note_directory.png" alt="Note Directory" title="Note Directory" width="30" height="30"/></a>';
	$query = mysqli_query($db_conx, $sql);
	$row = mysqli_fetch_row($query);
	$notescheck = $row[0];
	$sql = "SELECT id FROM notifications WHERE username='$log_username' AND date_time > '$notescheck' LIMIT 1";
	$query = mysqli_query($db_conx, $sql);
	$numrows = mysqli_num_rows($query);
    if ($numrows == 0) {
		$envelope = '<a href="notifications.php" title="Your notifications and friend requests"><img src="images/notifications.png" width="20" height="20" alt="Notifications"/></a>';
    } else {
		$envelope = '<a href="notifications.php" title="You have'.$numrows.' new notifications"><img src="images/new_notifications.png" width="20" height="20" alt="'.$numrow.' new notifications"/></a>';
	}
    $loginLink = '<a href="user.php?u='.$log_username.'">'.$log_username.'</a> &nbsp; | &nbsp; <a href="logout.php">Log Out</a>';
}
?>
<div id="pageTop">
  <div id="pageTopWrap">
    <div id="pageTopLogo">
      <a href="index.php">
        <img src="images/NoteShare_Logo.png" alt="logo" title="NoteShare">
      </a>
    </div>
    <div id="pageTopRest">
      <div id="menu1">
        <div>
          <?php echo $envelope; ?> &nbsp; &nbsp; <?php echo $loginLink; ?>
        </div>
      </div>
      <div id="menu2">
        <div>
					<a href="directory.php"><img src="images/directory.png" alt="User Directory" title="User Directory" width="30" height="30"/></a>
					<?php echo $user_profile; ?>
					<?php echo $settings; ?>
					<?php echo $my_notes; ?>
					<?php echo $directory; ?>
					<!-- <?php echo $note_directory; ?> -->
          <!--<a href="#">Menu_Item_1</a>
          <a href="#">Menu_Item_2</a> -->
        </div>
      </div>
    </div>
  </div>
</div>
