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
#   Copyright 2005-2010 by webspell.org                                  #
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

// important data include
include("_mysql.php");
include("_settings.php");
include("_functions.php");

$_language->read_module('index');
$index_language = $_language->module;
// end important data include
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>NEWDESTINY eSports &copy; 2015</title>
		<link href="css/page.css" rel="stylesheet" type="text/css" />
		<link href="_stylesheet.css" rel="stylesheet" type="text/css" />
		<link href="menu.css" rel="stylesheet" type="text/css" media="screen" />
		<link href="http://fonts.googleapis.com/css?family=Jura:600" rel="stylesheet" type="text/css">
		<link href="http://fonts.googleapis.com/css?family=Questrial" rel="stylesheet" type="text/css">
		
    	<script type="text/javascript" language="javascript" src="easytabs.js"></script>
		
		<script src="js/mouseovertabs.js" type="text/javascript"></script>    
		
		<script type="text/javascript" src="js/jquery.js"></script>
		
		<link rel="stylesheet" href="css/nivo-slider.css" type="text/css" media="screen" />      
        
		<script src="js/bbcode.js" language="jscript" type="text/javascript"></script>
        
		<script type="text/javascript" src="js/jquery.nivo.slider.pack.js"></script>
		
<script type="text/javascript">
$(window).load(function() {
	$('#slider').nivoSlider({
		effect:'fold', // Specify sets like: 'fold,fade,sliceDown'
	});
});
</script>
<script type="text/javascript">
 
$(document).ready(function(){
 
	$('#conte').fadeIn(2000);
 
});
 
</script>
		
		<script src="js/page.js" type="text/javascript"></script>
		<script src='https://www.google.com/recaptcha/api.js'></script>
</head>
<body>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/pt_PT/sdk.js#xfbml=1&version=v2.4&appId=524577050988795";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<div id="all">
<div id="top">
  <table width="100%" height="45" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td align="left" valign="middle">
		<?php include ("counter.php") ?>
	  </td>
    </tr>
  </table>
</div>
<div id="header">
  <table width="100%" height="200" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td width="30%"><div style="padding-left:50px;"><a class="toplogo" href="http://nd-esports.ga"><img src="slice/logo.png" alt="logo" width="200" height="200" /></a></div></td>
	  <!-- SPONSORS -->
      <td width="70%" align="center" valign="middle">
		<table>
			<tr><?php include ("sc_sponsors.php") ?></tr>
			<br /><br />
			<tr><?php include ("sc_sponsors_main.php") ?></tr>
		</table>
	  </td>
    </tr>
  </table>
</div>
<div id="navigation">
<?php include ("navigation.php") ?>
</div>
<br /><br />
<div id="wrapper">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="70%" valign="top"><br />
	<div id="conte" style="display:none;"> 
	<div style="word-break: break-all;">
         <?
     if(!isset($site)) $site="news";
     //Sichheitsl&uuml;cke beheben
     $invalide = array('/','/\/',':','.');
     $site = str_replace($invalide,' ',$site);
     if(!file_exists($site.".php")) $site = "home";
     include($site.".php");
     ?>
	</div>
   	</div></td>
      <td width="30%" valign="top">
	  <div id="sidebar">
	  
      <h2>Login panel</h2><hr>
      <div id="logpanel"><?php include ("login.php") ?></div>
	  <h2>NEWDESTINY News</h2><hr>
	  <div class="sidebarbox"><?php include ("sc_topnews.php") ?></div>
	  <h2>Scene News</h2><hr>
	  <div class="sidebarbox"><?php include ("sc_rubric2.php") ?></div>
	  <h2>Last Results</h2><hr>
	  <div class="sidebarbox"><?php include ("sc_results.php") ?></div>
	  <h2>Facebook</h2><hr>
	  <div class="sidebarbox" style="padding:5px;min-height:150px;"><div class="fb-page" data-href="https://www.facebook.com/newdestinyesports" data-width="350" data-height="350" data-small-header="true" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true" data-show-posts="true"><div class="fb-xfbml-parse-ignore"><blockquote cite="https://www.facebook.com/newdestinyesports"><a href="https://www.facebook.com/newdestinyesports">Facebook</a></blockquote></div></div></div>
	 
      </div>
	</td>
    </tr>
  </table>
</div>
<br />
<div id="footer">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td width="10%"><div style="padding-left:10px;"><img src="slice/logo.png" alt="logo" width="200" height="200" /></div></td>
	  <!-- SPONSORS -->
	  <td width="40%" align="center"><h5>About US</h5><br /><hr><div style="word-break: break-all;"><?php include ("sc_about.php") ?></div></td>
	  <td width="25%" align="center" style="line-height:18px;">
		  <h5>Navigation</h5>
		  <br /><hr><br />
			  <a href="index.php?site=home">HOME</a> <br />
			  <a href="index.php?site=news">NEWS</a> <br />
			  <a href="index.php?site=squads">SQUADS</a> <br />
			  <a href="index.php?site=videos">VIDEOS</a> <br />
			  <a href="index.php?site=forum">FORUMS</a> <br />
			  <a <a href="index.php?site=sponsors">SPONSORS</a> <br />
			  <a <a href="index.php?site=about">ABOUT US</a>
	  </td>
      <td width="25%" align="center">
		  <div style="padding-right:10px;line-height:70px;padding-top:20px;">
		  <img src="slice/fb.png" alt="logo" width="64" height="64" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		  <img src="slice/y2b.png" alt="logo" width="64" height="60" /> <br />
		  <img src="slice/twit.png" alt="logo" width="64" height="64" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		  <img src="slice/steam.png" alt="logo" width="64" height="64" />
		  </div>
	  </td>
    </tr>
  </table>
  <hr class="max">
  <center><h5>NEWDESTINY eSports &copy; 2015 - All rights reserved<br />Developed by <a href="http://joserodrigues.cf">José 'zhn' Rodrigues</a></h5></center>
</div>