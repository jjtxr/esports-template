<?php
/*
##########################################################################
#                                                                        #
#           Version 4       /                        /   /               #
#          -----------__---/__---__------__----__---/---/-               #
#           | /| /  /___) /   ) (_ `   /   ) /___) /   /                 #
#          _|/_|/__(___ _(___/_(__)___/___/_(___ _/___/___               #
#                       Free Content / Management System                 #
#                                   /                                    #
#                                                                        #
#                                                                        #
#   Copyright 2005-2009 by webspell.org                                  #
#                                                                        #
#   visit webSPELL.org, webspell.info to get webSPELL for free           #
#   - Script runs under the GNU GENERAL PUBLIC LICENSE                   #
#   - It's NOT allowed to remove this copyright-tag                      #
#   -- http://www.fsf.org/licensing/licenses/gpl.html                    #
#                                                                        #
#   Code based on WebSPELL Clanpackage (Michael Gruber - webspell.at),   #
#   Far Development by Development Team - webspell.org                   #
#                                                                        #
#   visit webspell.org                                                   #
#                                                                        #
##########################################################################
*/
// Start recaptcha Mod
require_once('recaptchalib.php');
include("_recaptcha.php");
// End recaptcha Mod
$_language->read_module('register');

eval("\$title_register = \"".gettemplate("title_register")."\";");
echo $title_register;
$show = true;
if(isset($_POST['save'])) {

	//register_globals = off

	$username = mb_substr(trim($_POST['username']), 0, 30);
	$nickname = htmlspecialchars(mb_substr(trim($_POST['nickname']), 0, 30));
	$pwd1 = $_POST['pwd1'];
	$pwd2 = $_POST['pwd2'];
	$mail = $_POST['mail'];
	$sex = $_POST['sex'];
	$country = $_POST['country'];
	$homepage = $_POST['homepage'];
	$firstname = $_POST['firstname'];
	$lastname = $_POST['lastname'];
	$birthday = $b_year.'-'.$b_month.'-'.$b_day;
	$b_month=$_POST['b_month'];
	$b_day=$_POST['b_day'];
	$b_year=$_POST['b_year'];
	$flag = preg_replace("/[^a-zA-Z0-9\s]/", "", $_POST['flag']);
	//Start recaptcha Mod
		$resp = recaptcha_check_answer ($privatekey,
                                        $_SERVER["REMOTE_ADDR"],
                                        $_POST["recaptcha_challenge_field"],
                                        $_POST["recaptcha_response_field"]);
		if($resp->is_valid) $run=1;
		//End recaptcha Mod
	
	$error = array();
  	
  // check nickname
	if(!(mb_strlen(trim($nickname)))) $error[]=$_language->module['enter_nickname'];
	
	// check name
	if(!(mb_strlen(trim($firstname)))) $error[]=$_language->module['enter_name'];
  
  // check nickname inuse
	$ergebnis = safe_query("SELECT * FROM ".PREFIX."user WHERE nickname = '$nickname' ");
	$num = mysql_num_rows($ergebnis);
	if($num) $error[]=$_language->module['nickname_inuse'];
  
  // check username
  	if(!(mb_strlen(trim($username)))) $error[]=$_language->module['enter_username'];
	elseif(mb_strlen(trim($username)) > 30 ) $error[]=$_language->module['username_toolong'];
  
  // check username inuse
	$ergebnis = safe_query("SELECT * FROM ".PREFIX."user WHERE username = '$username' ");
	$num = mysql_num_rows($ergebnis);
	if($num) $error[]=$_language->module['username_inuse'];
  
  // check passwort
	if($pwd1 == $pwd2) {
		if(!(mb_strlen(trim($pwd1)))) $error[]=$_language->module['enter_password'];
	}
	else $error[]=$_language->module['repeat_invalid'];
  
  // check e-mail
	$sem = '^[a-z0-9_\.-]+@[a-z0-9_-]+\.[a-z0-9_\.-]+$';
	if(!(eregi($sem, $mail))) $error[]=$_language->module['invalid_mail'];
  
  // check e-mail inuse
	$ergebnis = safe_query("SELECT userID FROM ".PREFIX."user WHERE email = '$mail' ");
	$num = mysql_num_rows($ergebnis);
	if($num) $error[]=$_language->module['mail_inuse'];
  
 // check captcha 
	  // Start recaptcha Mod
	    if (empty($_POST["recaptcha_response_field"]) or (!$run)) $error[]=$_language->module['wrong_securitycode'];
	  // End recapctha Mod
  
  	if(count($error)) {
    	$list = implode('<br />&#8226; ', $error);
    	$showerror = '<div class="errorbox">
      	<b>'.$_language->module['errors_there'].':</b><br /><br />
      	&#8226; '.$list.'
    	</div>';
	}
	else {
		// insert in db
		$md5pwd = md5(stripslashes($pwd1));
		$registerdate=time();
		$activationkey = 1;

		safe_query("INSERT INTO `".PREFIX."user` (`registerdate`, `lastlogin`, `username`, `password`, `nickname`, `email`, `sex`, `country`, `homepage`, `firstname`, `lastname`, `birthday`, `language`, `newsletter`, `activated`) VALUES ('$registerdate', '$registerdate', '$username', '$md5pwd', '$nickname', '$mail', '$sex', '$country', '$homepage', '$firstname', '$lastname', '$birthday', '$language', '1', '".$activationkey."')");

		$insertid = mysql_insert_id();

		// insert in user_groups
		safe_query("INSERT INTO ".PREFIX."user_groups ( userID ) values('$insertid' )");
		$show=false;
		echo redirect('index.php?site=login', $_language->module['activation_successful']);
	
	}
}
if(isset($_GET['key'])) {

	safe_query("UPDATE `".PREFIX."user` SET activated='1' WHERE activated='".$_GET['key']."'");
	if(mysql_affected_rows()) redirect('index.php?site=login',$_language->module['activation_successful'],3);
	else redirect('index.php?site=login',$_language->module['wrong_activationkey'],3);

}
elseif(isset($_GET['mailkey'])) {

	safe_query("UPDATE `".PREFIX."user` SET email_activate='1', email=email_change, email_change='' WHERE email_activate='".$_GET['mailkey']."'");
	if(mysql_affected_rows()) redirect('index.php?site=login',$_language->module['mail_activation_successful'],3);
	else redirect('index.php?site=login',$_language->module['wrong_activationkey'],3);

}
else {
	if($show == true){
		$bg1=BG_1;
		$bg2=BG_2;
		$bg3=BG_3;
		$bg4=BG_4;
	
		$CAPCLASS = new Captcha;
		$captcha = $CAPCLASS->create_captcha();
		$hash = $CAPCLASS->get_hash();
		$CAPCLASS->clear_oldcaptcha();
		
	
		if(!isset($showerror)) $showerror='';
		if(isset($_POST['nickname'])) $nickname=getforminput($_POST['nickname']);
		else $nickname='';
		if(isset($_POST['username'])) $username=getforminput($_POST['username']);
		else $username='';
		if(isset($_POST['pwd1'])) $pwd1=getforminput($_POST['pwd1']);
		else $pwd1='';
		if(isset($_POST['pwd2'])) $pwd2=getforminput($_POST['pwd2']);
		else $pwd2='';
		if(isset($_POST['mail'])) $mail=getforminput($_POST['mail']);
		else $mail='';
		
		//Start recaptcha Mod
			$recaptcha = recaptcha_get_html($publickey, $error);
			//End recaptcha Mod
	
		eval("\$register = \"".gettemplate("register")."\";");
		echo $register;
	}
}
?>