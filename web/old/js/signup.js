function restrict(elem){
  var tf = _(elem);
  var rx = new RegExp;
  if(elem == "email"){
    rx = /[' "]/gi;
  } else if(elem == "username"){
    rx = /[^a-z0-9]/gi;
  }
  tf.value = tf.value.replace(rx, "");
}
function emptyElement(x){
  _(x).innerHTML = "";
}
function checkusername(){
  var u = _("username").value;
  if(u != ""){
    _("unamestatus").innerHTML = 'checking ...';
    var ajax = ajaxObj("POST", "signup.php");
        ajax.onreadystatechange = function() {
          if(ajaxReturn(ajax) == true) {
              _("unamestatus").innerHTML = ajax.responseText;
          }
        }
        ajax.send("usernamecheck="+u);
  }
}

function checkEmail() {
  var email = _("email").value;
  var email_status = _("email_status")
  var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
  if(!re.test(email)){
    email_status.style.color= "#f66"
    email_status.innerHTML = "Invalid email address";
  }
  else{
    email_status.style.color= "#6c6"
    email_status.innerHTML = "Valid email address";
  }
}

function checkPassword(){
  var match_color = "#6c6"
  var not_match_color = "#f66"
  var pass1 = _("pass1");
  var pass2 = _("pass2");

  if (pass1.value == pass2.value){
    //Passwords match
    pass2.style.backgroundColor = match_color;
  }
  else{
    //Passwords do not match
    pass2.style.backgroundColor = not_match_color;
  }
}

function signup(){
  var u = _("username").value;
  var e = _("email").value;
  var p1 = _("pass1").value;
  var p2 = _("pass2").value;
  var c = _("country").value;
  var g = _("gender").value;
  var status = _("status");
  if(u == "" || e == "" || p1 == "" || p2 == "" || c == "" || g == ""){
    status.innerHTML = "Fill out all of the form data";
  } else if(p1 != p2){
    status.innerHTML = "Your password fields do not match";
  } else {
    _("signupbtn").style.display = "none";
    status.innerHTML = 'please wait ...';
    var ajax = ajaxObj("POST", "signup.php");
        ajax.onreadystatechange = function() {
          if(ajaxReturn(ajax) == true) {
              if(ajax.responseText != "signup_success"){
          status.innerHTML = ajax.responseText;
          _("signupbtn").style.display = "block";
        } else {
          window.scrollTo(0,0);
          _("signupform").innerHTML = "OK "+u+", check your email inbox and junk mail box at <u>"+e+"</u> in a moment to complete the sign up process by activating your account. You will not be able to do anything on the site until you successfully activate your account.";
        }
          }
        }
        ajax.send("u="+u+"&e="+e+"&p="+p1+"&c="+c+"&g="+g);
  }
}
