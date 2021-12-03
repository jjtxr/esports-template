<link href="cup.css" rel="stylesheet" type="text/css">

<?php

$_language->read_module('cupactions');

include ("config.php");

$bg1=BG_1;
$bg2=BG_1;
$bg3=BG_1;
$bg4=BG_1;
$cpr=ca_copyr();

match_query_type();
!$cpr || !ca_copyr() ? die() : '';

 $league_type = isset($_GET['cupID']) ?  "cupID" : "ladID";
 $match_link = matchlink($_GET['matchID'],$ac=0,$tg=0,$redirect=1);

 $tn=mysql_fetch_array(safe_query("SELECT * FROM ".PREFIX."cup_matches WHERE matchID='".$_GET['matchID']."'"));
 $t_name=($tn['ladID'] ? "laddID" : "cupID");

  if(isset($_GET['cupID']))
  { 
     getcuptimezone();
  
     $cupID = $_GET['cupID'];
     $typename = 'cupID';
     $typename2 = 'cupID';
     $typename3 = 'is1on1';
     $typename4 = 'cup';     
     
     include ("title_cup.php");
		if($typename3($cupID)) $participants = 'Players';		
		else $participants = 'Teams';
  }
  elseif(isset($_GET['groupID']))
  {
     $typename2 = 'groupID';
     $cupID = $_GET['groupID'];
  }
  else
  {
     getladtimezone();
  
     $cupID = $_GET['laddID'];
     $typename2 = 'laddID';
     $typename = 'ladID';
     $typename3 = 'ladderis1on1';
     $typename4 = 'ladder';
  }
  
    if(isset($_GET['type']) && $_GET['type']=='gs') {
       $name1 = "matchno = '$cupID'";
    }
    else{
       $name1 = "$typename = '$cupID'";
    }
       
    if(isset($_GET['type']) && $_GET['type']=='group') {
    
       $name2 = '&type=group';
       $name3 = "matchno = '$cupID'";
       $name4 = '| <a href="?site=groups&'.$t_name.'='.$cupID.'">Group Stages</a>';
    }
    else
       $name3 = "$typename = '$cupID'";
       

if(isset($_GET['action']) && $_GET['action'] == 'mediarequest'){

if(ismatchparticipant($userID,$_GET['matchID'],$all=1)) {

	$clanID = isset($_GET['clanID']) ? $_GET['clanID'] : 0;
	$matchID = $_GET['matchID'];	
	$mm_r_cupID = getleagueID($matchID);
	$mm_typename3 = cuptype($matchID);
	
	if(isset($_POST['post'])){
		if($mm_typename3($mm_r_cupID)){
			if($loggedin && mysql_num_rows(safe_query("SELECT * FROM ".PREFIX."cup_matches WHERE matchID = '$matchID' && (clan1 = '$userID' OR clan2 = '$userID')"))){
				safe_query("INSERT INTO ".PREFIX."cup_requests SET matchID = '".$matchID."', userID = '".$userID."', reason = '".$_POST['reason']."', time = '".time()."'");
				
				$ergebnis=safe_query("SELECT matchno FROM ".PREFIX."cup_matches WHERE matchID = '$matchID'");
				$dd=mysql_fetch_array($ergebnis);
				
				redirect($match_link, $_language->module['matchmedia_requested'].'<br />'.$_language->module['redirect'], 3);
			}
		}else{
			if(isleader($userID, $clanID))
				safe_query("INSERT INTO ".PREFIX."cup_requests SET matchID = '".$matchID."', userID = '".$userID."', reason = '".$_POST['reason']."', time = '".time()."'");
			
			$ergebnis=safe_query("SELECT matchno FROM ".PREFIX."cup_matches WHERE matchID = '$matchID'");
			$dd=mysql_fetch_array($ergebnis);
			
			redirect($match_link, $_language->module['matchmedia_requested'].'<br />'.$_language->module['redirect'], 3);
		}
	}else{ 
		eval ("\$cupactions_mediarequest = \"".gettemplate("cupactions_mediarequest")."\";");
		echo $cupactions_mediarequest;	
    }
}	
// Matchdetails 
}elseif(isset($_GET['action']) && $_GET['action'] == 'match_edit'){
	$clanID = isset($_GET['clanID']) ? $_GET['clanID'] : 0;
	$matchID = $_GET['matchID'];
	
	if(isset($_POST['submit'])){
		if($typename3($cupID)){
			if($loggedin && mysql_num_rows(safe_query("SELECT matchID FROM ".PREFIX."cup_matches WHERE matchID = '$matchID' && (clan1 = '$userID' OR clan2 = '$userID')"))){
				$clan_return = safe_query("SELECT matchID FROM ".PREFIX."cup_matches WHERE matchID = '$matchID' && clan1 = '$userID'");
				$report_clan = mysql_num_rows($clan_return) ? '1' : '2';
				
				safe_query("UPDATE ".PREFIX."cup_matches SET report_team".$report_clan."='".mysql_escape_string($_POST['report'])."', server='".$_POST['server']."', hltv='".$_POST['hltv']."' WHERE matchID = '$matchID' && clan".$report_clan." = '$userID'");

				$ergebnis = safe_query("SELECT matchno FROM ".PREFIX."cup_matches WHERE matchID = '$matchID' && clan".$report_clan."='$userID'");
				$dd = mysql_fetch_array($ergebnis);
				
				redirect($match_link, $_language->module['matchdetails_edited'].'<br />'.$_language->module['redirect'], 3);
			}
		}else{
			if(isleader($userID, $clanID)){
				$clan_return = safe_query("SELECT matchID FROM ".PREFIX."cup_matches WHERE matchID = '$matchID' && clan1 = '$clanID'");
				$report_clan = mysql_num_rows($clan_return) ? '1' : '2';
				
				safe_query("UPDATE ".PREFIX."cup_matches SET report_team".$report_clan." = '".mysql_escape_string($_POST['report'])."', server = '".$_POST['server']."', hltv='".$_POST['hltv']."' WHERE matchID = '$matchID' && clan".$report_clan." = '$clanID'");
			}

			$ergebnis = safe_query("SELECT matchno FROM ".PREFIX."cup_matches WHERE matchID = '$matchID' && clan".$report_clan."='$clanID'");
			$dd = mysql_fetch_array($ergebnis);
			
			redirect($match_link, $_language->module['matchdetails_edited'].'<br />'.$_language->module['redirect'], 3);
		}
	}else{
		if($typename3($cupID)){
			if($loggedin && mysql_num_rows(safe_query("SELECT matchID FROM ".PREFIX."cup_matches WHERE matchID = '$matchID' && (clan1 = '$userID' OR clan2 = '$userID')"))){
				$clan_return = safe_query("SELECT matchID FROM ".PREFIX."cup_matches WHERE $name1 AND matchID = '$matchID' && clan1 = '$userID'");
				$report_clan = mysql_num_rows($clan_return) ? '1' : '2';
				$dd = mysql_fetch_array(safe_query("SELECT report_team".$report_clan." as thereport, server, hltv FROM ".PREFIX."cup_matches WHERE matchID = '$matchID' && clan".$report_clan." = '$userID'"));
				$report = $dd['thereport'];
				$server = $dd['server'];
				$hltv = $dd['hltv'];
				
				$bg1 = BG_1;
				$bg2 = BG_2;
				
				eval ("\$cupactions_matchedit = \"".gettemplate("cupactions_matchedit")."\";");
				echo $cupactions_matchedit;
			}
		}else{
			if(isleader($userID, $clanID)){
				$clan_return = safe_query("SELECT matchID FROM ".PREFIX."cup_matches WHERE $name1 AND matchID = '$matchID' && clan1 = '$clanID'");
				$report_clan = mysql_num_rows($clan_return) ? '1' : '2';
				$dd = mysql_fetch_array(safe_query("SELECT report_team".$report_clan." as thereport, server, hltv FROM ".PREFIX."cup_matches WHERE matchID = '$matchID' && clan".$report_clan." = '$clanID'"));
				$report = $dd['thereport'];
				$server = $dd['server'];
				$hltv = $dd['hltv'];
				
				$bg1 = BG_1;
				$bg2 = BG_2;
				
				eval ("\$cupactions_matchedit = \"".gettemplate("cupactions_matchedit")."\";");
				echo $cupactions_matchedit;
			}
		}
	}

//Comments Accessibility	
}elseif(isset($_GET['action']) && $_GET['action'] == 'comments'){

$matchID = $_GET['matchID'];
$clanID = $_GET['clanID'];

$getaccess = safe_query("SELECT comment FROM ".PREFIX."cup_matches WHERE matchID='$matchID'");
  $dr=mysql_fetch_array($getaccess);	
  
    switch($dr['comment']) {
      case 0: $comment = 'Comments disabled for this match';
      case 1: $comment = 'Comments only for logged in users';
      case 2: $comment = 'Comments opened for all';
   }

		
  $commentsaccess = '<option selected value="'.$dr['comment'].'">-- Select Access --</option>
			         <option value="0">Disable Comments</option>
			         <option value="1">Users Only</option>
			         <option value="2">Users & Guests</option>'; 
			         
	         
     if(ismatchparticipant($userID,$matchID,$all=0)) { 
     
		echo '
		  <tr bgcolor="'.$bg1.'">
		    <td align="right" bgcolor="'.$bg1.'">Comments Access:</td>
		    <td bgcolor="'.$bg2.'"><select name="comment" onChange="MM_confirm(\'Confirm?\', \'?site=cupactions&action=comments&clanID='.$clanID.'&matchID='.$matchID.'&do=change&commentsaccess=\'+this.value)">'.$commentsaccess.'</select> '.$comment.'</td>
		</tr>'; 	

    if(isset($_GET['do']) && $_GET['do'] == 'change'){
		safe_query("UPDATE ".PREFIX."cup_matches SET comment='".$_GET['commentsaccess']."' WHERE matchID='$matchID'");	
		redirect($match_link, '<center><b>Match comments accessibility successfully changed<img src="images/cup/period'.$period_dot.'_ani.gif"></center>', 1);	}	
	 }	
  
// Screenshots hochladen
}elseif(isset($_GET['action']) && $_GET['action'] == 'screenupload'){
	$clanID = isset($_GET['clanID']) ? $_GET['clanID'] : 0;
	$matchID = $_GET['matchID'];
	
if(ismatchparticipant($userID,$_GET['matchID'],$all=1)) {

	$clanID = isset($_GET['clanID']) ? $_GET['clanID'] : 0;
	$matchID = $_GET['matchID'];	
	$su_r_cupID = getleagueID($matchID);
	$su_typename3 = cuptype($matchID);
	
	if(isset($_POST['submit'])){
		if($su_typename3($su_r_cupID)){
			if($loggedin && mysql_num_rows(safe_query("SELECT matchID FROM ".PREFIX."cup_matches WHERE matchID = '$matchID' && (clan1 = '$userID' || clan2 = '$userID')"))){
				$screen = $_FILES['screenshots'];
				$anz = count($screen['name']);		
				
				for ($i = 0; $i <= $anz+1; $i++) {
					if(!empty($screen['name'][$i])) {
						$filepath = './images/cup-screens/';
						move_uploaded_file($screen['tmp_name'][$i], $filepath.$screen['name'][$i]);
						@chmod($filepath.$screen['name'][$i], 0755);
						$file = $i.'_'.time().'.jpg';
						rename($filepath.$screen['name'][$i], $filepath.$file);
						$ergebnis = safe_query("SELECT screens FROM ".PREFIX."cup_matches WHERE matchID = '$matchID' && (clan1 = '$userID' || clan2 = '$userID')");
						$ds=mysql_fetch_array($ergebnis);
						$screens = explode("|", $ds['screens']);
						$screens[] = $file;
						$screens_string = implode("|", $screens);
						safe_query("UPDATE ".PREFIX."cup_matches SET screens = '$screens_string' WHERE matchID = '$matchID' && '$su_r_cupID' && (clan1 = '$userID' || clan2 = '$userID')");
						// Name eintragen
						$ergebnis2 = safe_query("SELECT screen_name FROM ".PREFIX."cup_matches WHERE matchID = '$matchID' && (clan1 = '$userID' || clan2 = '$userID')");
						$ds= mysql_fetch_array($ergebnis2);
						$screens_name_ar = explode("||", $ds['screen_name']);
						$screens_name_ar[] = $_POST['screen_name'][$i];
						$screens_name = implode("||", $screens_name_ar);
						safe_query("UPDATE ".PREFIX."cup_matches SET screen_name ='$screens_name' WHERE matchID = '$matchID' && (clan1 = '$userID' || clan2 = '$userID')");
						// Upper eintragen
						$ergebnis3 = safe_query("SELECT screen_upper FROM ".PREFIX."cup_matches WHERE matchID = '$matchID' && (clan1 = '$userID' || clan2 = '$userID')");
						$ds = mysql_fetch_array($ergebnis3);
						$screens_upper_ar = explode("|", $ds['screen_upper']);
						$screens_upper_ar[] = $userID;
						$screens_upper = implode("|", $screens_upper_ar);
						safe_query("UPDATE ".PREFIX."cup_matches SET screen_upper = '$screens_upper' WHERE matchID = '$matchID' && (clan1 = '$userID' || clan2 = '$userID')");
						unset($filepath, $file, $ergebnis, $ds, $screens, $screens_upper_ar, $screens_upper, $screens_name_ar, $screens_name);
	  				}  
				}
				$ergebnis = safe_query("SELECT matchno FROM ".PREFIX."cup_matches WHERE matchID = '$matchID' && (clan1 = '$userID' || clan2 = '$userID')");
				$dd = mysql_fetch_array($ergebnis);
				redirect($match_link, $_language->module['screen_upload'].'.<br />'.$_language->module['redirect'], 3);
			}
		}else{
			$screen = $_FILES['screenshots'];
			$anz = count($screen['name']);
			
			for ($i = 0; $i <= $anz+1; $i++) {
				if(!empty($screen['name'][$i])) {
					$filepath = './images/cup-screens/';
					move_uploaded_file($screen['tmp_name'][$i], $filepath.$screen['name'][$i]);
					@chmod($filepath.$screen['name'][$i], 0755);
					$file = $i.'_'.time().'.jpg';
					rename($filepath.$screen['name'][$i], $filepath.$file);
					$ergebnis = safe_query("SELECT screens FROM ".PREFIX."cup_matches WHERE matchID = '$matchID' && (clan1='$clanID' || clan2='$clanID')");
					$ds = mysql_fetch_array($ergebnis);
					$screens = explode("|", $ds['screens']);
					$screens[] = $file;
					$screens_string = implode("|", $screens);
					safe_query("UPDATE ".PREFIX."cup_matches SET screens='$screens_string' WHERE matchID = '$matchID' && (clan1='$clanID' || clan2='$clanID')");
					// Name eintragen
					$ergebnis2 = safe_query("SELECT screen_name FROM ".PREFIX."cup_matches WHERE matchID = '$matchID' && (clan1='$clanID' || clan2='$clanID')");
					$ds = mysql_fetch_array($ergebnis2);
					$screens_name_ar = explode("||", $ds['screen_name']);
					$screens_name_ar[] = $_POST['screen_name'][$i];
					$screens_name = implode("||", $screens_name_ar);
					safe_query("UPDATE ".PREFIX."cup_matches SET screen_name ='$screens_name' WHERE matchID = '$matchID' && (clan1='$clanID' || clan2='$clanID')");
					// Upper eintragen
					$ergebnis3 = safe_query("SELECT screen_upper FROM ".PREFIX."cup_matches WHERE matchID = '$matchID' && (clan1='$clanID' || clan2='$clanID')");
					$ds = mysql_fetch_array($ergebnis3);
					$screens_upper_ar = explode("|", $ds['screen_upper']);
					$screens_upper_ar[] = $userID;
					$screens_upper = implode("|", $screens_upper_ar);
					safe_query("UPDATE ".PREFIX."cup_matches SET screen_upper ='$screens_upper' WHERE matchID = '$matchID' && (clan1='$clanID' || clan2='$clanID')");
					unset($filepath, $file, $ergebnis, $ds, $screens, $screens_upper_ar, $screens_upper, $screens_name_ar, $screens_name);
				}  
			}
			$ergebnis = safe_query("SELECT matchno FROM ".PREFIX."cup_matches WHERE matchID = '$matchID' && (clan1='$clanID' || clan2='$clanID')");
			$dd = mysql_fetch_array($ergebnis);
			redirect($match_link, $_language->module['screen_upload'].'.<br />'.$_language->module['redirect'], 3);
		}

	}else{
		if(memin($userID,$clanID) OR ($su_typename3($su_r_cupID) && ($loggedin && mysql_num_rows(safe_query("SELECT * FROM ".PREFIX."cup_matches WHERE matchID = '$matchID' && (clan1 = '$userID' || clan2 = '$userID')"))))){
			$bg1 = BG_1;
			$bg2 = BG_2;
			eval ("\$cupactions_scrupload = \"".gettemplate("cupactions_scrupload")."\";");
			echo $cupactions_scrupload;
		}else echo '<div style="margin: 5px; padding: 6px; border: 4px solid #D6B4B4; text-align: center;"><b>You must be a member or higher ranked in this team to upload screenshots!</b> <img src="images/cup/error.png" width="16" height="16"></div>';
    } 
}   
// Ladder Alteration
}elseif(isset($_GET['action']) && $_GET['action'] == 'updatestandings'){	

		$matchID = $_GET['matchID'];
		$laddID = $_GET['laddID'];
		$clanID = isset($_GET['clanID']) ? $_GET['clanID'] : 0;
		$partID = isset($_GET['clanID']) ? $_GET['clanID'] : $userID;
		$db=mysql_fetch_array(safe_query("SELECT * FROM ".PREFIX."cup_matches WHERE matchID='$matchID'"));
		
		 if($matchID && $partID && (ismatchparticipant($userID,$matchID,$all=1) || iscupadmin($userID)) && !$db['si']) {

				$rk1=mysql_fetch_array(safe_query("SELECT * FROM ".PREFIX."cup_clans WHERE ladID='".$db['ladID']."' && clanID='".$db['clan1']."'"));	  
				$rk2=mysql_fetch_array(safe_query("SELECT * FROM ".PREFIX."cup_clans WHERE ladID='".$db['ladID']."' && clanID='".$db['clan2']."'"));
                        
                  $t1_totalp = $rk1['credit']+$rk1['xp'];
                  $t2_totalp = $rk2['credit']+$rk2['xp'];                        
                  $t1_wmc = $rk1['xp']+$rk1['won'];     
                  $t2_wmc = $rk2['xp']+$rk2['won'];                                 
                       
                       if($db['score1']>$db['score2']) {               
                         safe_query("UPDATE ".PREFIX."cup_clans SET tp = '$t1_totalp', wc = '$t1_wmc' WHERE clanID = '".$db['clan1']."' && ladID = '".$db['ladID']."'");                                                               
                         safe_query("UPDATE ".PREFIX."cup_clans SET tp = '$t2_totalp', wc = '$t2_wmc' WHERE clanID = '".$db['clan2']."' && ladID = '".$db['ladID']."'");
                   
                       }elseif($db['score1']<$db['score2']) {  
                         safe_query("UPDATE ".PREFIX."cup_clans SET tp = '$t1_totalp', wc = '$t1_wmc' WHERE clanID = '".$db['clan1']."' && ladID = '".$db['ladID']."'");
                         safe_query("UPDATE ".PREFIX."cup_clans SET tp = '$t2_totalp', wc = '$t2_wmc' WHERE clanID = '".$db['clan2']."' && ladID = '".$db['ladID']."'");
                       }else{
                         safe_query("UPDATE ".PREFIX."cup_clans SET tp = '$t1_totalp', wc = '$t1_wmc' WHERE clanID = '".$db['clan1']."' && ladID = '".$db['ladID']."'");
                         safe_query("UPDATE ".PREFIX."cup_clans SET tp = '$t2_totalp', wc = '$t2_wmc' WHERE clanID = '".$db['clan2']."' && ladID = '".$db['ladID']."'"); 
                       }   
                       
                        if(!$db['matchno'])
                            $match_t = 'matchID='.$db['matchID'];
                        else
                            $match_t = 'match='.$db['matchno'];
                            
                        $query = safe_query("SELECT * FROM ".PREFIX."cup_tickets WHERE matchID='".$db['matchID']."'");
                        $ti=mysql_fetch_array($query);
                        
                        if(mysql_num_rows($query)) $this_ticket = '<input type="button" class="button" onClick="MM_goToURL(\'parent\',\'admin/admincenter.php?site=cuptickets&action=view_ticket&tickID='.$ti['ticketID'].'\');return document.MM_returnValue" value="This Ticket">';
                            
                        if(isset($_GET['type'])=="protest" && iscupadmin($userID))
                           echo '<center><b>Standings have been updated!</b><br><input type="button" class="button" onClick="MM_goToURL(\'parent\',\'admin/admincenter.php?site=cuptickets\');return document.MM_returnValue" value="All Tickets"> '.$this_ticket.' <input type="button" class="button" onClick="javascript:self.close()" value="Close Window"></center>';   
						else
						   echo '<center><br />'.$_language->module['score_confirmed'].'<br /><br/><br /><a href="?site=cup_matches&'.$match_t.'&'.laddID.'='.$laddID.'">Match-Details</a>  | <a href="?site=standings&ladderID='.$laddID.'">View Standings</a>';  
						
						safe_query("UPDATE ".PREFIX."cup_matches SET si = '1' WHERE ladID='".$db['ladID']."'");                  
		 }   
	
// Score Confirmation
}elseif(isset($_GET['action']) && $_GET['action'] == 'confirmscore'){

      $matchID = $_GET['matchID'];
      $clanID = isset($_GET['clanID']) ? $_GET['clanID'] : 0;	
		
      if(isset($_GET['laddID']) && participantID($userID,$_GET['laddID'])!=$clanID && !$_POST['submit']) 
      {
         die('access denied');
      }
		
	  if(isset($_GET['cupID'])) {
		 eval ("\$title_cup = \"".gettemplate("title_cup")."\";");
		 echo $title_cup; 
	  }
	  elseif(isset($_GET['laddID'])){
		 include("title_ladder.php");
		 echo '<br />';
	  }
	  
	  $ct = mysql_fetch_array(safe_query("SELECT * FROM ".PREFIX."cup_matches WHERE matchID='$matchID'"));
	  
	  if(empty($matchID))
	  {
	     die('No match.');
	  }
	  
	  if($_GET['cupID'] && $ct['type']=='ladder')
	  {
	     redirect('?site=cupactions&action=confirmscore&matchID='.$matchID.($clanID ? '&clanID='.$clanID : '').'&laddID='.$_GET['cupID'].($_GET['one']==1 ? '&one=1' : ''), '', 0);
	  }
	  elseif($_GET['laddID'] && $ct['type']=='cup')
	  {
	     redirect('?site=cupactions&action=confirmscore&matchID='.$matchID.($clanID ? '&clanID='.$clanID : '').'&cupID='.$_GET['laddID'].($_GET['one']==1 ? '&one=1' : ''), '', 0);
	  }
			
	if(isset($_POST['submit'])){
		if($clanID == 'onecup'){ 
			$ergebnis = safe_query("SELECT * FROM ".PREFIX."cup_matches WHERE matchID = '$matchID' && confirmscore = '0' && inscribed != '$userID' LIMIT 0,1");
			if(mysql_num_rows($ergebnis)){ 
				$db=mysql_fetch_array($ergebnis);
				$clan_return = safe_query("SELECT * FROM ".PREFIX."cup_matches WHERE matchID = '$matchID' && (clan1 = '$userID' || clan2 = '$userID')");
			    $report_clan=mysql_num_rows($clan_return) ? 'team1' : 'team2';
			       
			    while($mm=mysql_fetch_array($clan_return)) {
			    
			    if($mm['clan1']==$userID) { 
				safe_query("UPDATE `".PREFIX."cup_matches` SET `confirmscore` = '1', `confirmed_date` = '".time()."', `report_team1` = '".mysql_escape_string($_POST['report'])."' WHERE matchID = '$matchID'");
				
				}else 
				safe_query("UPDATE `".PREFIX."cup_matches` SET `confirmscore` = '1', `confirmed_date` = '".time()."', `report_team2` = '".mysql_escape_string($_POST['report'])."' WHERE matchID = '$matchID'");
				
		//tournament autoswitch (getnextmatch)	
		
			}if(isset($_GET['cupID']) && $_GET['type']!="group") {	
			
			 $mc=mysql_fetch_array(safe_query("SELECT maxclan FROM ".PREFIX."cups WHERE ID='$cupID'"));
			 
			 $query = safe_query("SELECT matchno FROM ".PREFIX."cup_matches WHERE cupID='$cupID'");
			     while($cm=mysql_fetch_array($query)) {
			         $matchnos[]=$cm['matchno'];			     
			     }
			 
			   if($mc['maxclan']==640) {
			     $initNo1 = "61";
			     $initNo2 = "62";
			     $returnNo = "64";
			   }  
			   
			    $sw1=mysql_fetch_array(safe_query("SELECT * FROM ".PREFIX."cup_matches WHERE matchno='$initNo1'"));
			    
			    if($sw1['clan1']==$userID && $sw1['score1'] < $sw1['score2']) {
			       $to_switch1 = $sw1['clan1'];
			       $returnPlace1 = "clan1";
			    }elseif($sw1['clan2']==$userID && $sw1['score1'] > $sw1['score2']) {
			       $to_switch1 = $sw1['clan2'];
			       $returnPlace1 = "clan2";
			    }elseif($sw1['clan2']==$userID && $sw1['score1'] < $sw1['score2']) {
			       $to_switch1 = $sw1['clan1'];
			       $returnPlace1 = "clan1";
			    }elseif($sw1['clan1']==$userID && $sw1['score1'] > $sw1['score2']) {
			       $to_switch1 = $sw1['clan2'];
			       $returnPlace1 = "clan2";
			    }
			    
			    $sw2=mysql_fetch_array(safe_query("SELECT * FROM ".PREFIX."cup_matches WHERE matchno='$initNo2'"));
			    
			    if($sw2['clan1']==$userID && $sw2['score1'] < $sw2['score2']) {
			       $to_switch = $sw2['clan1'];
			       $returnPlace = "clan1";
			    }elseif($sw2['clan2']==$userID && $sw2['score1'] > $sw2['score2']) {
			       $to_switch = $sw2['clan2'];
			       $returnPlace = "clan2";
			    }elseif($sw2['clan2']==$userID && $sw2['score1'] < $sw2['score2']) {
			       $to_switch = $sw2['clan1'];
			       $returnPlace = "clan1";
			    }elseif($sw2['clan1']==$userID && $sw2['score1'] > $sw2['score2']) {
			       $to_switch = $sw2['clan2'];
			       $returnPlace = "clan2";
			    }
			    
			     $type_cup=(is1on1($cupID) ? "1" : "0");
			   
			     if(in_array($initNo1,$matchnos) && !$sw1[$returnPlace]) {
				  	 if(!mysql_num_rows(safe_query("SELECT * FROM ".PREFIX."cup_matches WHERE cupID='".$cupID."' && matchno='$returnNo'"))) {	
						safe_query("INSERT INTO ".PREFIX."cup_matches (cupID, ladID, matchno, date, ".$returnPlace1.", comment, 1on1) VALUES ('".$cupID."', '0', '$returnNo', '".time()."', '$to_switch1', '2', '$type_cup')");
					 }else
						 safe_query("UPDATE ".PREFIX."cup_matches SET ".$returnPlace1." = '".$to_switch1."' WHERE cupID='".$cupID."' && matchno='$returnNo'");
				    
			    }if(in_array($initNo2,$matchnos) && !$sw2[$returnPlace]) {
					 if(!mysql_num_rows(safe_query("SELECT * FROM ".PREFIX."cup_matches WHERE cupID='".$cupID."' && matchno='$returnNo'"))) { 	
						 safe_query("INSERT INTO ".PREFIX."cup_matches (cupID, ladID, matchno, date, ".$returnPlace.", comment, 1on1) VALUES ('".$cupID."', '0', '$returnNo', '".time()."', '$to_switch', '2', '$type_cup')");
					 }else
						 safe_query("UPDATE ".PREFIX."cup_matches SET ".$returnPlace." = '".$to_switch."' WHERE cupID='".$cupID."' && matchno='$returnNo'");
				    
			      }
			      			      			
		//tournament autoswitch (place winner)
							
				$matchinfo = getnextmatchnr($cupID, $db['matchno']);
				$looserswitch = looserautoswitch($db['matchno'],$cupID,$p);	
				$type_cup=(is1on1($cupID) ? "1" : "0");	
				
				if($matchinfo['winner'])
				   safe_query("UPDATE ".PREFIX."cup_baum SET wb_winner='".($db['score1'] > $db['score2'] ? $db['clan1'] : $db['clan2'])."' WHERE $typename = '".$cupID."'");
				elseif($matchinfo['lb_winner'])
				   safe_query("UPDATE ".PREFIX."cup_baum SET lb_winner = '".($db['score1'] > $db['score2'] ? $db['clan1'] : $db['clan2'])."', third_winner='".($db['score1'] < $db['score2'] ? $db['clan1'] : $db['clan2'])."' WHERE $typename = '".$cupID."'");	
                elseif($matchinfo['third_winner']) 
	               safe_query("UPDATE ".PREFIX."cup_baum SET third_winner = '".($db['score1'] > $db['score2'] ? $db['clan1'] : $db['clan2'])."' WHERE $typename = '".$cupID."'"); 
			
				if($matchinfo['matchno'] && !mysql_num_rows(safe_query("SELECT * FROM ".PREFIX."cup_matches WHERE $typename='".$cupID."' && matchno='".$matchinfo['matchno']."'"))) 
				   safe_query("INSERT INTO ".PREFIX."cup_matches (cupID, ladID, matchno, date, ".$matchinfo['place'].", comment, 1on1) VALUES ('".$cupID."', '0', '".$matchinfo['matchno']."', '".time()."', '".($db['score1'] > $db['score2'] ? $db['clan1'] : $db['clan2'])."', '2', '$type_cup')");
				elseif($matchinfo['matchno'])
				   safe_query("UPDATE ".PREFIX."cup_matches SET ".$matchinfo['place']." = '".($db['score1'] > $db['score2'] ? $db['clan1'] : $db['clan2'])."' WHERE $typename = '".$cupID."' && matchno = '".$matchinfo['matchno']."'");
				    
	    //tournament auto-close
	    
	            if($auto_close_cup && $matchinfo['winner'])
	               safe_query("UPDATE ".PREFIX."cups SET ende = '".time()."' WHERE ID='$cupID'");
				    
		//tournament autoswitch (place looser)
						
				if($looserswitch && !mysql_num_rows(safe_query("SELECT * FROM ".PREFIX."cup_matches WHERE $typename='".$cupID."' && matchno='$looserswitch'"))) 
					safe_query("INSERT INTO ".PREFIX."cup_matches (cupID, ladID, matchno, date, ".$matchinfo['place'].", comment, 1on1) VALUES ('".$cupID."', '0', '$looserswitch', '".time()."', '".($db['score1'] < $db['score2'] ? $db['clan1'] : $db['clan2'])."', '2', '$type_cup')");
			    elseif($looserswitch)
	                safe_query("UPDATE ".PREFIX."cup_matches SET ".$matchinfo['place']." = '".($db['score1'] < $db['score2'] ? $db['clan1'] : $db['clan2'])."' WHERE $typename = '".$cupID."' && matchno = '$looserswitch'");
				     
			    
		//ladder ranking system
		
			}elseif(isset($_GET['laddID'])) {	
			
				  $rank1 = safe_query("SELECT * FROM ".PREFIX."cup_clans WHERE ladID='".$db['ladID']."' && clanID='".$db['clan1']."'");
				  $rk1 = mysql_fetch_array($rank1); 
				  
				  $rank2 = safe_query("SELECT * FROM ".PREFIX."cup_clans WHERE ladID='".$db['ladID']."' && clanID='".$db['clan2']."'");
				  $rk2 = mysql_fetch_array($rank2); 
				  				
				   $rs=mysql_fetch_array(safe_query("SELECT ranksys, mode, d_xp FROM ".PREFIX."cup_ladders WHERE ID='".$db['ladID']."'"));
				   
				   if($rs['ranksys']==1) {
				      $team1_rank = $rk1['credit'];
				      $team2_rank = $rk2['credit'];
				   }elseif($rs['ranksys']==2) {
				      $team1_rank = $rk1['xp'];
				      $team2_rank = $rk2['xp'];
				   }elseif($rs['ranksys']==3) {
				      $team1_rank = $rk1['won'];
				      $team2_rank = $rk2['won'];
				   }elseif($rs['ranksys']==4) {
				      $team1_rank = $rk1['tp'];
				      $team2_rank = $rk2['tp'];
				   }elseif($rs['ranksys']==5) {
				      $team1_rank = $rk1['wc'];
				      $team2_rank = $rk2['wc'];
				   }elseif($rs['ranksys']==6) {
				      $team1_rank = $rk1['streak'];
				      $team2_rank = $rk2['streak'];
				   }	
				  				
                    if($rs['mode']==1) { 
                      $team_ranks = array($team1_rank, $team2_rank);
                      function average_cal($team_ranks){
                        return array_sum($team_ranks)/count($team_ranks) ;
                      }
                        $halfup = +average_cal($team_ranks);
                        
                    }elseif($rs['mode']==2) {
                      if($db['clan1']==$rk1['clanID'] && $team1_rank > $team2_rank) {
                        $team1_rank = $rk2['credit'];
                        $team2_rank = $rk1['credit'];
                      }else{
                        $team1_rank = $rk1['credit'];
                        $team2_rank = $rk2['credit'];
                      }
                    }
                                          
                        $team1_lost = $rk1['credit']-$lostcredit;
                        $team2_lost = $rk2['credit']-$lostcredit;
                        $team1_won = $rk1['credit']+$woncredit;
                        $team2_won = $rk2['credit']+$woncredit;
                        $team1_draw = $rk1['credit']+$drawcredit;
                        $team2_draw = $rk2['credit']+$drawcredit;
                        
                        $rank1 = $rk1['rank_now'];
                        $rank2 = $rk2['rank_now']; 
                        
                        $t1_totalp = $rk1['credit']+$rk1['xp'];
                        $t2_totalp = $rk2['credit']+$rk2['xp'];                        
                        $t1_wmc = $rk1['xp']+$rk1['won'];     
                        $t2_wmc = $rk2['xp']+$rk2['won'];                                             
                                              
                        $t1_addwon = $rk1['won']+1;
                        $t2_addwon = $rk2['won']+1;
                        $t1_addlost = $rk1['lost']+1;
                        $t2_addlost = $rk2['lost']+1;
                        $t1_addtie = $rk1['draw']+1;
                        $t2_addtie = $rk2['draw']+1;                      
                        $t1_addmatch = $rk1['ma']+1;
                        $t2_addmatch = $rk2['ma']+1;
                        
                        if($userID==$db['clan1'])
                           $la1 = ", lastact = '".time()."'";
                           
                        if($userID==$db['clan2'])
                           $la2 = ", lastact = '".time()."'";

                        if($differential)
                        {              
                           $t1_addxp = $db['score1']-$db['score2']+$rk1['xp'];
                           $t2_addxp = $db['score2']-$db['score1']+$rk2['xp'];
                        }
                        else
                        {                        
                           $t1_addxp = $db['score1']+$rk1['xp'];
                           $t2_addxp = $db['score2']+$rk2['xp'];
                        }
                           
                        $c2_xp = (!$rs['d_xp'] ? "xp = '$t2_addxp'," : "");
                        $c1_xp = (!$rs['d_xp'] ? "xp = '$t1_addxp'," : "");                       
                                                                                         
                        if($db['score1']>$db['score2']) {      
                           safe_query("UPDATE ".PREFIX."cup_clans SET ma = '$t1_addmatch', credit = '$team1_won', xp = '$t1_addxp', won = '$t1_addwon', lastpos = '1', rank_then='$rank1' $la1 WHERE clanID = '".$db['clan1']."' && ladID = '".$db['ladID']."'");                                                  
                           safe_query("UPDATE ".PREFIX."cup_clans SET ma = '$t2_addmatch', credit = '$team2_lost', $c2_xp lost = '$t2_addlost', lastpos = '2', rank_then='$rank2' $la2 WHERE clanID = '".$db['clan2']."' && ladID = '".$db['ladID']."'");
                                                                                                                              
                          redirect('?site=cupactions&action=updatestandings&matchID='.$db['matchID'].'&clanID='.$clanID.'&'.$t_name.'='.$db['ladID'].'', '...', 0);
                                                                
                        }elseif($db['score1']<$db['score2']) {  
                           safe_query("UPDATE ".PREFIX."cup_clans SET ma = '$t1_addmatch', credit = '$team1_lost', $c1_xp lost = '$t1_addlost', lastpos = '2', rank_then='$rank1' $la1 WHERE clanID = '".$db['clan1']."' && ladID = '".$db['ladID']."'");                                        
                           safe_query("UPDATE ".PREFIX."cup_clans SET ma = '$t2_addmatch', credit = '$team2_won', xp = '$t2_addxp', won = '$t2_addwon', lastpos = '1', rank_then='$rank2' $la2  WHERE clanID = '".$db['clan2']."' && ladID = '".$db['ladID']."'");
                                                              
                          redirect('?site=cupactions&action=updatestandings&matchID='.$db['matchID'].'&clanID='.$clanID.'&'.$t_name.'='.$db['ladID'].'', '...', 0);
                                                                
                        }else{  
                           safe_query("UPDATE ".PREFIX."cup_clans SET ma = '$t1_addmatch', credit = '$team1_draw', xp = '$t1_addxp', draw = '$t1_addtie', lastpos = '3', rank_then='$rank1' $la1 WHERE clanID = '".$db['clan1']."' && ladID = '".$db['ladID']."'");                                   
                           safe_query("UPDATE ".PREFIX."cup_clans SET ma = '$t2_addmatch', credit = '$team2_draw', xp = '$t2_addxp', draw = '$t2_addtie', lastpos = '3', rank_then='$rank2' $la2 WHERE clanID = '".$db['clan2']."' && ladID = '".$db['ladID']."'");                                                                   
                                                                
                           redirect('?site=cupactions&action=updatestandings&matchID='.$db['matchID'].'&clanID='.$clanID.'&'.$t_name.'='.$db['ladID'].'', '...', 0);
                        }                   
    
                  }	


	        $details_link = '?site=cup_matches&match='.$db['matchno'].'&'.$typename2.'='.$cupID.'';
	
				echo'<center><br /><br /><br /><br /><b>'.$_language->module['score_confirmed'].'<br /><br/><br /><a href='.matchlink($db['matchID'],$ac=0,$tg=0,$redirect=0).'>Match-Details</a> '.$name4.'';
	if(isset($_GET['laddID'])) echo ' | <a href="?site=standings&ladderID='.$_GET['laddID'].'">View Standings</a>';			
			}
		}else{		
			if(isleader($userID,$clanID)){
				$ergebnis = safe_query("SELECT * FROM ".PREFIX."cup_matches WHERE matchID = '$matchID' && confirmscore = '0' && inscribed != '$clanID' LIMIT 0,1");
				if(mysql_num_rows($ergebnis)){
					if(isleader($userID, $clanID)){
						$db=mysql_fetch_array($ergebnis);
						$clan_return = safe_query("SELECT * FROM ".PREFIX."cup_matches WHERE matchID = '$matchID' && (clan1 = '$clanID'  || clan2 = '$clanID')");
				        $report_clan=mysql_num_rows($clan_return) ? 'team1' : 'team2';
				        				        
			            while($mm=mysql_fetch_array($clan_return)) {
			            
			            if(isset($_GET['type']) && $_GET['type'] == 'group')
				           $details_link = '?site=cup_matches&match='.$db['matchID'].'&'.$t_name.'='.$cupID.'&type=gs';
				        elseif(!$db['matchno'])
				           $details_link = '?site=cup_matches&matchID='.$db['matchID'].'&'.$typename2.'='.$cupID.'';
				        else
				           $details_link = '?site=cup_matches&match='.$db['matchno'].'&'.$typename2.'='.$cupID.'';
			    
			            if($mm['clan1']==$clanID) {
						safe_query("UPDATE `".PREFIX."cup_matches` SET `confirmscore` = '1', `confirmed_date` = '".time()."', `report_team1` = '".mysql_escape_string($_POST[report])."' WHERE `".PREFIX."cup_matches`.`matchID` = '$matchID'");
						echo'<center><br /><br /><br /><br /><b>'.$_language->module['score_confirmed'].'<br /><br/><br /><a href='.matchlink($db['matchID'],$ac=0,$tg=0,$redirect=0).'>Match-Details</a> '.$name4.'';
						
                        }else
                        safe_query("UPDATE `".PREFIX."cup_matches` SET `confirmscore` = '1', `confirmed_date` = '".time()."', `report_team2` = '".mysql_escape_string($_POST[report])."' WHERE `".PREFIX."cup_matches`.`matchID` = '$matchID'");
                        echo'<center><br /><br /><br /><br /><b>'.$_language->module['score_confirmed'].'<br /><br/><br /><a href='.matchlink($db['matchID'],$ac=0,$tg=0,$redirect=0).'>Match-Details</a> '.$name4.'';
                        }
                        					
		//tournament autoswitch (getnextmatch)		
				 
			}if(isset($_GET['cupID']) && $_GET['type']!="group") {
			   	
			 $mc=mysql_fetch_array(safe_query("SELECT maxclan FROM ".PREFIX."cups WHERE ID='$cupID'"));
			 
			 $query = safe_query("SELECT matchno FROM ".PREFIX."cup_matches WHERE cupID='$cupID'");
			     while($cm=mysql_fetch_array($query)) {
			         $matchnos[]=$cm['matchno'];			     
			     }
			 
			   if($mc['maxclan']==640) {
			     $initNo1 = "61";
			     $initNo2 = "62";
			     $returnNo = "64";
			   }  
			   
			    $sw1=mysql_fetch_array(safe_query("SELECT * FROM ".PREFIX."cup_matches WHERE matchno='$initNo1'"));
			    
			    if($sw1['clan1']==$userID && $sw1['score1'] < $sw1['score2']) {
			       $to_switch1 = $sw1['clan1'];
			       $returnPlace1 = "clan1";
			    }elseif($sw1['clan2']==$userID && $sw1['score1'] > $sw1['score2']) {
			       $to_switch1 = $sw1['clan2'];
			       $returnPlace1 = "clan2";
			    }elseif($sw1['clan2']==$userID && $sw1['score1'] < $sw1['score2']) {
			       $to_switch1 = $sw1['clan1'];
			       $returnPlace1 = "clan1";
			    }elseif($sw1['clan1']==$userID && $sw1['score1'] > $sw1['score2']) {
			       $to_switch1 = $sw1['clan2'];
			       $returnPlace1 = "clan2";
			    }
			    
			    $sw2=mysql_fetch_array(safe_query("SELECT * FROM ".PREFIX."cup_matches WHERE matchno='$initNo2'"));
			    
			    if($sw2['clan1']==$userID && $sw2['score1'] < $sw2['score2']) {
			       $to_switch = $sw2['clan1'];
			       $returnPlace = "clan1";
			    }elseif($sw2['clan2']==$userID && $sw2['score1'] > $sw2['score2']) {
			       $to_switch = $sw2['clan2'];
			       $returnPlace = "clan2";
			    }elseif($sw2['clan2']==$userID && $sw2['score1'] < $sw2['score2']) {
			       $to_switch = $sw2['clan1'];
			       $returnPlace = "clan1";
			    }elseif($sw2['clan1']==$userID && $sw2['score1'] > $sw2['score2']) {
			       $to_switch = $sw2['clan2'];
			       $returnPlace = "clan2";
			    }
			  		   
			  	   $type_cup=(is1on1($cupID) ? "1" : "0");	   
			  		   
			     if(in_array($initNo1,$matchnos) && !$sw1[$returnPlace]) {
				  	 if(!mysql_num_rows(safe_query("SELECT * FROM ".PREFIX."cup_matches WHERE cupID='".$cupID."' && matchno='$returnNo'"))) { 	
						safe_query("INSERT INTO ".PREFIX."cup_matches (cupID, ladID, matchno, date, ".$returnPlace1.", comment, 1on1) VALUES ('".$cupID."', '0', '$returnNo', '".time()."', '$to_switch1', '2', '$type_cup')");
					 }else
						 safe_query("UPDATE ".PREFIX."cup_matches SET ".$returnPlace1." = '".$to_switch1."' WHERE cupID='".$cupID."' && matchno='$returnNo'");
				    
			    }if(in_array($initNo2,$matchnos) && !$sw2[$returnPlace]) {
					 if(!mysql_num_rows(safe_query("SELECT * FROM ".PREFIX."cup_matches WHERE cupID='".$cupID."' && matchno='$returnNo'"))) { 	
						 safe_query("INSERT INTO ".PREFIX."cup_matches (cupID, ladID, matchno, date, ".$returnPlace.", comment, 1on1) VALUES ('".$cupID."', '0', '$returnNo', '".time()."', '$to_switch', '2', '$type_cup')");
					 }else
						 safe_query("UPDATE ".PREFIX."cup_matches SET ".$returnPlace." = '".$to_switch."' WHERE cupID='".$cupID."' && matchno='$returnNo'");
				    
			      }
			    		        
		//tournament autoswitch (place winner)
							
				$matchinfo = getnextmatchnr($cupID, $db['matchno']);
				$looserswitch = looserautoswitch($db['matchno'],$cupID,$p);	
				$type_cup=(is1on1($cupID) ? "1" : "0");	
							
				if($matchinfo['winner'])
				   safe_query("UPDATE ".PREFIX."cup_baum SET wb_winner='".($db['score1'] > $db['score2'] ? $db['clan1'] : $db['clan2'])."' WHERE $typename = '".$cupID."'");
				elseif($matchinfo['lb_winner'])
				   safe_query("UPDATE ".PREFIX."cup_baum SET lb_winner = '".($db['score1'] > $db['score2'] ? $db['clan1'] : $db['clan2'])."', third_winner='".($db['score1'] < $db['score2'] ? $db['clan1'] : $db['clan2'])."' WHERE $typename = '".$cupID."'");	
                elseif($matchinfo['third_winner'])
	               safe_query("UPDATE ".PREFIX."cup_baum SET third_winner = '".($db['score1'] > $db['score2'] ? $db['clan1'] : $db['clan2'])."' WHERE $typename = '".$cupID."'"); 

				if($matchinfo['matchno'] && !mysql_num_rows(safe_query("SELECT * FROM ".PREFIX."cup_matches WHERE $typename='".$cupID."' && matchno='".$matchinfo['matchno']."'"))) { echo 'y85sa';
				   safe_query("INSERT INTO ".PREFIX."cup_matches (cupID, ladID, matchno, date, ".$matchinfo['place'].", comment, 1on1) VALUES ('".$cupID."', '0', '".$matchinfo['matchno']."', '".time()."', '".($db['score1'] > $db['score2'] ? $db['clan1'] : $db['clan2'])."', '2', '$type_cup')");
				}elseif($matchinfo['matchno'])
				   safe_query("UPDATE ".PREFIX."cup_matches SET ".$matchinfo['place']." = '".($db['score1'] > $db['score2'] ? $db['clan1'] : $db['clan2'])."' WHERE $typename = '".$cupID."' && matchno = '".$matchinfo['matchno']."'");
				    
		//tournament autoswitch (place looser)
						
				if(!mysql_num_rows(safe_query("SELECT * FROM ".PREFIX."cup_matches WHERE $typename='".$cupID."' && matchno='$looserswitch'"))) { echo '8fa5a';
					safe_query("INSERT INTO ".PREFIX."cup_matches (cupID, ladID, matchno, date, ".$matchinfo['place'].", comment, 1on1) VALUES ('".$cupID."', '0', '$looserswitch', '".time()."', '".($db['score1'] < $db['score2'] ? $db['clan1'] : $db['clan2'])."', '2', '$type_cup')");
				}elseif($looserswitch)
	                safe_query("UPDATE ".PREFIX."cup_matches SET ".$matchinfo['place']." = '".($db['score1'] < $db['score2'] ? $db['clan1'] : $db['clan2'])."' WHERE $typename = '".$cupID."' && matchno = '$looserswitch'");
  
		//tournament auto-close
	    
				if($auto_close_cup && $matchinfo['winner'])
	                safe_query("UPDATE ".PREFIX."cups SET ende = '".time()."' WHERE ID='$cupID'");
						
		//ladder ranking system
				
			   	}elseif(isset($_GET['laddID'])) {
				
				  $rank1 = safe_query("SELECT * FROM ".PREFIX."cup_clans WHERE ladID='".$db['ladID']."' && clanID='".$db['clan1']."'");
				  $rk1 = mysql_fetch_array($rank1); 
				  
				  $rank2 = safe_query("SELECT * FROM ".PREFIX."cup_clans WHERE ladID='".$db['ladID']."' && clanID='".$db['clan2']."'");
				  $rk2 = mysql_fetch_array($rank2); 
				  
				   $rs=mysql_fetch_array(safe_query("SELECT ranksys, mode, d_xp FROM ".PREFIX."cup_ladders WHERE ID='".$db['ladID']."'"));
				   
				   if($rs['ranksys']==1) {
				      $team1_rank = $rk1['credit'];
				      $team2_rank = $rk2['credit'];
				   }elseif($rs['ranksys']==2) {
				      $team1_rank = $rk1['xp'];
				      $team2_rank = $rk2['xp'];
				   }elseif($rs['ranksys']==3) {
				      $team1_rank = $rk1['won'];
				      $team2_rank = $rk2['won'];
				   }elseif($rs['ranksys']==4) {
				      $team1_rank = $rk1['tp'];
				      $team2_rank = $rk2['tp'];
				   }elseif($rs['ranksys']==5) {
				      $team1_rank = $rk1['wc'];
				      $team2_rank = $rk2['wc'];
				   }elseif($rs['ranksys']==6) {
				      $team1_rank = $rk1['streak'];
				      $team2_rank = $rk2['streak'];
				   }
				  				
                    if($rs['mode']==1) { 
                      $team_ranks = array($team1_rank, $team2_rank);
                      function average_cal($team_ranks){
                        return array_sum($team_ranks)/count($team_ranks) ;
                      }
                        $halfup = +average_cal($team_ranks);
                        
                    }elseif($rs['mode']==2) {
                      if($db['clan1']==$rk1['clanID'] && $team1_rank > $team2_rank) {
                        $team1_rank = $rk2['credit'];
                        $team2_rank = $rk1['credit'];
                      }else{
                        $team1_rank = $rk1['credit'];
                        $team2_rank = $rk2['credit'];
                      }
                    }
                                           
                        $team1_lost = $rk1['credit']-$lostcredit;
                        $team2_lost = $rk2['credit']-$lostcredit;
                        $team1_won = $rk1['credit']+$woncredit;
                        $team2_won = $rk2['credit']+$woncredit;
                        $team1_draw = $rk1['credit']+$drawcredit;
                        $team2_draw = $rk2['credit']+$drawcredit;
                        
                        $rank1 = $rk1['rank_now'];
                        $rank2 = $rk2['rank_now']; 
                        
                        $t1_totalp = $rk1['credit']+$rk1['xp'];
                        $t2_totalp = $rk2['credit']+$rk2['xp'];                        
                        $t1_wmc = $rk1['xp']+$rk1['won'];     
                        $t2_wmc = $rk2['xp']+$rk2['won'];                                             
                        $t1_addxp = $db['score1']+$rk1['xp'];
                        $t2_addxp = $db['score2']+$rk2['xp'];
                                              
                        $t1_addwon = $rk1['won']+1;
                        $t2_addwon = $rk2['won']+1;
                        $t1_addlost = $rk1['lost']+1;
                        $t2_addlost = $rk2['lost']+1;
                        $t1_addtie = $rk1['draw']+1;
                        $t2_addtie = $rk2['draw']+1;
                        $t1_addmatch = $rk1['ma']+1;
                        $t2_addmatch = $rk2['ma']+1;
                        
                        if($clanID==$db['clan1'])
                           $la1 = ", lastact = '".time()."'";
                           
                        if($clanID==$db['clan2'])
                           $la2 = ", lastact = '".time()."'";

                        if($differential)
                        {              
                           $t1_addxp = $db['score1']-$db['score2']+$rk1['xp'];
                           $t2_addxp = $db['score2']-$db['score1']+$rk2['xp'];
                        }
                        else
                        {                        
                           $t1_addxp = $db['score1']+$rk1['xp'];
                           $t2_addxp = $db['score2']+$rk2['xp'];
                        }
                           
                        $c2_xp = (!$rs['d_xp'] ? "xp = '$t2_addxp'," : "");
                        $c1_xp = (!$rs['d_xp'] ? "xp = '$t1_addxp'," : "");
                                              
                        if($db['score1']>$db['score2']) {               
                          safe_query("UPDATE ".PREFIX."cup_clans SET ma = '$t1_addmatch', credit = '$team1_won', xp = '$t1_addxp', won = '$t1_addwon', lastpos = '1', rank_then='$rank1' $la1 WHERE clanID = '".$db['clan1']."' && ladID = '".$db['ladID']."'");                                                  
                          safe_query("UPDATE ".PREFIX."cup_clans SET ma = '$t2_addmatch', credit = '$team2_lost', $c2_xp lost = '$t2_addlost', lastpos = '2', rank_then='$rank2' $la2 WHERE clanID = '".$db['clan2']."' && ladID = '".$db['ladID']."'");
                                                                                                                              
                          redirect('?site=cupactions&action=updatestandings&matchID='.$db['matchID'].'&clanID='.$clanID.'&'.$t_name.'='.$db['ladID'].'', '...', 0);
                                                                
                        }elseif($db['score1']<$db['score2']) {  
                          safe_query("UPDATE ".PREFIX."cup_clans SET ma = '$t1_addmatch', credit = '$team1_lost', $c1_xp lost = '$t1_addlost', lastpos = '2', rank_then='$rank1' $la1 WHERE clanID = '".$db['clan1']."' && ladID = '".$db['ladID']."'");                                        
                          safe_query("UPDATE ".PREFIX."cup_clans SET ma = '$t2_addmatch', credit = '$team2_won', xp = '$t2_addxp', won = '$t2_addwon', lastpos = '1', rank_then='$rank2' $la2  WHERE clanID = '".$db['clan2']."' && ladID = '".$db['ladID']."'");
                                                              
                          redirect('?site=cupactions&action=updatestandings&matchID='.$db['matchID'].'&clanID='.$clanID.'&'.$t_name.'='.$db['ladID'].'', '...', 0);
                                                                
                        }else{  
                          safe_query("UPDATE ".PREFIX."cup_clans SET ma = '$t1_addmatch', credit = '$team1_draw', xp = '$t1_addxp', draw = '$t1_addtie', lastpos = '3', rank_then='$rank1' $la1 WHERE clanID = '".$db['clan1']."' && ladID = '".$db['ladID']."'");                                   
                          safe_query("UPDATE ".PREFIX."cup_clans SET ma = '$t2_addmatch', credit = '$team2_draw', xp = '$t2_addxp', draw = '$t2_addtie', lastpos = '3', rank_then='$rank2' $la2 WHERE clanID = '".$db['clan2']."' && ladID = '".$db['ladID']."'");                                                                   
                                                                
                          redirect('?site=cupactions&action=updatestandings&matchID='.$db['matchID'].'&clanID='.$clanID.'&'.$t_name.'='.$db['ladID'].'', '...', 0);
                       }                   
                    }            
				 }
			  }
		   }
	}else{
	
	$query1 = safe_query("SELECT * FROM ".PREFIX."cup_matches WHERE matchID='".$_GET['matchID']."'");
	$q1=mysql_fetch_array($query1);
	
  if($_GET['laddID']) {
	
	$query2 = safe_query("SELECT * FROM ".PREFIX."cup_challenges WHERE chalID='".$q1['matchno']."'");
	$ch=mysql_fetch_array($query2);
	
    if($ch['map1_final']) 
       $map1_pic = mapPic($ch['map1_final'],$_GET['laddID']);
    if($ch['map2_final']) 
       $map2_pic = mapPic($ch['map2_final'],$_GET['laddID']);
    if($ch['map3_final']) 
       $map3_pic = mapPic($ch['map3_final'],$_GET['laddID']);
    if($ch['map4_final']) 
       $map4_pic = mapPic($ch['map4_final'],$_GET['laddID']);
    
    if($q1['map4_score1'] || $q1['map4_score2']) {
       $round4_score1 = getname1($q1['clan1'],$_GET['laddID'],$ac=0,$var="ladder").' +'.$q1['map4_score1'].'';
       $round4_score2 = getname1($q1['clan2'],$_GET['laddID'],$ac=0,$var="ladder").' +'.$q1['map4_score2'].'';
       $round3_score1 = getname1($q1['clan1'],$_GET['laddID'],$ac=0,$var="ladder").' +'.$q1['map3_score1'].'';
       $round3_score2 = getname1($q1['clan2'],$_GET['laddID'],$ac=0,$var="ladder").' +'.$q1['map3_score2'].'';
       $round2_score1 = getname1($q1['clan1'],$_GET['laddID'],$ac=0,$var="ladder").' +'.$q1['map2_score1'].'';
       $round2_score2 = getname1($q1['clan2'],$_GET['laddID'],$ac=0,$var="ladder").' +'.$q1['map2_score2'].'';
       $round1_score1 = getname1($q1['clan1'],$_GET['laddID'],$ac=0,$var="ladder").' +'.$q1['map1_score1'].'';
       $round1_score2 = getname1($q1['clan2'],$_GET['laddID'],$ac=0,$var="ladder").' +'.$q1['map1_score2'].'';
    }elseif($q1['map3_score1'] || $q1['map3_score2']) {
       $round3_score1 = getname1($q1['clan1'],$_GET['laddID'],$ac=0,$var="ladder").' +'.$q1['map3_score1'].'';
       $round3_score2 = getname1($q1['clan2'],$_GET['laddID'],$ac=0,$var="ladder").' +'.$q1['map3_score2'].'';
       $round2_score1 = getname1($q1['clan1'],$_GET['laddID'],$ac=0,$var="ladder").' +'.$q1['map2_score1'].'';
       $round2_score2 = getname1($q1['clan2'],$_GET['laddID'],$ac=0,$var="ladder").' +'.$q1['map2_score2'].'';
       $round1_score1 = getname1($q1['clan1'],$_GET['laddID']).' +'.$q1['map1_score1'].'';
       $round1_score2 = getname1($q1['clan2'],$_GET['laddID'],$ac=0,$var="ladder").' +'.$q1['map1_score2'].'';
    }elseif($q1['map2_score1'] || $q1['map2_score2']) {
       $round2_score1 = getname1($q1['clan1'],$_GET['laddID'],$ac=0,$var="ladder").' +'.$q1['map2_score1'].'';
       $round2_score2 = getname1($q1['clan2'],$_GET['laddID'],$ac=0,$var="ladder").' +'.$q1['map2_score2'].'';
       $round1_score1 = getname1($q1['clan1'],$_GET['laddID'],$ac=0,$var="ladder").' +'.$q1['map1_score1'].'';
       $round1_score2 = getname1($q1['clan2'],$_GET['laddID'],$ac=0,$var="ladder").' +'.$q1['map1_score2'].'';
    }else{
       $round1_score1 = getname1($q1['clan1'],$_GET['laddID'],$ac=0,$var="ladder").' +'.$q1['score1'].'';
       $round1_score2 = getname1($q1['clan2'],$_GET['laddID'],$ac=0,$var="ladder").' +'.$q1['score2'].'';
    }
 }
	
		if($_GET['one'] == 1){
			$data=safe_query("SELECT * FROM ".PREFIX."cup_matches WHERE matchID = '$matchID' LIMIT 0,1");
			$db=mysql_fetch_array($data);
			
				$date=safe_query("SELECT date FROM ".PREFIX."cup_matches WHERE matchID = '$matchID'");
				$da=mysql_fetch_array($date);
				
			    $date = date('l M dS Y \@\ g:i a', $da['date']);
							
			if($db['clan1'] == $userID) 
				$gegner = getnickname($db['clan2']); 
			else 
				$gegner = getnickname($db['clan1']);
			$server = $db['server'];
			$hltv = $db['hltv'];
			$clanID = 'onecup';
			$memberID = $_GET['clanID'];
			

	    $off_score1 = $db['score1'];
	    $off_score2 = $db['score2']; 	
	    		
			if($_GET['clanID']==$db['clan1']) 
			{  
			
	            if($off_score1 > $off_score2) 
	            {
	              $status = '<span style="background-color: #00CC00; color: white; padding: 1px;"><b>YOU ARE CONFIRMING A WIN</b></span>'; 
	              $score1 = '<span style="background-color: #00CC00; color: white; padding: 1px;"><b>'.$off_score1.'</b></span>'; 
	              $score2 = $off_score2;
	              $myscore = $off_score1;
	              $oppscore = $off_score2;
	          }
	          elseif($off_score1==$off_score2)
	          {
	              $status = '<span style="background-color: #FF6600; color: white; padding: 1px;"><b>YOU ARE CONFIRMING A DRAW</b></span>'; 
	              $score1 = $off_score1; 
	              $score2 = $off_score2;
	              $myscore = $off_score1;
	              $oppscore = $off_score2;
	          }
	          else
	          {
	              $status = '<span style="background-color: #FF0000; color: white; padding: 1px;"><b>YOU ARE CONFIRMING A LOSS</b></span>'; 
	              $score2 = $off_score2;
	              $score1 = '<span style="background-color: #FF0000; color: white; padding: 1px;"><b>'.$off_score1.'</b></span>'; 
	              $myscore = $off_score1;
	              $oppscore = $off_score2; 
	          }
	            
			}
			else
			{  

	            if($off_score2 > $off_score1) 
	            { 
	              $status = '<span style="background-color: #00CC00; color: white; padding: 1px;"><b>YOU ARE CONFIRMING A WIN</b></span>'; 
	              $score1 = '<span style="background-color: #00CC00; color: white; padding: 1px;"><b>'.$off_score2.'</b></span>';
	              $score2 = $off_score1;
	              $myscore = $off_score2;
	              $oppscore = $off_score1;
	           }
	           elseif($off_score1==$off_score2)
	           { 
	              $status = '<span style="background-color: #FF6600; color: white; padding: 1px;"><b>YOU ARE CONFIRMING A DRAW</b></span>'; 
	              $score1 = $off_score2; 
	              $score2 = $off_score1;
	              $myscore = $off_score2;
	              $oppscore = $off_score1;
	          }
	          else
	          { 
	              $status = '<span style="background-color: #FF0000; color: white; padding: 1px;"><b>YOU ARE CONFIRMING A LOSS</b></span>';
	              $score1 = '<span style="background-color: #FF0000; color: white; padding: 1px;"><b>'.$off_score2.'</b></span>';
	              $score2 = $off_score1; 
	              $myscore = $off_score2;
	              $oppscore = $off_score1; 
	          }
	        
			}
						
			$bg1 = BG_1;
			$bg2 = BG_2;
			
			if($userID==$db['clan1'] && $db['inscribed']==$db['clan2'] && !$db['einspruch'] && $db['confirmscore']==0) {
			
			eval ("\$cupactions_confirmscore = \"".gettemplate("cupactions_confirmscore")."\";");
			echo $cupactions_confirmscore;
			
			}elseif($userID==$db['clan2'] && $db['inscribed']==$db['clan1'] && !$db['einspruch'] && $db['confirmscore']==0) {
			
			  
			eval ("\$cupactions_confirmscore = \"".gettemplate("cupactions_confirmscore")."\";");
			echo $cupactions_confirmscore;
			
			}		
		}else{
			if(isleader($userID, $clanID)){
				$data=safe_query("SELECT * FROM ".PREFIX."cup_matches WHERE matchID = '$matchID' LIMIT 0,1");
				$db=mysql_fetch_array($data);
				
				$date=safe_query("SELECT date FROM ".PREFIX."cup_matches WHERE matchID = '$matchID'");
				$da=mysql_fetch_array($date); 
				
			    $date = date('l M dS Y \@\ g:i a', $da['date']);
							
				if($db['clan1'] == $clanID) 
					$gegner = getclanname2($db['clan2']); 
				else 
					$gegner = getclanname2($db['clan1']);
				$server = $db['server'];
				$hltv = $db['hltv'];
				
	    $off_score1 = $db['score1'];
	    $off_score2 = $db['score2'];
				
			if($_GET['clanID']==$db['clan1']) {
			
	            if($off_score1 > $off_score2) {
	              $status = '<span style="background-color: #00CC00; color: white; padding: 1px;"><b>YOU ARE CONFIRMING A WIN</b></span>'; 
	              $score1 = '<span style="background-color: #00CC00; color: white; padding: 1px;"><b>'.$off_score1.'</b></span>'; 
	              $score2 = $off_score2;
	              $myscore = $off_score1;
	              $oppscore = $off_score2;
	          }else{
	              $status = '<span style="background-color: #FF0000; color: white; padding: 1px;"><b>YOU ARE CONFIRMING A LOSS</b></span>'; 
	              $score1 = $off_score1;
	              $score2 = '<span style="background-color: #FF0000; color: white; padding: 1px;"><b>'.$off_score2.'</b></span>'; 
	              $myscore = $off_score2;
	              $oppscore = $off_score1; }
	            
			}else{ 

	            if($off_score2 > $off_score1) {
	              $status = '<span style="background-color: #00CC00; color: white; padding: 1px;"><b>YOU ARE CONFIRMING A WIN</b></span>'; 
	              $score1 = '<span style="background-color: #00CC00; color: white; padding: 1px;"><b>'.$off_score2.'</b></span>';
	              $score2 = $off_score1;
	              $myscore = $off_score2;
	              $oppscore = $off_score1;
	          }else{
	              $status = '<span style="background-color: #FF0000; color: white; padding: 1px;"><b>YOU ARE CONFIRMING A LOSS</b></span>';
	              $score1 = $off_score2;
	              $score2 = '<span style="background-color: #FF0000; color: white; padding: 1px;"><b>'.$off_score1.'</b></span>'; 
	              $myscore = $off_score2;
	              $oppscore = $off_score1; }
	        
			}
				
				$bg1 = BG_1;
				$bg2 = BG_2;	

				if(isleader($userID,$db['clan1']) && $db['inscribed']==$db['clan2'] && !$db['einspruch'] && $db['confirmscore']==0) {			
				   
				eval ("\$cupactions_confirmscore = \"".gettemplate("cupactions_confirmscore")."\";");
				echo $cupactions_confirmscore;
				   
				}elseif(isleader($userID,$db['clan2']) && $db['inscribed']==$db['clan1'] && !$db['einspruch'] && $db['confirmscore']==0){

				eval ("\$cupactions_confirmscore = \"".gettemplate("cupactions_confirmscore")."\";");
				echo $cupactions_confirmscore;
			  }
		   }     
	    }     	
     }
	
//Enter Result
  }elseif(isset($_GET['action']) && $_GET['action'] == 'ladscore'){  	

$clanID = isset($_GET['clan1']) ? $_GET['clan1'] : 0;
 if(isset($_POST['submit'])){
 
   $participants = safe_query("SELECT * FROM ".PREFIX."cup_matches WHERE matchID='".$_GET['matchID']."'");
       while($dp=mysql_fetch_array($participants)) {
       
        $challenge_info = safe_query("SELECT * FROM ".PREFIX."cup_challenges WHERE chalID='".$dp['matchno']."'");
        $ch = mysql_fetch_array($challenge_info);
       
     if($ch['map4_final']) {  
       $numeric_array = array();
     
          if(!is_numeric($_POST['map1_score1'])) $numeric_array[] = 'You must enter a numeric value for your score ('.$ch['map1_final'].')'; 
          if(!is_numeric($_POST['map1_score2'])) $numeric_array[] = 'You must enter a numeric value for opponent score ('.$ch['map1_final'].')'; 
          if(!is_numeric($_POST['map2_score1'])) $numeric_array[] = 'You must enter a numeric value for your score ('.$ch['map2_final'].')'; 
          if(!is_numeric($_POST['map2_score2'])) $numeric_array[] = 'You must enter a numeric value for opponent score ('.$ch['map2_final'].')'; 
          if(!is_numeric($_POST['map3_score1'])) $numeric_array[] = 'You must enter a numeric value for your score ('.$ch['map3_final'].')'; 
          if(!is_numeric($_POST['map3_score2'])) $numeric_array[] = 'You must enter a numeric value for opponent score ('.$ch['map3_final'].')';  
          if(!is_numeric($_POST['map4_score1'])) $numeric_array[] = 'You must enter a numeric value for your score ('.$ch['map4_final'].')'; 
          if(!is_numeric($_POST['map4_score2'])) $numeric_array[] = 'You must enter a numeric value for opponent score ('.$ch['map4_final'].')'; 

            $post_scores = "`map1_score1` = '".$_POST['map1_score1']."', 
                            `map1_score2` = '".$_POST['map1_score2']."', 
                            `map2_score1` = '".$_POST['map2_score1']."', 
                            `map2_score2` = '".$_POST['map2_score2']."', 
                            `map3_score1` = '".$_POST['map3_score1']."', 
                            `map3_score2` = '".$_POST['map3_score2']."', 
                            `map4_score1` = '".$_POST['map4_score1']."', 
                            `map4_score2` = '".$_POST['map4_score2']."',";
                            
                      $score1_total = $_POST['map1_score1']+
                                      $_POST['map2_score1']+
                                      $_POST['map3_score1']+
                                      $_POST['map4_score1'];    
                                      
                      $score2_total = $_POST['map1_score2']+
                                      $_POST['map2_score2']+
                                      $_POST['map3_score2']+
                                      $_POST['map4_score2'];   
                            
             $post_score1 = "`score1` = '$score1_total',";
             $post_score2 = "`score2` = '$score2_total',";
                            
     }elseif($ch['map3_final']) {   
       $numeric_array = array();
     
          if(!is_numeric($_POST['map1_score1'])) $numeric_array[] = 'You must enter a numeric value for your score ('.$ch['map1_final'].')'; 
          if(!is_numeric($_POST['map1_score2'])) $numeric_array[] = 'You must enter a numeric value for opponent score ('.$ch['map1_final'].')'; 
          if(!is_numeric($_POST['map2_score1'])) $numeric_array[] = 'You must enter a numeric value for your score ('.$ch['map2_final'].')'; 
          if(!is_numeric($_POST['map2_score2'])) $numeric_array[] = 'You must enter a numeric value for opponent score ('.$ch['map2_final'].')'; 
          if(!is_numeric($_POST['map3_score1'])) $numeric_array[] = 'You must enter a numeric value for your score ('.$ch['map3_final'].')'; 
          if(!is_numeric($_POST['map3_score2'])) $numeric_array[] = 'You must enter a numeric value for opponent score ('.$ch['map3_final'].')';  

            $post_scores = "`map1_score1` = '".$_POST['map1_score1']."', 
                            `map1_score2` = '".$_POST['map1_score2']."', 
                            `map2_score1` = '".$_POST['map2_score1']."', 
                            `map2_score2` = '".$_POST['map2_score2']."', 
                            `map3_score1` = '".$_POST['map3_score1']."', 
                            `map3_score2` = '".$_POST['map3_score2']."',";
                            
                      $score1_total = $_POST['map1_score1']+
                                      $_POST['map2_score1']+
                                      $_POST['map3_score1'];    
                                      
                      $score2_total = $_POST['map1_score2']+
                                      $_POST['map2_score2']+
                                      $_POST['map3_score2'];   
                            
             $post_score1 = "`score1` = '$score1_total',";
             $post_score2 = "`score2` = '$score2_total',";            
 
     }elseif($ch['map2_final']) {
       $numeric_array = array();
     
          if(!is_numeric($_POST['map1_score1'])) $numeric_array[] = 'You must enter a numeric value for your score ('.$ch['map1_final'].')'; 
          if(!is_numeric($_POST['map1_score2'])) $numeric_array[] = 'You must enter a numeric value for opponent score ('.$ch['map1_final'].')'; 
          if(!is_numeric($_POST['map2_score1'])) $numeric_array[] = 'You must enter a numeric value for your score ('.$ch['map2_final'].')'; 
          if(!is_numeric($_POST['map2_score2'])) $numeric_array[] = 'You must enter a numeric value for opponent score ('.$ch['map2_final'].')';  

            $post_scores = "`map1_score1` = '".$_POST['map1_score1']."', 
                            `map1_score2` = '".$_POST['map1_score2']."', 
                            `map2_score1` = '".$_POST['map2_score1']."', 
                            `map2_score2` = '".$_POST['map2_score2']."',";
                            
                      $score1_total = $_POST['map1_score1']+
                                      $_POST['map2_score1'];    
                                      
                      $score2_total = $_POST['map1_score2']+
                                      $_POST['map2_score2'];   
                            
             $post_score1 = "`score1` = '$score1_total',";
             $post_score2 = "`score2` = '$score2_total',";

     }
     
	if(count($numeric_array)) 
	{
		$errors=implode('<br />&#8226; ', $numeric_array);
		$showerror = '<div class="errorbox">
		  <b>Errors occured:</b><br /><br />
		  &#8226; '.$errors.'
		</div>';
		echo $showerror.'<br /><input type="button" class="button" onClick="javascript:history.back()" value="&#171; Go Back">';

     }else{
       
         if((ladderis1on1($_GET['laddID']) && ($dp['clan1']==$userID || $dp['clan2']==$userID)) || 
           (!ladderis1on1($_GET['laddID']) && isleader($userID,$dp['clan1']) || isleader($userID,$dp['clan2']))) {
            
            
           if(ladderis1on1($_GET['laddID'])) 
              $cup_type = $userID;
           else
              $cup_type = $clanID; 
            
		   if(safe_query("UPDATE `".PREFIX."cup_matches` SET $post_score1 $post_score2 $post_scores `report_team1` = '".mysql_escape_string($_POST['report'])."', `inscribed` = '".$cup_type."', `inscribed_date` = '".time()."' WHERE matchID = '".$_GET['matchID']."' && inscribed = '0'") &&
		      safe_query("UPDATE `".PREFIX."cup_clans` SET lastact='".time()."' WHERE $typename='$cupID' AND clanID='$cup_type'"))
			   echo'<center><br /><br /><br /><br /><b>'.$_language->module['match_score'].'<br /><br/><br /><a href='.matchlink($dp['matchID'],$ac=0,$tg=0,$redirect=0).'>Match-Details</a> '.$name4.'';
		   else
			   echo $_language->module['error'];
		   }
	    }
     } 
  }
}elseif(isset($_GET['action']) && $_GET['action'] == 'score'){

	$clanID = isset($_GET['clan1']) ? $_GET['clan1'] : 0;
	$matchID = $_GET['matchID'];
	
    $started = safe_query("SELECT * FROM ".PREFIX."cups WHERE ID='$cupID' AND status='2'");
    $st1=mysql_fetch_array($started); $cup_started = mysql_num_rows($started);
    
    $ladd_started = safe_query("SELECT status FROM ".PREFIX."cup_ladders WHERE ID='".$_GET['laddID']."' AND status='2'");
    $st2=mysql_fetch_array($ladd_started); $ladd_started = mysql_num_rows($ladd_started);
    
     if($_GET['matchID']!="directmatch" && $matchID && !ismatchparticipant($userID,$_GET['matchID'],$all=0))
        die('You are not a valid participant of this match.');

		$ergebnis2 = safe_query("SELECT count(*) as anzahl FROM ".PREFIX."cup_clans WHERE groupID='".$_GET['laddID']."'");
		$dv=mysql_fetch_array($ergebnis2);
		
            if(!$_GET['laddID']) {
               $link_typ = '?site=cups&action=details&cupID='.$cupID.'';
               $ds=mysql_fetch_array(safe_query("SELECT * FROM ".PREFIX."cups WHERE ID='".$_GET['cupID']."'"));
            }else{
               $link_typ = '?site=ladders&ID='.$cupID.'';
               $ds=mysql_fetch_array(safe_query("SELECT * FROM ".PREFIX."cup_ladders WHERE ID='".$_GET['laddID']."'"));
            }
    
    //if(($_GET['cupID'] && $cup_started) || 
    //   ($_GET['laddID'] && $ladd_started) || 
    //   ($dv['anzahl'] >= $ds['maxclan']+$ds['maxclan'])) {
	
	if(!$_GET['cupID'])
	   $gs_t = mysql_fetch_array(safe_query("SELECT gs_start, gs_end FROM ".PREFIX."cup_ladders WHERE ID='".$_GET['laddID']."'"));
	else
	   $gs_t = mysql_fetch_array(safe_query("SELECT gs_start, gs_end FROM ".PREFIX."cups WHERE ID='$cupID'"));
	   
	if(($_GET['type']=='group' && $gs_t['gs_start'] <= time() && $gs_t['gs_end'] >= time()) || 
	   (($_GET['cupID'] && $cup_started && $_GET['type']!='group') || ($_GET['laddID'] && $ladd_started && $_GET['type']!='group')))
	{
	
	if(isset($_GET['cupID'])) {
	eval ("\$title_cup = \"".gettemplate("title_cup")."\";");
	echo $title_cup.'<br />'; } 
	
	if(isset($_POST['submit'])){   
		if($clanID == 'onecup'){
		if (strlen($_POST['score1']) >= 0 && is_numeric($_POST['score1']))
  {$score1=TRUE;}
 else
  {$score1=FALSE;
  $message_score1='<div style="margin: 5px; padding: 6px; border: 4px solid #D6B4B4; text-align: center;"><b>You must enter a numeric value for score1!</b> <img src="images/cup/error.png" width="16" height="16"></div>';}

 if (strlen($_POST['score2']) >= 0 && is_numeric($_POST['score2']))
  {$score2=TRUE;}
 else
  {$score2=FALSE;
  $message_score2='<div style="margin: 5px; padding: 6px; border: 4px solid #D6B4B4; text-align: center;"><b>You must enter a numeric value for score2!</b> <img src="images/cup/error.png" width="16" height="16"></div>';}

 if($_GET['type']=="group" && $_POST['score1']+$_POST['score2'] != $ds['gs_maxrounds'])
    die('<div style="margin: 5px; padding: 6px; border: 4px solid #D6B4B4; text-align: center;">Your scores entered does not equal to max rounds <b>'.$ds['gs_maxrounds'].'</b></div>');

if(isset($_GET['cupID']) || $_GET['type']=="group") {
 if (($_POST['score1'] == $_POST['score2']))
  {$scorecheck=FALSE;
  $message_scorecheck='<div style="margin: 5px; padding: 6px; border: 4px solid #D6B4B4; text-align: center;"><b>Both scores entered are the same!</b> <img src="images/cup/error.png" width="16" height="16"><br>Draws are not supported in tournaments or group stages.</div>';}
 else
  {$scorecheck=TRUE;
  }
}
  			if ($message_score1) die("$message_score1");
			if ($message_score2) die("$message_score2");
			if ($message_scorecheck) die("$message_scorecheck");
			
/* Ladder Open-Play (1on1) */

if(isset($_GET['matchID']) && $_GET['matchID']=="directmatch") {

	$day = $_POST['day'];
	$month = $_POST['month'];
	$year = $_POST['year'];
	$hour = $_POST['hour'];
	$min = $_POST['min'];
	$date = @mktime($hour, $min, 0, $month, $day, $year);	
	
       safe_query("INSERT INTO ".PREFIX."cup_matches (`ladID`, `cupID`, `date`, `added_date`, `inscribed_date`, `clan1`, `clan2`, `score1`, `score2`, `server`, `hltv`, `report_team1`, `comment`, `inscribed`, `1on1`) VALUES
                                                     ('".$_GET['laddID']."',
                                                      '0',
                                                      '$date',
                                                      '".time()."',
                                                      '".time()."',
                                                      '$userID',
                                                      '".$_GET['challenged']."',
                                                      '".$_POST['score1']."',
                                                      '".$_POST['score2']."',
                                                      '".$_POST['server']."',
                                                      '".$_POST['hltv']."',
                                                      '".$_POST['report']."',
                                                      '2',
                                                      '$userID',
                                                      '1')");  
                      $matchID = mysql_insert_id();       
                      
       safe_query("UPDATE ".PREFIX."cup_clans SET lastact = '".time()."' WHERE ladID = '".$_GET['laddID']."' AND clanID = '$userID'");
                                              
                      echo '<center>'.$_language->module['match_score'].' <br /><br/><br /><a href='.matchlink($matchID,$ac=0,$tg=0,$redirect=0).'>Match-Details</a> '.$name4.'</center>';
  }else{

/* END Ladder Open-Play */		
					
			$clan_return = safe_query("SELECT * FROM ".PREFIX."cup_matches WHERE matchID = '$matchID' AND $name3");
			$report_clan = mysql_num_rows($clan_return) ? 'team1' : 'team2';
			$score1 = mysql_num_rows($clan_return) ? $_POST['score1'] : $_POST['score2'];
			$score2 = mysql_num_rows($clan_return) ? $_POST['score2'] : $_POST['score1'];
			$report = $_POST['report'];
			
            if($_GET['laddID']) {                          
             $postmap_score1 = "`map1_score1` = '$score1',";
             $postmap_score2 = "`map1_score2` = '$score2',";
            }
			
			while($mm=mysql_fetch_array($clan_return)) { 
			
            
            if($mm['clan1']==$userID) { 
            
			if(safe_query("UPDATE `".PREFIX."cup_matches` SET `score1` = '$score1', `score2` = '$score2', $postmap_score1 $postmap_score2 `report_team1` = '".mysql_escape_string($report)."', `inscribed` = '$userID', `inscribed_date` = '".time()."' WHERE matchID = '$matchID' && inscribed = '0'")){
				$db = mysql_fetch_array($clan_return);
				echo'<center><br /><br /><br /><br /><b>'.$_language->module['match_score'].'<br /><br/><br /><a href='.matchlink($matchID,$ac=0,$tg=0,$redirect=0).'>Match-Details</a> '.$name4.'';
			}else
			    echo $_language->module['error'];
			    
			}else 
			
			if(safe_query("UPDATE `".PREFIX."cup_matches` SET `score1` = '$score2', `score2` = '$score1', `report_team2` = '".mysql_escape_string($report)."', `inscribed` = '$userID', `inscribed_date` = '".time()."' WHERE matchID = '$matchID' && inscribed = '0'")){
				$db = mysql_fetch_array($clan_return);
				echo'<center><br /><br /><br /><br /><b>'.$_language->module['match_score'].'<br /><br/><br /><a href='.matchlink($matchID,$ac=0,$tg=0,$redirect=0).'>Match-Details</a> '.$name4.'';
			}else
			    echo $_language->module['error'];
		  }    
	   }
	}else{
			if(isleader($userID, $clanID)){ 
		if (strlen($_POST['score1']) >= 0 && is_numeric($_POST['score1']))
  {$score1=TRUE;}
 else
  {$score1=FALSE;
  $message_score1='<div style="margin: 5px; padding: 6px; border: 4px solid #D6B4B4; text-align: center;"><b>You must enter a numeric value for score1!</b> <img src="images/cup/error.png" width="16" height="16"></div>';}

 if (strlen($_POST['score2']) >= 0 && is_numeric($_POST['score2']))
  {$score2=TRUE;}
 else
  {$score2=FALSE;
  $message_score2='<div style="margin: 5px; padding: 6px; border: 4px solid #D6B4B4; text-align: center;"><b>You must enter a numeric value for score2!</b> <img src="images/cup/error.png" width="16" height="16"></div>';}

 if($_GET['type']=="group" && $_POST['score1']+$_POST['score2'] != $ds['gs_maxrounds'])
    die('<div style="margin: 5px; padding: 6px; border: 4px solid #D6B4B4; text-align: center;">Your scores entered does not equal to max rounds <b>'.$ds['gs_maxrounds'].'</b></div>');

if(isset($_GET['cupID']) || $_GET['type']=="group") {
 if (($_POST['score1'] == $_POST['score2']))
  {$scorecheck=FALSE;
  $message_scorecheck='<div style="margin: 5px; padding: 6px; border: 4px solid #D6B4B4; text-align: center;"><b>Both scores entered are the same!</b> <img src="images/cup/error.png" width="16" height="16"><br>Draws are not supported in tournaments or group stages.</div>';}
 else
  {$scorecheck=TRUE;
  }
}
  			if ($message_score1) die("$message_score1");
			if ($message_score2) die("$message_score2");
			if ($message_scorecheck) die("$message_scorecheck");	
			
/* Ladder Open-Play (Teams) */

if(isset($_GET['matchID']) && $_GET['matchID']=="directmatch") {

	$day = $_POST['day'];
	$month = $_POST['month'];
	$year = $_POST['year'];
	$hour = $_POST['hour'];
	$min = $_POST['min'];
	$date = @mktime($hour, $min, 0, $month, $day, $year);
	
       safe_query("INSERT INTO ".PREFIX."cup_matches (`ladID`, `cupID`, `date`, `added_date`, `inscribed_date`, `clan1`, `clan2`, `score1`, `score2`, `server`, `hltv`, `report_team1`, `comment`, `inscribed`, `1on1`) VALUES
                                                     ('".$_GET['laddID']."',
                                                      '0',
                                                      '$date',
                                                      '".time()."',
                                                      '".time()."',
                                                      '".$_GET['clan1']."',
                                                      '".$_GET['challenged']."',
                                                      '".$_POST['score1']."',
                                                      '".$_POST['score2']."',
                                                      '".$_POST['server']."',
                                                      '".$_POST['hltv']."',
                                                      '".$_POST['report']."',
                                                      '2',
                                                      '".$_GET['clan1']."',
                                                      '0')");
                      $matchID = mysql_insert_id();      
                      
                      safe_query("UPDATE ".PREFIX."cup_clans SET lastact = '".time()."' WHERE ladID = '".$_GET['laddID']."' AND clanID = '".$_GET['clan1']."'");
                                               
                      echo '<center>'.$_language->module['match_score'].' <br /><br/><br /><a href='.matchlink($matchID,$ac=0,$tg=0,$redirect=0).'>Match-Details</a> '.$name4.'</center>';
  
  }else{

/* END Ladder Open-Play */	
			
			    $clan_return = safe_query("SELECT * FROM ".PREFIX."cup_matches WHERE matchID = '$matchID' AND (clan1 = '$clanID' || clan2 = '$clanID')");
			    $report_clan = mysql_num_rows($clan_return) ? 'team1' : 'team2';
				$score1 = mysql_num_rows($clan_return) ? $_POST['score1'] : $_POST['score2'];
				$score2 = mysql_num_rows($clan_return) ? $_POST['score2'] : $_POST['score1'];
				$report = $_POST['report'];
				
            if($_GET['laddID']) {                          
             $postmap_score1 = "`map1_score1` = '$score1',";
             $postmap_score2 = "`map1_score2` = '$score2',";
            }
				
            while($mm=mysql_fetch_array($clan_return)) {     
                
            
            if($mm['clan1']==$clanID) { 
					
				if(safe_query("UPDATE `".PREFIX."cup_matches` SET `score1` = '$score1', `score2` = '$score2', $postmap_score1 $postmap_score2 `report_team1` = '".mysql_escape_string($report)."', `inscribed` = '$clanID', `inscribed_date` = '".time()."' WHERE `".PREFIX."cup_matches`.`matchID` = '$matchID' && `".PREFIX."cup_matches`.`inscribed` = '0'")){
					$db = mysql_fetch_array($clan_return);
					echo'<center><br /><br /><br /><br /><b>'.$_language->module['match_score'].'.<br /><br/><br /><a href='.matchlink($matchID,$ac=0,$tg=0,$redirect=0).'>Match-Details</a> '.$name4.'';
			   }else
			        echo $_language->module['error'];
			        
			   }else 
			   
				if(safe_query("UPDATE `".PREFIX."cup_matches` SET `score1` = '$score2', `score2` = '$score1', `report_team2` = '".mysql_escape_string($report)."', `inscribed` = '$clanID', `inscribed_date` = '".time()."' WHERE `".PREFIX."cup_matches`.`matchID` = '$matchID' && `".PREFIX."cup_matches`.`inscribed` = '0'")){
					$db = mysql_fetch_array($clan_return);
					echo'<center><br /><br /><br /><br /><b>'.$_language->module['match_score'].'.<br /><br/><br /><a href='.matchlink($matchID,$ac=0,$tg=0,$redirect=0).'>Match-Details</a> '.$name4.'';
			   }else
			        echo $_language->module['error']; 
			} 
		  }
		}
      }		
	}else{
		if($typename3($cupID)){
			$data=safe_query("SELECT * FROM ".PREFIX."cup_matches WHERE matchID = '$matchID' && inscribed = '0' LIMIT 0,1");
			$db = mysql_fetch_array($data);
			
				$date=safe_query("SELECT date FROM ".PREFIX."cup_matches WHERE matchID = '$matchID'");
				$da=mysql_fetch_array($date); 
				
			    $date = date('l M dS Y \@\ g:i a', $da['date']);
			
			if($db['clan1'] == $userID) 
				$gegner = getnickname($db['clan2']); 
			else 
				$gegner = getnickname($db['clan1']);
			$server = $db['server'];
			$hltv = $db['hltv'];
			$clanID = 'onecup';
				
			$bg1 = BG_1;
			$bg2 = BG_2;
			
				$result=safe_query("SELECT * FROM ".PREFIX."cup_matches WHERE matchID = '$matchID'");
				$ds=mysql_fetch_array($result);
				
				if($ds['score1']>0 || $ds['score2']>0)
				die("A score for this match has already been entered!");
				
/* V4.1.4d */				
			    $notchecked = safe_query("SELECT * FROM ".PREFIX."cup_clans WHERE $name1 && clanID='$userID' && 1on1='1' && checkin='0'");
		        $unchecked=mysql_num_rows($notchecked);
		        
		        if($unchecked) die('<div style="margin: 5px; padding: 6px; border: 4px solid #D6B4B4; text-align: center;"><b>You are not checked into this cup!</b> <img src="images/cup/error.png" width="16" height="16"><br> Please <a href="?site=cups&action=admins&'.$typename2.'='.$cupID.'">contact an admin</a> to check you in.</div>');
	            if($ds['einspruch']==1) die('<div style="margin: 5px; padding: 6px; border: 4px solid #D6B4B4; text-align: center;"><b>There is an open protest for this match!</b> <img src="images/cup/error.png" width="16" height="16"><br> Please wait or <a href="?site=cups&action=admins&'.$typename2.'='.$cupID.'">contact an admin</a> for details.</div>');
/* EXIT */ 

			  if($userID==$db['clan1'] || $userID==$db['clan2'])
			  if($_GET['laddID'] && time() <= $db['date'] && $report_after_date)
			     die('<center><b>Your gamedate is on '.date('l M dS Y \@\ g:i a', $db['date']).'</b><br>You may report your result after this date and time.<br><br>Current date/time: <b>'.date('l M dS Y \@\ g:i a').'</b></center>');

            if($_GET['laddID'] && $_GET['type']!="group") {
            
               $challenge_info = safe_query("SELECT * FROM ".PREFIX."cup_challenges WHERE chalID='".$db['matchno']."'");
               $ch=mysql_fetch_array($challenge_info); $map2 = $ch['map2_final'];
               
               if($_GET['clan1']==$db['clan1']) {
                  $score1 = 1;
                  $score2 = 2;
               }else{
                  $score1 = 2;
                  $score2 = 1;
               }
               
               if($ch['map4_final']) {
               
			     eval ("\$cupactions_score_head = \"".gettemplate("cupactions_score_head")."\";");
			     echo $cupactions_score_head;
			     
			       $maps = mapPic($ch['map1_final'],$_GET['laddID']);
			        $ladscore1 = "map1_score$score1";
			         $ladscore2 = "map1_score$score2";             
			        eval ("\$ladactions_score = \"".gettemplate("ladactions_score")."\";");
			       echo $ladactions_score;

			       $maps = mapPic($ch['map2_final'],$_GET['laddID']);
			        $ladscore1 = "map2_score$score1";
			         $ladscore2 = "map2_score$score2";              
			        eval ("\$ladactions_score = \"".gettemplate("ladactions_score")."\";");
			       echo $ladactions_score;

			       $maps = mapPic($ch['map3_final'],$_GET['laddID']);
			        $ladscore1 = "map3_score$score1";
			         $ladscore2 = "map3_score$score2";           
			        eval ("\$ladactions_score = \"".gettemplate("ladactions_score")."\";");
			       echo $ladactions_score;

			       $maps = mapPic($ch['map4_final'],$_GET['laddID']); 
			        $ladscore1 = "map4_score$score1";
			         $ladscore2 = "map4_score$score2";       
			        eval ("\$ladactions_score = \"".gettemplate("ladactions_score")."\";");
			       echo $ladactions_score;
			     
			     eval ("\$ladactions_report = \"".gettemplate("ladactions_report")."\";");
			     echo $ladactions_report;

               }elseif($ch['map3_final']) {
               
			     eval ("\$cupactions_score_head = \"".gettemplate("cupactions_score_head")."\";");
			     echo $cupactions_score_head;
               		     
			       $maps = mapPic($ch['map1_final'],$_GET['laddID']);
			        $ladscore1 = "map1_score$score1";
			         $ladscore2 = "map1_score$score2";             
			        eval ("\$ladactions_score = \"".gettemplate("ladactions_score")."\";");
			       echo $ladactions_score;

			       $maps = mapPic($ch['map2_final'],$_GET['laddID']);
			        $ladscore1 = "map2_score$score1";
			         $ladscore2 = "map2_score$score2";  
			        eval ("\$ladactions_score = \"".gettemplate("ladactions_score")."\";");
			       echo $ladactions_score;
			       
			       $maps = mapPic($ch['map3_final'],$_GET['laddID']); 
			        $ladscore1 = "map3_score$score1";
			         $ladscore2 = "map3_score$score2";           
			        eval ("\$ladactions_score = \"".gettemplate("ladactions_score")."\";");
			       echo $ladactions_score;
			     
			     eval ("\$ladactions_report = \"".gettemplate("ladactions_report")."\";");
			     echo $ladactions_report;

               }elseif($ch['map2_final']) {
               
			     eval ("\$cupactions_score_head = \"".gettemplate("cupactions_score_head")."\";");
			     echo $cupactions_score_head;
               
			       $maps = mapPic($ch['map1_final'],$_GET['laddID']);   
			        $ladscore1 = "map1_score$score1";
			         $ladscore2 = "map1_score$score2";      
			        eval ("\$ladactions_score = \"".gettemplate("ladactions_score")."\";");
			       echo $ladactions_score;

			       $maps = mapPic($ch['map2_final'],$_GET['laddID']);     
			        $ladscore1 = "map2_score$score1";
			         $ladscore2 = "map2_score$score2";   
			        eval ("\$ladactions_score = \"".gettemplate("ladactions_score")."\";");
			       echo $ladactions_score;
			     
			     eval ("\$ladactions_report = \"".gettemplate("ladactions_report")."\";");
			     echo $ladactions_report;
			    }
            }
            
            if(!$map2) {
            
              if(!$ds['inscribed'] || ($ds['score1']==0 && $ds['score2']==0)) {
	      
	        if(time() >= $ds['date']) {

			eval ("\$cupactions_score = \"".gettemplate("cupactions_score")."\";");
			echo $cupactions_score;
			
		}
		else{
		        echo '<font color="red"><strong>Your match date is for: '.date("d/m/Y H:i", $ds['date']).' - you cannot report until then.</strong></font>';
		}
	      }
	      else{
	                echo getname1($ds['inscribed'],$cupID,0,$typename4).' has already inscribed!';
	      }
			
             }		   			
	        }
		 else{
			if(isleader($userID,$clanID)){
				$data=safe_query("SELECT * FROM ".PREFIX."cup_matches WHERE matchID = '$matchID' && inscribed = '0' LIMIT 0,1");
				$db=mysql_fetch_array($data);
				
				$date=safe_query("SELECT date FROM ".PREFIX."cup_matches WHERE matchID = '$matchID'");
				$da=mysql_fetch_array($date); 
				
			  	$date = date('l M dS Y \@\ g:i a', $da['date']);
			  							
				if($db['clan1'] == $clanID) 
					$gegner = getclanname2($db['clan2']); 
				else 
					$gegner = getclanname2($db['clan1']);
				$server = $db['server'];
				$hltv = $db['hltv'];
				
				$bg1 = BG_1;
				$bg2 = BG_2;
				
				$result=safe_query("SELECT * FROM ".PREFIX."cup_matches WHERE matchID = '$matchID'");
				$ds=mysql_fetch_array($result);
				
				if($ds['score1']>0 || $ds['score2']>0)
				die("A score for this match has already been entered!");
		
				
/* V4.1.4d */				
			    $notchecked = safe_query("SELECT * FROM ".PREFIX."cup_clans WHERE $name1 && clanID='$clanID' && 1on1='0' && checkin='0'");
		        $unchecked=mysql_num_rows($notchecked);
		        
		        if($unchecked) die('<div style="margin: 5px; padding: 6px; border: 4px solid #D6B4B4; text-align: center;"><b>You are not checked into this cup!</b> <img src="images/cup/error.png" width="16" height="16"><br> Please <a href="?site=cups&action=admins&'.$typename2.'='.$cupID.'">contact an admin</a> to check you in.</div>');
		        if($ds['einspruch']==1) die('<div style="margin: 5px; padding: 6px; border: 4px solid #D6B4B4; text-align: center;"><b>There is an open protest for this match!</b> <img src="images/cup/error.png" width="16" height="16"><br> Please wait or <a href="?site=cups&action=admins&'.$typename2.'='.$cupID.'">contact an admin</a> for details.</div>');					
/* EXIT */	
                if(isleader($userID,$db['clan1']) || isleader($userID,$db['clan2']))
			    if($_GET['laddID'] && time() <= $db['date'] && $report_after_date)
			       die('<center><b>Your gamedate is on '.date('l M dS Y \@\ g:i a', $db['date']).'</b><br>You may report your result after this date and time.<br><br>Current date/time: <b>'.date('l M dS Y \@\ g:i a').'</b></center>');
					
            if($_GET['laddID']) {
            
               $challenge_info = safe_query("SELECT * FROM ".PREFIX."cup_challenges WHERE chalID='".$db['matchno']."'");
               $ch=mysql_fetch_array($challenge_info); $map2 = $ch['map2_final'];
               
               if($_GET['clan1']==$db['clan1']) {
                  $score1 = 1;
                  $score2 = 2;
               }else{
                  $score1 = 2;
                  $score2 = 1;
               }
               
               if($ch['map4_final']) {
               
			     eval ("\$cupactions_score_head = \"".gettemplate("cupactions_score_head")."\";");
			     echo $cupactions_score_head;
			     
			       $maps = mapPic($ch['map1_final'],$_GET['laddID']);
			        $ladscore1 = "map1_score$score1";
			         $ladscore2 = "map1_score$score2";             
			        eval ("\$ladactions_score = \"".gettemplate("ladactions_score")."\";");
			       echo $ladactions_score;

			       $maps = mapPic($ch['map2_final'],$_GET['laddID']);
			        $ladscore1 = "map2_score$score1";
			         $ladscore2 = "map2_score$score2";              
			        eval ("\$ladactions_score = \"".gettemplate("ladactions_score")."\";");
			       echo $ladactions_score;

			       $maps = mapPic($ch['map3_final'],$_GET['laddID']);
			        $ladscore1 = "map3_score$score1";
			         $ladscore2 = "map3_score$score2";           
			        eval ("\$ladactions_score = \"".gettemplate("ladactions_score")."\";");
			       echo $ladactions_score;

			       $maps = mapPic($ch['map4_final'],$_GET['laddID']); 
			        $ladscore1 = "map4_score$score1";
			         $ladscore2 = "map4_score$score2";       
			        eval ("\$ladactions_score = \"".gettemplate("ladactions_score")."\";");
			       echo $ladactions_score;
			     
			     eval ("\$ladactions_report = \"".gettemplate("ladactions_report")."\";");
			     echo $ladactions_report;

               }elseif($ch['map3_final']) {
               
			     eval ("\$cupactions_score_head = \"".gettemplate("cupactions_score_head")."\";");
			     echo $cupactions_score_head;
               		     
			       $maps = mapPic($ch['map1_final'],$_GET['laddID']);
			        $ladscore1 = "map1_score$score1";
			         $ladscore2 = "map1_score$score2";             
			        eval ("\$ladactions_score = \"".gettemplate("ladactions_score")."\";");
			       echo $ladactions_score;

			       $maps = mapPic($ch['map2_final'],$_GET['laddID']);
			        $ladscore1 = "map2_score$score1";
			         $ladscore2 = "map2_score$score2";  
			        eval ("\$ladactions_score = \"".gettemplate("ladactions_score")."\";");
			       echo $ladactions_score;
			       
			       $maps = mapPic($ch['map3_final'],$_GET['laddID']); 
			        $ladscore1 = "map3_score$score1";
			         $ladscore2 = "map3_score$score2";           
			        eval ("\$ladactions_score = \"".gettemplate("ladactions_score")."\";");
			       echo $ladactions_score;
			     
			     eval ("\$ladactions_report = \"".gettemplate("ladactions_report")."\";");
			     echo $ladactions_report;

               }elseif($ch['map2_final']) {
               
			     eval ("\$cupactions_score_head = \"".gettemplate("cupactions_score_head")."\";");
			     echo $cupactions_score_head;
               
			       $maps = mapPic($ch['map1_final'],$_GET['laddID']);   
			        $ladscore1 = "map1_score$score1";
			         $ladscore2 = "map1_score$score2";      
			        eval ("\$ladactions_score = \"".gettemplate("ladactions_score")."\";");
			       echo $ladactions_score;

			       $maps = mapPic($ch['map2_final'],$_GET['laddID']);     
			        $ladscore1 = "map2_score$score1";
			         $ladscore2 = "map2_score$score2";   
			        eval ("\$ladactions_score = \"".gettemplate("ladactions_score")."\";");
			       echo $ladactions_score;
			     
			     eval ("\$ladactions_report = \"".gettemplate("ladactions_report")."\";");
			     echo $ladactions_report;
			    }
            }
            
            if(!$map2) {
		
                if(!$ds['inscribed'] || ($ds['score1']==0 && $ds['score2']==0)) {
					
	            if(time() >= $ds['date']) {

			eval ("\$cupactions_score = \"".gettemplate("cupactions_score")."\";");
			echo $cupactions_score;
			
		    }
		    else{
		    
		        echo '<font color="red"><strong>Your match date is for: '.date("d/m/Y H:i", $ds['date']).' - you cannot report until then.</strong></font>';
		    }
	      }
	      else{
	                echo getname1($ds['inscribed'],$cupID,0,$typename4).' has already inscribed!';
	      }
           }
	} 
     }
  }
 }else echo '<div style="margin: 5px; padding: 6px; border: 4px solid #D6B4B4; text-align: center;"><b>The cup or ladder has not started yet.</b> <img src="images/cup/error.png" width="16" height="16"><br> Please <a href="'.$link_typ.'">view details</a> on start times.</div>';	
 
//new ticket
}elseif(isset($_GET['action']) && $_GET['action'] == 'newticket'){

if($_POST['saveticket']) { 

  	$error_array = array();
  	if(empty($_POST['matchID']) && empty($_POST['department'])) $error_array[] = "You must select a department.";
	if(empty($_POST['subject'])) $error_array[] = "You must enter in a subject.";
    if(empty($_POST['desc'])) $error_array[] = "You must describe the ticket.";
	if(count($error_array)) 
	{
		$errors=implode('<br />&#8226; ', $error_array);
		echo '<div class="errorbox">
		  <b>Errors Occured:</b><br /><br />
		  &#8226; '.$errors.'
		</div>';

	}else{
	
        safe_query("INSERT INTO ".PREFIX."cup_tickets (`department`, `userID`, `subject`, `desc`, `time`, `status`) VALUES 
                                                      ('".$_POST['department']."',
                                                       '".$userID."',  
                                                       '".$_POST['subject']."',
                                                       '".$_POST['desc']."',
                                                       '".time()."',
                                                       '1')");
                                                
                                                     
      $inserted_ticketID = mysql_insert_id();
      redirect('?site=cupactions&action=mytickets&tickID='.$inserted_ticketID .'', 'Ticket successfully created!', 2);
  }                                             

}

echo '<a href="?site=cupactions&action=mytickets"><img src="images/cup/icons/goback.png" width="16" height="16"> My Tickets</a>';

 if(!$userID)
    die('You must be logged in to open a new ticket!');

    $departments = '<option selected value="">-- Select Department --</option>';
      $query = safe_query("SELECT ID FROM ".PREFIX."cup_departments");
        while($ds=mysql_fetch_array($query)) {
            $departments.='<option value="'.$ds['ID'].'">'.departmentname($ds['ID']).'</option>';
        }
  
     $type = '<select name="department">'.$departments.'</select>';

	eval ("\$newticket = \"".gettemplate("new_ticket")."\";");
	echo $newticket;
	
//view tickets
}elseif(isset($_GET['action']) && $_GET['action'] == 'mytickets'){

if(!$userID)
    echo 'You must be logged in to view or create tickets.';
    
 else{
    
    $ID = $_GET['tickID'];
    
    $ds=mysql_fetch_array(safe_query("SELECT * FROM ".PREFIX."cup_tickets WHERE ticketID='$ID'"));
    $db=mysql_fetch_array(safe_query("SELECT * FROM ".PREFIX."cup_matches WHERE matchID='".$ds['matchID']."'"));
    
    $league = league($ds['matchID']);
    
    $update = safe_query("SELECT * FROM ".PREFIX."comments WHERE type='ts' && parentID='".$ID."' ORDER BY commentID DESC");
    $tic = mysql_fetch_array($update); $num_rows = mysql_num_rows($update);
    
    $subject = getinput($ds['subject']);
    $date = date('l M dS Y \@\ g:i a', $ds['time']);
    $user = '<a href="index.php?site=profile&id='.$ds['userID'].'"><b>'.getnickname($ds['userID']).'</b></a>';
    $staff = ($ds['adminID'] ? '<a href="index.php?site=profile&id='.$ds['adminID'].'"><b>'.getnickname($ds['adminID']).'</b></a>' : "n/a");

    if($ds['matchID']) {
       $dm=mysql_fetch_array(safe_query("SELECT * FROM ".PREFIX."cup_matches WHERE matchID='".$ds['matchID']."'"));
       $desc = getname1($dm['clan1'],getleagueID($ds['matchID']),$ac=0,$league).' vs '.getname1($dm['clan2'],getleagueID($ds['matchID']),$ac=0,$league).' - <a href='.matchlink($ds['matchID']).'><img src="images/icons/foldericons/newhotfolder.gif"></a>';
    }else
       $desc = cleartext(stripslashes(str_replace(array('\r\n', '\n'),array("\n","\n" ), $ds['desc'])));

    if(!$num_rows)
        $updated_date = "(no update)";
    else{
        $updated_date = date('l M dS Y \@\ g:i a', $tic['date']);
        $updated_by = 'by '.(iscupadmin($userID) ? "admin" : "user").' <a href="?site=profile&id="'.$tic['userID'].'"><b>'.getnickname($tic['userID']).'</b></a>';
   }

         if($ds['status']==1)
            $status = $status_unreviewed;
         elseif($ds['status']==2)
            $status = $status_pending;
         elseif($ds['status']==3)
            $status = $status_onhold;
         elseif($ds['status']==4)
            $status = $status_waiting;
         elseif($ds['status']==5)
            $status = $status_resolved;         
         elseif($ds['status']==6)
            $status = $status_custom1;
         elseif($ds['status']==7)
            $status = $status_custom2;
            
    
if(valid_ticketer($_GET['tickID'],$userID)) {

 if($ds['status']!=5 && $user_close_ticket && $_GET['do']=="close") { 
   safe_query("UPDATE ".PREFIX."cup_tickets SET status='5' WHERE ticketID='".$_GET['tickID']."'");
   redirect('?site=cupactions&action=mytickets&tickID='.$_GET['tickID'].'', 'Ticket successfully closed!', 2);

}elseif($user_delete_ticket && $_GET['do']=="delete") {
   safe_query("DELETE FROM ".PREFIX."cup_tickets WHERE ticketID='".$_GET['tickID']."'");
   safe_query("DELETE FROM ".PREFIX."comments WHERE parentID='".$_GET['tickID']."' && type='ts'");
   redirect('?site=cupactions&action=mytickets', 'Ticket successfully deleted!', 2);

}  
    echo '<a href="?site=cupactions&action=mytickets"><img src="images/cup/icons/goback.png" width="16" height="16"> My Tickets</a>';
    
      if($ds['status']!=5 && $user_close_ticket) 
         $status.='<br><a href="?site=cupactions&action=mytickets&tickID='.$_GET['tickID'].'&do=close"><b>Close Ticket</b>';
      if($user_delete_ticket) 
         $status.='<br><a href="?site=cupactions&action=mytickets&tickID='.$_GET['tickID'].'&do=delete" onclick="return confirm(\'Are you sure you want to delete ticket? (you can not recover this!))\');"><b>Delete Ticket</b>';

         $type=($ds['matchID'] ? '&nbsp;Match Protest' : departmentname($ds['department']));
         $irc = '<a href="javascript:MM_openBrWindow(\'cup_chat/protest_chat.php?tickID='.$_GET['tickID'].'\',\'Protest Chat\',\'scrollbars=no,width=650,height=410\')"><img border="0" src="images/cup/icons/irc.png"></a>';
      
	  eval ("\$mytickets = \"".gettemplate("view_ticket")."\";");
	  echo $mytickets;
	  
              $lc=mysql_fetch_array(safe_query("SELECT * FROM ".PREFIX."comments WHERE parentID='".$ds['ticketID']."' && type='ts' ORDER BY date DESC LIMIT 0,1"));
              $autoclose = time()-$ticket_autoclose_time;
            
              if(!$lc['date'] && $ds['time'] <= $autoclose && in_array($ds['status'],$only_autoclose_ticket)) 
                 safe_query("UPDATE ".PREFIX."cup_tickets SET status = '$ticket_autoclose_status' WHERE ticketID='".$ds['ticketID']."'"); 
              elseif($lc['date'] && $lc['date'] <= $autoclose && in_array($ds['status'],$only_autoclose_ticket))
                 safe_query("UPDATE ".PREFIX."cup_tickets SET status = '$ticket_autoclose_status' WHERE ticketID='".$ds['ticketID']."'");           
	  
		$parentID = $ds['ticketID'];
		$comments_allowed = 4;
		$type = "ts";
		$referer = "index.php?site=cupactions&action=mytickets&tickID=$_GET[tickID]";

		include("comments.php");	

}else{

  if(isset($_GET['tickID']) && $_GET['tickID']) echo "<center><b><img src='images/cup/error.png' width='16' height='16'> Sorry, invalid ticket! (<a href='?site=cupactions&action=mytickets'>go back</a>)</b></center><br><br>";
    
     echo '<a href="?site=cupactions&action=newticket"><img src="images/cup/icons/addresult.gif" width="16" height="16"> <strong>Create a Ticket</b></a>';
     
      $tickets_gp = safe_query("SELECT * FROM ".PREFIX."cup_tickets GROUP BY department"); 
      
        while($tgp=mysql_fetch_array($tickets_gp)) {
	
          echo '<table width="100%" cellspacing="'.$cellspacing.'" border="0" cellspacing="'.$cellspacing.'" bgcolor="'.$border.'">
                <tr>
                  <td class="title" align="center" colspan="4">Tickets for department '.departmentname($tgp['department']).'</td>
                </tr>';
     
      $order_tickets = ($order_by ? "ORDER BY updated DESC" : "ORDER BY time DESC");
      $tickets = safe_query("SELECT * FROM ".PREFIX."cup_tickets WHERE department='".$tgp['department']."' $order_tickets");
      
        if(!mysql_num_rows($tickets)) { $no_rows = '<tr><td colspan="4" align="center" bgcolor="'.$bg1.'">-- No tickets --</td></tr>'; }
        
          echo '
                <tr>
                  <td class="title2" width="25%" align="center">Created</td>
                  <td class="title2" width="25%" align="center">Subject</td>
                  <td class="title2" width="25%" align="center">Status</td>
                  <td class="title2" width="5%" align="center">Details</td>
                </tr>'.$no_rows;

          while($ds=mysql_fetch_array($tickets)) {
          
	    if(!valid_ticketer($ds['ticketID'],$userID)) $no_inner_rows = 1;
            if(!valid_ticketer($ds['ticketID'],$userID)) continue;
            
              $lc=mysql_fetch_array(safe_query("SELECT * FROM ".PREFIX."comments WHERE parentID='".$ds['ticketID']."' && type='ts' ORDER BY date DESC LIMIT 0,1"));
              $autoclose = time()-$ticket_autoclose_time;
            
              if(!$lc['date'] && $ds['time'] <= $autoclose && in_array($ds['status'],$only_autoclose_ticket)) 
                 safe_query("UPDATE ".PREFIX."cup_tickets SET status = '$ticket_autoclose_status' WHERE ticketID='".$ds['ticketID']."'"); 
              elseif($lc['date'] && $lc['date'] <= $autoclose && in_array($ds['status'],$only_autoclose_ticket))
                 safe_query("UPDATE ".PREFIX."cup_tickets SET status = '$ticket_autoclose_status' WHERE ticketID='".$ds['ticketID']."'");  
              
              
              $date = date('D, dS M Y', $ds['time']);
              $department = departmentname($ds['department']);
                
              if($ds['status']==1)
                 $status = $status_unreviewed;
              elseif($ds['status']==2)
                 $status = $status_pending;
              elseif($ds['status']==3)
                 $status = $status_onhold;
              elseif($ds['status']==4)
                 $status = $status_waiting;
              elseif($ds['status']==5)
                 $status = $status_resolved;         
              elseif($ds['status']==6)
                 $status = $status_custom1;
              elseif($ds['status']==7)
                 $status = $status_custom2;

          	  eval ("\$mytickets = \"".gettemplate("ticketlist")."\";");
	          echo $mytickets;
      
          }
	  
	  if($no_inner_rows) { echo '<tr><td bgcolor="'.$bg2.'" colspan="4" align="center">-- No tickets for this department --</td></tr><br>'; }
     }
     
      
          
      echo "</table>";
          
    }
 }
// Protest
}elseif(isset($_GET['action']) && $_GET['action'] == 'protest'){
	$clanID = isset($_GET['clanID']) ? $_GET['clanID'] : 0;
	$matchID = $_GET['matchID'];
	
	eval ("\$title_cup = \"".gettemplate("title_cup")."\";");
	echo $title_cup;
	
  $dm=mysql_fetch_array(safe_query("SELECT * FROM ".PREFIX."cup_matches WHERE matchID='".$matchID."'"));
  
  if(!$dm['einspruch']) {
	
/* V5 PROTEST TICKETS */	
	
     $array = array('a','b','c','d','e','f','g','h');
     
     if(in_array($dm['cupID'],$array) || in_array($dm['ladID'],$array)) {
        $leagueID = $dm['matchno'];
        $type_league = (!$dm['cupID'] ? "ladID" : "cupID");
     }elseif($dm['cupID']) {
        $leagueID = $dm['cupID'];
        $type_league = "cupID";
     }elseif($dm['ladID']) {
        $leagueID = $dm['ladID'];
        $type_league = "ladID";
	}
        safe_query("INSERT INTO ".PREFIX."cup_tickets (`department`, `userID`, `$type_league`, `matchID`, `subject`, `time`, `updated`, `status`) VALUES 
                                                       ('0',
                                                        '".$userID."', 
                                                        '".$leagueID."', 
                                                        '".$matchID."', 
                                                        'Match Protest',
                                                        '".time()."',
                                                        '".time()."',
                                                        '1')");
                                                        
/* END V5 PROTEST TICKETS */	
	
	if($typename3($cupID)){
		if(safe_query("UPDATE `".PREFIX."cup_matches` SET `einspruch` = '1' WHERE `".PREFIX."cup_matches`.`matchID` = '$matchID' && `".PREFIX."cup_matches`.`einspruch` = '0' && `".PREFIX."cup_matches`.`confirmscore` = '0' AND (`".PREFIX."cup_matches`.`clan1` = '$userID' || `".PREFIX."cup_matches`.`clan2` = '$userID')")){
			echo '<center><br /><br /><br /><br /><b>'.$_language->module['protest'];
			
			$ergebnis = safe_query("SELECT userID FROM ".PREFIX."user_groups WHERE cup='1' || super='1'");
	 		while($ds = mysql_fetch_array($ergebnis))
				$touser[] = $ds['userID'];

			$data=safe_query("SELECT * FROM ".PREFIX."cup_matches WHERE matchID = '$matchID'");
			$db=mysql_fetch_array($data);
			
				$date=safe_query("SELECT date FROM ".PREFIX."cup_matches WHERE matchID = '$matchID'");
				$da=mysql_fetch_array($date); 
				
				$date = date('l M dS Y \@\ g:i a', $da['date']);
			
			if($db['clan1'] == $userID)
				$clanID2 = $db['clan2'];
			else
				$clanID2 = $db['clan1'];
			
			/*$message = $_language->module['protest_message'].' 
						Cup: '.getcupname($cupID).'
						Nick: '.getnickname($userID).'
						Match: '.getnickname($userID).' '.$_language->module['versus'].' '.getnickname($clanID2).'
						'.$_language->module['game'].': '.$matchID.' 
						
						[url=admin/matches.php?action=edit&match='.$db['matchno'].'&'.$typename2.'='.$cupID.'&extern=1]'.$_language->module['edit_match'].'[/url]'; */
					
					
			foreach($touser as $id)
    			sendmessage($id, 'Match Protest', 'There has been a recent protest, view the ticket at admincenter.');		
		}
	}else{	
		if(isleader($userID, $clanID)){	
			if(safe_query("UPDATE `".PREFIX."cup_matches` SET `einspruch` = '1' WHERE `".PREFIX."cup_matches`.`matchID` = '$matchID' && `".PREFIX."cup_matches`.`einspruch` = '0' && `".PREFIX."cup_matches`.`confirmscore` = '0' && (`".PREFIX."cup_matches`.`clan1` = '$clanID' || `".PREFIX."cup_matches`.`clan2` = '$clanID')")){
				echo '<center><br /><br /><br /><br /><b>'.$_language->module['protest'];

				$ergebnis = safe_query("SELECT userID FROM ".PREFIX."user_groups WHERE cup='1' || super='1'");
		 		while($ds = mysql_fetch_array($ergebnis))
					$touser[] = $ds['userID'];
		 		
				$data=safe_query("SELECT * FROM ".PREFIX."cup_matches WHERE matchID = '$matchID'");
				$db=mysql_fetch_array($data);
				
				$date=safe_query("SELECT date FROM ".PREFIX."cup_matches WHERE matchID = '$matchID'");
				$da=mysql_fetch_array($date); 
				
                $date = date('l M dS Y \@\ g:i a', $da['date']);
				
				if($db['clan1'] == $clanID)
					$clanID2 = $db['clan2'];
				else
					$clanID2 = $db['clan1'];
				
				/*$message = $_language->module['protest_message'].'  
							Nick: '.getnickname($userID).'
							Cup: '.getcupname($cupID).'
							Match: '.getclanname2($clanID).' '.$_language->module['versus'].' '.getclanname2($clanID2).'
							'.$_language->module['game'].': '.$matchID.'
						
							[url=admin/matches.php?action=edit&match='.$db['matchno'].'&'.$typename2.'='.$cupID.'&extern=1]'.$_language->module['edit_match'].'[/url]'; */

				foreach($touser as $id)
	    			sendmessage($id, 'Match Protest', 'There has been a recent protest, view the ticket at admincenter.');
			  }		
	 	   }
	    }
     }
  }echo ($cpr ? ca_copyr() : die()); 
?>