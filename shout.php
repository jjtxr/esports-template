<script src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
<script language="javascript" type="text/javascript" src="js/tchat.js"></script>
<link href="css/tchat.css" rel="stylesheet" type="text/css" />


<!-- CUP INTEGRATION -->
<?php 
$channelID = mysql_real_escape_string($_GET['id']);
$type = mysql_real_escape_string($_GET['type']);

   if($_GET['call']=='true') {
       redirect('popup.php?site=shout&id='.$channelID.'&type=userID', '<font color="red"><strong>You have been called to your chat!</strong></font>', 5);
   }
   
   if($type=='matchID') {
       $md=mysql_fetch_array(safe_query("SELECT * FROM ".PREFIX."cup_matches WHERE matchID='$channelID'"));    
   }

   switch($type) {
       case '':        $title = '';                               
           break;
       case 'userID':  $title = 'with <a href="index.php?site=profile&id='.$channelID.'" target="_blank">'.getnickname($channelID).'</a>';  
	   break; 
       case 'clanID':  $title = 'with <a href="index.php?site=clans&action=show&clanID='.$channelID.'" target="_blank">'.getclanname2($channelID).'</a>'; 
	   break;
       case 'cupID':   $title = 'on <a href=""index.php?site=cups&action=details&cupID='.$channelID.'" target="_blank">'.getcupname($channelID).'</a>';      
	   break;
       case 'laddID':  $title = 'on <a href=""index.php?site=ladders&ID='.$channelID.'" target="_blank">'.getladname($channelID).'</a>';                                       
   	   break;
       case 'matchID': $title = ($md['1on1']==1 ? '(<a href="index.php?site=profile&id='.$md['clan1'].'" target="_blank">'.getnickname($md['clan1']).'</a> vs <a href="index.php?site=profile&id='.$md['clan2'].'" target="_blank">'.getnickname($md['clan2']).'</a>)' : '(<a href="index.php?site=clans&action=show&clanID='.$md['clan1'].'" target="_blank">'.getclanname($md['clan1']).'</a> vs <a href="?site=clans&action=show&clanID='.$md['clan2'].'" target="_blank">'.getclanname($md['clan2']).'</a>)'); 
	   break;
   }

   if($channelID != "" AND $type != "") {
          $validation_pass = 0;	
          $parent = "&id=$channelID&type=$type";
          $whois_qry = "&id=$channelID&type=$type";
   }
   else{
          $validation_pass = "true";	
          $parent = "";
          $whois_qry = "&id=0&type=";
   }

   if($type=='userID') {

          $validation_pass = 0;	
          $validation_pass = "true";	
	   
   }
   elseif($type=='clanID') {
 
          $td=mysql_fetch_array(safe_query("SELECT * FROM ".PREFIX."cup_all_clans WHERE ID='$channelID'"));
 
          $validation_pass = 0;	

          if(islocked($channel)) {
	             $validation_pass = "Team is locked";  
	  }
	  elseif(!$td['chat']) {
	             $validation_pass = "Leader disabled chat";  
	  }  
	  elseif((memin($userID,$channelID) && $td['chat']==1) || $td['chat']==2){
                 $validation_pass = "true";	 
	  }
	  elseif(iscupadmin($userID)){
                 $validation_pass = "true";	 
	  }
	  else{
                 $validation_pass = "Insufficient permissions";
	  }
   }
   elseif($type=='matchID') {

          $validation_pass = 0;	

          if(ismatchparticipant($userID,$channelID,1) || iscupadmin($userID)) {
                 $validation_pass = "true";
	  }
	  else{
	         $validation_pass = "Insufficient permissions";
	  }
   }
   elseif($type=='cupID' OR $type=='laddID') {
   
          $validation_pass = 0;			  
	  $iscup = ($type=='cupID' ? 1 : 0);
          $cup=mysql_fetch_array(safe_query("SELECT * FROM ".PREFIX.($type=='cupID' ? 'cups' : 'cup_ladders')." WHERE ID='$channelID'"));  
		  
          if((!$iscup && isladparticipant_memin($userID,$channelID)) || ($iscup && iscupparticipant_memin($userID,$channelID)) && $cup['status']==2) {
                 $validation_pass = "true";	 
	  }
	  elseif(iscupadmin($userID)){
                 $validation_pass = "true";
	  }
	  elseif($cup['status']!=2){
                 $validation_pass = "Chat not started or already ended";
	  }
	  else{
                 $validation_pass = "Insufficient permissions";
	  }
   
   }
?>
<!--!CUP INTEGRATION -->

<script type="text/javascript">
function refreshTchat(){
	$.ajax({ 
		type: "GET",
		url: "some.php",
		data: "action=refresh<?php echo $parent; ?>", 
		success: function(msg){ 
			document.getElementById("Tchat").innerHTML = msg; 
		} 
	});
	setTimeout("refreshTchat()",1000);
}
function refreshUsers(){
	$.ajax({ 
		type: "GET",
		url: "some.php",
		data: "action=refreshUser<?php echo $whois_qry; ?>", 
		success: function(msg){ 
			document.getElementById("users").innerHTML = msg; 
		} 
	});
	setTimeout("refreshUsers()",1000);
}
refreshUsers();
refreshTchat();
</script>
<!-- /CUP INTEGRATION -->

<?php
header('Content-Type: text/html; charset=utf-8'); 
$_language->read_module('shout');
$_language->read_module('bbcode', true);
eval ("\$shout_head = \"".gettemplate("shout_head")."\";");
echo $shout_head;

if($loggedin) {

   $userID = $userID;
   $send = $_language->module['send'];

   if(iscupadmin($userID) || $userID==$channelID){
	          $admin = '<center><a onclick="delMessage('.$ds['channelID'].');" style="cursor:pointer;">'.$_language->module['del_message'].'</a></center>';
   }

   if($validation_pass=='true') {
   
	          eval ("\$addbbcode = \"".gettemplate("addbbcode")."\";");
	          eval ("\$shout = \"".gettemplate("shout")."\";");
	          echo $shout;
   }
   elseif(isbanned($userID)) {
	          echo "You cannot connect to this chat: <font color='red'>You are banned</font>";
   }
   else{
	          echo "You cannot connect to this chat: <font color='red'>$validation_pass</font>";
   }
}
else{
	          echo "You cannot connect to this chat: <font color='red'>Not logged in</font>";
}
eval ("\$shout_foot = \"".gettemplate("shout_foot")."\";");
echo $shout_foot;

include("shout_all.php");
?>


