
<link rel="stylesheet" href="css/dd_style.css" type="text/css" />
<script type="text/javascript" src="js/dd_script.js"></script>

<?php

include ("config.php");

$bg1=BG_1;
$bg2=BG_1;
$bg3=BG_1;
$bg4=BG_1;

//date and timezone

$timezone = safe_query("SELECT timezone FROM ".PREFIX."cup_settings");
$tz = mysql_fetch_array($timezone); $gmt = $tz['timezone'];
date_default_timezone_set($tz['timezone']);

//get members in team
$getteams=safe_query("SELECT clanID, userID FROM ".PREFIX."cup_clan_members WHERE userID='$userID'");


   echo '<ul class="menu" id="menu">
	   <li><a href="#" class="menulink">Cup Navigation <img src="images/cup/icons/next.gif" align="right"></a>
	      <ul>
	      
	        <li><a href="#" class="sub"><img src="images/cup/icons/time.png">  Date/Timezone</a>	   
				<ul>
					<li class="topline"><a href="#"><strong>Now:</strong> '.date('d/m/Y \a\t H:i').'</a></li>
					<li><a href="#"><strong>Timezone:</strong> '.$tz['timezone'].'</a></li>
				</ul>
		</li>
	      
	        <li><a href="?site=myteams" class="sub"><img src="images/cup/icons/yourteams.png" width="16" height="16">  My Teams</a>
              <ul>';
	      
   if(!$loggedin) {
   
         echo ' <li><a href="?site=login">- login account -</a></li>';
   }
   elseif(!mysql_num_rows($getteams)) {
   
         echo ' <li><a href="#">- no teams -</a></li>';
   }   

   while($te=mysql_fetch_array($getteams)) { 
      $teaminfo = safe_query("SELECT * FROM ".PREFIX."cup_all_clans WHERE ID='".$te['clanID']."'");
         while($tt=mysql_fetch_array($teaminfo)) {   
      
         $isfounder = mysql_num_rows(safe_query("SELECT ID FROM ".PREFIX."cup_all_clans WHERE leader = '".$userID."' AND ID='".$te['clanID']."'"));

         if($isfounder) {
	    $removal='<option value="?site=clans&action=delclan&clanID='.$tt['ID'].'" onclick="return confirm(\'This will delete all your team data! \r Are you sure you want to delete this team? If you want to leave this team instead, promote a leader as owner.\');">Delete Team</option>';
         }
	 else{
            $removal='<option value="?site=clans&action=clanleave&clanID='.$tt['ID'].'" onclick="return confirm(\'Are you sure you want to leave this team?\');">Leave Team</option>';
         }
	 
	 if(ismember($userID,$tt['ID'])) {
	    $leave_team = '<li><a href="?site=clans&action=clanleave&clanID='.$tt['ID'].'" onclick="return confirm(\'Are you sure you want to leave this team?\');"><img src="images/cup/icons/leave.gif"> Leave Team</a></li>';
	 }
	 
	 $clancountry = '<img src="images/flags/'.getclancountry($tt['ID'],$img=0).'.gif">';
		
         echo ' <li>
		        <a href="?site=clans&action=show&clanID='.$tt['ID'].'" class="sub">'.$clancountry.' '.getclanname2($tt['ID']).'</a>
				<ul>
				
					<li class="topline"><a href="?site=clans&action=show&clanID='.$tt['ID'].'"><img src="images/icons/foldericons/folder.gif"> Team Details</a></li>
					<li><a href="?site=matches&action=viewmatches&clanID='.$tt['ID'].'"><img src="images/cup/icons/add_result.gif" width="16" height="16"> Team Matches</a></li>
					<li><a href="?site=clans&action=clanedit&clanID='.$tt['ID'].'"><img src="images/cup/icons/manage.gif"> Edit Team</a></li>
					<li><a href="?site=clans&action=editpwd&clanID='.$tt['ID'].'"><img src="images/cup/icons/password.png"> Edit Password</a></li>
					<li><a href="?site=clans&action=invite&clanID='.$tt['ID'].'"><img src="images/cup/icons/message.png" width="16" height="16"> Email Invitation</a></li>
					<li><a href="javascript:void(0)" onclick="MM_openBrWindow(\'popup.php?site=shout&id='.$tt['ID'].'&type=clanID\',\'Team Chat\',\'toolbar=no,status=no,scrollbars=no,width=550,height=340\');"><img src="images/cup/icons/tchat.png" width="16" height="16"> Chat</a></li>
					'.$leave_team.'
				</ul>		     
		</li>';
	 
	
  }
 } 
 
//-- Awaiting match action by participant --//

  if($loggedin)
  { 
     $query_1on1=safe_query("SELECT * FROM ".PREFIX."cup_matches WHERE (clan1='$userID' || clan2='$userID') AND 1on1='1' AND confirmscore='0'");
     
     if(participantTeamID($userID)) 
     {
        $query_teams=safe_query("SELECT * FROM ".PREFIX."cup_matches WHERE (clan1='".participantTeamID($userID)."' || clan2='".participantTeamID($userID)."') AND confirmscore='0' AND 1on1='0'");
     }

     $totalRows = mysql_num_rows($query_1on1)+mysql_num_rows($query_teams);
     
     while($cids=mysql_fetch_array($query_1on1)) {
          
        $league = league($cids['matchID']);  
        $type = getleagueType($cids['matchID']);
        $t_name = ($league=="cup" ? "cupID" : "laddID");
        
        if($league=="cup" && is1on1($cids[$type]))
        {
           $one_cup = "&type=1";
        }
        elseif($league=="ladder" && ladderis1on1($cids[$type]))
        {
           $one_cup = "&type=1";
        }
        else
        {
           $one_cup = '';           
        }
        
	if($type=="matchno") 
	          $report_link = '?site=groups&'.$t_name.'='.$cids[$type].'&match='.$cids['matchID'];
	else{		
		
                  if($userID==$cids['clan1']) 
		     $report_link = '?site=matches&action=viewmatches&clanID='.$cids['clan1'].'&'.$t_name.'='.$cids[$type].$one_cup.'#'.$dd['matchno'].'';
		  else
		     $report_link = '?site=matches&action=viewmatches&clanID='.$cids['clan1'].'&'.$t_name.'='.$cids[$type].$one_cup.'#'.$dd['matchno'].'';
        }

        if($cids[clan1]	&& $cids[clan2]) {      
           $report1.='<a href="'.$report_link.'"><img src="images/cup/icons/edit.png" width="16" height="16"> '.getname2($cids['clan1'],$cids[$type],$ac=0,$league).' vs '.getname2($cids['clan2'],$cids[$type],$ac=0,$league).'</a>';
        }
     }
     while($cids1=mysql_fetch_array($query_teams)) 
     {
     
        $league = league($cids1['matchID']);  
        $type = getleagueType($cids1['matchID']);
        $t_name = ($league=="cup" ? "cupID" : "laddID");
        
        if($league=="cup" && is1on1($cids1[$type]))
           $one_cup = "&type=1";
        elseif($league=="ladder" && ladderis1on1($cids1[$type]))
           $one_cup = "&type=1";
        else
           $one_cup = '';
        
	if($type=="matchno") {
		    $report_link = '?site=groups&'.$t_name.'='.$cids1[$type].'&match='.$cids1['matchID'].'';
	}
	else{		
		
          if(memin($userID,$cids1['clan1'])) 
		     $report_link = '?site=matches&action=viewmatches&clanID='.$cids1['clan1'].'&'.$t_name.'='.$cids1[$type].$one_cup.'#'.$dd['matchno'].'';
		  else
		     $report_link = '?site=matches&action=viewmatches&clanID='.$cids1['clan1'].'&'.$t_name.'='.$cids1[$type].$one_cup.'#'.$dd['matchno'].'';
        }

        if($cids1[clan1] && $cids1[clan2]) {
           $report2.='<a href="'.$report_link.'"><img src="images/cup/icons/edit.png" width="16" height="16"> '.getname2($cids1['clan1'],$cids1[$type],$ac=0,$league).' vs '.getname2($cids1['clan2'],$cids1[$type],$ac=0,$league).'</a>';
        }
     }
  }  
  
  
  if($report1 || $report2) {
     $reporting_blk1 = "<blink>";
     $reporting_blk2 = "</blink>";
     $report .= '<li><blink>'.$report1.'</blink></li>';
     $report .= '<li><blink>'.$report2.'</blink></li>';
  }
  
//--!Awaiting match action by participant --//
  
echo '   
      </ul>
          <li><a href="?site=quicknavi"><img src="images/cup/icons/status.png"> Signups</a></li>
          <li><a href="?site=cups"><img src="images/cup/icons/tournament.png"> Tournaments</a></li>
	  <li><a href="?site=ladders"><img src="images/cup/icons/ladder.png"> Ladders</a></li>
	  <li><a href="?site=platforms"><img src="images/cup/icons/category.png"> Platforms</a></li>
	  <li><a href="?site=freeagents"><img src="images/cup/icons/freeagents.png" width="16" height="16"> Free Agents</a></li>
	  
	  
	  <li><a href="?site=matches" class="sub">'.$reporting_blk1.'<img src="images/cup/icons/add_result.gif" width="16" height="16"> Matches & Reporting'.$reporting_blk2.'</a>
				<ul>
					<li class="topline"><a href="?site=matches"><img src="images/cup/icons/add_result.gif" width="16" height="16"> Match Search</a></li>
					'.$report.'
				</ul>
	  </li>
	  
	  
	  <li><a href="?site=clans" class="sub"><img src="images/cup/icons/yourteams.png" width="16" height="16"> Teams</a>
				<ul>
					<li class="topline"><a href="?site=clans"><img src="images/cup/icons/yourteams.png" width="16" height="16"> All Teams / Search</a></li>
					<li><a href="?site=clans&action=clanjoin"><img src="images/cup/icons/join.gif"> Join Team</a></li>
					<li><a href="?site=clans&action=clanadd"><img src="images/cup/icons/add2.gif"> Create Team</a></li>
				</ul>
	  </li>
	  <li><a href="?site=halloffame" class="sub"><img src="images/cup/icons/ratio.png"> Hall of Fame</a>
	  
	  <ul>
	        <li><a href="?site=halloffame" class="sub"><img src="images/cup/icons/tournament.png"> Tournaments</a>
				<ul>
					<li class="topline"><a href="?site=halloffame&type=one"><img src="images/cup/icons/profile.png" width="14" height="14"> Single Tournaments</a></li>
					<li><a href="?site=halloffame"><img src="images/cup/icons/yourteams.png" width="16" height="16"> Team Tournaments</a></li>
				</ul>
		</li>
		<li><a href="?site=halloffame&cup=ladders" class="sub"><img src="images/cup/icons/ladder.png"> Ladders</a>
				<ul>
					<li class="topline"><a href="?site=halloffame&type=one&cup=ladders"><img src="images/cup/icons/profile.png"> Single Ladders</a></li>
					<li><a href="?site=halloffame&cup=ladders"><img src="images/cup/icons/yourteams.png" width="16" height="16"> Team Ladders</a></li>
				</ul>
		
		</li>
          </ul>
	  
	  <li><a href="?site=profile&id='.$userID.'" class="sub"><img src="images/cup/icons/profile.png" width="14" height="14"> My Profile</a>';
	  
if($loggedin) {

echo '
				<ul>
					<li class="topline"><a href="?site=profile&id='.$userID.'"><img src="images/icons/foldericons/folder.gif"> View Profile</a></li>
					<li><a href="?site=myprofile"><img src="images/cup/icons/manage.gif" width="16" height="16"> Edit Profile</a></li>
					<li><a href="?site=myprofile&action=gameaccounts"><img src="images/cup/icons/edit.png" width="16" height="16"> Edit Gameaccounts</a></li>
					<li><a href="?site=messenger"><img src="images/cup/icons/message.png" width="16" height="16"> PM Inbox</a></li>
					<li><a href="?site=matches&action=viewmatches&memberID='.$userID.'"><img src="images/cup/icons/add_result.gif" width="16" height="16"> My Matches</a></li>
				</ul>';
}else{

echo '
				<ul>
					<li class="topline"><a href="?site=login">- login account -</a></li>
				</ul>';
}

echo '    <li><a href="?site=shout"><img src="images/cup/icons/tchat.png" width="16" height="16"> Chat</a></li>
	  </li>
	  <li><a href="?site=cupactions&action=mytickets" class="sub"><img src="images/cup/icons/support.png" width="16" height="16"> Help</a>
				<ul>
					<li class="topline"><a href="?site=faq"><img src="images/cup/icons/forward.gif"> FAQ</a></li>
					<li><a href="?site=cupactions&action=mytickets"><img src="images/cup/icons/forward.gif"> My Tickets</a></li>
					<li><a href="?site=contact"><img src="images/cup/icons/forward.gif"> Contact Webmaster</a></li>
					<li><a href="?site=bugtracker"><img src="images/cup/icons/forward.gif"> Report Bug</a></li>
				</ul>
	  </li>
      </ul>
   </li>
</ul>';


?>

<script type="text/javascript">
	var menu=new menu.dd("menu");
	menu.init("menu","menuhover");
</script>
	