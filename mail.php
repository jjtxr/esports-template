<?php

$email = stripslashes($_POST['email']);
$subject = stripslashes($_POST['subject']);
$msg  = "From : $name \r\n";
$msg .= "e-Mail : $email \r\n";
$msg .= "Website : $website \r\n";
$msg .= "Subject : $subject \r\n\n";
$password = $HTTP_POST_VARS['password'];
$msg .= "---Message--- \r\n".stripslashes($_POST['msg'])."\r\n";

/* PHP form validation: the script checks that the Email field contains a valid email address and the Subject field isn't empty. preg_match performs a regular expression match. It's a very powerful PHP function to validate form fields and other strings - see PHP manual for details. */
if (!preg_match("/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/", $email)) {
  echo "<div class='errorbox'>Invalid email address";
  echo "<br><a href='javascript:history.back(1);'><- Go Back</a></div>";
} elseif ($subject == "") {
echo "<div class='errorbox'>No subject</h4></div>";
} elseif ($msg == "") {
echo "<div class='errorbox'>No message</h4></div>";
}

elseif (mail($email,$subject,$message,$msg,$password)) {
  echo "<div style='margin: 5px; padding: 6px; border: 4px solid #D6B4B4; text-align: center;'><b>Your invitation to ".stripslashes($_POST['email'])." has been successfully sent!</b> <img src='images/cup/success.png' width='16' height='16'><br><a href='index.php'><b>Return to Homepage</b></a> | <a href='?site=myteams'><b>My Teams</b></a> | <a href='javascript: history.go(-1)'><b>Invite Another</b></a></div>";
} else {
  echo "<div class='errorbox'>Can't send email to $email</div>";
}
?>