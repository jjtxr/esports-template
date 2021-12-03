<link href="cup.css" rel="stylesheet" type="text/css">
<style>
/* Team inactive, fade/grey out images */
#myImage {
    opacity : 0.5;
    filter: alpha(opacity=40); // msie
}


.show_hide_ci, .show_hide_cr, .show_hide_lg, .show_hide_gs, .slidingDiv_dt  {
	display:none;
}
</style>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.js" type="text/javascript"></script>
<script type="text/javascript">

$(document).ready(function(){

        $(".slidingDiv_ci").hide();
        $(".show_hide_ci").show();

	$('.show_hide_ci').click(function(){
	$(".slidingDiv_ci").slideToggle();
	});
	
        $(".slidingDiv_cr").hide();
        $(".show_hide_cr").show();

	$('.show_hide_cr').click(function(){
	$(".slidingDiv_cr").slideToggle();
	});
	
        $(".slidingDiv_lg").hide();
        $(".show_hide_lg").show();
	
	$('.show_hide_lg').click(function(){
	$(".slidingDiv_lg").slideToggle();
	});
	
        $(".slidingDiv_gs").<?php echo $_GET['display']=='gs' ? 'show' : 'hide'; ?>();
        $(".show_hide_gs").show();
	
	$('.show_hide_gs').click(function(){
	$(".slidingDiv_gs").slideToggle();
	});
	
        $(".slidingDiv_dt").show();
        $(".show_hide_dt").show();
	
	$('.show_hide_dt').click(function(){
	$(".slidingDiv_dt").slideToggle();
	});
	
        $(".slidingDiv_dr").show();
        $(".show_hide_dr").show();
	
	$('.show_hide_dr').click(function(){
	$(".slidingDiv_dr").slideToggle();
	});

});

</script>

<?php
/*
##########################################################################
#               CUP ADDON LADDER EXTENSION BY CUPADDON.COM               #                                                                                                                    #
##########################################################################
*/

$_language->read_module('ladders');
qualifiersToLeague($_GET['ID']);
check_winners();
match_query_type();

//include configuration
include("config.php");
$name_type = "Ladder";
$typ_e = "type";

$bg1=BG_1;
$bg2=BG_1;
$bg3=BG_1;
$bg4=BG_1;
$cpr=ca_copyr();
!$cpr || !ca_copyr() ? die() : '';

//date and timezone

getladtimezone();
$gmt = getladtimezone(1);
$gmt.= " (Now: ".date("dS - H:i").")";

/* Ladder SQL-Querys */
// safe_query("UPDATE ".PREFIX."cup_ladders SET status='2' WHERE start<='".time()."'");
// safe_query("UPDATE ".PREFIX."cup_ladders SET status='3' WHERE end<='".time()."'");
/**************/

  $ladID = $_GET['ID'];

  if(!$ladID || platform_enabled($ladID)=="true") {
  if($_GET['ID']) include("title_ladder.php");

//ladder registration

if(isset($_GET['action']) && $_GET['action'] == 'register') {

    $clanID=isset($_GET['clanID']) ? $_GET['clanID'] : 0;
    $ladderID=$_GET['laddID'];
    $checkin_status = $_GET['checkin']==1 ? 1 : 0;

    $info = safe_query("SELECT * FROM ".PREFIX."cup_ladders WHERE ID='".$ladderID."'");
    $lad = mysql_fetch_array($info);
    
    $ergebnis2 = safe_query("SELECT count(*) as anzahl FROM ".PREFIX."cup_clans WHERE ladID='".$ladderID."' && 1on1='1'");
    $dm=mysql_fetch_array($ergebnis2);
	
  //check group stages
  
    $check_qualifyers = safe_query("SELECT qual, clanID FROM ".PREFIX."cup_clans WHERE groupID='$ladderID' AND qual='1' AND cupID='0'");
    $is_qualifyers = mysql_num_rows($check_qualifyers); 
    
    while($iq = mysql_fetch_array($check_qualifyers)) {
       $qualified_ID[]=$iq['clanID']; 
    }
    
    $check_fcfs = safe_query("SELECT qual, clanID FROM ".PREFIX."cup_clans WHERE groupID='$ladderID' AND qual='2' AND cupID='0'");
    $is_fcfs = mysql_num_rows($check_fcfs); 
    
    while($fs = mysql_fetch_array($check_fcfs)) {
       $fcfs_ID[]=$fs['clanID']; 
    }
    
    $c_qualifiers = count($qualified_ID);

    $gs=mysql_fetch_array(safe_query("SELECT * FROM ".PREFIX."cup_ladders WHERE ID='$ladderID'"));
    
    if($gs['status']==1 && $gs['gs_start'] <= time() && $gs['gs_end'] >= time())
    { $is_groupstages = true;  }else{ $is_groupstages = false; }
    
    if($gs['status']==1 && $gs['gs_start'] > time()) 
    { $is_groupreg = true; }else{ $is_groupreg = false; }
	
  //date
  
	$day = date('d');
	$month = date('m');
	$year = date('Y');
	$hour = date('H');
	$min = date('i');
	$date = @mktime($hour, $min, 0, $month, $day, $year);

  //if not logged in

	if(!$loggedin) die('You are not logged in!');
		
  //1on1 ladder
	
	if(ladderis1on1($ladderID)) {
    	
		$checkgameacc=mysql_num_rows(safe_query("SELECT * FROM ".PREFIX."user_gameacc WHERE type = '".$lad['gameaccID']."' && userID = '$userID' && log='0'"));
		$gameacc_sql2 = safe_query("SELECT type FROM ".PREFIX."gameacc WHERE gameaccID = '".$lad['gameaccID']."'");
	    $dr=mysql_fetch_array($gameacc_sql2);
  
		$ergebnis2 = safe_query("SELECT count(*) as participants FROM ".PREFIX."cup_clans WHERE ladID='".$ladderID."' && checkin='1'");
		$dv=mysql_fetch_array($ergebnis2);	

		$ergebnis3 = safe_query("SELECT * FROM ".PREFIX."cup_clans WHERE clanID= '".$userID."' && 1on1='1' && ladID='".$ladderID."'");
		$clannum=mysql_num_rows($ergebnis3);
		
  	if($is_groupreg) {
            echo '<div '.$error_box.'>To be an eligible contestant in this tournament you must first signup and qualify to the <a href="?site=groups&laddID='.$ladderID.'">group stages</a>.</div>';
	    redirect('?site=groups&laddID='.$ladderID, '', 3);
       }elseif($is_groupstages)
  	    echo '<div style="margin: 5px; padding: 6px; border: 4px solid #D6B4B4; text-align: center;"><b>Group stages is currently running, to be an eligible contestant in this ladder you must first qualify to the <a href="?site=groups&laddID='.$_GET['laddID'].'">group stages</a>.</b></div>';		    
        elseif($is_qualifyers && $c_qualifiers > $dm['anzahl'] && in_array($userID,$fcfs_ID))
            echo '<div style="margin: 5px; padding: 6px; border: 4px solid #D6B4B4; text-align: center;"><b>Sorry, you must wait for qualified participants first.</b></div>';
        elseif($is_qualifyers && !in_array($userID,$qualified_ID) && !in_array($userID,$fcfs_ID))
            echo '<div style="margin: 5px; padding: 6px; border: 4px solid #D6B4B4; text-align: center;"><b>Sorry, you are not a qualified participant.</b></div>';
	elseif($clannum)
	    echo '<br>You are already a participant, if you have not checked then leave the cup and then check-in immediately.';
	elseif(userislocked($userID))
	    echo '<div style="margin: 5px; padding: 6px; border: 4px solid #D6B4B4; text-align: center;"><b>Sorry, your cup profile is locked!</b> <img src="images/cup/error.png" width="16" height="16"><br>View your penalty points at <a href="?site=profile&userID='.$userID.'">your profile</a>.</div>'; 
        elseif($lad['clanmembers']==1 && !isclanmember($userID))
           echo '<div style="margin: 5px; padding: 6px; border: 4px solid #D6B4B4; text-align: center;"><b>Sorry, this is a clanmembers cup only!</b> <img src="images/cup/error.png" width="16" height="16"><br>To become a clanmember and assigned to the members roster, contact a squad admin.</div>'; 
        elseif(($lad['ratio_low'] || $lad['ratio_high']) AND userscoreratio($userID) < $lad['ratio_low'] || userscoreratio($userID) > $lad['ratio_high'])
	   echo '<div style="margin: 5px; padding: 6px; border: 4px solid #D6B4B4; text-align: center;"><b>Sorry, your score ratio is not for this cup.</b> <img src="images/cup/error.png" width="16" height="16"><br>Your score ratio must range from <font color="red"><b>'.$lad['ratio_low'].'%</b></font> to <font color="red"><b>'.$lad['ratio_high'].'%</b></font> for this cup. Your score ratio right now is <font color="red"><b>'.userscoreratio($userID).'%</b></font></div>';
	elseif($lad['status']==1 && $lad['gs_start'] <= time() && $lad['gs_end'] >= time())
	   echo '<center>You can not register yet as group stages are in progress. Register now to group stages? <br><br> <a href="?site=groups&action=register&laddID='.$_GET['laddID'].'"><b>Yes</b></a> | <a href="?site=standings&ladderID='.$_GET['laddID'].'"><b>No</b></a></center>';
	elseif($lad['status']==3)
	   echo 'This ladder is closed. Sign-ups are only available on sign-up phase.';
	elseif($dm['anzahl'] >= $lad['maxclan']) {
	   echo '<center>The ladder has already been filled up, sorry.</center>';
	   redirect('?site=standings&ladderID='.$ladderID, '', 4); }
	elseif($lad['cgameacclimit'] && !$checkgameacc)
	   echo '<div style="margin: 5px; padding: 6px; border: 4px solid #D6B4B4; text-align: center;">You must register the <b>'.$dr['type'].'</b> gameaccount.<img border="0" src="images/cup/error.png" width="16" height="16"></b></font><br />You can do so anytime from <a href="?site=myprofile&action=gameaccounts">this link</a> to enter your accounts.</div>';
	else{
	   safe_query("INSERT INTO ".PREFIX."cup_clans (platID, ladID, credit, registered, clanID, 1on1, checkin, tp) VALUES ('$platID', '$ladderID', '$startupcredit', '$date', '$userID', '1', '$checkin_status', '$startupcredit')");
	   redirect('?site=standings&ladderID='.$ladderID, 'Successfully registered to the ladder!', 2); }

	}
        else
        {
	
    //team participants
  
		$ergebnis2 = safe_query("SELECT count(*) as participants FROM ".PREFIX."cup_clans WHERE ladID='".$ladderID."' && checkin='1'");
		$dv=mysql_fetch_array($ergebnis2);

		$ergebnis3 = safe_query("SELECT * FROM ".PREFIX."cup_clans WHERE clanID= '".$clanID."' && 1on1='0' && ladID='".$ladderID."'");
		$clannum=mysql_num_rows($ergebnis3);
		
  //Enough members in team?

        $ergebnis = safe_query("SELECT type FROM ".PREFIX."cup_ladders WHERE ID = '".$ladderID."'");
		$dl=mysql_fetch_array($ergebnis);	
		$min_anz = strstr($dl['type'], 'on', true);
		
		$ergebnis2 = safe_query("SELECT clanID FROM ".PREFIX."cup_clan_members WHERE clanID = '".$clanID."'");
		$anz_mem = mysql_num_rows($ergebnis2);	

        $sql_members = safe_query("SELECT * FROM ".PREFIX."cup_clan_members WHERE clanID = '".$clanID."'");	
		$members = mysql_num_rows($sql_members);
		
		$needed = $lad['type']-$members;	
		
  //check gameaccounts & team status

	    $ergebnis2 = safe_query("SELECT status, password FROM ".PREFIX."cup_all_clans WHERE ID = '".$clanID."'");		
		$dl=mysql_fetch_array($ergebnis2); $password = $dl['password'];
		
		$gameacc_sql2 = safe_query("SELECT type FROM ".PREFIX."gameacc WHERE gameaccID = '".$lad['gameaccID']."'");
		$dr=mysql_fetch_array($gameacc_sql2);
		
  //can not register team to cup if your team is already in cup
 
    $myteams=safe_query("SELECT clanID, userID FROM ".PREFIX."cup_clan_members WHERE userID='$userID'");
    while($tt=mysql_fetch_array($myteams)) { 

    $cupclans=safe_query("SELECT * FROM ".PREFIX."cup_clans WHERE ladID='$ladderID' AND clanID='".$tt['clanID']."'");
    while($ee=mysql_fetch_array($cupclans))  {

  //team register validations

		if(count($ee)>1);
			echo '<div style="margin: 5px; padding: 6px; border: 4px solid #D6B4B4; text-align: center;"><img src="images/cup/error.png" width="16" height="16"> <b>You are already in this cup!<br>You can not register more teams or the same one in one cup, if you have not checked then leave the cup and then check-in immediately.</b></div>'; exit; }   
	       }if($is_groupreg) {
  		        echo '<div '.$error_box.'>To be an eligible contestant in this tournament you must first signup and qualify to the <a href="?site=groups&laddID='.$ladderID.'">group stages</a>.</div>';
		        redirect('?site=groups&laddID='.$ladderID, '', 3);
  	       }elseif($is_groupstages)
  		    echo '<div style="margin: 5px; padding: 6px; border: 4px solid #D6B4B4; text-align: center;">Group stages is currently running, to be an eligible contestant in this ladder you must first qualify to the <a href="?site=groups&laddID='.$_GET['laddID'].'">group stages</a>.</div>';	
                elseif($is_qualifyers && $c_qualifiers > $dm['anzahl'] && in_array($userID,$fcfs_ID))
                    echo '<div style="margin: 5px; padding: 6px; border: 4px solid #D6B4B4; text-align: center;"><b>Sorry, you must wait for qualified participants first.</b></div>';
                elseif($is_qualifyers && !in_array($clanID,$qualified_ID) && !in_array($clanID,$fcfs_ID))
                    echo '<div style="margin: 5px; padding: 6px; border: 4px solid #D6B4B4; text-align: center;"><b>Sorry, you are not a qualified participant.</b></div>';
	        elseif(!isleader($userID,$clanID))
			echo 'You are not a team-leader of '.getclanname($clanID).'.';
	        elseif($clannum)
			echo '<br>You are already a participant, if you have not checked then leave the cup and then check-in immediately.';
	        elseif(!$clanID)
	            echo 'Invalid team selected!';
	        elseif($lad['status']==1 && $lad['gs_start'] <= time() && $lad['gs_end'] >= time())
	            echo '<center>You can not register yet as group stages are in progress. View / register team to group stages now?> <br><br> <a href="?site=groups&laddID='.$_GET['laddID'].'"><b>Yes</b></a> | <a href="?site=standings&ladderID='.$_GET['laddID'].'"><b>No</b></a></center>';	        
		elseif($anz_mem < $lad['type'])
			echo '<center><div style="margin: 5px; padding: 6px; border: 4px solid #D6B4B4; text-align: center;"><b>You have <font color="red"><b>'.$members.'</b></font> and this is a '.$lad['type'].' cup! <img border="0" src="images/cup/error.png" width="16" height="16"></b></font><br/>You need <font color="red"><b>'.$needed.'</b></font> more in your team. <br><br>Users can <img src="images/cup/icons/join.png"> <a href="?site=clans&action=clanjoin&clanID='.$clanID.'&password='.$password.'">join this team</a> or you can <img src="images/cup/icons/invite.gif"> <a href="?site=clans&action=invite&clanID='.$clanID.'">send an invite</a>.</div></center>';
		elseif(!checkinvalidgameacc($clanID, $ladderID, $gameacc))
			echo '<center><div style="margin: 5px; padding: 6px; border: 4px solid #D6B4B4; text-align: center;">'.$_language->module['not_all'].' <B>'.$checklineup.'</B> '.$_language->module['members_entered'].' <b>'.$dr['type'].'</b> gameaccount.<img border="0" src="images/cup/error.png" width="16" height="16"></b></font><br />'.$_language->module['provide_them'].' <a href="?site=myprofile&action=gameaccounts">'.$_language->module['this_link'].'</a> '.$_language->module['so_they_can'].'</div></center>';	
		elseif($dv['participants'] >= $lad['maxclan'])
			echo 'The ladder is already filled-up';
		elseif(islocked($clanID))
			echo 'Your team is locked';
                elseif(!$dl['status'])
			echo $_language->module['register_inactive'];    
	        elseif($lad['status']==3)
	            echo 'This ladder is closed. Sign-ups are only available on sign-up phase.';
		elseif($lad['clanmembers']==1 && !isclanmember($userID))
		    echo '<div style="margin: 5px; padding: 6px; border: 4px solid #D6B4B4; text-align: center;"><b>Sorry, this is a clanmembers cup only!</b> <img src="images/cup/error.png" width="16" height="16"><br>To become a clanmember and assigned to the members roster, contact a squad admin.</div>';
		elseif(($lad['ratio_low'] || $lad['ratio_high']) AND clanscoreratio($clanID) < $lad['ratio_low'] || clanscoreratio($clanID) > $lad['ratio_high']) 		
		    echo '<div style="margin: 5px; padding: 6px; border: 4px solid #D6B4B4; text-align: center;"><b>Sorry, your team score ratio is not for this cup.</b> <img src="images/cup/error.png" width="16" height="16"><br>Your score ratio must range from <font color="red"><b>'.$lad['ratio_low'].'%</b></font> to <font color="red"><b>'.$lad['ratio_high'].'%</b></font> for this cup. Your score ratio right now is <font color="red"><b>'.clanscoreratio($clanID).'%</b></font></div>';
		else{
		  safe_query("INSERT INTO ".PREFIX."cup_clans (platID, ladID, credit, registered, clanID, 1on1, checkin, tp) VALUES ('$platID', '$ladderID', '$startupcredit', '$date', '$clanID', '0', '$checkin_status', '$startupcredit')");
		  redirect('?site=standings&ladderID='.$ladderID, 'Successfully registered to the ladder!', 2);
		 }
    }       
		

}

//leave ladder

 elseif(isset($_GET['action']) && $_GET['action'] == 'leave') 
 {
    $clanID = $_GET['clanID'];
    $laddID = $_GET['laddID'];
	
	$one = ladderis1on1($laddID) ? "1on1='1'" : "1on1='0'";
   
	$ergebnis3 = safe_query("SELECT * FROM ".PREFIX."cup_clans WHERE clanID='$clanID' && ladID='$laddID' && $one");
	$notin=mysql_num_rows($ergebnis3);
	
	$ergebnis3 = safe_query("SELECT * FROM ".PREFIX."cup_clans WHERE clanID='$clanID' && ladID='$laddID' && $one && checkin='1'");
	$notchecked=mysql_num_rows($ergebnis3);
	
    if(!$loggedin) 
    {
        echo 'Not logged in.';	
    }
    else
    {
    
  //can not leave cup if status is not signup phase
  
	$getstatus=safe_query("SELECT status FROM ".PREFIX."cup_ladders WHERE ID='$laddID'");
	$st=mysql_fetch_array($getstatus); 
	
    if($st['status']==1) 
    {
       $status = '<font color="#1cac00">Signup phase</font>';
    }
	elseif($st['status']==2) 
    {
           $status = '<font color="#FF6600">Started</font>';
    }
	elseif($st['status']==3) 
    {  
           $status = '<font color="#DD0000">Closed</font>';
    }
    
	if(!$notin)  
    {             
           echo 'You are not a registered participant.';
    }
    elseif($notchecked)   
    {    
           echo 'You are checked in therefore cannot leave the ladder.';
    }
    elseif($st['status']=='2' || $st['status']=='3') 
    {
           echo '<div '.$error_box.'>The ladder is not in sign-up phase therefore ladder registrations is not active.</div>';	
    }
    else
    {
		safe_query("DELETE FROM ".PREFIX."cup_clans WHERE clanID='".$clanID."' && ladID='$laddID' && $one");
		redirect('?site=ladders', 'You have successfully left the ladder!', 2);
	}
  }
}
 

//ladder rules

 elseif(isset($_GET['action']) && $_GET['action'] == 'rules') { 
 
	$ID = $_GET['ID']; 
	$cupname = getladname($ID);
	
	if(is1on1($ID)) $participants = 'Players';
	else $participants = 'Teams';
	
//admin entered rules
		
	$getrules = safe_query("SELECT * FROM ".PREFIX."cup_rules WHERE ladID= '".$ID."' && cupID='0'");
	if(!mysql_num_rows($getrules)){
		echo '<br /><br /><center><b>There were no rules yet registered!</b></center><br /><br />Please try again later!<br /><br />';
		echo $inctemp; echo base64_decode('');
	}else{ 
			$dd=mysql_fetch_array($getrules);
					
			if(empty($dd[value])){ 
				echo '<br /><br /><center><b>There were no rules yet registered!</b></center><br /><br />Please try again later!<br /><br />';
				echo $inctemp; echo base64_decode('');
			}else{
			
				echo '<table width="100%" cellspacing="'.$cellspacing.'" cellpadding="'.$cellpadding.'" bgcolor="'.$border.'">
				  <tr> 
					<td bgcolor="'.$bghead.'" class="title">Ladder Rules:</td>
				  </tr>
				  <tr>
				  <td bgcolor="'.$pagebg.'"></td>
				  </tr>
				  <tr> 
					<td bgcolor="'.BG_1.'">'.htmloutput(toggle($dd[value], 1)).'</td>
				  </tr> 
				  <tr> 
					<td bgcolor="'.BG_2.'">Last updated '.date("d.m.Y \a\t H:i", $dd['lastedit']).'</td>
				  </tr> 
				  </table>';
			      echo $inctemp; echo base64_decode(''); 
			}
	  }
}
		

//ladder details

elseif(isset($_GET['ID']) && $_GET['ID'] && $_GET['action']!="rules") { 
	
	  $ladder = safe_query("SELECT * FROM ".PREFIX."cup_ladders WHERE ID='".$ladID."'");
	   $ds=mysql_fetch_array($ladder);
	   
		$start = '<a name="start">'.date('l M dS Y \@\ g:i a', $ds['start']).'</a>';
		$ende = date('l M dS Y \@\ g:i a', $ds['end']);	
		$start_gs = date('l M dS Y \@\ g:i a', $ds['gs_start']);
		$end_gs = date('l M dS Y \@\ g:i a', $ds['gs_end']); 
    //winners
    
		if($ds['1st']) {
                   $first_name = getname1($ds['1st'],$ladID,$ac=0,$var='ladder');
                }
                else{
                   $first_name = 'n/a';
                }

		if($ds['2nd']) {
		   $second_name = getname1($ds['2nd'],$ladID,$ac=0,$var='ladder');
		}
		else{
		   $second_name = 'n/a';
		}
		
		if($ds['3rd']) {
		   $third_name = getname1($ds['3rd'],$ladID,$ac=0,$var='ladder');
		}
		else{
		   $third_name = 'n/a';
		}

		if($ds['1st']) 
		  $winners = '
             
            <table width="100%" cellspacing="'.cellspacing.'" cellpadding="'.$cellpadding.'" bgcolor="'.$border.'">
              <tr>
                <td bgcolor="'.$bghead.'" class="title" colspan="6"><img src="images/profile/awards.png"> Winners</td>
              </tr>
	           <tr>
		        <td bgcolor="'.$pagebg.'" colspan="6"></td>
	          </tr>
	           <tr>
		        <td bgcolor="'.$bg1.'" width="15%"><img src="images/cup/icons/award_gold.png"> <strong>1st Place</strong></td>
		        <td bgcolor="'.$bg1.'"><b>'.$first_name.'</b></td>
		      </tr>
	           <tr>
		        <td bgcolor="'.$bg1.'" width="15%"><img src="images/cup/icons/award_silver.png"> <strong>2nd Place</strong></td>
		        <td bgcolor="'.$bg1.'"><b>'.$second_name.'</b></td>
		      </tr>
	           <tr>
		        <td bgcolor="'.$bg1.'" width="15%"><img src="images/cup/icons/award_bronze.png"> <strong>3rd Place</strong></td>
		        <td bgcolor="'.$bg1.'"><b>'.$third_name.'</b></td>
		      </tr>
            </table><br>';
		

    //!winners    	
		
    $getmatches = safe_query("SELECT * FROM ".PREFIX."cup_matches WHERE ladID= '".$ladID."' && cupID='0'");
    $played_matches = mysql_num_rows($getmatches);
		
  $ladder_abbrev = '('.$ds['abbrev'].')';		
		
  $ladder_platform = '
	<tr>
		<td bgcolor="'.$bg1.'" width="20%"><img src="images/cup/icons/category.png"><strong>Platform</strong></td>
		<td bgcolor="'.$bg1.'">'.getplatname($ds['platID']).'</td>
	</tr>';		
		
		$cupdesc = htmloutput(toggle($ds['desc'], 1));
		$status = $ds['status'];
		$checkin = $ds['checkin'];
		$checkindate = date('H:i', ($ds['start']-($ds['checkin']*60))).'';
		$checkintime = $ds['start']-($ds['checkin']*60);
		
	$rl_checkin_date = '	
	<tr>
		<td bgcolor="'.$bg1.'"><img src="images/cup/icons/date.png"> <strong>Ends</strong></td>
		<td bgcolor="'.$bg1.'">'.date('l M dS Y \@\ g:i a', $ds['end']).'</td>
	</tr>'; 
		
		if($status == 1){
			if(time() >= $checkintime)
				$status = 'Signup phase (Check-In)';
			else
				$status = 'Signup phase';
		}elseif($status == 2)
			$status = 'Started';
		elseif($status == 3)
			$status = 'Closed';
	   
		$ergebnis = safe_query("SELECT count( ID ) as clans FROM ".PREFIX."cup_clans WHERE ladID = '".$ladID."'");
		$db = mysql_fetch_array($ergebnis);	   
	   
		$ergebnis2 = safe_query("SELECT count( ID ) as clans2 FROM ".PREFIX."cup_clans WHERE ladID = '".$ladID."' && checkin = '1'");
		$dd = mysql_fetch_array($ergebnis2);
		
		$ergebnis2 = safe_query("SELECT count( ID ) as clans3 FROM ".PREFIX."cup_clans WHERE groupID = '".$ladID."'");
		$dd3 = mysql_fetch_array($ergebnis2);	   
	   
		$ergebnis2 = safe_query("SELECT count( ID ) as clans4 FROM ".PREFIX."cup_clans WHERE groupID = '".$ladID."' && checkin = '1'");
		$dd4 = mysql_fetch_array($ergebnis2);
		
		if($ds['maxclan']== 8)
			$max = 8;
		elseif($ds['maxclan']== 16)
			$max = 16;
		elseif($ds['maxclan']== 32)
			$max = 32;				
		
		if(ladderis1on1($ladID)) 
			$members = $db['clans'].' (<a href="?site=standings&ladderID='.$ladID.'">All players</a>)';
			
		else 
			$members = $db['clans'].' (<a href="?site=standings&ladderID='.$ladID.'">All Teams</a>)';

        $group_max = $max+$max;

		$members2 = $dd['clans2'].' / '.$max;
		$members3 = $dd3['clans3'].' / '.$group_max;
		$members4 = $dd4['clans4'].' / '.$group_max;
			
		$gameacc_sql = safe_query("SELECT * FROM ".PREFIX."gameacc WHERE gameaccID='".$ds['gameaccID']."'");
		$dv = mysql_fetch_array($gameacc_sql);
		$gameacc = (!$dv['type'] ? "n/a" : $dv['type']);
		
	$show_prts = "
	<tr>
		<td bgcolor='$bg1'><img src='images/cup/icons/yourteams.png' width='16' height='16'> <strong>Participants</strong></td>
	        <td bgcolor='$bg1'>$members</td>
	</tr>";	

  if(!$ds['ratio_low'] || !$ds['ratio_high']) {
   $ratio_level = $none;
   
  }else{
  
   if($skill_type) {
      $low_t = '<img src="images/cup/icons/skill_low.gif">';
      $med_t = '<img src="images/cup/icons/skill_medium.gif">';
      $high_t = '<img src="images/cup/icons/skill_high.gif">';
   }else{
      $low_t = $low;
      $med_t = $med;
      $high_t = $high;
   }
    
    if($ratio=='1') {
      if($ds['ratio_low'] || $ds['ratio_high']) {
		
            if($ds['ratio_low'] >= $h1 && $ds['ratio_high'] <= $l1) 
               $ratio_level = $low_t;        
        elseif($ds['ratio_low'] >= $h2 && $ds['ratio_high'] <= $l2)
               $ratio_level = $med_t;
        elseif($ds['ratio_low'] >= $h3 && $ds['ratio_high'] <= $l3)
               $ratio_level = $lowmed;      
        elseif($ds['ratio_low'] >= $h4 && $ds['ratio_high'] <= $l4)
               $ratio_level = $high_t; 
        elseif($ds['ratio_low'] >= $h5 && $ds['ratio_high'] <= $l5) 
               $ratio_level = $medhigh;  
        elseif($ds['ratio_low'] >= $h6 && $ds['ratio_high'] <= $l6)  
               $ratio_level = $high1; 
        elseif($ds['ratio_low'] >= $h7 && $ds['ratio_high'] <= $l7)  
               $ratio_level = $high2; 
        else{
           $ratio_level = 'Ratio: '.$ds['ratio_low'].'%-'.$ds['ratio_high'].'%'; 
	   $rl_na = 1;
	}
      }
    }else{
      if($ds['ratio_low'] || $ds['ratio_high']) { 
           $ratio_level = 'Ratio: '.$ds['ratio_low'].'%-'.$ds['ratio_high'].'%'; 
        }else $ratio_level = $none; 
      }
  }  
  
  if($ds['mode']==1)
     $mode_table = '
     
	<tr>
	  <td bgcolor="'.$bg1.'">Mode:</td>
	  <td bgcolor="'.$bg1.'">Half-Way Up - move half-way up from opponent\'s position</td>
	</tr>';
	
  elseif($ds['mode']==2)
     $mode_table = '
     
	<tr>
	  <td bgcolor="'.$bg1.'">Mode:</td>
	  <td bgcolor="'.$bg1.'">Position Exchange - take opponent\'s position</td>
	</tr>';
	
  elseif($ds['mode']==3)
     $mode_table = '
     
	<tr>
	  <td bgcolor="'.$bg1.'">Mode:</td>
	  <td bgcolor="'.$bg1.'">ELO Rating System</td>
	</tr>';
	
  if($ds['ranksys']==1)	
     $ranked_by = "Credibility Only";
  elseif($ds['ranksys']==2)
     $ranked_by = "XP Only";
  elseif($ds['ranksys']==3)
     $ranked_by = "Match Wins/Losses";
  elseif($ds['ranksys']==4)
     $ranked_by = "XP + Credibility";
  elseif($ds['ranksys']==5)
     $ranked_by = "Match Wins/Losses + Credibility";
  elseif($ds['ranksys']==5)
     $ranked_by = "Streak Only";
     
  if(!$ds['challallow'])	
     $allow_challenging = "Challenging Only - matches can only be scheduled on-site";
  elseif($ds['challallow']==1)
     $allow_challenging = "Open-Play Only - you must schedule matches yourself off-site";
  elseif($ds['challallow']==2)
     $allow_challenging = "Challenging & Open-Play - schedule matches on-site or off";


	$ladder_details = '
	
    '.$mode_table.'
	<tr>
	  <td bgcolor="'.$bg1.'"><img src="images/cup/icons/add_result.gif" width="16" height="16"> <strong>Match Play</strong></td>
	  <td bgcolor="'.$bg1.'">'.$allow_challenging.'</td>
	</tr>
	<!--
	<tr>
	  <td bgcolor="'.$bg1.'"><img src="images/cup/icons/alert.png" width="16" height="16"> <strong>Unranking</strong></td>
	  <td bgcolor="'.$bg1.'">0 Credits</td>
	</tr>
	-->
	<tr>
	  <td bgcolor="'.$bg1.'"><img src="images/cup/icons/ladder.png"> <strong>Ranked By</strong></td>
	  <td bgcolor="'.$bg1.'">'.$ranked_by.'</td>
	</tr>';
	
      if($ds['gametype']) {
      
        $gametype = '
	<tr>
	  <td bgcolor="'.$bg1.'"><strong>Gametype</strong></td>
	  <td bgcolor="'.$bg1.'">'.$ds['gametype'].'</td>
	</tr>';
      }
     
//group stages table

   if($ds['gs_regtype']) {
      $reg_type = 'Your group selection';
   }
   else{
      $reg_type = 'Randomized group registration';
   }
   
   if(!$ds['gs_staging']) {
          $to_qualify = 'Win your single match to qualify';
   }   
   elseif($ds['gs_staging']==2) {
          $to_qualify = 'Gain most XP - from your single match';   
   }
   elseif($ds['gs_staging']==1){
          $to_qualify = 'Gain most XP from your matches against all '.(ladderis1on1($ladID) ? "players" : "teams").' in your group';   
   } 
   
   $alpha_groups = "ladID='a' || ladID='b' || ladID='c' || ladID='d' || ladID='e' || ladID='f' || ladID='g' || ladID='h'";
   $dt=mysql_fetch_array(safe_query("SELECT count(*) as totalm FROM ".PREFIX."cup_matches WHERE matchno='$ladID' && ($alpha_groups) && cupID='0' && confirmscore='1' && einspruch='0'"));    

   if($ds['gs_staging']==1)
      $total_matches = $max*4;
   else
      $total_matches = $max;
   
  if($ds['gs_start'] || $ds['gs_end']) {
  
     $groups_table = '<table width="100%" cellspacing="'.$cellspacing.'" cellpadding="'.$cellpadding.'" bgcolor="'.$border.'">
	                <tr>
		          <td class="title" colspan="2"><a href="?site=groups&laddID='.$ladID.'"><img src="images/cup/icons/groups.png" border="0"></a> Group Stages <a href="#gs" name="gs" class="show_hide_gs"><img src="images/cup/icons/arrow_up_down.gif" align="right"></a></td>
	                </tr>
		      </table>';
  
  if($ds['status']==1 && $ds['gs_start'] > time()) {		    
       $gs_status = 'Signup-Phase';
  }
  elseif($ds['status']==1 && $ds['gs_start'] <= time() && $ds['gs_end'] >= time()) {
       $gs_status = 'Started';
  }
  else{
       $gs_status = 'Closed';
  }  
  
  $groups_table.= '
  
    <div class="slidingDiv_gs"> 
      <table width="100%" cellspacing="'.$cellspacing.'" cellpadding="'.$cellpadding.'" bgcolor="'.$border.'">
        <tr>
             <td bgcolor="'.$bg1.'"><img src="images/cup/icons/type_size.png" width="16" height="16"> <strong>Total</strong></td> 
             <td bgcolor="'.$bg1.'"><b>'.$ds['gs_maxrounds'].'</b> Maxrounds & <b>'.$dt['totalm'].'/'.$total_matches.'</b> Totalmatches</td>
        </tr>		
	<tr>
	        <td bgcolor="'.$bg1.'"><img src="images/cup/icons/yourteams.png" width="16" height="16"> <strong>Participants</strong></td>
	        <td bgcolor="'.$bg1.'">'.$members3.'</td>
	</tr>
	<tr>
		<td bgcolor="'.$bg1.'"><img src="images/cup/icons/date.png"> <strong>Start</strong></td>
		<td bgcolor="'.$bg1.'">'.$start_gs.'</td>
	</tr>
        <tr>
             <td bgcolor="'.$bg1.'"><img src="images/cup/icons/random.png"> <strong>Type</strong></td>
             <td bgcolor="'.$bg1.'">'.$reg_type.'</td>
        </tr>
        <tr>
             <td bgcolor="'.$bg1.'"><img src="images/cup/icons/award_silver.png" width="16" height="16"> <strong>How to qualify</strong></td>
             <td bgcolor="'.$bg1.'">'.$to_qualify.'</td>
        </tr>
        <tr>
             <td bgcolor="'.$bg1.'"><img src="images/cup/icons/ratio.png" width="16" height="16"> <strong>Approved XP</strong></td>
             <td bgcolor="'.$bg1.'">'.($ds['d_xp'] ? "Matches won only will count towards your XP" : "Mathches won and lost will count towards your XP").'</td>
        </tr>
	<tr>
		<td bgcolor="'.$bg1.'"><img src="images/cup/icons/checkin.png"> <strong>Status</strong></td>
		<td bgcolor="'.$bg1.'">'.$gs_status.'</td>
	</tr>
	<tr>
	        <td bgcolor="'.$bg1.'"><img src="images/cup/icons/status.png"> <strong>Link</strong></td>
		<td bgcolor="'.$bg1.'"><input type="button" value="Group Stages" onClick="window.location=\'?site=groups&laddID='.$ladID.'\'"></td></td>
	</tr>
      </table>
    </div>
    <br />';
    
      	$show_gs_info = '
			<tr>
			  <td class="title2" colspan="2"><img src="images/cup/icons/support.png" width="16" height="16"> This ladder requires you to qualify in <a href="?site=groups&laddID='.$ladID.'">group league</a> before permitting your registration to the ladder.</td>
			</tr>
			<tr>
			  <td bgcolor="'.$pagebg.'" colspan="2"></td>
			</tr>';
  
  }

  if($ds['inactivity']) {
  
   $col = "2";
  
     $inactive_deduct_head = '
		<td bgcolor="'.$bg1.'" align="center" class="title2" width="20%">Inactive For</td>
		<td bgcolor="'.$bg1.'" align="center" class="title2" width="10%"><i>...then...</i></td>
		<td bgcolor="'.$bg1.'" align="center" class="title2" width="20%" colspan="2">Deduct</td>';
  
     $inactive_deduct_content = '
		<td bgcolor="'.$bg1.'" align="center">'.Sec2Time($ds['inactivity']).'</td>
		<td bgcolor="'.$bg1.'" align="center"><i>(repeats)</i></td>
		<td bgcolor="'.$bg1.'" align="center" colspan="2">'.$ds['deduct_credits'].' credits</td>';	
    }
     else
          $col = "6";
          
    if($ds['remove_inactive'])
       $remove_idler = Sec2Time($ds['remove_inactive']);
    else
       $remove_idler = "Never";
       
  $credits_table = '
                      <table width="100%" cellspacing="'.$cellspacing.'" cellpadding="'.$cellpadding.'" bgcolor="'.$border.'">
	                <tr>
		          <td bgcolor="'.$bghead.'" class="title" colspan="6"><img src="images/cup/icons/ladder.png"> Credibility & Inactivity <a href="#cr" name="cr" class="show_hide_cr"><img src="images/cup/icons/arrow_up_down.gif" align="right"></a></td>
	                </tr>
		      </table>';
		      
  if($drawcredit == 0) {
     $dc = "draw";
  }
  elseif($drawcredit >0) {
     $dc = "win";
  }
  else{
     $dc = "loss";
  }
  
  //
  
  if($forfeitloss == 0) {
     $cfl = "draw";
  }
  elseif($forfeitloss >0) {
     $cfl = "loss";
  }
  
  //
  
  if($forfeitaward == 0) {
     $cfa = "draw";
  }
  elseif($forfeitaward >0) {
     $cfa = "win";
  }
  

  $credits_table.= '
  
<div class="slidingDiv_cr"> 
<table width="100%" cellspacing="'.$cellspacing.'" cellpadding="'.$cellpadding.'" bgcolor="'.$border.'">
	<tr>
		<td bgcolor="'.$bg1.'" align="center" class="title2" width="15%">Start-Up</td>
		<td bgcolor="'.$bg1.'" align="center" class="title2" width="14%">Win</td>
		<td bgcolor="'.$bg1.'" align="center" class="title2" width="14%">Draw</td>
		<td bgcolor="'.$bg1.'" align="center" class="title2" width="14%">Loss</td>
		<td bgcolor="'.$bg1.'" align="center" class="title2" width="20%">Forfeit Loss</td>
		<td bgcolor="'.$bg1.'" align="center" class="title2" width="23%">Forfeit Award</td>
	</tr>
	<tr>
		<td bgcolor="'.$bg1.'" align="center"><div class="win">+ '.$startupcredit.'</div></td>
		<td bgcolor="'.$bg1.'" align="center"><div class="win">+ '.$woncredit.'</div></td>
		<td bgcolor="'.$bg1.'" align="center"><div class="'.$dc.'">+ '.$drawcredit.'</div></td>
		<td bgcolor="'.$bg1.'" align="center"><div class="loss">- '.$lostcredit.'</div></td>
		<td bgcolor="'.$bg1.'" align="center"><div class="'.$cfl.'">- '.$forfeitloss.'</div></td>
		<td bgcolor="'.$bg1.'" align="center"><div class="'.$cfa.'">+ '.$forfeitaward.'</div></td>
	</tr>
	<tr>
	    '.$inactive_deduct_head.'
		<td bgcolor="'.$bg1.'" align="center" class="title2" width="20%" colspan="'.$col.'">Remove Idler</td>
	</tr>
	<tr>
		'.$inactive_deduct_content.'
		<td bgcolor="'.$bg1.'" align="center" colspan="'.$col.'">'.$remove_idler.'</td>
	</tr>
</table>
</div>';

//chall info table

     if(ladderis1on1($ds['ID'])) $participants = "players";
     else $participants = "teams";

     if($ds['ad_report']) $ad_report = "You can only report after the finalized Date/Time selected.";
     else $ad_report = "You can report anytime after challenge has been finalized.";
   
     if(is_numeric($ds['challup']) && $ds['challup']!=0) 
        $chall_up = 'Maximum of <b>'.$ds['challup'].'</b> ranks '.$participants.' can challenge above their rank.';
     elseif(!$ds['challup'])
        $chall_up = 'All '.$participants.' can be challenged above your rank.';
     elseif($ds['challup']=="No")
        $chall_up = 'You can not challenge '.$participants.' above your rank';
     
     if(is_numeric($ds['challdown']) && $ds['challdown']!=0) 
        $chall_down = 'Maximum of <b>'.$ds['challdown'].'</b> ranks '.$participants.' can challenge below their rank.';
     elseif(!$ds['challdown'])
        $chall_down = 'All '.$participants.' can be challenged below your rank.';
     elseif($ds['challdown']=="No")
        $chall_down = 'You can not challenge '.$participants.' below your rank.';
        
     if($ds['challquant'])
         $chall_quantity = 'You can make a total of <b>'.$ds['challquant'].'</b> challenges in this ladder.';
     else
         $chall_quantity = 'You can initiate unlimited challenges on this ladder.';
         
     if(!$ds['playdays'])
         $playdays = 'Match can be played anyday after the finalized Date/Time selected.';
     else
         $playdays = '<b>'.Sec2Time($ds['playdays']).'</b> match must be played after the finalized Date/Time selected.';
	 
	 
 if(!$ds['challallow'] || $ds['challallow'] == 2) {

  $challinfo_table = '<br>
                      <table width="100%" cellspacing="'.$cellspacing.'" cellpadding="'.$cellpadding.'" bgcolor="'.$border.'">
	                <tr>
		          <td class="title" colspan="6"><img src="images/cup/icons/challenge.gif" width="16" height="16"> Challenging Information <a href="#ci" name="ci" class="show_hide_ci"><img src="images/cup/icons/arrow_up_down.gif" align="right"></a></td>
	                </tr>
		      </table>';
 
  $challinfo_table.= '

<div class="slidingDiv_ci"> 
    <table width="100%" cellspacing="'.$cellspacing.'" cellpadding="'.$cellpadding.'" bgcolor="'.$border.'"> 
	<tr>
		<td bgcolor="'.$bg1.'" width="30%"><img id="myImage" src="images/cup/icons/map.png"> <strong>Map Selection</strong></td>
		<td bgcolor="'.$bg1.'" width="70%">Challenger must choose <b>'.$ds['select_map'].'</b> map'.($ds['select_map']==1 ? "" : "s").'. </td>
	</tr>
	<tr>
		<td bgcolor="'.$bg1.'" width="30%"><img src="images/cup/icons/map.png"> <strong>Map Finalized Selection</strong></td>
		<td bgcolor="'.$bg1.'" width="70%">Challenged must confirm <b>'.$ds['selected_map'].'</b> map'.($ds['selected_map']==1 ? "" : "s").'.</td>
	</tr>
	<tr>
		<td bgcolor="'.$bg1.'" width="30%"><img src="images/cup/icons/start.png"> <strong>Start Date/Time Availability</strong></td>
		<td bgcolor="'.$bg1.'" width="70%">Date/Times will start <b>'.$ds['timestart'].'</b>.</td>
	</tr>
	<tr>
		<td bgcolor="'.$bg1.'" width="30%"><img src="images/cup/icons/end.png"> <strong>End Date/Time Availability</strong></td>
		<td bgcolor="'.$bg1.'" width="70%">Date/Times will end <b>'.$ds['timeend'].'</b>.</td>
	</tr>
	<tr>
		<td bgcolor="'.$bg1.'" width="30%"><img id="myImage" src="images/cup/icons/time.png"> <strong>Time to Respond</strong></td>
		<td bgcolor="'.$bg1.'" width="70%">Challenged must respond <b>'.Sec2Time($ds['timetorespond']).'</b> after challenge creation.</b></td>
	</tr>
	<tr>
		<td bgcolor="'.$bg1.'" width="30%"><img src="images/cup/icons/time.png"> <strong>Time to Finalize</strong></td>
		<td bgcolor="'.$bg1.'" width="70%">Challenger must finalize <b>'.Sec2Time($ds['timetofinalize']).'</b> after challenge response.</b></td>
	</tr>
	<tr>
		<td bgcolor="'.$bg1.'" width="30%"><img src="images/cup/icons/play.png"> <strong>Play Days</strong></td>
		<td bgcolor="'.$bg1.'" width="70%">'.$playdays.'</td>
	</tr>
	<tr>
		<td bgcolor="'.$bg1.'" width="30%"><img src="images/cup/icons/forward.gif"> <strong>After-Date Reporting</strong></td>
		<td bgcolor="'.$bg1.'" width="70%">'.$ad_report.'</td>
	</tr>
	<tr>
		<td bgcolor="'.$bg1.'" width="30%"><img src="images/cup/icons/add.png"> <strong>Challenge Up</strong></td>
		<td bgcolor="'.$bg1.'" width="70%">'.$chall_up.'</td>
	</tr>
	<tr>
		<td bgcolor="'.$bg1.'" width="30%"><img src="images/cup/icons/deduct.png"> <strong>Challenge Down</strong></td>
		<td bgcolor="'.$bg1.'" width="70%">'.$chall_down.'</td>
	</tr>
	<tr>
		<td bgcolor="'.$bg1.'" width="30%"><img src="images/cup/icons/challenge.gif" width="16" height="16"> <strong>Challenge Quantity</strong></td>
		<td bgcolor="'.$bg1.'" width="70%">'.$chall_quantity.'</td>
	</tr>
    </table>
</div>';

}
//
	   
      if($ds['ratio_low'] || $ds['ratio_high']) {
      
        $show_score_ratio = '
	<tr>
		<td bgcolor="'.$bg1.'"><img src="images/cup/icons/ratio.png"> <strong>Required Ratio</strong></td>
		<td bgcolor="'.$bg1.'">'.$ds['ratio_low'].'%-'.$ds['ratio_high'].'%</td>
	</tr>';
      }
      
      if($ratio_level && $ratio_level!='n/a' && $rl_na==0) {
       
        $show_skill_level = '
      	<tr>
		<td bgcolor="'.$bg1.'"><img src="images/cup/icons/ratio.png"> <strong>Skill-level</strong></td>
		<td bgcolor="'.$bg1.'">'.$ratio_level.'</td>
	</tr>';
      }
      
      if($cupdesc) {
      
     $show_desc = '<br><table width="100%" cellspacing="'.$cellspacing.'" cellpadding="'.$cellpadding.'" bgcolor="'.$border.'">
	                <tr>
		          <td bgcolor="'.$bghead.'" class="title" colspan="2"><img src="images/icons/foldericons/folder.gif" width="13" height="13"> Info <a href="#dr" name="dr" class="show_hide_dr"><img src="images/cup/icons/arrow_up_down.gif" align="right"></a></td>
	                </tr>
		      </table>';
      
$show_desc .= '<div class="slidingDiv_dr"> 
                 <table width="100%" cellspacing="'.$cellspacing.'" cellpadding="'.$cellpadding.'" bgcolor="'.$border.'">
	            <tr>
		      <td bgcolor="'.$bg1.'" colspan="2" style="padding:3px;">'.$cupdesc.'</td>
	            </tr>
                  </table>
		</div>';

      }

		
if($_GET['ID'] && $legend && !(isset($_GET['action']))) {

  $tb_legend = '<br>
                      <table width="100%" cellspacing="'.$cellspacing.'" cellpadding="'.$cellpadding.'" bgcolor="'.$border.'">
	                <tr>
		          <td class="title" colspan="2"><img src="images/cup/icons/support.png" width="16" height="16"> Legend <a href="#lg" name="lg" class="show_hide_lg"><img src="images/cup/icons/arrow_up_down.gif" align="right"></a></td>
	                </tr>
		      </table>';

$tb_legend .= '<div class="slidingDiv_lg"> 
        <table width="100%" cellspacing="'.$cellspacing.'" cellpadding="'.$cellpadding.'" bgcolor="'.$border.'">
          <tr>
           <td bgcolor="'.$bg1.'" align="center"><img src="images/cup/icons/ratio.png"></td>
           <td bgcolor="'.$bg1.'">Average ranking by other participants or ratio.</td>
         </tr>
          <tr>
           <td bgcolor="'.$bg1.'" align="center"><img src="images/cup/icons/credits.png"></td>
           <td bgcolor="'.$bg1.'">Credits gained, see "Credibility & Inactivity" above.</td>
         </tr>
          <tr>
           <td bgcolor="'.$bg1.'" align="center"><img width="16" height="16" src="images/cup/icons/rank_up.gif"></td>
           <td bgcolor="'.$bg1.'">Won last match, onclick to view user-dashboard.</td>
         </tr>
          <tr>
           <td bgcolor="'.$bg1.'" align="center"><img width="16" height="16" src="images/cup/icons/rank_down.gif"></td>
           <td bgcolor="'.$bg1.'">Lost last match, onclick to view user-dashboard.</td>
         </tr>
          <tr>
           <td bgcolor="'.$bg1.'" align="center"><img width="16" height="16" src="images/cup/icons/refresh.png"></td>
           <td bgcolor="'.$bg1.'">Draw last match, onclick to view user-dashboard.</td>
         </tr>
          <tr>
           <td bgcolor="'.$bg1.'" align="center"><img width="16" height="16" src="images/cup/icons/warning.png"></td>
           <td bgcolor="'.$bg1.'">Warning sign indicating upcoming action.</td>
         </tr>
          <tr>
           <td bgcolor="'.$bg1.'" align="center"><img width="16" height="16" src="images/cup/icons/na.png"></td>
           <td bgcolor="'.$bg1.'">Registered participant with no recent actions.</td>
         </tr>
          <tr>
           <td bgcolor="'.$bg1.'" align="center"><img width="16" height="16" src="images/cup/icons/na.png"><img width="16" height="16" src="images/cup/icons/na.png"></td>
           <td bgcolor="'.$bg1.'">Meets the average ranking by other participants.</td>
         </tr>
          <tr>
           <td bgcolor="'.$bg1.'" align="center"><img src="images/cup/icons/na.png" width="16" height="16"><img id="myImage" src="images/cup/icons/na.png" width="16" height="16"></td>
           <td bgcolor="'.$bg1.'">Just below or above the average ranking by other participants.</td>
         </tr>
          <tr>
           <td bgcolor="'.$bg1.'" align="center"><img src="images/cup/icons/nok_32.png" width="16" height="16"><img src="images/cup/icons/nok_32.png" width="16" height="16"></td>
           <td bgcolor="'.$bg1.'">Bad rating for the average ranking by other participants.</td>
         </tr>
          <tr>
           <td bgcolor="'.$bg1.'" align="center"><img src="images/cup/icons/nok_32.png" width="16" height="16"><img id="myImage" src="images/cup/icons/nok_32.png" width="16" height="16"</td>
           <td bgcolor="'.$bg1.'">Not good rating for the average ranking by other participants.</td>
         </tr>
          <tr>
           <td bgcolor="'.$bg1.'" align="center"><img src="images/cup/icons/ok_32.png" width="16" height="16"><img id="myImage" src="images/cup/icons/ok_32.png" width="16" height="16"></td>
           <td bgcolor="'.$bg1.'">Good rating for the average ranking by other participants.</td>
         </tr>
          <tr>
           <td bgcolor="'.$bg1.'" align="center"><img src="images/cup/icons/ok_32.png" width="16" height="16"><img src="images/cup/icons/ok_32.png" width="16" height="16"></td>
           <td bgcolor="'.$bg1.'">Very good rating for the average ranking by other participants.</td>
         </tr>
          <tr>
           <td bgcolor="'.$bg1.'" align="center"><img src="images/cup/icons/challenge.gif" width="20" height="20"></td>
           <td bgcolor="'.$bg1.'">Challenge system for scheduled match initiation.</td>
         </tr>
          <tr>
           <td bgcolor="'.$bg1.'" align="center"><img src="images/cup/icons/report.png" width="20" height="20"></td>
           <td bgcolor="'.$bg1.'">Open-Play system for direct match reporting.</td>
         </tr>
       </table>
       </div>';
       
  $c_admins = safe_query("SELECT * FROM ".PREFIX."cup_admins WHERE ladID='$ladID'");
  $admin_rows = mysql_num_rows($c_admins);
  
  if($admin_rows) {
   
     $cups_s_admins = '
  
        <tr>
		<td bgcolor="'.$bg1.'"><img src="images/cup/icons/admin.png"> <strong>Admins</strong></td>
		<td bgcolor="'.$bg1.'"><a href="?site=ladders&action=admins&ID='.$ladID.'">'.$admin_rows.' Admin'.($admin_rows==1 ? '' : 's').'</a></td>
	</tr>';
  }	
   
 
		eval ("\$inctemp = \"".gettemplate("cup_details")."\";");
		echo $inctemp;		
       		

}elseif($_GET['action'] == 'admins'){
		$cupID = mysql_escape_string($_GET['ID']);
		
		$admin_sql=safe_query("SELECT * FROM ".PREFIX."cup_admins WHERE ladID='$cupID'");
		if(!mysql_num_rows($admin_sql))
			echo '<br /><br /><center><b>There were no admins entered!</b></center><br /><br />Please try again later!<br /><br />';
		else{
			while($dv=mysql_fetch_array($admin_sql)) {
			
				//Variablen
				$avatar = '<img src="images/avatars/'.getavatar($dv[userID]).'">';
				$nickname = '<a href="?site=profile&id='.$dv[userID].'">'.getnickname($dv[userID]).'</a>';
				
				$firstname = getfirstname($dv[userID]);
				if(empty($firstname))
				$firstname = 'n/a';
				
				$lastname = getlastname($dv[userID]);
				if(empty($lastname))
				$lastname = 'n/a';
				
				$res = mysql_query("SELECT birthday, DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW()) - TO_DAYS(birthday)), '%y') 'age' FROM ".PREFIX."user WHERE userID = '$dv[userID]'");
				$cur = mysql_fetch_array($res);
				$birthday= $cur['age'];
				if(empty($birthday))
				$birthday = 'n/a';
			
				$function = "Cup-Admin";
	
                                $id=$dv[userID];
                                include ("livecontact.php");

				eval ("\$cup_admins = \"".gettemplate("cup_admins")."\";");
				echo $cup_admins;
			}
		}
		echo $inctemp;
	 }
 }
	 else
	      {

//show all ladders
 
  eval ("\$inctemp = \"".gettemplate("cups_head")."\";");
  echo $inctemp; echo base64_decode('');
  
    if(isset($_GET['platID'])) {
       echo 'Ladders for <b>'.getplatname($_GET['platID']).'</b> - <a href="?site=ladders"><b>All Ladders</b></a><br>';
       $if_platform = 'WHERE platID='.$_GET['platID'].'';
    }else{
    
               $plat_ids = '<option selected>-- Select Platform --</option>';    
    
       $platIDs = safe_query("SELECT ID FROM ".PREFIX."cup_platforms");
           while($pID = mysql_fetch_array($platIDs)) {
               $plat_ids.='<option value="'.$pID['ID'].'">'.getplatname($pID['ID']).'</option>';
           }
    
               echo 'Show ladders for platform: <select name="platID" onChange="MM_confirm(\'Show ladders for selected platform?\', \'?site=ladders&platID=\'+this.value)">'.$plat_ids.'</select><br>';
       
    }

	  $ladders = safe_query("SELECT * FROM ".PREFIX."cup_ladders $if_platform ORDER BY ID DESC");
	    if(!mysql_num_rows($ladders)) echo '<div style="margin: 5px; padding: 6px; border: 4px solid #D6B4B4;"><img src="images/cup/error.png" width="16" height="16"> No ladders for this platform...</div>';
	      while($ds=mysql_fetch_array($ladders)) {
	      
		$fa=mysql_fetch_array(safe_query("SELECT * FROM ".PREFIX."cup_agents WHERE ladID='".$ds['ID']."' && cupID='0'"));
		$cm=mysql_fetch_array(safe_query("SELECT * FROM ".PREFIX."cup_matches WHERE ladID='".$ds['ID']."' && cupID='0'"));
	      
		if(is_array($fa)) 
		   $free_agents = '<a href="?site=freeagents&action=view&laddID='.$ds['ID'].'"><img src="images/cup/icons/freeagents.png" align="right" width="16" height="16"></a>';
		
		if(is_array($cm)) 
		   $matches_exist = '<a href="?site=matches&action=viewmatches&laddID='.$ds['ID'].'"><img src="images/cup/icons/add_result.gif" align="right" width="16" height="16"></a>';
	      
		    if($ds['gs_start'] || $ds['gs_end'])
		       $group_stages = '<a href="?site=groups&laddID='.$ds['ID'].'"><img src="images/cup/icons/groups.png" align="right"></a>';
		    else
		       $group_stages = '';
				
				
	    	$game='<img src="images/games/'.$ds[game].'.gif" width="20" height="20" border="0">';
	    	$name = '<a href="?site=ladders&ID='.$ds['ID'].'">'.$ds['name'].'</a>';
	    	$status = $ds['status'];
	    	$checkintime=$ds['start']-($ds['checkin']*60);
		    if($status == 1){
		    	if(time() >= $checkintime) {
		    		$status = 'Signup phase (Check-In)';
				$signup = '<img src="images/cup/icons/go_sel.gif"> <a href="?site=quicknavi&type=ladders&cup='.getalphaladname($ds['ID']).'">Signup</a>';
		    	}
			else{
		    		$status = 'Signup phase';
				$signup = '<img src="images/cup/icons/go_sel.gif"> <a href="?site=quicknavi&type=ladders&cup='.getalphaladname($ds['ID']).'">Signup</a>';
			}
		    }
		    elseif($status == 2) {
		     	$status = 'Started';
			$signup = '<img src="images/cup/icons/go.png"> <s>Signup</s';
		    }
		    elseif($status == 3) {
		    	$status = 'Closed';
			$signup = '<img src="images/cup/icons/go.png"> <s>Signup</s>';
		    }
		
		    $typ = $ds['type'];
		    $matches = '<a href="?site=matches&action=viewmatches&laddID='.$ds['ID'].'"><img src="images/cup/icons/add_result.gif" border="0"></a>';
		    $detail = '<a href="?site=ladders&ID='.$ds['ID'].'"><img src="images/icons/foldericons/folder.gif" width="14" height="15" border="0"></a>';

// RATIO (V4.1.6)

  if(!$ds['ratio_low'] || !$ds['ratio_high']) {
   $ratio_level = $none;
   
  }else{
  
   if($skill_type) {
      $low_t = '<img src="images/cup/icons/skill_low.gif">';
      $med_t = '<img src="images/cup/icons/skill_medium.gif">';
      $high_t = '<img src="images/cup/icons/skill_high.gif">';
   }else{
      $low_t = $low;
      $med_t = $med;
      $high_t = $high;
   }
    
    if($ratio=='1') {
      if($ds['ratio_low'] || $ds['ratio_high']) {
		
            if($ds['ratio_low'] >= $h1 && $ds['ratio_high'] <= $l1) 
               $ratio_level = $low_t;        
        elseif($ds['ratio_low'] >= $h2 && $ds['ratio_high'] <= $l2)
               $ratio_level = $med_t;
        elseif($ds['ratio_low'] >= $h3 && $ds['ratio_high'] <= $l3)
               $ratio_level = $lowmed;      
        elseif($ds['ratio_low'] >= $h4 && $ds['ratio_high'] <= $l4)
               $ratio_level = $high_t; 
        elseif($ds['ratio_low'] >= $h5 && $ds['ratio_high'] <= $l5) 
               $ratio_level = $medhigh;  
        elseif($ds['ratio_low'] >= $h6 && $ds['ratio_high'] <= $l6)  
               $ratio_level = $high1; 
        elseif($ds['ratio_low'] >= $h7 && $ds['ratio_high'] <= $l7)  
               $ratio_level = $high2; 
        else
           $ratio_level = '<br>Ratio: '.$ds['ratio_low'].'%-'.$ds['ratio_high'].'%'; 
      }
    }else{
      if($ds['ratio_low'] || $ds['ratio_high']) { 
           $ratio_level = '<br>Ratio: '.$ds['ratio_low'].'%-'.$ds['ratio_high'].'%'; 
        }else $ratio_level = $none; 
      }
  }  

//echo template	      
	      
		    eval ("\$inctemp = \"".gettemplate("cups_content")."\";");
		    echo $inctemp; echo base64_decode('');

	      
	      }
	  echo '</table>';
     }
   echo ($cpr ? ca_copyr() : die());
}else echo 'All ladders for <strong>'.getplatname($_GET['ID'],$type=1).'</strong> has been disabled.';

?>