<link href="cup.css" rel="stylesheet" type="text/css">
<style>
.show_hide_gs, .slidingDiv_dt  {
	display:none;
}
</style>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.js" type="text/javascript"></script>
<script type="text/javascript">

$(document).ready(function(){
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
/* Cup SQL-Querys */
safe_query("UPDATE ".PREFIX."cups SET status='2' WHERE start<='".time()."'");
safe_query("UPDATE ".PREFIX."cups SET status='3' WHERE ende<='".time()."'");
safe_query("UPDATE ".PREFIX."cup_warnings SET expired='1' WHERE deltime<='".time()."'");
/**************/

$name_type = "Cup";
$typ_e = "typ";

include ("config.php");

$bg1=BG_1;
$bg2=BG_1;
$bg3=BG_1;
$bg4=BG_1;
$cpr=ca_copyr();

//date and timezone

getcuptimezone();
$gmt = getcuptimezone(1);
$gmt.= " (Now: ".date("dS - H:i").")";

//automation functions

randomize_brackets($_GET['cupID']);
tournament_winners($_GET['cupID']);
qualifiersToLeague($_GET['cupID']);
match_query_type();

if(!$cpr || !ca_copyr()) die();
if(isset($_GET['action'])){
	if($_GET['action'] == 'details'){
		$cupID = mysql_escape_string($_GET['cupID']);
		$ergebnis = safe_query("SELECT * FROM ".PREFIX."cups WHERE ID = '".$cupID."'");
		$ds=mysql_fetch_array($ergebnis);
		$wn=mysql_fetch_array(safe_query("SELECT * FROM ".PREFIX."cup_baum WHERE cupID='$cupID'"));
		
		if(in_array($ds['maxclan'],$maxclan_array))
		   $final_match = $ds['maxclan'];
		else 
		   $final_match = $ds['maxclan']-1;

		   $dm=mysql_fetch_array(safe_query("SELECT * FROM ".PREFIX."cup_matches WHERE matchno='$final_match' AND cupID='$cupID'"));
		   $second_place=($dm['score1'] > $dm['score2'] ? getname1($dm['clan2'],$cupID,$ac=0,$var='cup') : getname1($dm['clan1'],$cupID,$ac=0,$var='cup'));

	
		$start = '<a name="start">'.date('l M dS Y \@\ g:i a', $ds['start']).'</a>';
		$ende = date('l M dS Y \@\ g:i a', $ds['ende']);	
		$start_gs = date('l M dS Y \@\ g:i a', $ds['gs_start']);
		$end_gs = date('l M dS Y \@\ g:i a', $ds['gs_end']); 
		
		if($wn['wb_winner']) {
                   $first_name = getname1($wn['wb_winner'],$cupID,0,'cup');
                }
                else{
                   $first_name = 'n/a';
                }

		if($wn['second_winner']) {
		   $second_name = getname1($wn['second_winner'],$cupID,0,'cup');
		}
		else{
		   $second_name = 'n/a';
		}
		
		if($wn['third_winner']) {
		   $third_name = getname1($wn['third_winner'],$cupID,0,'cup');
		}
		else{
		   $third_name = 'n/a';
		}
		
		if($wn['wb_winner'] || $wn['second_winner'] || $wn['third_winner']) 
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
            </table>';
		
		
		$cupdesc = htmloutput(toggle($ds['desc'], 1));
		$status = $ds['status'];
		$checkin = $ds['checkin'];
		$checkindate = date('H:i', ($ds['start']-($ds['checkin']*60))).'';
		$checkintime = $ds['start']-($ds['checkin']*60);
		
	$rl_checkin_date = '	
	<tr>
		<td bgcolor="'.$bg1.'"><img src="images/cup/icons/status.png"> <strong>Checkin Begins</strong></td>
		<td bgcolor="'.$bg1.'">'.date('l M dS Y \@\ g:i a', $checkintime).'</td>
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
	
		$ergebnis = safe_query("SELECT count( ID ) as clans FROM ".PREFIX."cup_clans WHERE cupID = '".$cupID."'");
		$db = mysql_fetch_array($ergebnis);
		
		$ergebnis2 = safe_query("SELECT count( ID ) as clans2 FROM ".PREFIX."cup_clans WHERE cupID = '".$cupID."' && checkin = '1'");
		$dd = mysql_fetch_array($ergebnis2);
		
		$ergebnis2 = safe_query("SELECT count( ID ) as clans3 FROM ".PREFIX."cup_clans WHERE groupID = '".$cupID."'");
		$dd3 = mysql_fetch_array($ergebnis2);	   
	   
		$ergebnis2 = safe_query("SELECT count( ID ) as clans4 FROM ".PREFIX."cup_clans WHERE groupID = '".$cupID."' && checkin = '1'");
		$dd4 = mysql_fetch_array($ergebnis2);
		
		if($ds['maxclan'] == 80 || $ds['maxclan']== 8)
			$max = 8;
		elseif($ds['maxclan'] == 160 || $ds['maxclan']== 16)
			$max = 16;
		elseif($ds['maxclan'] == 320 || $ds['maxclan']== 32)
			$max = 32;
		elseif($ds['maxclan'] == 640 || $ds['maxclan']== 64)
			$max = 64;
		
		if(is1on1($cupID)) 
			$members = $db['clans'].' / '.$max.'  (<a href="?site=clans&cupID='.$cupID.'">All players</a>)';
			
		else 
			$members = $db['clans'].' / '.$max.'  (<a href="?site=clans&cupID='.$cupID.'">All Teams</a>)';
			
                $group_max = $max+$max;

		$members2 = $dd['clans2'].' / '.$max;
		$members3 = $dd3['clans3'].' / '.$group_max;
		$members4 = $dd4['clans4'].' / '.$group_max;	

        if(is1on1($cupID)) $participants = 'Players';
		else $participants = 'Teams';
		
		$members2 = $dd['clans2'].' / '.$max;
		
		$gameacc_sql = safe_query("SELECT * FROM ".PREFIX."gameacc WHERE gameaccID='".$ds['gameaccID']."'");
		$dv = mysql_fetch_array($gameacc_sql);
		$gameacc = $dv['type'];
		
		$getname = safe_query("SELECT * FROM ".PREFIX."cups WHERE ID='$cupID'");
	    while($dd = mysql_fetch_array($getname)) 
	    $cupname = getcupname($cupID);
	  	    
        include ("title_cup.php");
        
		eval ("\$title_cup = \"".gettemplate("title_cup")."\";");
		echo $title_cup;
		
	$show_prts = "
	<tr>
		<td bgcolor='$bg1'><img id='myImage' src='images/cup/icons/yourteams.png' width='16' height='16'> <strong>Participants</strong></td>
	        <td bgcolor='$bg1'>$members</td>
	</tr>
	<tr>
		<td bgcolor='$bg1'><img src='images/cup/icons/yourteams.png' width='16' height='16'> <strong>Checked-In</strong></td>
		<td bgcolor='$bg1'>$members2</td>
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
    }else{
      if($ds['ratio_low'] || $ds['ratio_high']) { 
           $ratio_level = 'Ratio: '.$ds['ratio_low'].'%-'.$ds['ratio_high'].'%'; 
        }else $ratio_level = $none; 
      }
    }
  }  
      
        if(in_array($ds['maxclan'],$maxclan_array))
           $elimination_type = "- Double Elimination";
        else
           $elimination_type = "- Single Elimination";
	   
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
          $to_qualify = 'Gain most XP from your matches against all '.(is1on1($cupID) ? "players" : "teams").' in your group';   
   } 
   
   $alpha_groups = "cupID='a' || cupID='b' || cupID='c' || cupID='d' || cupID='e' || cupID='f' || cupID='g' || cupID='h'";
   $dt=mysql_fetch_array(safe_query("SELECT count(*) as totalm FROM ".PREFIX."cup_matches WHERE matchno='$cupID' && ($alpha_groups) && ladID='0' && confirmscore='1' && einspruch='0'"));    

   if($ds['gs_staging']==1)
      $total_matches = $max*4;
   else
      $total_matches = $max;
   
  if($ds['gs_start'] || $ds['gs_end']) {
  
      	$show_gs_info = '
			<tr>
			  <td class="title2" colspan="2"><img src="images/cup/icons/support.png" width="16" height="16"> Qualifying in <a href="?site=groups&cupID='.$ds['ID'].'">group league</a> will automatically place you in the play-off brackets.</td>
			</tr>
			<tr>
			  <td bgcolor="'.$pagebg.'" colspan="2"></td>
			</tr>';
  
     $groups_table = '<table width="100%" cellspacing="'.$cellspacing.'" cellpadding="'.$cellpadding.'" bgcolor="'.$border.'">
	                <tr>
		          <td class="title" colspan="2"><a href="?site=groups&cupID='.$cupID.'"><img src="images/cup/icons/groups.png" border="0"></a> Group Stages <a href="#gs" name="gs" class="show_hide_gs"><img src="images/cup/icons/arrow_up_down.gif" align="right"></a></td>
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
             <td bgcolor="'.$bg1.'">'.($ds['gs_dxp'] ? "Matches won only will count towards your XP" : "Mathches won and lost will count towards your XP").'</td>
        </tr>
	<tr>
		<td bgcolor="'.$bg1.'"><img src="images/cup/icons/checkin.png"> <strong>Status</strong></td>
		<td bgcolor="'.$bg1.'">'.$gs_status.'</td>
	</tr>
	<tr>
	        <td bgcolor="'.$bg1.'"><img src="images/cup/icons/status.png"> <strong>Link</strong></td>
		<td bgcolor="'.$bg1.'"><input type="button" value="Group Stages" onClick="window.location=\'?site=groups&cupID='.$ds['ID'].'\'"></td>
	</tr>
      </table>
    </div>
    <br />';
  
  }
  
  $wi=mysql_fetch_array(safe_query("SELECT * FROM ".PREFIX."cup_baum WHERE cupID='$cupID'"));
  
  if($wi['wb_winner'] && $wi['second_winner'] && $wi['third_winner'] && $ds['status']==3) {
  
     $l_ended = '
	<tr>
		<td bgcolor="'.$bg1.'"><img src="images/cup/icons/date.png"> <strong>Ended</strong></td>
		<td bgcolor="'.$bg1.'">'.$ende.'</td>
	</tr>';
  }
  
  $c_admins = safe_query("SELECT * FROM ".PREFIX."cup_admins WHERE cupID='$cupID'");
  $admin_rows = mysql_num_rows($c_admins);
  
  if($admin_rows) {
   
     $cups_s_admins = '
  
        <tr>
		<td bgcolor="'.$bg1.'"><img src="images/cup/icons/admin.png"> <strong>Admins</strong></td>
		<td bgcolor="'.$bg1.'"><a href="?site=cups&action=admins&cupID='.$cupID.'">'.$admin_rows.' Admin'.($admin_rows==1 ? '' : 's').'</a></td>
	</tr>';
  }
        	
		eval ("\$cup_details = \"".gettemplate("cup_details")."\";");
		echo $cup_details; 
		
}elseif($_GET['action'] == 'tree'){
		$cupID = mysql_escape_string($_GET['cupID']);
		$ergebnis = safe_query("SELECT * FROM ".PREFIX."cups WHERE ID = '".$cupID."'");
		$ds = mysql_fetch_array($ergebnis);

                redirect('?site=brackets&action=tree&cupID='.$cupID, '', 0);

}elseif($_GET['action'] == 'admins'){
		$cupID = mysql_escape_string($_GET['cupID']);

		if(is1on1($cupID)) $participants = 'Players';
		else $participants = 'Teams';
		
                include ("title_cup.php");
		
		eval ("\$title_cup = \"".gettemplate("title_cup")."\";");
		echo $title_cup;
		
		$admin_sql=safe_query("SELECT * FROM ".PREFIX."cup_admins WHERE cupID='$cupID'");
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
		echo $inctemp; echo base64_decode(''); 
}elseif($_GET['action'] == 'regeln'){
	$cupID = $_GET['cupID'];
	
	$getname = safe_query("SELECT * FROM ".PREFIX."cups WHERE ID='$cupID'");
	while($dd = mysql_fetch_array($getname)) 
	$cupname = getcupname($cupID);
	
	if(is1on1($cupID)) $participants = 'Players';
	else $participants = 'Teams';
	
    include ("title_cup.php");
	
	eval ("\$title_cup = \"".gettemplate("title_cup")."\";");
	echo $title_cup;
	
	$ergebnis = safe_query("SELECT * FROM ".PREFIX."cup_rules WHERE cupID= '".$cupID."'");
	if(!mysql_num_rows($ergebnis)){
		echo '<br /><br /><center><b>There were no rules yet registered!</b></center><br /><br />Please try again later!<br /><br />';
		echo $inctemp; echo base64_decode('');
	}else{
			$dd=mysql_fetch_array($ergebnis);
			
			if(empty($dd[value])){
				echo '<br /><br /><center><b>There were no rules yet registered!</b></center><br /><br />Please try again later!<br /><br />';
				echo $inctemp; echo base64_decode('');
			}else{
			
				echo '<table width="100%" cellspacing="'.cellspacing.'" cellpadding="'.$cellpadding.'" bgcolor="'.$border.'">
				  <tr> 
					<td bgcolor="'.$bghead.'" class="title">Rules:</td>
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
}else{
	
	eval ("\$title_cups= \"".gettemplate("title_cups")."\";");
	echo $title_cups;
	
	eval ("\$cups_head = \"".gettemplate("cups_head")."\";");
	echo $cups_head;
	$ergebnis = safe_query("SELECT * FROM ".PREFIX."cups ORDER BY ID DESC");
	$n=1;
	while($ds=mysql_fetch_array($ergebnis)) {
		
		if($n%2){
			$bg1=BG_1;
			$bg2=BG_2;
		}else{
			$bg1=BG_3;
			$bg2=BG_4;
		}
		
		$fa=mysql_fetch_array(safe_query("SELECT * FROM ".PREFIX."cup_agents WHERE cupID='".$ds['ID']."' && ladID='0'"));
		$cm=mysql_fetch_array(safe_query("SELECT * FROM ".PREFIX."cup_matches WHERE cupID='".$ds['ID']."' && ladID='0'"));
		
		if(is_array($fa)) 
		   $free_agents = '<a href="?site=freeagents&action=view&cupID='.$ds['ID'].'"><img src="images/cup/icons/freeagents.png" align="right" width="16" height="16"></a>';
		
		if(is_array($cm)) 
		   $matches_exist = '<a href="?site=matches&action=viewmatches&cupID='.$ds['ID'].'"><img src="images/cup/icons/add_result.gif" align="right" width="16" height="16"></a>';
		
		if($ds['gs_start'] || $ds['gs_end'])
		   $group_stages = '<a href="?site=groups&cupID='.$ds['ID'].'"><img src="images/cup/icons/groups.png" align="right" width="16" height="16"></a>';
		else
                   $group_stages = '';		
				
		$game='<img src="images/games/'.$ds[game].'.gif" width="20" height="20" border="0">';
		$name = '<a href="?site=cups&action=details&cupID='.$ds['ID'].'">'.$ds['name'].'</a>';
		$status = $ds['status'];
		$checkintime=$ds['start']-($ds['checkin']*60);
		    if($status == 1){
		    	if(time() >= $checkintime) {
		    		$status = 'Signup phase (Check-In)';
				$signup = '<img src="images/cup/icons/go_sel.gif"> <a href="?site=quicknavi&cup='.getalphacupname($ds['ID']).'">Signup</a>';
		    	}
			else{
		    		$status = 'Signup phase';
				$signup = '<img src="images/cup/icons/go_sel.gif"> <a href="?site=quicknavi&cup='.getalphacupname($ds['ID']).'">Signup</a>';
			}
		    }
		    elseif($status == 2) {
		     	$status = 'Started';
			$signup = '<img src="images/cup/icons/go.png"> <s>Signup</s>';
		    }
		    elseif($status == 3) {
		    	$status = 'Closed';
			$signup = '<img src="images/cup/icons/go.png"> <s>Signup</s>';
		    }
		
		$typ = $ds['typ'];
		$matches = '<a href="?site=matches&action=viewmatches&cupID='.$ds['ID'].'"><img src="images/cup/icons/add_result.gif" border="0"></a>';
		$detail = '<a href="?site=cups&action=details&cupID='.$ds['ID'].'"><img src="images/icons/foldericons/folder.gif" width="14" height="15" border="0"></a>';
		
		
		if(strlen($desc)>20) {
			$desc=substr($desc, 0, 20);
			$desc.='..';
		}	
		
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
    
// echo template
	
		eval ("\$cups_content = \"".gettemplate("cups_content")."\";");
		echo $cups_content;
		$n++;
	}
	eval ("\$inctemp = \"".gettemplate("cups_foot")."\";");

  }echo $inctemp.($cpr ? ca_copyr() : die());
?>