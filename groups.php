<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){
        $(".slidingDiv_wk").hide();
        $(".show_hide_wk").show();
	
	$('.show_hide_wk').click(function(){
	$(".slidingDiv_wk").slideToggle();
	});
</script>

<?php include("config.php"); ?>
<head>
    <link href="cup.css" rel="stylesheet" type="text/css">
    <script language="javascript">wmtt = null; document.onmousemove = updateWMTT; function updateWMTT(e) { x = (document.all) ? window.event.x + document.body.scrollLeft : e.pageX; y = (document.all) ? window.event.y + document.body.scrollTop  : e.pageY; if (wmtt != null) { wmtt.style.left=(x + 20) + "px"; wmtt.style.top=(y + 20) + "px"; } } function showWMTT(id) {	wmtt = document.getElementById(id);	wmtt.style.display = "block" } function hideWMTT() { wmtt.style.display = "none"; }</script>
    <div class="tooltip" id="no_score" align="left"><img src="images/cup/icons/faq.png"> Neither participant has inscribed a result yet.</div>
    <div class="tooltip" id="waiting_confirmation" align="left"><img src="images/cup/icons/pending.gif"> Awaiting score confirmation</div>
    <div class="tooltip" id="waiting_player2" align="left"><img src="images/cup/icons/pending.gif"> Waiting for player 2 registration</div>
</head>

<?php
/* Addon SQL-Querys */
// safe_query("UPDATE ".PREFIX."cup_ladders SET status='2' WHERE start<='".time()."'");
// safe_query("UPDATE ".PREFIX."cup_ladders SET status='3' WHERE end<='".time()."'");
// safe_query("UPDATE ".PREFIX."cup_ladders SET status='1' WHERE gs_start<='".time()."' && gs_end<='".time()."'");
safe_query("UPDATE ".PREFIX."cups SET status='1' WHERE gs_start<='".time()."' && gs_end<='".time()."'");
safe_query("UPDATE ".PREFIX."cups SET status='2' WHERE start<='".time()."'");
safe_query("UPDATE ".PREFIX."cups SET status='3' WHERE ende<='".time()."'");
safe_query("UPDATE ".PREFIX."cup_warnings SET expired='1' WHERE deltime<='".time()."'");
/**************/

$bg1=BG_1;
$bg2=BG_1;
$bg3=BG_1;
$bg4=BG_1;
$cpr=ca_copyr();

//language
$_language->read_module('groups');

$style3 = '
    
    </td>
  </tr>
 </table>';

  if(isset($_GET['cupID']))
  {
  
    getcuptimezone();
  
    if(is1on1($_GET['cupID'])) $participants = 'Players';	
    else $participants = 'Teams';
  
    include("title_cup.php");
    eval ("\$t_cup = \"".gettemplate("title_cup")."\";");
    echo $t_cup;
  
    $ID = $_GET['cupID'];
    $info = safe_query("SELECT * FROM ".PREFIX."cups WHERE ID='$ID'");
    $type = "cupID";
    $type_opp = "ladID";
    $type2 = "cupID";
    $name = "Cup";
    $name2 = "is1on1";
    $name3 = getcupname($ID);
    $details_link = "?site=cups&action=details&cupID=$ID";
    $t_dxp = "gs_dxp";
    
  }
  else{
  
    getladtimezone();
  
    include("title_ladder.php");
  
    $ID = $_GET['laddID'];
    $info = safe_query("SELECT * FROM ".PREFIX."cup_ladders WHERE ID='$ID'");
    $type = "ladID";
    $type_opp = "cupID";
    $type2 = "laddID";
    $name = "Ladder";
    $name2 = "ladderis1on1";
    $name3 = getladname($ID);
    $plat = "`platID`,";
    $platID = '\''.getplatID($ID).'\',';
    $details_link = "?site=ladders&ID=$ID";
    $t_dxp = "d_xp";
  }
  
    $ds = mysql_fetch_array($info);
    
    $start = date('l M dS Y \@\ g:i a', $ds['gs_start']);

  if($ID) {
  
  qualifiersToLeague($ID);
 
  if(!$cpr || !ca_copyr()) die();
  
  //count group participants
  
    $alpha_groups = "type='gs'";
  
        $participant_ent = safe_query("SELECT $type FROM ".PREFIX."cup_clans WHERE groupID='".$ID."' AND ($alpha_groups) AND $type_opp='0'");
        $ent_part = mysql_num_rows($participant_ent);
		
		$rowsa = safe_query("SELECT $type FROM ".PREFIX."cup_clans WHERE groupID='".$ID."' AND $type='a' AND $type_opp='0'");
		$a_rows = mysql_num_rows($rowsa);

		$rowsb = safe_query("SELECT $type FROM ".PREFIX."cup_clans WHERE groupID='".$ID."' AND $type='b' AND $type_opp='0'");
		$b_rows = mysql_num_rows($rowsb);
		
		$rowsc = safe_query("SELECT $type FROM ".PREFIX."cup_clans WHERE groupID='".$ID."' AND $type='c' AND $type_opp='0'");
		$c_rows = mysql_num_rows($rowsc);
		
		$rowsd = safe_query("SELECT $type FROM ".PREFIX."cup_clans WHERE groupID='".$ID."' AND $type='d' AND $type_opp='0'");
		$d_rows = mysql_num_rows($rowsd);
		
		$rowse = safe_query("SELECT $type FROM ".PREFIX."cup_clans WHERE groupID='".$ID."' AND $type='e' AND $type_opp='0'");
		$e_rows = mysql_num_rows($rowse);
		
		$rowsf = safe_query("SELECT $type FROM ".PREFIX."cup_clans WHERE groupID='".$ID."' AND $type='f' AND $type_opp='0'");
		$f_rows = mysql_num_rows($rowsf);
		
		$rowsg = safe_query("SELECT $type FROM ".PREFIX."cup_clans WHERE groupID='".$ID."' AND $type='g' AND $type_opp='0'");
		$g_rows = mysql_num_rows($rowsg);
		
		$rowsh = safe_query("SELECT $type FROM ".PREFIX."cup_clans WHERE groupID='".$ID."' AND $type='h' AND $type_opp='0'");
		$h_rows = mysql_num_rows($rowsh);
		
//count group matches	

		$match_a_rows = safe_query("SELECT $type FROM ".PREFIX."cup_matches WHERE matchno='".$ID."' AND $type='a' AND $type_opp='0'");
		$rows_a = mysql_num_rows($match_a_rows);
		
		$match_b_rows = safe_query("SELECT $type FROM ".PREFIX."cup_matches WHERE matchno='".$ID."' AND $type='b' AND $type_opp='0'");
		$rows_b = mysql_num_rows($match_b_rows);
		
		$match_c_rows = safe_query("SELECT $type FROM ".PREFIX."cup_matches WHERE matchno='".$ID."' AND $type='c' AND $type_opp='0'");
		$rows_c = mysql_num_rows($match_c_rows);
		
		$match_d_rows = safe_query("SELECT $type FROM ".PREFIX."cup_matches WHERE matchno='".$ID."' AND $type='d' AND $type_opp='0'");
		$rows_d = mysql_num_rows($match_d_rows);
		
		$match_e_rows = safe_query("SELECT $type FROM ".PREFIX."cup_matches WHERE matchno='".$ID."' AND $type='e' AND $type_opp='0'");
		$rows_e = mysql_num_rows($match_e_rows);
		
		$match_f_rows = safe_query("SELECT $type FROM ".PREFIX."cup_matches WHERE matchno='".$ID."' AND $type='f' AND $type_opp='0'");
		$rows_f = mysql_num_rows($match_f_rows);
		
		$match_g_rows = safe_query("SELECT $type FROM ".PREFIX."cup_matches WHERE matchno='".$ID."' AND $type='g' AND $type_opp='0'");
		$rows_g = mysql_num_rows($match_g_rows);
		
		$match_h_rows = safe_query("SELECT $type FROM ".PREFIX."cup_matches WHERE matchno='".$ID."' AND $type='h' AND $type_opp='0'");
		$rows_h = mysql_num_rows($match_h_rows);
		
		$match_a_rows_c = safe_query("SELECT $type FROM ".PREFIX."cup_matches WHERE matchno='".$ID."' AND $type='a' AND confirmscore='1' AND einspruch='0'");
		$rows_ac = mysql_num_rows($match_a_rows_c);
		
//check what group
	
    if($ds['maxclan']==8 || $ds['maxclan']==80) { 
	
      if($a_rows == $per_group_8) {
         $random_group = 'b';
         $dd_groups = '<option value="b">Group B</option>';
      }elseif($b_rows == $per_group_8) {
         $dd_groups = '<option value="a">Group A</option>';
         $random_group = 'a';
      }else{ 
         $dd_groups = '<option value="a">Group A</option><option value="b">Group B</option>';
         $groups = array ('a','b');
         $random_group = $groups[rand(0,1)];
      }
      
    }
    elseif($ds['maxclan']==16 || $ds['maxclan']==160) {
  
      if($a_rows == $per_group_16) {
         $dd_groups = '<option value="b">Group B</option><option value="c">Group C</option><option value="d">Group D</option>';
         $groups = array ('b','c','d');
         $random_group = $groups[rand(0,2)];
      }elseif($b_rows == $per_group_16) {
         $dd_groups = '<option value="A">Group a</option><option value="c">Group C</option><option value="d">Group D</option>';
         $groups = array ('a','c','d');
         $random_group = $groups[rand(0,2)];
      }elseif($c_rows == $per_group_16) {
         $dd_groups = '<option value="a">Group A</option><option value="b">Group B</option><option value="d">Group D</option>';
         $groups = array ('a','b','d');
         $random_group = $groups[rand(0,2)];
      }elseif($d_rows == $per_group_16) {
         $dd_groups = '<option value="a">Group A</option><option value="b">Group B</option><option value="c">Group C</option>';
         $groups = array ('a','b','c');
         $random_group = $groups[rand(0,2)];
      }else{
         $dd_groups = '<option value="a">Group A</option><option value="b">Group B</option><option value="c">Group C</option><option value="d">Group D</option>';
         $groups = array ('a','b','c','d');
         $random_group = $groups[rand(0,3)];
      }
      
    }
    elseif($ds['maxclan']==32 || $ds['maxclan']==64 || $ds['maxclan']==320 || $ds['maxclan']==640) {
         
      if($ds['maxclan']==32 || $ds['maxclan']==320)
         $num_groups = $per_group_32;
      else
         $num_groups = $per_group_64;
         
      if($a_rows == $num_groups) {
         $dd_groups = '<option value="b">Group B</option><option value="c">Group C</option><option value="d">Group D</option><option value="e">Group E</option><option value="f">Group F</option><option value="g">Group G</option><option value="h">Group H</option>';
         $groups = array ('b','c','d','e','f','g','h',);
         $random_group = $groups[rand(0,6)];
      }elseif($b_rows == $num_groups) {
         $dd_groups = '<option value="a">Group A</option><option value="c">Group C</option><option value="d">Group D</option><option value="e">Group E</option><option value="f">Group F</option><option value="g">Group G</option><option value="h">Group H</option>';
         $groups = array ('a','c','d','e','f','g','h',);
         $random_group = $groups[rand(0,6)];
      }elseif($c_rows == $num_groups) {
         $dd_groups = '<option value="a">Group A</option><option value="b">Group B</option><option value="d">Group D</option><option value="e">Group E</option><option value="f">Group F</option><option value="g">Group G</option><option value="h">Group H</option>';
         $groups = array ('b','a','d','e','f','g','h',);
         $random_group = $groups[rand(0,6)];
      }elseif($d_rows == $num_groups) {
         $dd_groups = '<option value="a">Group A</option><option value="b">Group B</option><option value="c">Group C</option><option value="e">Group E</option><option value="f">Group F</option><option value="g">Group G</option><option value="h">Group H</option>';
         $groups = array ('b','c','a','e','f','g','h',);
         $random_group = $groups[rand(0,6)];
      }elseif($e_rows == $num_groups) {
         $dd_groups = '<option value="a">Group A</option><option value="b">Group B</option><option value="c">Group C</option><option value="d">Group D</option><option value="f">Group F</option><option value="g">Group G</option><option value="h">Group H</option>';
         $groups = array ('b','c','d','a','f','g','h',);
         $random_group = $groups[rand(0,6)];
      }elseif($f_rows == $num_groups) {
         $dd_groups = '<option value="a">Group A</option><option value="b">Group B</option><option value="c">Group C</option><option value="d">Group D</option><option value="e">Group E</option><option value="g">Group G</option><option value="h">Group H</option>';
         $groups = array ('b','c','d','e','a','g','h',);
         $random_group = $groups[rand(0,6)];
      }elseif($g_rows == $num_groups) {
         $dd_groups = '<option value="a">Group A</option><option value="b">Group B</option><option value="c">Group C</option><option value="d">Group D</option><option value="e">Group E</option><option value="f">Group F</option><option value="h">Group H</option>';
         $groups = array ('b','c','d','e','f','a','h',);
         $random_group = $groups[rand(0,6)];
      }elseif($h_rows == $num_groups) {
         $dd_groups = '<option value="a">Group A</option><option value="b">Group B</option><option value="c">Group C</option><option value="d">Group D</option><option value="e">Group E</option><option value="f">Group F</option><option value="g">Group G</option>';
         $groups = array ('b','c','d','e','f','g','a',);
         $random_group = $groups[rand(0,6)];
      }else{
         $groups = array ('a','b','c','d','e','f','g','h');
         $random_group = $groups[rand(0,7)];
         $dd_groups = '<option value="a">Group A</option><option value="b">Group B</option><option value="c">Group C</option><option value="d">Group D</option>
                       <option value="e">Group E</option><option value="f">Group F</option><option value="g">Group G</option><option value="h">Group H</option>';
      }
      
	 } 
    
    if($ds['gs_regtype']) $reg_type = 'Your group selection';
    else $reg_type = 'Randomized group registration';
    
   $alpha_groups = "type='gs'";
   $dv=mysql_fetch_array(safe_query("SELECT count(*) as anzahl FROM ".PREFIX."cup_clans WHERE groupID='".$ID."' && $type_opp='0'"));  
   $dt=mysql_fetch_array(safe_query("SELECT count(*) as totalm FROM ".PREFIX."cup_matches WHERE matchno='$ID' && ($alpha_groups) && $type_opp='0' && confirmscore='1' && einspruch='0'"));      

$chk_typ=($_GET['laddID'] ? "cupID='0'" : "ladID='0'");
    $check_end = safe_query("SELECT * FROM ".PREFIX."cup_matches WHERE matchno='$ID' AND confirmscore='0' AND $chk_typ AND type='gs'"); 
        $ce = mysql_fetch_array($check_end);   

    if(!$ent_part) {
        $finished = 0; 
    }
    elseif(mysql_num_rows($check_end) || $dv['anzahl'] < $ds['maxclan']+$ds['maxclan']) {
        $finished = 0; 
    }
    else{
        $finished = 1;
    }
    
//start registration
    
    if($ds['status']==1 && $ds['gs_start'] > time()) { 
     
            if($dv['anzahl'] >= $ds['maxclan']+$ds['maxclan']) {
                   $dd_groups_sel = '<option value="">(no spaces available)</option>'; 
            }
	    else{ 
                   $dd_groups_sel = $dd_groups;
            }
   
            if($finished) {
                   $gs_signup = '<font color="#FF6600"><b>Finished</b></font>';
            }
            elseif(!$name2($ID)) {
      

	                $ergebnis = safe_query("SELECT * FROM ".PREFIX."cup_clan_members WHERE userID = '".$userID."' AND function = 'Leader'");
	                if(!mysql_num_rows($ergebnis)) $no_team = '<a href="?site=clans&action=clanadd">(leading no team - create team now!)</a>';		
	                $clan = '<option value="" selected="selected"> - Select Team - </option>';	
	                while($dm=mysql_fetch_array($ergebnis)) {
                        
	                $clan.= '<option name="clanID" value="'.$dm['clanID'].'">'.getclanname($dm['clanID']).'</option>'; }
	                
	    
                if($ds['gs_regtype']) {
             
                        $gs_signup = '<form action="index.php">
                                        <input type="hidden" name="site" value="groups">
                                        <input type="hidden" name="action" value="register">
                                        <input type="hidden" name="'.$type2.'" value="'.$_GET[$type2].'">
                                       <select name="group">
                                        '.$dd_groups_sel.'
                                       </select>
                                        <select name="clanID">'.$clan.'</select>
                                        <input type="submit" value="Signup" onclick="return confirm(\'League starts on '.$start.' '.$gmt.' \');">
                                       </form>';
	                
      
                }
		else{
      
                        $gs_signup.= '<form action="index.php">
                                        <input type="hidden" name="site" value="groups">
                                        <input type="hidden" name="action" value="register">
                                        <input type="hidden" name="'.$type2.'" value="'.$_GET[$type2].'">
                                      <select name="clanID">
				       '.$clan.'
				      </select>
                                        <input type="submit" value="Signup" onclick="return confirm(\'League starts on '.$start.' '.$gmt.' \');">
                                     </form>'; 
          }       
        }
	elseif($ds['gs_regtype']) {
        
        
                        $gs_signup = '<form action="index.php">
                                        <input type="hidden" name="site" value="groups">
                                        <input type="hidden" name="action" value="register">
                                        <input type="hidden" name="'.$type2.'" value="'.$_GET[$type2].'">
                                      <select name="group">
                                       '.$dd_groups_sel.'
                                      </select>
                                        <input type="submit" value="Signup" onclick="return confirm(\'League starts on '.$start.' '.$gmt.' \');">
                                      </form>';
                       
      }
      else{ 
      
                        $gs_signup = '<img border="0" src="images/cup/new_message.gif"> 
                                      <a href="?site=groups&action=register&'.$type2.'='.$ID.'" onclick="return confirm(\'League starts on '.$start.'GMT. \');">
                                      <strong>Signup</strong></a>';
      }                 
    }
    elseif($ds['gs_start'] <= time() && $ds['gs_end'] >= time()) {
         
                        $gs_signup = '<font color="'.$drawcolor.'"><b>Started</b></font>';
    }
    else{
         
                        $gs_signup = '<font color="'.$loosecolor.'"><b>Closed</b></font>';
    }
     
//end registration
     
   
   if(isset($_GET['laddID'])) 
      $st=mysql_fetch_array(safe_query("SELECT status FROM ".PREFIX."cup_ladders WHERE ID='".$_GET['laddID']."'"));
   else   
      $st=mysql_fetch_array(safe_query("SELECT status FROM ".PREFIX."cups WHERE ID='".$_GET['cupID']."'"));  
      
   if($st['status']==3)
      $status_m = 'Closed';
   elseif($st['status']==2)
      $status_m = 'Started';
   elseif($st['status']==1)
      $status_m = 'Sign-up Phase';
      
		if($ds['maxclan'] == 80 || $ds['maxclan']== 8)
			$max = 8;
		elseif($ds['maxclan'] == 160 || $ds['maxclan']== 16)
			$max = 16;
		elseif($ds['maxclan'] == 320 || $ds['maxclan']== 32)
			$max = 32;
		elseif($ds['maxclan'] == 640 || $ds['maxclan']== 64)
			$max = 64; 

   $dt2=mysql_fetch_array(safe_query("SELECT count(*) as totalm FROM ".PREFIX."cup_matches WHERE matchno='$ID' && type='gs' && $type_opp='0' && confirmscore='1' && einspruch='0'"));      
   $sp=mysql_fetch_array(safe_query("SELECT * FROM ".PREFIX."cup_matches WHERE matchno='".$ID."' && $type_opp='0' && type='gs' && si='0'"));
	
    if(is_array($sp)) {
          $all_vs_all = true;
    }
    else{ 
          $all_vs_all = false;
    }
	
    $max_participants = $max+$max;
   
   if($ds['gs_staging']==1) {
   
      if($all_vs_all==true) {
      
            $spec_mc = safe_query("SELECT * FROM ".PREFIX."cup_matches WHERE matchno='$ID' && type='gs' && $type_opp='0'");
            $total_matches = mysql_num_rows($spec_mc);
      }
      else{
            $total_matches = $max*4;
      }
   }
   else{
            $total_matches = $max;
   }
   
   if($dv['anzahl'] == $max+$max || $all_vs_all==true) {
      $slots = "League Full";
      $slots_ext = '';
   }
   else{
      $slots = $max+$max-$dv['anzahl']." Slots Available";
      $slots_ext = '/'.$max_participants;      
   }      
   
   if(iscupadmin($userID) && $_GET['rm']=='ava') {
           safe_query("DELETE FROM ".PREFIX."cup_matches WHERE matchno='$ID' && type='gs' && si='0' && $type_opp='0'");
	   redirect('?site=groups&'.$type2.'='.$ID, '<font color="red"><strong>Matches removed...</strong></font>', 3);
   }
      

   if(iscupadmin($userID) && $ds['gs_staging']==1) {
   
     if($all_vs_all==true) { 
           $sh_tit = 'Remove All vs. All Matches';
           $sh_msg = 'Are you sure you want to remove All vs. All matches that were previously generated?'; 
	   $sh_lnk = '?site=groups&'.$type2.'='.$ID.'&rm=ava';
     }
     else{ 
           $sh_tit = 'Generate All vs. All Matches';
           $sh_msg = 'Note: All vs. All matches automatically generate after all '.$max_participants.' are registered. If this league did not reach max you can generate these matches manually. Manual match generation does not check for equalness, make sure equal amount of teams are registered in each group. No further sign-ups allowed - click OK to confirm.'; 
	   $sh_lnk = '?site=groups&'.$type2.'='.$ID.'&gm=ava';
     }

        $admin_options = '<tr>
	                    <td bgcolor="'.$bg1.'"><img src="images/cup/icons/admin.png"> <strong>Admin</strong></td>
			    <td bgcolor="'.$bg2.'"><a href="'.$sh_lnk.'" onclick="return confirm(\''.$sh_msg.'\');">'.$sh_tit.'</a></td>
	                  </tr>';
   }   
      
   echo '<br />
         <table width="100%" cellspacing="'.$cellspacing.'" cellpadding="'.$cellpadding.'" bgcolor="'.$border.'">
            <tr>
             <td class="title" colspan="2"><img src="images/cup/icons/groups.png"> <strong>Group Stages for '.$name3.'</strong></td>
           </tr>
           <tr>
             <td bgcolor="'.$bg1.'"><img src="images/cup/icons/yourteams.png" width="16" height="16"> <strong>Slots</strong></td>
             <td bgcolor="'.$bg2.'">'.$slots.' ('.$dv['anzahl'].$slots_ext.' Signups)</td>
           </tr>
            <tr>
             <td bgcolor="'.$bg1.'"><img src="images/cup/icons/add_result.gif" width="16" height="16"> <strong>Total</strong></td> 
             <td bgcolor="'.$bg2.'">'.$dt['totalm'].'/'.$total_matches.' Confirmed Matches</td>
           </tr>
	   '.$admin_options.'
            <tr>
             <td bgcolor="'.$bg1.'"><img src="images/cup/icons/checkin.png"> <strong>Signup</strong></td>
             <td bgcolor="'.$bg2.'"><b>'.$gs_signup.'</b> '.$no_team.'</td>
           </tr>
          </table>
         <br>';

        if($ds['maxclan']==8 || $ds['maxclan']==80)
           $limit = '4';
        elseif($ds['maxclan']==16 || $ds['maxclan']==160)
           $limit = '4';
        elseif($ds['maxclan']==32 || $ds['maxclan']==320)
           $limit = '4';
        elseif($ds['maxclan']==64 || $ds['maxclan']==640)
           $limit = '8';
	   
      if($name2($ID)) $participant = 'Player';
      else $participant = 'Team';
      
      if($ent_part) { 
      
         $stats_table = '<table width="100%" cellspacing="'.$cellspacing.'" cellpadding="'.$cellpadding.'" bgcolor="'.$border.'">
                          <tr>
                            <td class="title" colspan="9">Qualifiers & Match Stats</td>
                          </tr>
                           <tr>
                            <td class="title2" align="center">'.$participant.'s</td>
                            <td class="title2" align="center">Played</td>
                            <td class="title2" align="center">W-D-L</td>
                            <td class="title2" align="center">Ratio</td>
                            <td class="title2" align="center">Points</td>
                            <td class="title2" align="center">Group</td>
                            <td class="title2" align="center">Eligible</td>
                          </tr>';
      }
                    
      $participants = safe_query("SELECT * FROM ".PREFIX."cup_clans WHERE groupID='$ID' AND $type_opp='0' ORDER BY $type ASC");
        while($gap=mysql_fetch_array($participants)) {
        
         $alpha_groups = "type='gs'";
        
		  $group_a_rows = safe_query("SELECT * FROM ".PREFIX."cup_matches WHERE matchno='".$ID."' AND ($alpha_groups) AND (clan1='".$gap['clanID']."' || clan2='".$gap['clanID']."') AND confirmscore='1' AND einspruch='0'");
		  $gam = mysql_fetch_array($group_a_rows); $group_a_matches = mysql_num_rows($group_a_rows);
		  
		if($gap['clanID']==$gam['clan1']) {
		   $score1 = "score1";
		   $score2 = "score2";
		}
		else{
		   $score1 = "score2";
		   $score2 = "score1";
		}
		
        if($name2($ID)) $flag = getusercountry($gap['clanID']);
        else $flag = getclancountry1($gap['clanID']);

		  $total_rows = safe_query("SELECT * FROM ".PREFIX."cup_matches WHERE matchno='".$ID."' AND ($alpha_groups) AND (clan1='".$gap['clanID']."' || clan2='".$gap['clanID']."') AND confirmscore='1' AND einspruch='0'");
		  $group_a_matches = mysql_num_rows($total_rows);
		  
		  $rows_won = safe_query("SELECT * FROM ".PREFIX."cup_matches WHERE matchno='".$ID."' AND ($alpha_groups) AND $score1 > $score2 AND (clan1='".$gap['clanID']."' || clan2='".$gap['clanID']."')");
		  $group_a_won = mysql_num_rows($rows_won);
		  
		  $rows_draw = safe_query("SELECT * FROM ".PREFIX."cup_matches WHERE matchno='".$ID."' AND ($alpha_groups) AND score1 = score2 AND (clan1='".$gap['clanID']."' || clan2='".$gap['clanID']."') AND (score1!='0' || score2!='0')");
		  $group_a_draw = mysql_num_rows($rows_draw);
		  
		  $rows_lost = safe_query("SELECT * FROM ".PREFIX."cup_matches WHERE matchno='".$ID."' AND ($alpha_groups) AND $score1 < $score2 AND (clan1='".$gap['clanID']."' || clan2='".$gap['clanID']."')");
		  $group_a_lost = mysql_num_rows($rows_lost);
		  
		  $po =mysql_fetch_array(safe_query("SELECT SUM($score1) as wonpoints FROM ".PREFIX."cup_matches WHERE matchno='".$ID."' AND $score1 > $score2 AND ($alpha_groups) AND (clan1='".$gap['clanID']."' || clan2='".$gap['clanID']."') AND confirmscore='1' && einspruch='0'")); 	  		 
		  $po2=mysql_fetch_array(safe_query("SELECT SUM($score1) as totalpoints FROM ".PREFIX."cup_matches WHERE matchno='".$ID."' AND ($alpha_groups) AND (clan1='".$gap['clanID']."' || clan2='".$gap['clanID']."') AND confirmscore='1' && einspruch='0'")); 
		  
		  $tp1=mysql_fetch_array(safe_query("SELECT SUM($score1) as total FROM ".PREFIX."cup_matches WHERE ($alpha_groups) AND matchno='$ID' AND clan1='".$gap['clanID']."'"));
		  $tp2=mysql_fetch_array(safe_query("SELECT SUM($score2) as total FROM ".PREFIX."cup_matches WHERE ($alpha_groups) AND matchno='$ID' AND clan2='".$gap['clanID']."'"));
		  
        if($_GET['laddID']) { 
           $dxp=mysql_fetch_array(safe_query("SELECT d_xp FROM ".PREFIX."cup_ladders WHERE ID='$ID'")); 
           $t_dxp = "d_xp";
        }else{ 
           $dxp=mysql_fetch_array(safe_query("SELECT gs_dxp FROM ".PREFIX."cups WHERE ID='$ID'"));
           $t_dxp = "gs_dxp";
        }  
 
        if(!$ds[$t_dxp]) {	 
		  
		  if(empty($po2['totalpoints'])) $c_points = "0"; 
		  else $c_points = $po2['totalpoints']; 
		  
		}else{ 
 		  
		  if(empty($po['wonpoints'])) $c_points = "0"; 
		  else $c_points = $po['wonpoints']; 
		  
		} if(empty($group_a_won)) $w_matches = "0"; 
		  else $w_matches = $group_a_won; 
		  
		  if(empty($group_a_draw)) $d_matches = "0"; 
		  else $d_matches = $group_a_draw; 
		 
		  if(empty($group_a_lost)) $l_matches = "0"; 
		  else $l_matches = $group_a_lost; 
		  
		  $ratio=percent($group_a_won, $group_a_matches, 2);
	
/* CHECK QUALIFICATION */	

$query = safe_query("SELECT SUM($score1) as wonpoints FROM ".PREFIX."cup_matches WHERE matchno='".$ID."' AND $score1 > $score2 AND ($alpha_groups) AND (clan1='".$gap['clanID']."' || clan2='".$gap['clanID']."')");
  while( $row = mysql_fetch_assoc($query) ) {
     $array[] = $row['wonpoints'];   
   }
   
 if($finished) {
     
    $check_qualifyers = safe_query("SELECT qual FROM ".PREFIX."cup_clans WHERE groupID='$ID' AND qual='1' AND $type_opp='0'");
    $is_qualifyers = mysql_num_rows($check_qualifyers); 
    
    $check_fcfs = safe_query("SELECT qual FROM ".PREFIX."cup_clans WHERE groupID='$ID' AND qual='2' AND $type_opp='0'");
    $is_fcfs = mysql_num_rows($check_fcfs); 
    
	     $day_lb = date('d')+$league_begin;
	     $month = date('m');
	     $year = date('Y');
       	 $hour = date('H');
	     $min = date('i');
     	 $date_begin_trans = @mktime($hour, $min, 0, $month, $day_lb, $year);
     	 
     	 $day_le = date('d')+$league_end;
	     $month = date('m');
	     $year = date('Y');
       	 $hour = date('H');
	     $min = date('i');
     	 $date_end_trans = @mktime($hour, $min, 0, $month, $day_le, $year);

       
      if($ds['gs_staging']==1 || $ds['gs_staging']==2) {
	 
		  if(in_array($c_points,is_qualified($ID,$arr=1))) {	 
		  
		     if(!$is_qualifyers)       
	    	     sendmessage($gap['clanID'], 'League Eligibility Notification', 'Congratulations! You are a qualified participant and your registration is reserved for the league.');

                     safe_query("UPDATE ".PREFIX."cup_clans SET qual='1' WHERE groupID='$ID' AND clanID='".$gap['clanID']."'");  
		     $qualified = '<font color="#00CC00"><b>Qualified</b></font> <img src="images/cup/success.png" align="right">'; 
	    	   		  
		  }
		  elseif(in_array($c_points,is_qualified($ID,$arr=0))) {
		  	
		     if(!$is_qualifyers)  
	    	     sendmessage($gap['clanID'], 'League Eligibility Notification', 'You are a FCFS participant which means it will be first-come-first-serve after all qualifiers are registered.');
	    		 	
                     safe_query("UPDATE ".PREFIX."cup_clans SET qual='2' WHERE groupID='$ID' AND clanID='".$gap['clanID']."'");
                     $qualified = '<font color="#FF6600"><b>FCFS</b></font> <img src="images/cup/icons/groups.png" width="16" height="16" align="right">';
             
		  }
		  else{ 
          
		     if(!$is_qualifyers)        
	    	     sendmessage($gap['clanID'], 'League Eligibility Notification', 'Sorry! You did not have enough points to pass through to the qualifying league. We hope you better luck next time!'); 
		     
		     $qualified = '<font color="#DD0000"><b>Unqualified</b></font> <img src="images/cup/error.png" width="16" height="16" align="right">';
		  }
        
      }
      else{
      
		  if($group_a_won) {
		        sendmessage($gap['clanID'], 'League Eligibility Notification', 'Congratulations! You are a qualified participant and your registration is reserved for the league.');
		        $qualified = '<font color="#00CC00"><b>Qualified</b></font> <img src="images/cup/success.png" align="right">';
		  }
		  else{
		        sendmessage($gap['clanID'], 'League Eligibility Notification', 'Sorry! You did not have enough points to pass through to the qualifying league. We hope you better luck next time!'); 
		        $qualified = '<font color="#DD0000"><b>Unqualified</b></font> <img src="images/cup/error.png" width="16" height="16" align="right">';	     
                  }
      }           
      
        if(!$is_qualifyers && $ds['gs_trans']) {
        
           if(!$_GET['laddID'])
              safe_query("UPDATE ".PREFIX."cups SET start='".$date_begin_trans."', gs_end='".time()."', ende='".$date_end_trans."', status='1' WHERE ID='$ID'");
           else   
              safe_query("UPDATE ".PREFIX."cup_ladders SET start='".$date_begin_trans."', gs_end='".time()."', end='".$date_end_trans."', status='1' WHERE ID='$ID'");
        } 
      
    }else
		     $qualified = '(Matches in Progress)';
        
/* END CHECK QUALIFICATION */



if($ds['gs_staging']==1) {

  if($all_vs_all==true) {
  
        $spec_mc = safe_query("SELECT * FROM ".PREFIX."cup_matches WHERE matchno='$ID' && type='gs' && (clan1='".$gap['clanID']."' || clan2='".$gap['clanID']."') && $type_opp='0'");
        $max_matches = mysql_num_rows($spec_mc);
	
	if(mysql_num_rows(safe_query("SELECT ma FROM ".PREFIX."cup_clans WHERE groupID='$ID' && type='gs' && $type_opp='0' && ma='0'"))) {
	    safe_query("UPDATE ".PREFIX."cup_clans SET ma='$max_matches' WHERE groupID='$ID' && type='gs' && $type_opp='0' && ma='0' ");
	}  
  }
  else{
  

        if($ds['maxclan']==8 || $ds['maxclan']==80)
           $max_matches = 4;
        elseif($ds['maxclan']==16 || $ds['maxclan']==160)
           $max_matches = 8;
        elseif($ds['maxclan']==32 || $ds['maxclan']==320)
           $max_matches = 8;
        elseif($ds['maxclan']==64 || $ds['maxclan']==640)
           $max_matches = 8;
  }   
}else
           $max_matches = 1; 
    
      $stats_content.='<tr>
                         <td bgcolor="'.$bg1.'" align="center">'.$flag.' '.getname1($gap['clanID'],$ID,$ac=0,strtolower($name)).'</td>
                         <td bgcolor="'.$bg1.'" align="center">'.$group_a_matches.' / '.$max_matches.'</td>
                         <td bgcolor="'.$bg1.'" align="center"><b>'.$group_a_won.'-'.$group_a_draw.'-'.$group_a_lost.'</b></td>
                         <td bgcolor="'.$bg1.'" align="center">'.$ratio.'%</td>
                         <td bgcolor="'.$bg1.'" align="center">'.$c_points.'</td>
                         <td bgcolor="'.$bg1.'" align="center">'.strtoupper($gap[$type]).'</td>
                         <td bgcolor="'.$bg1.'" align="center">'.$qualified.'</td>
                       </tr>';
               }
                              

//generate other matches

  if($ds['maxclan']==8 || $ds['maxclan']==80) {
     $groups = $per_group_8;
     $get_groups = "$type='a' || $type='b'";
  }elseif($ds['maxclan']==16 || $ds['maxclan']==160) {
     $groups = $per_group_16;
     $get_groups = "$type='a' || $type='b' || $type='c' || $type='d'";
  }elseif($ds['maxclan']==32 || $ds['maxclan']==320) {
     $groups = $per_group_32;
     $get_groups = "$type='a' || $type='b' || $type='c' || $type='d' || $type='e' || $type='f' || $type='g' || $type='h'";
  }elseif($ds['maxclan']==64 || $ds['maxclan']==640) {
     $groups = $per_group_64;
     $get_groups = "$type='a' || $type='b' || $type='c' || $type='d' || $type='e' || $type='f' || $type='g' || $type='h'";
  }    
  
    $ergebnis2 = safe_query("SELECT count(*) as anzahl FROM ".PREFIX."cup_clans WHERE groupID='".$ID."'");
    $dv=mysql_fetch_array($ergebnis2);
    
    $specchk = safe_query("SELECT * FROM ".PREFIX."cup_matches WHERE matchno='".$ID."' && $type_opp='0' && type='gs' && si='0'");
    $sp=mysql_fetch_array($specchk);
  
     if(($ds['gs_staging']==1 && $dv['anzahl'] == $max_participants && $dt2['totalm'] != $total_matches) || 
        (iscupadmin($userID) && $_GET['gm']=='ava') && !is_array($sp))  {
     
	     $day = date('d');
	     $month = date('m');
	     $year = date('Y');
       	     $hour = date('H');
	     $min = date('i');
     	     $date = @mktime($hour, $min, 0, $month, $day, $year);
	     
     if($rows_a != $max_participants)

      safe_query("INSERT INTO ".PREFIX."cup_matches ($type, $type_opp, matchno, clan1, clan2, comment, 1on1) (
        SELECT a.$type, a.$type_opp, a.matchno, a.clan1, b.clan2, a.comment, a.1on1
        FROM ".PREFIX."cup_matches a, ".PREFIX."cup_matches b
        WHERE a.clan2 != b.clan2 AND a.matchno = '$ID' AND b.matchno = '$ID' AND a.$type='a' AND b.$type='a')");
     	 
     if($rows_b != $max_participants)

      safe_query("INSERT INTO ".PREFIX."cup_matches ($type, $type_opp, matchno, clan1, clan2, comment, 1on1) (
        SELECT a.$type, a.$type_opp, a.matchno, a.clan1, b.clan2, a.comment, a.1on1
        FROM ".PREFIX."cup_matches a, ".PREFIX."cup_matches b
        WHERE a.clan2 != b.clan2 AND a.matchno = '$ID' AND b.matchno = '$ID' AND a.$type='b' AND b.$type='b')");

     if($rows_c != $max_participants)

      safe_query("INSERT INTO ".PREFIX."cup_matches ($type, $type_opp, matchno, clan1, clan2, comment, 1on1) (
        SELECT a.$type, a.$type_opp, a.matchno, a.clan1, b.clan2, a.comment, a.1on1
        FROM ".PREFIX."cup_matches a, ".PREFIX."cup_matches b
        WHERE a.clan2 != b.clan2 AND a.matchno = '$ID' AND b.matchno = '$ID' AND a.$type='c' AND b.$type='c')");
        
     if($rows_d != $max_participants)

      safe_query("INSERT INTO ".PREFIX."cup_matches ($type, $type_opp, matchno, clan1, clan2, comment, 1on1) (
        SELECT a.$type, a.$type_opp, a.matchno, a.clan1, b.clan2, a.comment, a.1on1
        FROM ".PREFIX."cup_matches a, ".PREFIX."cup_matches b
        WHERE a.clan2 != b.clan2 AND a.matchno = '$ID' AND b.matchno = '$ID' AND a.$type='d' AND b.$type='d')");
        
     if($rows_e != $max_participants)

      safe_query("INSERT INTO ".PREFIX."cup_matches ($type, $type_opp, matchno, clan1, clan2, comment, 1on1) (
        SELECT a.$type, a.$type_opp, a.matchno, a.clan1, b.clan2, a.comment, a.1on1
        FROM ".PREFIX."cup_matches a, ".PREFIX."cup_matches b
        WHERE a.clan2 != b.clan2 AND a.matchno = '$ID' AND b.matchno = '$ID' AND a.$type='e' AND b.$type='e')");
        
     if($rows_f != $max_participants)

      safe_query("INSERT INTO ".PREFIX."cup_matches ($type, $type_opp, matchno, clan1, clan2, comment, 1on1) (
        SELECT a.$type, a.$type_opp, a.matchno, a.clan1, b.clan2, a.comment, a.1on1
        FROM ".PREFIX."cup_matches a, ".PREFIX."cup_matches b
        WHERE a.clan2 != b.clan2 AND a.matchno = '$ID' AND b.matchno = '$ID' AND a.$type='f' AND b.$type='f')");
        
     if($rows_g != $max_participants)

      safe_query("INSERT INTO ".PREFIX."cup_matches ($type, $type_opp, matchno, clan1, clan2, comment, 1on1) (
        SELECT a.$type, a.$type_opp, a.matchno, a.clan1, b.clan2, a.comment, a.1on1
        FROM ".PREFIX."cup_matches a, ".PREFIX."cup_matches b
        WHERE a.clan2 != b.clan2 AND a.matchno = '$ID' AND b.matchno = '$ID' AND a.$type='g' AND b.$type='g')");
        
     if($rows_h != $max_participants)

      safe_query("INSERT INTO ".PREFIX."cup_matches ($type, $type_opp, matchno, clan1, clan2, comment, 1on1) (
        SELECT a.$type, a.$type_opp, a.matchno, a.clan1, b.clan2, a.comment, a.1on1
        FROM ".PREFIX."cup_matches a, ".PREFIX."cup_matches b
        WHERE a.clan2 != b.clan2 AND a.matchno = '$ID' AND b.matchno = '$ID' AND a.$type='h' AND b.$type='h')"); 
       
      $generated_matches = safe_query("SELECT * FROM ".PREFIX."cup_matches WHERE matchno='$ID'");
      while($dd=mysql_fetch_array($generated_matches)) {
      
      if(!$dd['date']) 
          safe_query("UPDATE ".PREFIX."cup_matches SET date='$date' WHERE date=''");
      }       
      
      if($_GET['gm']=='ava') redirect('?site=groups&'.$type2.'='.$ID, '<font color="red"><strong>Matches generated!</strong></font>', 3);
                 
     }

//registration to group stages
        
  if(isset($_GET['action']) && $_GET['action'] == 'register') {   
                 
	     $day = date('d');
	     $month = date('m');
	     $year = date('Y');
       	 $hour = date('H');
	     $min = date('i');
     	 $date = @mktime($hour, $min, 0, $month, $day, $year);
     	 
		$ergebnis2 = safe_query("SELECT count(*) as anzahl FROM ".PREFIX."cup_clans WHERE groupID='".$ID."' AND $type_opp='0'");
		$dv=mysql_fetch_array($ergebnis2);
		
//register validations

		  if(!$userID)
		     echo $_language->module['not_loggedin'];
		  elseif(!$name2($ID) && !$_GET['clanID'])   
		     echo $_language->module['invalid_team'].$style3."</div>";
		  elseif($ds['status']!=1)
		     echo 'Group stages is not on sign-up phase.';	     
		  elseif(isgroupparticipant($userID,$ID))
		     echo $_language->module['already_participant'].' <b>'.strtoupper(returnGroup($userID,$ID)).'</b>.'.$style3.'</div>';
		  elseif($dv['anzahl'] >= $ds['maxclan']+$ds['maxclan'])
		     echo $_language->module['too_much_teams']; 
		  elseif(($ds['maxclan']==8 || $ds['maxclan']==80) && $a_rows == $per_group_8 && $_GET['group']=="a")
		     echo 'Sorry group A is already filled up.';  
		  elseif(($ds['maxclan']==8 || $ds['maxclan']==80) && $b_rows == $per_group_8 && $_GET['group']=="b")
		     echo 'Sorry group B is already filled up.';    
		  elseif(($ds['maxclan']==16 || $ds['maxclan']==160) && $a_rows == $per_group_16  && $_GET['group']=="a")
		     echo 'Sorry group A is already filled up.';  
		  elseif(($ds['maxclan']==16 || $ds['maxclan']==160) && $b_rows == $per_group_16 && $_GET['group']=="b")
		     echo 'Sorry group B is already filled up.';  
		  elseif(($ds['maxclan']==16 || $ds['maxclan']==160) && $c_rows == $per_group_16 && $_GET['group']=="c")
		     echo 'Sorry group C is already filled up.';  
		  elseif(($ds['maxclan']==16 || $ds['maxclan']==160) && $d_rows == $per_group_16 && $_GET['group']=="d")
		     echo 'Sorry group D is already filled up.';    
		  elseif(($ds['maxclan']==32 || $ds['maxclan']==64 || $ds['maxclan']==320 || $ds['maxclan']==640) && $a_rows == $num_groups && $_GET['group']=="a")
		     echo 'Sorry group A is already filled up.';  
		  elseif(($ds['maxclan']==32 || $ds['maxclan']==64 || $ds['maxclan']==320 || $ds['maxclan']==640) && $b_rows == $num_groups && $_GET['group']=="b")
		     echo 'Sorry group B is already filled up.';  
		  elseif(($ds['maxclan']==32 || $ds['maxclan']==64 || $ds['maxclan']==320 || $ds['maxclan']==640) && $c_rows == $num_groups && $_GET['group']=="c")
		     echo 'Sorry group C is already filled up.';  
		  elseif(($ds['maxclan']==32 || $ds['maxclan']==64 || $ds['maxclan']==320 || $ds['maxclan']==640) && $d_rows == $num_groups && $_GET['group']=="d")
		     echo 'Sorry group D is already filled up.';  
		  elseif(($ds['maxclan']==32 || $ds['maxclan']==64 || $ds['maxclan']==320 || $ds['maxclan']==640) && $e_rows == $num_groups && $_GET['group']=="e")
		     echo 'Sorry group E is already filled up.';  
		  elseif(($ds['maxclan']==32 || $ds['maxclan']==64 || $ds['maxclan']==320 || $ds['maxclan']==640) && $f_rows == $num_groups && $_GET['group']=="f")
		     echo 'Sorry group F is already filled up.';  
		  elseif(($ds['maxclan']==32 || $ds['maxclan']==64 || $ds['maxclan']==320 || $ds['maxclan']==640) && $g_rows == $num_groups && $_GET['group']=="g")
		     echo 'Sorry group G is already filled up.';  
		  elseif(($ds['maxclan']==32 || $ds['maxclan']==64 || $ds['maxclan']==320 || $ds['maxclan']==640) && $h_rows == $num_groups && $_GET['group']=="h")
		     echo 'Sorry group H is already filled up.';  
		    
	elseif($ds['status']==1 && $ds['gs_start'] > time()) {
	
   if($name2($ID)) {
          
             if($ds['gs_regtype']) {
                safe_query("INSERT INTO ".PREFIX."cup_clans (`$type`, $plat `groupID`, `clanID`, `1on1`) VALUES ('".$_GET['group']."', $platID '$ID', '$userID', '1')");
                  if(mysql_num_rows(safe_query("SELECT * FROM ".PREFIX."cup_matches WHERE $type='".$_GET['group']."' AND matchno='$ID' AND clan1!='0' AND clan2='0'"))) {
                     safe_query("UPDATE ".PREFIX."cup_matches SET clan2='$userID' WHERE $type='".$_GET['group']."' AND matchno='$ID' AND clan2='0'"); 
                     redirect('?site=groups&'.$type2.'='.$ID.'', 'Successfully registered in <b>Group '.$_GET['group'].'</b>!', 2);         
                  }else{ 
                     safe_query("INSERT INTO ".PREFIX."cup_matches (`$type`, `$type_opp`, `date`, `matchno`, `clan1`, `comment`, `1on1`, `si`) VALUES ('".$_GET['group']."', '0', '$date', '$ID', '$userID', '2', '1', '1')");
                     redirect('?site=groups&'.$type2.'='.$ID.'', 'Successfully registered in <b>Group '.$_GET['group'].'</b>!', 2);
                  }
             }else{
             
                safe_query("INSERT INTO ".PREFIX."cup_clans (`$type`, $plat `groupID`, `clanID`, `1on1`) VALUES ('$random_group', $platID '$ID', '$userID', '1')");
                  if(mysql_num_rows(safe_query("SELECT * FROM ".PREFIX."cup_matches WHERE $type='$random_group' AND matchno='$ID' AND clan1!='0' AND clan2='0'"))) {
                     safe_query("UPDATE ".PREFIX."cup_matches SET clan2='$userID' WHERE $type='$random_group' AND matchno='$ID' AND clan2='0'"); 
                     redirect('?site=groups&'.$type2.'='.$ID.'', 'Successfully registered in  <b>Group '.$random_group.'</b>!', 2);
                  }else{
                     safe_query("INSERT INTO ".PREFIX."cup_matches (`$type`, `$type_opp`, `date`, `matchno`, `clan1`, `comment`, `1on1`, `si`) VALUES ('$random_group', '0', '$date', '$ID', '$userID', '2', '1', '1')"); 
                     redirect('?site=groups&'.$type2.'='.$ID.'', 'Successfully registered in  <b>Group '.$random_group.'</b>!', 2);
                  }
             }
             
          }else{
          
           $clanID = $_GET['clanID'];
          
             if(!isleader($userID,$clanID)) 
                echo 'You are not the leader';
                
             elseif($ds['gs_regtype']) {
                safe_query("INSERT INTO ".PREFIX."cup_clans (`$type`, $plat `groupID`, `clanID`, `1on1`) VALUES ('".$_GET['group']."', $platID '$ID', '$clanID', '0')");
                  if(mysql_num_rows(safe_query("SELECT * FROM ".PREFIX."cup_matches WHERE $type='".$_GET['group']."' AND matchno='$ID' AND clan1!='0' AND clan2='0'"))) {
                     safe_query("UPDATE ".PREFIX."cup_matches SET clan2='$clanID' WHERE $type='".$_GET['group']."' AND matchno='$ID' AND clan2='0'"); 
                     redirect('?site=groups&'.$type2.'='.$ID.'', 'Successfully registered in <b>Group '.$_GET['group'].'</b>!', 2);         
                  }else{ 
                     safe_query("INSERT INTO ".PREFIX."cup_matches (`$type`, `$type_opp`, `date`, `matchno`, `clan1`, `comment`, `1on1`, `si`) VALUES ('".$_GET['group']."', '0', '$date', '$ID', '$clanID', '2', '0', '1')");
                     redirect('?site=groups&'.$type2.'='.$ID.'', 'Successfully registered in <b>Group '.$_GET['group'].'</b>!', 2);
                  }
             }else{
             
                safe_query("INSERT INTO ".PREFIX."cup_clans (`$type`, $plat `groupID`, `clanID`, `1on1`) VALUES ('$random_group', $platID '$ID', '$clanID', '0')");
                  if(mysql_num_rows(safe_query("SELECT * FROM ".PREFIX."cup_matches WHERE $type='$random_group' AND matchno='$ID' AND clan1!='0' AND clan2='0'"))) {
                     safe_query("UPDATE ".PREFIX."cup_matches SET clan2='$clanID' WHERE $type='$random_group' AND matchno='$ID' AND clan2='0'"); 
                     redirect('?site=groups&'.$type2.'='.$ID.'', 'Successfully registered in  <b>Group '.$random_group.'</b>!', 2);
                  }else{
                     safe_query("INSERT INTO ".PREFIX."cup_matches (`$type`, `$type_opp`, `date`, `matchno`, `clan1`, `comment`, `1on1` `si`) VALUES ('$random_group', '0', '$date', '$ID', '$clanID', '2', '0', '1')"); 
                     redirect('?site=groups&'.$type2.'='.$ID.'', 'Successfully registered in  <b>Group '.$random_group.'</b>!', 2);
                 }
               }
             }
           }else echo 'You can only register in between the start time and end time. (Times available in overview above)';
         } 
       
       $cup = safe_query("SELECT max(ID) as maxcupID FROM ".PREFIX."cups");
       $cID = mysql_fetch_array($cup);
       
//anas loop query       
       
    $participants = safe_query("SELECT * FROM ".PREFIX."cup_matches WHERE matchno='$ID' && type='gs' ORDER BY date ASC"); 
        while($dd=mysql_fetch_array($participants)) {

	
        if(!$dd['clan2']) {
           $status = '<font color="#FF6600"><b>WAIT</b></font>';
           $clan2 = '<a style="text-decoration:none; cursor:help" onmouseover="showWMTT(\'waiting_player2\')" onmouseout="hideWMTT()"><img src="images/cup/period'.$period_dot.'_ani.gif"></a>';
        }else
           $clan2 = getname1($dd['clan2'],$ID,$ac=0,strtolower($name));
        
      
    if($name2($ID)) {
            
        if(!$dd['score1'] && !$dd['score2'] && !$dd['einspruch'] && $loggedin && $userID == $dd['clan1'] && $dd['clan2'])
                 $status='&nbsp;<a href="?site=cupactions&amp;action=score&matchID='.$dd['matchID'].'&amp;clan1='.$dd['clan1'].'&amp;'.$type2.'='.$ID.'&one=1&type=group"><strong>Add Result</strong> <img border="0" src="images/cup/icons/addresult.gif" width="20" height="20"></a>';
            
        elseif(!$dd['score1'] && !$dd['score2'] && !$dd['einspruch'] && $loggedin && $userID == $dd['clan2'] && $dd['clan1']) 
                $status='&nbsp;<a href="?site=cupactions&amp;action=score&matchID='.$dd['matchID'].'&amp;clan1='.$dd['clan2'].'&amp;'.$type2.'='.$ID.'&one=1&type=group"><strong>Add Result</strong> <img border="0" src="images/cup/icons/addresult.gif" width="20" height="20"></a>';
           
        elseif(($dd['score1'] || $dd['score2']) && $dd['inscribed']==$dd['clan2'] && !$dd['einspruch'] && $dd['confirmscore']==0 && $userID == $dd['clan1'])
                $status='&nbsp;<a href="?site=cupactions&amp;action=confirmscore&amp;matchID='.$dd['matchID'].'&amp;clanID='.$dd['clan1'].'&amp;groupID='.$ID.'&one=1&type=group"><img border="0" src="images/cup/icons/agreed.gif" border="0"></a> 
                                <a href="?site=cupactions&amp;action=protest&amp;matchID='.$dd['matchID'].'&clanID=onecup&'.$type2.'='.$ID.'" onclick="return confirm(\'Are you sure there was something wrong with the match?\');"><img border="0" src="images/cup/icons/protest.gif"></a>';
                     
        elseif(($dd['score1'] || $dd['score2']) && $dd['inscribed']==$dd['clan1'] && !$dd['einspruch'] && $dd['confirmscore']==0 && $userID == $dd['clan2'])
                $status='&nbsp;<a href="?site=cupactions&amp;action=confirmscore&amp;matchID='.$dd['matchID'].'&amp;clanID='.$dd['clan2'].'&amp;groupID='.$ID.'&one=1&type=group"><img border="0" src="images/cup/icons/agreed.gif" border="0"></a> 
                                <a href="?site=cupactions&amp;action=protest&amp;matchID='.$dd['matchID'].'&clanID=onecup&'.$type2.'='.$ID.'" onclick="return confirm(\'Are you sure there was something wrong with the match?\');"><img border="0" src="images/cup/icons/protest.gif"></a>';
       
        elseif(!$dd['confirmscore'] && $dd['inscribed']==$dd['clan1'] && !$dd['einspruch']) 
                $status='&nbsp;<a style="text-decoration:none; cursor:help" onmouseover="showWMTT(\'waiting_confirmation\')" onmouseout="hideWMTT()"><font color="#FF6600"><b>WAIT</b></a>';    

        elseif(!$dd['confirmscore'] && $dd['inscribed']==$dd['clan2'] && !$dd['einspruch']) 
                $status='&nbsp;<a style="text-decoration:none; cursor:help" onmouseover="showWMTT(\'waiting_confirmation\')" onmouseout="hideWMTT()"><font color="#FF6600"><b>WAIT</b></a>';            
  
        elseif(!$dd['score1'] AND !$dd['score2']) 
                $status='&nbsp;<a style="text-decoration:none; cursor:help" onmouseover="showWMTT(\'no_score\')" onmouseout="hideWMTT()"><strong>PROGRESS</strong> <img src="images/cup/icons/faq.png"></a>';
                
        elseif($dd['confirmscore'] && $dd['inscribed'] && $dd['einspruch']=='0')
                $status='&nbsp;<font color="#00CC00"><b>Confirmed</b></font>';
                
        elseif($dd['einspruch'])
                $status='&nbsp;<font color="#DD0000"><b>Protest</b></font>';

    }else{
    
        if(!$dd['score1'] && !$dd['score2'] && !$dd['einspruch'] && isleader($userID,$dd['clan1']) && $dd['clan2'])
           $status='&nbsp;<a href="?site=cupactions&amp;action=score&matchID='.$dd['matchID'].'&amp;clan1='.$dd['clan1'].'&amp;'.$type2.'='.$ID.'&type=group"><strong>Add Result</strong> <img border="0" src="images/cup/icons/addresult.gif" width="20" height="20"></a>';
            
        elseif(!$dd['score1'] && !$dd['score2'] && !$dd['einspruch'] && isleader($userID,$dd['clan2']) && $dd['clan1'])
                $status='&nbsp;<a href="?site=cupactions&amp;action=score&matchID='.$dd['matchID'].'&amp;clan1='.$dd['clan2'].'&amp;'.$type2.'='.$ID.'&type=group"><strong>Add Result</strong> <img border="0" src="images/cup/icons/addresult.gif" width="20" height="20"></a>';
    
        elseif(($dd['score1'] || $dd['score2']) && $dd['inscribed']==$dd['clan2'] && !$dd['einspruch'] && $dd['confirmscore']==0 && isleader($userID,$dd['clan1']))
                $status='&nbsp;<a href="?site=cupactions&amp;action=confirmscore&amp;matchID='.$dd['matchID'].'&amp;clanID='.$dd['clan1'].'&amp;groupID='.$ID.'&type=group"><img border="0" src="images/cup/icons/agreed.gif" border="0"></a> 
                                <a href="?site=cupactions&amp;action=protest&amp;matchID='.$dd['matchID'].'&clanID='.$dd['clan1'].'&'.$type2.'='.$ID.'" onclick="return confirm(\'Are you sure there was something wrong with the match?\');"><img border="0" src="images/cup/icons/protest.gif"></a>';
                     
        elseif(($dd['score1'] || $dd['score2']) && $dd['inscribed']==$dd['clan1'] && !$dd['einspruch'] && $dd['confirmscore']==0 && isleader($userID,$dd['clan2']))
                $status='&nbsp;<a href="?site=cupactions&amp;action=confirmscore&amp;matchID='.$dd['matchID'].'&amp;clanID='.$dd['clan2'].'&amp;groupID='.$ID.'&type=group"><img border="0" src="images/cup/icons/agreed.gif" border="0"></a> 
                                <a href="?site=cupactions&amp;action=protest&amp;matchID='.$dd['matchID'].'&clanID='.$dd['clan2'].'&'.$type2.'='.$ID.'" onclick="return confirm(\'Are you sure there was something wrong with the match?\');"><img border="0" src="images/cup/icons/protest.gif"></a>';
                     
        elseif($dd['confirmscore'] == '0' && $dd['inscribed']==$dd['clan1'] && !$dd['einspruch']) 
                $status='&nbsp;<a style="text-decoration:none; cursor:help" onmouseover="showWMTT(\'waiting_confirmation\')" onmouseout="hideWMTT()"><font color="#FF6600"><b>WAIT</b></a>';    
                
        elseif($dd['confirmscore'] == '0' && $dd['inscribed']==$dd['clan2'] && !$dd['einspruch']) 
                $status='&nbsp;<a style="text-decoration:none; cursor:help" onmouseover="showWMTT(\'waiting_confirmation\')" onmouseout="hideWMTT()"><font color="#FF6600"><b>WAIT</b></a>'; 
                
        elseif(!$dd['score1'] AND !$dd['score2']) 
                $status='&nbsp;<a style="text-decoration:none; cursor:help" onmouseover="showWMTT(\'no_score\')" onmouseout="hideWMTT()"><strong>PROGRESS</strong> <img src="images/cup/icons/faq.png"></a>';
                
        elseif($dd['confirmscore']=='1' && $dd['inscribed'] && $dd['einspruch']=='0')
                $status='&nbsp;<font color="#00CC00"><b>Confirmed</b></font>';
                
        elseif($dd['einspruch'])
                $status='&nbsp;<font color="#DD0000"><b>Protest</b></font>';
    
    }

 /* } */
 
            if($dd['clan1'] && $dd['clan2'])
                $status.='<a href="?site=cup_matches&match='.$dd['matchID'].'&'.$type2.'='.$ID.'&type=gs"><img border="0" src="images/icons/foldericons/folder.gif" align="right"></a>';
   
//end column 6
            
    if($name2($ID) && ($userID == $dd['clan1'] || $userID == $dd['clan2'])) {
       $color = $border; 
       $c6 = 'Action';
    }elseif($name2($ID)) {
       $color = $bg1;
       $c6 = 'Status';
    }
       
    if(!$name2($ID) && (isleader($userID,$dd['clan1']) || isleader($userID,$dd['clan2']))) {
       $color = $border;
       $c6 = 'Action';
    }elseif(!$name2($ID)) {
       $color = $bg1;
       $c6 = 'Status';
    }
       
    if($name2($ID)) {
       $flag1 = getusercountry($dd['clan1']);
       $flag2 = getusercountry2($dd['clan2']);
    }else{
       $flag1 = getclancountry1($dd['clan1']);
       $flag2 = getclancountry4($dd['clan2']);
    } 
    
    $ds1=mysql_fetch_array(safe_query("SELECT SUM(score1) as wonpoints FROM ".PREFIX."cup_matches WHERE matchno='$ID' && clan1='".$dd['clan1']."' && ($alpha_groups) && score1 > score2 ORDER BY score1 LIMIT 0,$limit"));
    $ds2=mysql_fetch_array(safe_query("SELECT SUM(score2) as wonpoints FROM ".PREFIX."cup_matches WHERE matchno='$ID' && clan2='".$dd['clan2']."' && ($alpha_groups) && score1 < score2 ORDER BY score1 LIMIT 0,$limit"));

	if(isset($_GET['match']) && $_GET['match']==$dd['matchID'] && !isset($_GET['ID'])){
	redirect('?site=groups&'.$type2.'='.$ID.'&match='.$dd['matchID'].'&ID=#'.$dd['matchID'], '', 0); }
	
	if(isset($_GET['match']) && $_GET['match']==$dd['matchID']) 
       $color = $selected_match;
       
    if($dd['confirmscore']) {
       $score_p1 = $dd['score1'];
       $score_p2 = $dd['score2'];
    }else{
       $score_p1 = 0;
       $score_p2 = 0;
    }
    

    //for($i = 1; $i <= 10; $i++) {
     
      $data_table = '<tr>
                       <td width="" bgcolor="'.$bg1.'" align="center" colspan="2">'.$flag1.' '.getname1($dd['clan1'],$ID,$ac=0,strtolower($name)).'</td>
                       <td width="" bgcolor="'.$bg1.'" align="center">('.$score_p1.') vs. ('.$score_p2.')</td>
                       <td width="" bgcolor="'.$bg1.'" align="center" colspan="2">'.$flag2.' '.$clan2.'</td>
                       <td width="" bgcolor="'.$bg1.'" align="center">'.date("d/m/Y H:i", $dd['date']).'</td>
                       <td width="" bgcolor="'.$bg1.'" align="center" colspan="2">'.$status.' '.$details.'</td>
                     </tr>';        

        
        if($dd[$type]=='a') {  
           $groupa.= $data_table;    
        }if($dd[$type]=='b') {
           $groupb.= $data_table;                  
        }if($dd[$type]=='c') {  
           $groupc.= $data_table;                  
        }if($dd[$type]=='d') { 
           $groupd.= $data_table;                  
        }if($dd[$type]=='e') {
           $groupe.= $data_table;                  
        }if($dd[$type]=='f') { 
           $groupf.= $data_table;                  
        }if($dd[$type]=='g') {    
           $groupg.= $data_table;                  
        }if($dd[$type]=='h') {   
           $grouph.= $data_table;                  
        }
           
        $no++;      
      } $no = 1;
      
      if($name2($ID)) $participant = 'Player';
      else $participant = 'Team';
      
          $table = '<tr>
                      <td class="title2" align="center" colspan="2">'.$participant.' 1</td>
                      <td class="title2" align="center">Score</td>
                      <td class="title2" align="center" colspan="2">'.$participant.' 2</td>
                      <td class="title2" align="center">Added/Play Date</td>
                      <td class="title2" align="center" colspan="2">'.$c6.'</td>
                    </tr>';
		    
    //weeks
    /*
    
    if($all_vs_all==true) {
    
        $query = safe_query("SELECT ma FROM ".PREFIX."cup_clans WHERE type='gs' && $type_opp='0' && groupID='$ID'");
        $dh = mysql_fetch_array($query);
	
	for ($i = 1; $i <= $dh['ma']; $i++) { 
	     $weeks .= '<tr>
	                 <td colspan="7" class="title2"></td>
	               </tr>';
	}
	
    }
    */
  //!weeks
		    
      if($ent_part) { 
      
		if($ds['maxclan']==8 || $ds['maxclan']==80) {
		
            echo '<table width="100%" cellspacing="'.$cellspacing.'" cellpadding="'.$cellpadding.'" bgcolor="'.$border.'">
                    <tr>';
                    
		  if($a_rows) { 
            echo ' 
                      <td class="title" colspan="8">Group A</td>
                    </tr>
                   '.$table.'
                   '.$groupa;
		  }if($b_rows) {
            echo ' 
                    <tr>
                      <td class="title" colspan="8">Group B</td>
                    </tr>
                   '.$table.'
                   '.$groupb; 
                   
		  }if($show_matchstats || ($finished && !$show_matchstats))
		  
            echo $stats_table.$stats_content.'';
            echo '</table>';                  
                      
		}elseif($ds['maxclan']==16 || $ds['maxclan']==160) {

            echo '<table width="100%" cellspacing="'.$cellspacing.'" cellpadding="'.$cellpadding.'" bgcolor="'.$border.'">
                    <tr>';
                    
		  if($a_rows) {
            echo ' 
                      <td class="title" colspan="8">Group A</td>
                    </tr>
                   '.$table.'
                   '.$groupa;
		  }if($b_rows) {
            echo ' 
                    <tr>
                      <td class="title" colspan="8">Group B</td>
                    </tr>
                   '.$table.'
                   '.$groupb; 
		  }if($c_rows) {
            echo ' 
                    <tr>
                      <td class="title" colspan="8">Group C</td>
                    </tr>
                   '.$table.'
                   '.$groupc; 
		  }if($d_rows) {
            echo ' 
                    <tr>
                      <td class="title" colspan="8">Group D</td>
                    </tr>
                   '.$table.'
                   '.$groupd; 
                   
		  }if($show_matchstats || ($finished && !$show_matchstats))
		  
            echo $stats_table.$stats_content.'';
            echo '</table>';
            
		}elseif($ds['maxclan']==32 || $ds['maxclan']==64 || $ds['maxclan']==320 || $ds['maxclan']==640) {
		
            echo '<table width="100%" cellspacing="'.$cellspacing.'" cellpadding="'.$cellpadding.'" bgcolor="'.$border.'">.
                    <tr>';
                    
		  if($a_rows) {
            echo ' 
                      <td class="title" colspan="8">Group A</td>
                    </tr>
                   '.$table.'
                   '.$groupa.'';
		  }if($b_rows) {
            echo ' 
                    <tr>
                      <td class="title" colspan="8">Group B</td>
                    </tr>
                   '.$table.'
                   '.$groupb.''; 
		  }if($c_rows) {
            echo ' 
                    <tr>
                      <td class="title" colspan="8">Group C</td>
                    </tr>
                   '.$table.'
                   '.$groupc.''; 
		  }if($d_rows) {
            echo ' 
                    <tr>
                      <td class="title" colspan="8">Group D</td>
                    </tr>
                   '.$table.'
                   '.$groupd.''; 
		  }if($e_rows) {
            echo ' 
                      <td class="title" colspan="8">Group E</td>
                    </tr>
                   '.$table.'
                   '.$groupe.'';
		  }if($f_rows) {
            echo ' 
                    <tr>
                      <td class="title" colspan="8">Group F</td>
                    </tr>
                   '.$table.'
                   '.$groupf.''; 
		  }if($g_rows) {
            echo ' 
                    <tr>
                      <td class="title" colspan="8">Group G</td>
                    </tr>
                   '.$table.'
                   '.$groupg.''; 
		  }if($h_rows) {
            echo ' 
                    <tr>
                      <td class="title" colspan="8">Group H</td>
                    </tr>
                   '.$table.'
                   '.$grouph.''; 
 
		  }if($show_matchstats || ($finished && !$show_matchstats))
		  
            echo $stats_table.$stats_content;
            echo '</table>';
      }   
	    
   }echo ($cpr ? ca_copyr() : die());
    
 }else //end if ID
     redirect('?site=cups', '', 0);

?>