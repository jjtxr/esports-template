<?php 
include("_mysql.php");
include("_settings.php");
include("_functions.php");
header("Content-Type: text/html");
$_language->read_module('shout');
//option
$limit = 30;

/* CUP INTEGRATION */

$channelID = mysql_real_escape_string($_GET['id']);
$type = mysql_real_escape_string($_GET['type']);

if($channelID != "" AND $type != "") {

       $whois_qry = "WHERE channelID='$channelID' && type='$type'";
       $channel = ($channelID==$userID ? "channelID='$channelID' && type='$type'" : "channelID='$channelID' && type='$type' && (pseudo='$userID' || pseudo='$channelID')");
       
}
else{
       $whois_qry = "WHERE channelID='0' && type=''";
       $channel = "channelID=''";
} 

//refresh tchat

if(isset($_GET['action']) AND $_GET['action'] == "refresh"){

$requete = "SELECT * FROM ".PREFIX."tchat WHERE $channel ORDER BY ID DESC LIMIT $limit";
$reponse = safe_query($requete) or die (mysql_error());

        /* CUP INTEGRATION */
		
		if($type=='clanID') {

                   $ds=mysql_fetch_array(safe_query("SELECT * FROM ".PREFIX."cup_all_clans WHERE ID='$channelID'"));
	   
	           if(iscupadmin($userID)) {
		       $rank = '(admin)'; 
	           }
		   elseif($userID==$ds['leader']) {
		       $rank = '(owner)';   
	           }
	           elseif(isleader($userID,$channelID)) {
	               $rank = '(leader)';  
	           }
	           elseif(ismember($userID,$channelID)) {
	               $rank = '(member)';  
	           }
	           else{
	               $rank = '(guest)';  
	           }		
		}
			
	/* !CUP INTEGRATION */	
         

$texte = "";
while($donnees = mysql_fetch_array($reponse)){
$date = date($_language->module['date'], $donnees['heure']);
$time = date("H:i", $donnees['heure']);
if(iscupadmin($donnees['pseudo'])) {
	$texte .= "
	<table width='100%' style='border-bottom:1px solid #ccc;'>
	<tr valign='top'>
	<td width='24'><img src='images/userpics/".getuserpic($donnees['pseudo'])."' width='24'/></td>
	<td><a href='?site=profile&id=".$donnees['pseudo']."' target='_blank'><b style='color:red; solo'>".getnickname($donnees['pseudo'])."</b></a> ".$rank." ".$date.' '.$_language->module['at'].' '.$time."<br />".cleartext($donnees['message'])."</td>";
	if(iscupadmin($userID)) $texte .="<td  width='10' style='cursor:pointer' onclick='delMsg(".$donnees['ID'].")'><img src='http://cdn1.iconfinder.com/data/icons/cc_mono_icon_set/blacks/48x48/delete.png' width='10' /></td>";
	echo"
	</tr>
	</table>";
}
else{
	$texte .= "
	<table width='100%' style='border-bottom:1px solid #ccc;'>
	<tr valign='top'>
	<td width='24'><img src='images/userpics/".getuserpic($donnees['pseudo'])."' width='24'/></td>
	<td><a href='?site=profile&id=".$donnees['pseudo']."' target='_blank'><b>".getnickname($donnees['pseudo'])."</b></a> ".$rank." ".$date.' '.$_language->module['at'].' '.$time."<br />".cleartext($donnees['message'])."</td>";
	if(iscupadmin($userID)) $texte .="<td  width='10' style='cursor:pointer' onclick='delMsg(".$donnees['ID'].")'><img src='http://cdn1.iconfinder.com/data/icons/cc_mono_icon_set/blacks/48x48/delete.png' width='10' /></td>";
	if(!iscupadmin($userID) && $userID == $donnees['pseudo']) $texte .="<td  width='10' style='cursor:pointer' onclick='delMsg(".$donnees['ID'].")'><img src='http://cdn1.iconfinder.com/data/icons/cc_mono_icon_set/blacks/48x48/delete.png' alt='delete' width='10' /></td>";
	echo"
	</tr>
	</table>";
	}
}

/* CUP INTEGRATION */

if($channelID AND $type=='userID') {

  $co=mysql_fetch_array(safe_query("SELECT * FROM ".PREFIX."whoisonline WHERE userID='$channelID'"));
  
  $onclick = 'onclick="popupcentree(\'some.php?action=call&id='.$channelID.'&call=now\',\'550\',\'300\')"';
  
  if($userID!=$channelID) {
  
    if(is_array($co) && $co[url]=="?site=shout&id=$channelID&type=userID") {
       echo "";
    }
    elseif(is_array($co) && $co[url]!="?site=shout&userID=$channelID") {
       echo "<a href='?site=profile&id=".$channelID."'>".getnickname($channelID)."</a> is online but not on their chat. (<a ".$onclick.">call user</a>) <br />";
    }
    else{
       echo "User is not online <br />";
    }
  }
}

/*!CUP INTEGRATION */

if($texte != ""){
	echo $texte;
}else echo $_language->module['not_message'];

}
//refresh tchat Private
elseif(isset($_GET['action']) AND $_GET['action'] == "refreshPrivate" AND isset($_GET['guess']) AND isset($_GET['user']) AND $_GET['guess'] !="" AND $_GET['user'] !=""){
$requete = safe_query("SELECT * FROM ".PREFIX."tchat_private WHERE (userID=".$_GET['user']." AND friend =".$_GET['guess']." OR userID=".$_GET['guess']." AND friend =".$_GET['user'].") ORDER BY ID DESC");

$texte = "";
while($donnees = mysql_fetch_array($requete)){
$date = date($_language->module['date'], $donnees['heure']);
$time = date("H:i", $donnees['heure']);
if(iscupadmin($donnees['userID'])) {
	$texte .= "
	<table width='100%' style='border-bottom:1px solid #ccc;'>
	<tr valign='top'>
	<td width='24'><img src='images/userpics/".getuserpic($donnees['userID'])."' width='24'/></td>
	<td><a href='?site=profile&id=".$donnees['userID']."' target='_blank'><b style='color:red; solo'>".getnickname($donnees['userID'])."</b> ".$date.' '.$_language->module['at'].' '.$time."<br /><span id='text'>".cleartext($donnees['message'])."</span></td>";
	if(iscupadmin($userID)) $texte .="<td  width='10' style='cursor:pointer' onclick='delMsgPrivate(".$donnees['ID'].")'><img src='http://cdn1.iconfinder.com/data/icons/cc_mono_icon_set/blacks/48x48/delete.png' width='10' /></td>";
	//if(iscupadmin($userID)) $texte .="<td  width='10' style='cursor:pointer' onclick='selectEditMsgPrivate(".$donnees['ID'].")'><img src='http://cdn1.iconfinder.com/data/icons/cc_mono_icon_set/blacks/16x16/doc_edit.png' alt='edit' width='10' /></td>";
	echo"
	</tr>
	</table>";
}
else{
	$texte .= "
	<table width='100%' style='border-bottom:1px solid #ccc;'>
	<tr valign='top'>
	<td width='24'><img src='images/userpics/".getuserpic($donnees['userID'])."' width='24'/></td>
	<td><a href='?site=profile&id=".$donnees['userID']."' target='_blank'><b>".getnickname($donnees['userID'])."</b></a> ".$date.' '.$_language->module['at'].' '.$time."<br /><span id='text'>".cleartext($donnees['message'])."</span></td>";
	if(iscupadmin($userID)) $texte .="<td  width='10' style='cursor:pointer' onclick='delMsgPrivate(".$donnees['ID'].")'><img src='http://cdn1.iconfinder.com/data/icons/cc_mono_icon_set/blacks/48x48/delete.png' width='10' /></td>";
	//if(iscupadmin($userID)) $texte .="<td  width='10' style='cursor:pointer' onclick='selectEditMsgPrivate(".$donnees['ID'].")'><img src='http://cdn1.iconfinder.com/data/icons/cc_mono_icon_set/blacks/16x16/doc_edit.png' alt='edit' width='10' /></td>";
	if(!iscupadmin($userID) && $userID == $donnees['userID']) $texte .="<td  width='10' style='cursor:pointer' onclick='delMsgPrivate(".$donnees['ID'].")'><img src='http://cdn1.iconfinder.com/data/icons/cc_mono_icon_set/blacks/48x48/delete.png' alt='delete' width='10' /></td>";
	//if(!iscupadmin($userID) && $userID == $donnees['userID']) $texte .="<td  width='10' style='cursor:pointer' onclick='selectEditMsgPrivate(".$donnees['ID'].")'><img src='http://cdn1.iconfinder.com/data/icons/cc_mono_icon_set/blacks/16x16/doc_edit.png' alt='edit' width='10' /></td>";
	echo"
	</tr>
	</table>";
	}
}
if($texte != ""){
	echo $texte;
}else echo $_language->module['not_message'];

}
//send message
elseif(isset($_POST['action']) AND isset($_POST['pseudo']) AND isset($_POST['message']) AND $_POST['pseudo'] != "" AND $_POST['message'] != "" AND $_POST['action'] == "envoi" ){
	$message  = $_POST['message'];
	$heure = time(); 
	$pseudo = $_POST['pseudo'];
	
        $post_id = $_POST['channelID'];
        $post_type = $_POST['type'];
	
	$site_url = "?".$_SERVER['QUERY_STRING'];
	
	safe_query("INSERT INTO ".PREFIX."tchat (`channelID`,`pseudo`,`message`,`heure`,`type`) VALUES ('$post_id','$pseudo','$message','$heure','$post_type')");
	
	if(mysql_num_rows(safe_query("SELECT userID FROM ".PREFIX."whoisonline WHERE userID='$userID'"))) {
		safe_query("UPDATE ".PREFIX."whoisonline SET time='".time()."', ip='$ip', site='shout', url='?site=shout', channelID='$post_id', type='$post_type' WHERE userID='$userID'");
		safe_query("UPDATE ".PREFIX."user SET lastlogin='".time()."' WHERE userID='$userID'");
	}
	else{
		return false;
	}
}

//send message Private
elseif(isset($_POST['action']) AND isset($_POST['pseudoPrivate']) AND isset($_POST['guessPrivate']) AND isset($_POST['message']) AND $_POST['pseudoPrivate'] != "" AND $_POST['guessPrivate'] != "" AND $_POST['message'] != "" AND $_POST['action'] == "envoiPrivate" ){
	$message  = $_POST['message'];
	$heure = time(); 
	$pseudo = $_POST['pseudoPrivate'];
	$guess = $_POST['guessPrivate'];
	
	safe_query("INSERT INTO ".PREFIX."tchat_private VALUES('','$pseudo','$guess','$message','$heure')"); 
	
	if(mysql_num_rows(safe_query("SELECT userID FROM ".PREFIX."whoisonline WHERE userID='$userID'"))) {
		safe_query("UPDATE ".PREFIX."whoisonline SET time='".time()."', ip='$ip', site='shout', url='?site=shout' WHERE userID='$userID'");
		safe_query("UPDATE ".PREFIX."user SET lastlogin='".time()."' WHERE userID='$userID'");
	}
	else safe_query("INSERT INTO ".PREFIX."whoisonline (time, userID, ip, site, url, type) VALUES ('".time()."', '$userID', '$ip', 'shout', '?site=shout')");
	
}
//del message all private
elseif(isset($_GET['action']) AND $_GET['action'] == 'delPrivate'){
	safe_query("DELETE FROM ".PREFIX."tchat_private WHERE (userID = ".$_GET['user']." AND friend = ".$_GET['guess']." OR userID = ".$_GET['guess']." AND friend = ".$_GET['user'].")");
}
//del message private
elseif(isset($_GET['action']) AND $_GET['action'] == 'delMsgPrivate' AND isset($_GET['id'])){
	safe_query("DELETE FROM ".PREFIX."tchat_private WHERE id=".$_GET['id']."");
}
//del message
elseif(isset($_GET['action']) AND $_GET['action'] == 'del'){
	safe_query("DELETE FROM ".PREFIX."tchat");
	echo'';
}//del message 1by1
elseif(isset($_GET['action']) AND $_GET['action'] == 'delMsg' AND isset($_GET['id'])){
	safe_query("DELETE FROM ".PREFIX."tchat WHERE ID = ".$_GET['id']."");
	echo'';
}
//members connected
elseif(isset($_GET['action']) AND $_GET['action'] == "refreshUser"){
	$requete = safe_query("SELECT * FROM ".PREFIX."whoisonline $whois_qry ORDER BY userID");
	$private = safe_query("SELECT * FROM ".PREFIX."tchat_private");
	$d = mysql_num_rows($private);
	$privateArray = array();
	$array = array();
	while($dm = mysql_fetch_assoc($private)){
		$privateArray[] = $dm['userID'].','.$dm['friend'];
	}
	//total connected
	$connected = safe_query("SELECT count(*) AS TOTAL_USERS FROM ".PREFIX."whoisonline $whois_qry && userID != 0");
	$ds = mysql_num_rows($connected);
	$dm = mysql_fetch_assoc($connected);
	$req = safe_query("SELECT * FROM ".PREFIX."whoisonline $whois_qry");
	if($dm['TOTAL_USERS'] == 1){
		echo $_language->module['user'].'('.$dm['TOTAL_USERS'].')';
	}
	elseif($dm['TOTAL_USERS'] > 1){
		echo $_language->module['users'].'('.$dm['TOTAL_USERS'].')';
	}
	else{
		echo $_language->module['users'].'(0)';
	}
	//	
	
	while($ds=mysql_fetch_assoc($requete)){
		if($ds['userID'] != 0){
			echo '<li style="list-style:none;">';
			
			/* CUP INTEGRATION */
			
			if($ds['userID'] != $userID){
				echo'<a style="cursor:pointer;" onclick="private('.$ds['userID'].','.$userID.');" ><img src="shout/Discussion.png" width="20" /></a>';
			}
			else{
				echo'<img src="shout/Discussion_out.png" width="20" />';
			}
			
			echo '<div class="tooltip" id="location" align="left">online @ '.$ds['site'].'</div>';
			
			if(!$ds['afk']) {
			
			    if($ds['site']=='shout') {
			       $chat_now = '<img src="images/icons/online.gif">';
			    }
			    else{
			       $chat_now = '<a target="_blank" href="'.$ds['url'].'" style="text-decoration:none; cursor:pointer"><img src="images/cup/icons/question.png" border="0" alt="" title="online @ '.$ds['site'].'"  /></a>';
			    }
			}
			else{
			       $chat_now = '<img src="images/icons/offline.gif" title="AFK '.returnTime2($ds['time']).' | Last location @ '.$ds['site'].'">';
			}			
			
			/* !CUP INTEGRATION */
			
			if(iscupadmin($ds['userID'])){
				echo'<a href="?site=profile&id='.$ds['userID'].'" target="_blank"><span style="position:relative; top:-5px; color:red"><b>'.getnickname($ds['userID']).'</b> '.$chat_now.' </span></a>'; 
			}else{
				echo'<a href="?site=profile&id='.$ds['userID'].'" target="_blank"><span style="position:relative; top:-5px;color:#000"><b>'.getnickname($ds['userID']).'</b> '.$chat_now.' </span></a>';
			}
			echo'<span id="priv">';
			foreach($privateArray AS $cle => $val){
				$friend = substr($val,2,2);
				$user = substr($val,0,1);
				if($friend == $userID AND $ds['userID'] == $userID){
					echo'<a onclick="popupcentree(\'shout_user_private.php?guess='.$userID.'&user='.$user.'\',\'550\',\'300\')"><img src="http://cdn1.iconfinder.com/data/icons/fugue/icon/mail.png" /></a>';
					break;
				}
			}
			echo'</span></li>';
		}
			
	}
}
elseif(isset($_GET['action']) AND $_GET['action'] == "call"){
?>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script>
var refreshId = setInterval(function() 
{
    $("#live").load('some.php?action=call&id=<?php echo $channelID; ?>');
}, 3000);
</script>



<?php

echo '<div id="live">';

    $calltimer = time()-60;

    safe_query("UPDATE ".PREFIX."whoisonline SET calltimer='0' WHERE `call`='0' AND calltimer!='0'");
    safe_query("UPDATE ".PREFIX."whoisonline SET `call`='0' WHERE `call`='1' AND calltimer<='".$calltimer."'");

    $ds=mysql_fetch_array(safe_query("SELECT * FROM ".PREFIX."whoisonline WHERE userID='$channelID'"));
    $db=mysql_fetch_array(safe_query("SELECT * FROM ".PREFIX."whoisonline WHERE userID='$channelID' && channelID='$channelID' && type='userID'"));
    
        if($channelID && $ds['call']==0 && $ds['calltimer']==0 && $_GET['call']=='now') {
	      safe_query("UPDATE ".PREFIX."whoisonline SET `call`='1', calltimer='".time()."' WHERE userID='$channelID'");
	}
	elseif($_GET['call']=='hangup') {
	      safe_query("UPDATE ".PREFIX."whoisonline SET `call`='0', calltimer='0' WHERE userID='$channelID'");
	}
	
    if(isset($_GET['call']) && $_GET['call']=='now') {
       redirect('some.php?action=call&id='.$channelID.'&call=fail', '', 50);
    }
    
    if(is_array($db)) {
       echo '<center><strong>The user is now in the chat.</strong></center><script>setTimeout("window.close()",3000)</script></center>';
    }    
    elseif($_GET['call']=='fail'){
       safe_query("UPDATE ".PREFIX."whoisonline SET `call`='0', calltimer='0' WHERE userID='$channelID'");
       echo '<center><strong>No answer or unable to connect to '.getnickname($channelID).'</strong><br/> User maybe inactive or have pop-up blocker.<script>setTimeout("window.close()",5000)</script></center>';
    }
    elseif($_GET['call']=='hangup'){
       echo '<center><strong>You terminated the call.</strong><script>setTimeout("window.close()",3000)</script></center>';
    }
    elseif($ds['call']==1) {
       echo '<center><strong>Waiting for '.getnickname($channelID).'</strong><img src="images/cup/period_ani.gif"><br /><a href="some.php?action=call&id='.$channelID.'&call=hangup">Terminate?</a><br /></center>';
    }
    else{
       echo '<center><strong>Establishing connection</strong><img src="images/cup/period_ani.gif"></center>';
       redirect('some.php?action=call&id='.$channelID.'&call=now', '', 10);
    }

    echo '</div>'.exit;
}
?> 