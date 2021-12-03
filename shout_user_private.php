<?php
include("_mysql.php");
include("_settings.php");
include("_functions.php");
$_language->read_module('shout');
?>
<script src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
<script language="javascript" type="text/javascript" src="js/tchat.js"></script>
<link href="css/tchat.css" rel="stylesheet" type="text/css" />
<link href="_stylesheet.css" rel="stylesheet" type="text/css" />
<div class="title"><?php echo $_language->module['userPrivate'] ?></div>
<?php 
$test="";
$requete = safe_query("SELECT DISTINCT userID, friend FROM ".PREFIX."tchat_private WHERE friend =".$userID."");
while($ds = mysql_fetch_array($requete)){
	echo'<a style="cursor:pointer;" onclick="popupcentree(\'shout_popup_guess.php?guess='.$ds['friend'].'&user='.$ds['userID'].'\',\'550\',\'300\')">'.getnickname($ds['userID']).'</a><br>';	
}
?> 
<script>
setTimeout("refre()",10000);
function refre(){
	window.location.reload();	
}
</script>