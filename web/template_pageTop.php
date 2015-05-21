<?php
include_once("php_includes/check_login_status.php");
$envelope = '';
$loginLink = '<a href="login.php">Log In</a> &nbsp; | &nbsp; <a href="signup.php">Sign Up</a>';
$user_profile = "";
$settings = "";
if($user_ok == true) {
	$sql = "SELECT notescheck FROM users WHERE username='$log_username' LIMIT 1";
	$user_profile = '<a href="user.php?u='.$log_username.'"><img src="images/profile.png" alt="My Profile" title="My Profile"/></a>';
	$settings = '<a href="settings.php?u='.$log_username.'"><img src="images/setting-256.png" alt="My Settings" title="My Settings"/></a>';
	$query = mysqli_query($db_conx, $sql);
	$row = mysqli_fetch_row($query);
	$notescheck = $row[0];
	$sql = "SELECT id FROM notifications WHERE username='$log_username' AND date_time > '$notescheck' LIMIT 1";
	$query = mysqli_query($db_conx, $sql);
	$numrows = mysqli_num_rows($query);
    if ($numrows == 0) {
		$envelope = '<a href="notifications.php" title="Your notifications and friend requests"><img src="images/notifications.png" width="22" height="12" alt="Notes"/></a>';
    } else {
		$envelope = '<a href="notifications.php" title="You have new notifications"><img src="images/new_notifications.png" width="22" height="12" alt="Notes"/></a>';
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
					<?php echo $user_profile; ?>
					<?php echo $settings; ?>
          <!--<a href="#">Menu_Item_1</a>
          <a href="#">Menu_Item_2</a> -->
        </div>
      </div>
    </div>
  </div>
</div>
