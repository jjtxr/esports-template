<script language="javascript" type="text/javascript" src="js/bbcode.js"></script>
<?php
include("_mysql.php");
include("_settings.php");
include("_functions.php");
header('Content-Type: text/html; charset=utf-8'); 
$_language->read_module('shout');
$_language->read_module('bbcode', true);
if($loggedin) {
$userID = $_GET['guess'];
$guess = $_GET['user'];
$guessname = getnickname($_GET['user']);
eval ("\$shout_head_popup = \"".gettemplate("shout_head_popup")."\";");
echo $shout_head_popup;
$send = $_language->module['send'];

$admin = '<center><a onclick="delPrivate('.$userID.','.$guess.');" style="cursor:pointer;">'.$_language->module['del_message'].'</a></center>';
eval ("\$addbbcode = \"".gettemplate("addbbcode")."\";");
eval ("\$shout_popup = \"".gettemplate("shout_popup")."\";");
echo $shout_popup;
}else{
	echo $_language->module['not_conected'];	
}
eval ("\$shout_foot_popup = \"".gettemplate("shout_foot_popup")."\";");
echo $shout_foot_popup;

?>
<script src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
<script language="javascript" type="text/javascript" src="js/tchat.js"></script>
<link href="css/tchat.css" rel="stylesheet" type="text/css" />
<link href="_stylesheet.css" rel="stylesheet" type="text/css" />
<?php
echo'
<script>
	refreshTchatPrivate('.$_GET['guess'].','.$_GET['user'].');
	function ref(){
		setTimeout("refreshTchatPrivate('.$_GET['guess'].','.$_GET['user'].')",1000);
	}
</script>
';
?>
