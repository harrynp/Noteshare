<!-- SQL registration in line 13-108 -->
<!-- SQL in activation.php -->
<!-- Edit line 95-97 here for email -->
<!-- SQL in user.php, login.php, chack_login_status.php, once_daily.php, forgot_pass.php, friend_system.php, notifications.php, photos.php, photo_system.php, template_status.php-->
<!-- add templatepageTop.php to your version-->
<!-- working on view_friends.php later -->


<?php
// session_start();
// If user is logged in, header them away
if(isset($_SESSION["username"])){
	header("location: message.php?msg=already_logged_in");
    exit();
}
?><?php
// Ajax calls this NAME CHECK code to execute
if(isset($_POST["usernamecheck"])){
	include_once("php_includes/db_conx.php");
	$username = preg_replace('#[^a-z0-9]#i', '', $_POST['usernamecheck']);
	$sql = "SELECT id FROM users WHERE username='$username' LIMIT 1";
    $query = mysqli_query($db_conx, $sql);
    $uname_check = mysqli_num_rows($query);
    if (strlen($username) < 3 || strlen($username) > 16) {
	    echo '<strong style="color:#F00;">3 - 16 characters please</strong>';
	    exit();
    }
	if (is_numeric($username[0])) {
	    echo '<strong style="color:#F00;">Usernames must begin with a letter</strong>';
	    exit();
    }
    if ($uname_check < 1) {
	    echo '<strong style="color:#009900;">' . $username . ' is OK</strong>';
	    exit();
    } else {
	    echo '<strong style="color:#F00;">' . $username . ' is taken</strong>';
	    exit();
    }
}
?><?php
	if(isset($_POST["emailcheck"])){
		include_once("php_includes/db_conx.php");
		$email = $_POST['emailcheck'];
		if(filter_var($email, FILTER_VALIDATE_EMAIL)){
		$sql = "SELECT id FROM users WHERE email='$email' LIMIT 1";
			$query = mysqli_query($db_conx, $sql);
			$email_check = mysqli_num_rows($query);
			if($email_check < 1){
				echo '<strong style="color:#6c6;">Valid email address</strong>';
				exit();
			}
			else{
				echo '<strong style="color:#f66;">This email address is already taken.</strong>';
				exit();
			}

		}
		else{
			echo '<strong style="color:#f66;">Invalid email address</strong>';
			exit();
		}
	}
?><?php
// Ajax calls this REGISTRATION code to execute
if(isset($_POST["u"])){
	// CONNECT TO THE DATABASE
	include_once("php_includes/db_conx.php");
	// GATHER THE POSTED DATA INTO LOCAL VARIABLES
	$u = preg_replace('#[^a-z0-9]#i', '', $_POST['u']);
	$e = mysqli_real_escape_string($db_conx, $_POST['e']);
	$p = $_POST['p'];
	$g = preg_replace('#[^a-z]#', '', $_POST['g']);
	$c = preg_replace('#[^a-z ]#i', '', $_POST['c']);
	// GET USER IP ADDRESS
    $ip = preg_replace('#[^0-9.]#', '', getenv('REMOTE_ADDR'));
	// DUPLICATE DATA CHECKS FOR USERNAME AND EMAIL
	$sql = "SELECT id FROM users WHERE username='$u' LIMIT 1";
    $query = mysqli_query($db_conx, $sql);
	$u_check = mysqli_num_rows($query);
	// -------------------------------------------
	$sql = "SELECT id FROM users WHERE email='$e' LIMIT 1";
    $query = mysqli_query($db_conx, $sql);
	$e_check = mysqli_num_rows($query);
	// FORM DATA ERROR HANDLING
	if($u == "" || $e == "" || $p == "" || $g == "" || $c == ""){
		echo "The form submission is missing values.";
        exit();
	} else if ($u_check > 0){
        echo "The username you entered is alreay taken";
        exit();
	} else if ($e_check > 0){
        echo "That email address is already in use in the system";
        exit();
	} else if (strlen($u) < 3 || strlen($u) > 16) {
        echo "Username must be between 3 and 16 characters";
        exit();
    } else if (is_numeric($u[0])) {
        echo 'Username cannot begin with a number';
        exit();
    } else {
	// END FORM DATA ERROR HANDLING
	    // Begin Insertion of data into the database
		// Hash the password and apply your own mysterious unique salt
		$salt = crypt($p);
	  $p_hash = hash('sha256', $p.$salt);


		//$p_hash = $p;
		// Add user info into the database table for the main site table
		$sql = "INSERT INTO users (username, email, password_hash, salt, gender, country, ip, signup, lastlogin, notescheck)
		        VALUES('$u','$e','$p_hash', '$salt', '$g','$c','$ip',now(),now(),now())";
		$query = mysqli_query($db_conx, $sql);
		$uid = mysqli_insert_id($db_conx);
		// Establish their row in the useroptions table
		$sql = "INSERT INTO useroptions (id, username, background) VALUES ('$uid','$u','original')";
		$query = mysqli_query($db_conx, $sql);
		// Create directory(folder) to hold each user's files(pics, MP3s, etc.)
		if (!file_exists("user/$u")) {
			mkdir("user/$u", 0755);
		}
		// Email the user their activation link
		require '../vendor/autoload.php';
		$sendgrid = new SendGrid('app36608097@heroku.com', 'zeqckzkd7900');
		$email = new SendGrid\Email();
		$email->addTo($e)->
						setFrom('app366080987@heroku.com')->
						setSubject("NoteShare Account Activation")->
						setHtml('<!DOCTYPE html><html><head><meta charset="UTF-8"><title>NoteShare Message</title></head><body style="margin:0px; font-family:Tahoma, Geneva, sans-serif;"><div style="padding:10px; background:#333; font-size:24px; color:#CCC;"><a href="http://secure-savannah-9905.herokuapp.com/"><img src="http://secure-savannah-9905.herokuapp.com/web/images/logo.png" width="36" height="30" alt="NoteShare" style="border:none; float:left;"></a>NoteShare Account Activation</div><div style="padding:24px; font-size:17px;">Hello '.$u.',<br /><br />Click the link below to activate your account when ready:<br /><br /><a href="http://secure-savannah-9905.herokuapp.com/activation.php?id='.$uid.'&u='.$u.'&e='.$e.'&p='.$p_hash.'">Click here to activate your account now</a><br /><br />Login after successful activation using your:<br />* E-mail Address: <b>'.$e.'</b></div></body></html>');
		try {
		    $sendgrid->send($email);
		} catch(\SendGrid\Exception $e) {
		    echo $e->getCode();
		    foreach($e->getErrors() as $er) {
		        echo $er;
		    }
		}
		 //$to = "$e";
		 //$from = "harrynp@uci.edu";
		 //$subject = 'NoteShare Account Activation';
		 //$message = '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Friendster Message</title></head><body style="margin:0px; font-family:Tahoma, Geneva, sans-serif;"><div style="padding:10px; background:#333; font-size:24px; color:#CCC;"><a href="http://www.yoursitename.com"><img src="http://www.yoursitename.com/images/logo.png" width="36" height="30" alt="yoursitename" style="border:none; float:left;"></a>yoursitename Account Activation</div><div style="padding:24px; font-size:17px;">Hello '.$u.',<br /><br />Click the link below to activate your account when ready:<br /><br /><a href="http://www.yoursitename.com/activation.php?id='.$uid.'&u='.$u.'&e='.$e.'&p='.$p_hash.'">Click here to activate your account now</a><br /><br />Login after successful activation using your:<br />* E-mail Address: <b>'.$e.'</b></div></body></html>';
		 //$headers = "From: $from\n";
    //     $headers .= "MIME-Version: 1.0\n";
    //     $headers .= "Content-type: text/html; charset=iso-8859-1\n";
		// mail($to, $subject, $message, $headers);
		//echo 'Signup successful.  Please login <a href="login.php">here</a>.';
		//echo 'Activation email sent.  Please check your inbox.';
		exit();
	}
	exit();
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Sign Up</title>
<link rel="icon" href="favicon.ico" type="image/x-icon">
<link rel="stylesheet" href="style/style.css">
<style type="text/css">
#signupform{
	margin-top:24px;
}
#signupform > div {
	margin-top: 12px;
}
#signupform > input,select {
	width: 200px;
	padding: 3px;
	background: #F3F9DD;
}
#signupbtn {
	font-size:18px;
	padding: 12px;
}
</style>
<script src="js/main.js"></script>
<script src="js/ajax.js"></script>
<script src="js/signup.js"></script>
</head>
<body>
<?php include_once("template_pageTop.php"); ?>
<div id="pageMiddle">
  <h3>Sign Up Here</h3>
  <form name="signupform" id="signupform" onsubmit="return false;">
    <div>Username: </div>
    <input id="username" type="text" oninput="checkusername()" onkeyup="restrict('username')" maxlength="16">
    <span id="unamestatus"></span>
    <div>Email Address:</div>
    <input id="email" type="text" onfocus="emptyElement('status')" onkeyup="restrict('email')" oninput="checkEmail()" maxlength="88">
		<span id="email_status"></span>
    <div>Create Password:</div>
    <input id="pass1" type="password" onfocus="emptyElement('status')" maxlength="16">
    <div>Confirm Password:</div>
    <input id="pass2" type="password" onfocus="emptyElement('status')" onkeyup="checkPassword(); return false" maxlength="16">
    <div>Gender:</div>
    <select id="gender" onfocus="emptyElement('status')">
      <option value=""></option>
      <option value="m">Male</option>
      <option value="f">Female</option>
    </select>
    <div>Country:</div>
    <select id="country" onfocus="emptyElement('status')">
      <?php include_once("template_country_list.php"); ?>
    </select>
    <br /><br />
    <button id="signupbtn" onclick="signup()">Create Account</button>
    <span id="status"></span>
  </form>
</div>
<?php include_once("template_pageBottom.php"); ?>
</body>
</html>
