<script language="javascript">wmtt = null; document.onmousemove = updateWMTT; function updateWMTT(e) { x = (document.all) ? window.event.x + document.body.scrollLeft : e.pageX; y = (document.all) ? window.event.y + document.body.scrollTop  : e.pageY; if (wmtt != null) { wmtt.style.left=(x + 20) + "px"; wmtt.style.top=(y + 20) + "px"; } } function showWMTT(id) {	wmtt = document.getElementById(id);	wmtt.style.display = "block" } function hideWMTT() { wmtt.style.display = "none"; }</script>

<div class="tooltip" id="entercup" align="left">Entering the cup gives you the ability to leave, your place is not guaranteed.</div>
<div class="tooltip" id="checkincup" align="left">Checkin guarantees your place in the cup but restricts your ability to leave.</div>
<div class="tooltip" id="leavecup" align="left">You can only leave the cup if you are entered and not checked-in.</div>
<div class="tooltip" id="gs" align="left">To register in this league you must first compete in the group stages.</div>


<?php
/* Cup SQL-Querys */
safe_query("UPDATE ".PREFIX."cups SET status='2' WHERE start<='".time()."'");
safe_query("UPDATE ".PREFIX."cups SET status='3' WHERE ende<='".time()."'");
safe_query("UPDATE ".PREFIX."cup_warnings SET expired='1' WHERE deltime<='".time()."'");
/**************/

/* Cup GLOBALS */

gettimezone();
include("config.php");

$bg1=BG_1;
$bg2=BG_1;
$bg3=BG_1;
$bg4=BG_1;
$cpr=ca_copyr();

$length_qn = 20;

  !$cpr || !ca_copyr() ? die() : '';
  
    if($_GET['type']=='ladders')
    {
       echo 'Showing: <b>Ladders</b> <br> <img src="images/cup/icons/go.png"> <a href="?site=quicknavi"><b>Show Tournaments</b></a>';
       $cup = "Ladders";
    }
    else
    {
       echo 'Showing: <b>Tournaments</b> <br> <img src="images/cup/icons/go.png"> <a href="?site=quicknavi&type=ladders"><b>Show Ladders</b></a>';
       $cup = "Tournaments";
    }
    
      if(!$_GET['type']) 
      {      
    
        $one_cups = safe_query("SELECT * FROM ".PREFIX."cups WHERE status='1' && 1on1='1'");
	  $a_rows = mysql_num_rows($one_cups);
           while($ds=mysql_fetch_array($one_cups))
            {   
                              
               $cupID = $ds['ID'];
               $start = date('d.m.Y \a\t H:i', $ds['start']);
               $is_groups = ($ds['gs_start'] <= time() && $ds['gs_end'] >= time() ? 1 : 0);
               $game = '<img src="images/games/'.$ds['game'].'.gif" border="0" align="left" width="20" height="20">';
               $register = (iscupparticipant($userID,$cupID) ? '(Already Entered)' : '<img border="0" src="images/cup/new_message.gif"> <a href="?site=clans&action=clanregister&cupID='.$cupID.'" onclick="return confirm(\'Cup starts on '.$start.'GMT. \');"><b>Signup</b></a>');
               $autocheckin = (iscupparticipant($userID,$cupID,$checkin=1) ? '(Already Checked)' : '<img border="0" src="images/cup/new_message.gif"> <a href="?site=clans&action=autocheckin&cupID='.$cupID.'" onclick="return confirm(\'Cup starts on '.$start.'GMT. \');"><b>Checkin</b></a>');
               $leavecup = (iscupparticipant($userID,$cupID,$checkin=2) ? ' <img border="0" src="images/cup/error.png" width="16" height="16"> <a href="index.php?site=clans&action=delclan&clanID='.$userID.'&cupID='.$dr['ID'].'&one=1" onclick="return confirm(\'Are you sure you want to leave the '.getcupname($dr['ID']).' cup?\');"><font color="#FF0000"><b>Leave Cup</b></font></a>' : '<img src="images/cup/error.png" width="16" height="16">');           
               $cupalpha = getalphacupname($cupID);
                               
                if(isset($_GET['cup']) && $_GET['cup']==$cupalpha && !isset($_GET['bm'])){
                redirect('?site=quicknavi&cup='.$cupalpha.'&bm=#'.getcupname($cupID), '', 0); }
	
                if(isset($_GET['cup']) && $_GET['cup']==$cupalpha) 
                {
                   $bgcolor1 = $selected_cup_quicknavi; 
                }
                else
                { 
                   $bgcolor1 = $bg1; 
                }
		
		$str_qn = strlen(getcupname($cupID))-$length_qn;
		 
	        if(strlen(getcupname($cupID)) > $length_qn) {
	                $cupname_qn = substr(getcupname($cupID), 0, -$str_qn).'...';
	        }
	        else{
	                $cupname_qn = getcupname($cupID);
	        }
            
                 $onecup .= '<tr>
			                   <td bgcolor="'.($bgcolor1 ? $bgcolor1 : $bg1).'" align="center">'.$game.' <a name="'.getcupname($cupID).'" href="?site=cups&action=details&cupID='.$cupID.'">'.$cupname_qn.'</a></td>
			                   <td bgcolor="'.($bgcolor1 ? $bgcolor1 : $bg1).'" align="center">'.$register.'</td>	
			                   <td bgcolor="'.($bgcolor1 ? $bgcolor1 : $bg1).'" align="center">'.$autocheckin.'</td>
			                   <td bgcolor="'.($bgcolor1 ? $bgcolor1 : $bg1).'" align="center">'.$leavecup.'</td>
			                   <td bgcolor="'.($bgcolor1 ? $bgcolor1 : $bg1).'" align="center">'.($is_groups ? "<img src=\"images/cup/icons/groups.png\">" : "<img src=\"images/cup/error.png\" width=\"16\" height=\"16\">").'</td>	
			                 </tr>';	 

       
           }
	   
           if(empty($a_rows)) $onecup = '<tr><td bgcolor="'.$bg1.'" align="center" colspan="5">-- No current 1on1 tournaments in signup-phase --</td>';
           
        $team_cups = safe_query("SELECT * FROM ".PREFIX."cups WHERE status='1' && 1on1='0'");
	  $b_rows = mysql_num_rows($team_cups);
            while($ds=mysql_fetch_array($team_cups))
             {    
             
	             $members = safe_query("SELECT * FROM ".PREFIX."cup_clan_members WHERE userID = '".$userID."' AND function = 'Leader'");	
                   
                  if(!$loggedin) $clan = '<option value="0">(must login)</option>';
                  elseif(!mysql_num_rows($members)) $clan = '<option value="0">(no team)</option>';

                  $start = date('d.m.Y \a\t H:i', $ds['start']); 	          
	
	               $clan = '<option value="0" selected="selected"> - Select Team - </option>';	
	                 while($dm=mysql_fetch_array($members)) {                       
	               $clan .= '<option name="clanID" value="'.$dm['clanID'].'">'.getclanname($dm['clanID']).'</option>';
                 }
            
            
                 $detail = '<form action="index.php">
                                 <input type="hidden" name="site" value="clans">
                                 <input type="hidden" name="action" value="clanregister">
                                 <input type="hidden" name="cupID" value="'.$ds['ID'].'">
                                 <select name="clanID">'.$clan.'</select>
                                 <input type="submit" value="Go" onclick="return confirm(\'Cup starts on '.$start.' '.$gmt.' \');">
                          </form>'; 
                       
                 $autocheckin = '<form action="index.php">
                                   <input type="hidden" name="site" value="clans">
                                   <input type="hidden" name="action" value="autocheckin">
                                   <input type="hidden" name="cupID" value="'.$ds['ID'].'">
                                   <select name="clanID">'.$clan.'</select>
                                   <input type="submit" value="Go" onclick="return confirm(\'This will check your selected team in the '.getcupname($ds['ID']).' cup. Upon success, you will be unable to leave this cup or change your lineup. Will your team be ready by '.$start.' '.$gmt.'?\');">
                                 </form>';
                                 
                 $leavecup = '<form action="index.php">
                                <input type="hidden" name="site" value="clans">
                                <input type="hidden" name="action" value="leavecup">
                                <input type="hidden" name="cupID" value="'.$ds['ID'].'">
                                <select name="clanID">'.$clan.'</select>
                                <input type="submit" value="Go" onclick="return confirm(\'Are you sure you want the selected team to leave the '.getcupname($ds['ID']).' cup?\');">
                              </form>';
                       
                 $cupID = $ds['ID'];
                 $start = date('d.m.Y \a\t H:i', $ds['start']);
                 $is_groups = ($ds['gs_start'] <= time() && $ds['gs_end'] >= time() ? 1 : 0);
                 $game = '<img src="images/games/'.$ds['game'].'.gif" border="0" align="left" width="20" height="20">';
                 $register = (iscupparticipant($userID,$cupID) ? '(Already Entered)' : $detail);
                 $autocheckin = (iscupparticipant($userID,$cupID,$checkin=1) ? '(Already Checked)' : $autocheckin);
                 $leavecup = (iscupparticipant($userID,$cupID,$checkin=2) ? $leavecup : '<img src="images/cup/error.png" width="16" height="16">');   
                 $cupalpha = getalphacupname($cupID);  
                 
                 if(isset($_GET['cup']) && $_GET['cup']==$cupalpha && !isset($_GET['bm'])){
                 redirect('?site=quicknavi&cup='.$cupalpha.'&bm=#'.getcupname($cupID), '', 0); }
	
                 if(isset($_GET['cup']) && $_GET['cup']==$cupalpha) 
                 { 
                    $bgcolor2 = $selected_cup_quicknavi; 
                 }
                 else
                 { 
                    $bgcolor2 = $bg1; 
                 }
		 
		 $str_qn = strlen(getcupname($cupID))-$length_qn;
		 
	         if(strlen(getcupname($cupID)) > $length_qn) {
	                 $cupname_qn = substr(getcupname($cupID), 0, -$str_qn).'...';
	         }
	         else{
	                 $cupname_qn = getcupname($cupID);
	         }
            
                 $cups .= '<tr>
			                   <td bgcolor="'.($bgcolor2 ? $bgcolor2 : $bg1).'" align="center">'.$game.' <a name="'.getcupname($cupID).'" href="?site=cups&action=details&cupID='.$cupID.'">'.$cupname_qn.'</a></td>
			                   <td bgcolor="'.($bgcolor2 ? $bgcolor2 : $bg1).'" align="center">'.$register.'</td>	
			                   <td bgcolor="'.($bgcolor2 ? $bgcolor2 : $bg1).'" align="center">'.$autocheckin.'</td>
			                   <td bgcolor="'.($bgcolor2 ? $bgcolor2 : $bg1).'" align="center">'.$leavecup.'</td>	
			                   <td bgcolor="'.($bgcolor2 ? $bgcolor2 : $bg1).'" align="center">'.($is_groups ? "<img src=\"images/cup/icons/groups.png\">" : "<img src=\"images/cup/error.png\" width=\"16\" height=\"16\">").'</td>
			                 </tr>';		

        
           }
           if(empty($b_rows)) $cups = '<tr><td bgcolor="'.$bg1.'" align="center" colspan="5">-- No current team tournaments in signup-phase --</td>';
       }
       elseif($_GET['type']=='ladders')
       {
     
         $one_cups = safe_query("SELECT * FROM ".PREFIX."cup_ladders WHERE (status='1' || status='2') && 1on1='1'");
	  $a_rows = mysql_num_rows($one_cups);
           while($ds=mysql_fetch_array($one_cups))
            {   
            
               $cupID = $ds['ID'];
               $start = date('d.m.Y \a\t H:i', $ds['start']);
               $is_groups = ($ds['gs_start'] <= time() && $ds['gs_end'] >= time() ? 1 : 0);
               $game = '<img src="images/games/'.$ds['game'].'.gif" border="0" align="left" height="20" width="20">';
               $register = (isladparticipant($userID,$cupID) ? '(Already Entered)' : '<img border="0" src="images/cup/new_message.gif"> <a href="?site=ladders&action=register&clanID='.$userID.'&laddID='.$cupID.'" onclick="return confirm(\'Ladder starts on '.$start.' GMT. \');"><b>Signup</b></a>');
               $autocheckin = (isladparticipant($userID,$cupID,$checkin=1) ? '(Already Checked)' : '<img border="0" src="images/cup/new_message.gif"> <a href="?site=ladders&action=register&clanID='.$userID.'&checkin=1&laddID='.$cupID.'" onclick="return confirm(\'Ladder starts on '.$start.' GMT ? \');"><b>Checkin</b></a>');
               $leavecup = (isladparticipant($userID,$cupID,$checkin=2) ? ' <img border="0" src="images/cup/error.png" width="16" height="16"> <a href="?site=ladders&action=leave&clanID='.$userID.'&laddID='.$cupID.'" onclick="return confirm(\'Are you sure you want to leave the '.getladname($cupID).' ladder?\');"><font color="#FF0000"><b>Leave Ladder</b></font></a>' : '<img src="images/cup/error.png" width="16" height="16">');           
               $cupalpha = getalphaladname($cupID);
               
                 if(isset($_GET['cup']) && $_GET['cup']==$cupalpha && !isset($_GET['bm'])){
                 redirect('?site=quicknavi&cup='.$cupalpha.'&type=ladders&bm=#'.getladname($cupID), '', 0); }
	
                 if(isset($_GET['cup']) && $_GET['cup']==$cupalpha) {
                    $bgcolor3 = $selected_cup_quicknavi; 
                 }
                 else{ 
                    $bgcolor3 = $bg1; 
                 }
		 
		 $str_qn = strlen(getladname($cupID))-$length_qn;
		 
	         if(strlen(getladname($cupID)) > $length_qn) {
	                 $cupname_qn = substr(getladname($cupID), 0, -$str_qn).'...';
	         }
	         else{
	                 $cupname_qn = getladname($cupID);
	         }
              
                 $onecup .= '<tr>
			                   <td bgcolor="'.($bgcolor3 ? $bgcolor3 : $bg1).'" align="center">'.$game.' <a name="'.getladname($cupID).'" href="?site=ladders&ID='.$cupID.'">'.$cupname_qn.'</a></td>
			                   <td bgcolor="'.($bgcolor3 ? $bgcolor3 : $bg1).'" align="center">'.$register.'</td>	
			                   <td bgcolor="'.($bgcolor3 ? $bgcolor3 : $bg1).'" align="center">'.$autocheckin.'</td>
			                   <td bgcolor="'.($bgcolor3 ? $bgcolor3 : $bg1).'" align="center">'.$leavecup.'</td>
			                   <td bgcolor="'.($bgcolor3 ? $bgcolor3 : $bg1).'" align="center">'.($is_groups ? "<img src=\"images/cup/icons/groups.png\">" : "<img src=\"images/cup/error.png\" width=\"16\" height=\"16\">").'</td>	
			                 </tr>'; 
            
       
           }
	   
           if(empty($a_rows)) $onecup = '<tr><td bgcolor="'.$bg1.'" align="center" colspan="5">-- No current 1on1 ladders in signup-phase --</td>';
           
         $team_cups = safe_query("SELECT * FROM ".PREFIX."cup_ladders WHERE (status='1' || status='2') && 1on1='0'");
	  $b_rows = mysql_num_rows($team_cups);
           while($ds=mysql_fetch_array($team_cups))
            {    
       
	             $members = safe_query("SELECT * FROM ".PREFIX."cup_clan_members WHERE userID = '".$userID."' AND function = 'Leader'");	
	             
                  if(!$loggedin) $clan = '<option value="0">(must login)</option>';
                  elseif(!mysql_num_rows($members)) $clan = '<option value="0">(no team)</option>';  		 
	
	               $clan = '<option value="0" selected="selected"> - Select Team - </option>';	
	                 while($dm=mysql_fetch_array($members)) {                       
	               $clan .= '<option name="clanID" value="'.$dm['clanID'].'">'.getclanname($dm['clanID']).'</option>';
                 }

                 $cupID = $ds['ID'];
                 $start = date('d.m.Y \a\t H:i', $ds['start']);

            
            
                 $detail = '<form action="index.php">
                                 <input type="hidden" name="site" value="ladders">
                                 <input type="hidden" name="action" value="register">
                                 <input type="hidden" name="checkin" value="0">    
                                 <input type="hidden" name="laddID" value="'.$ds['ID'].'">
                                 <select name="clanID">'.$clan.'</select>
                                 <input type="submit" value="Go" onclick="return confirm(\'Ladder starts on '.$start.' '.$gmt.' \');">
                          </form>'; 
                       
                 $autocheckin = '<form action="index.php">
                                   <input type="hidden" name="site" value="ladders">
                                   <input type="hidden" name="action" value="register"> 
                                   <input type="hidden" name="checkin" value="1">
                                   <input type="hidden" name="laddID" value="'.$ds['ID'].'">
                                   <select name="clanID">'.$clan.'</select>
                                   <input type="submit" value="Go" onclick="return confirm(\'Ladder starts on '.$start.' '.$gmt.'?\');">
                                 </form>';
                                 
                 $leavecup = '<form action="index.php">
                                <input type="hidden" name="site" value="ladders">
                                <input type="hidden" name="action" value="leave">
                                <input type="hidden" name="laddID" value="'.$ds['ID'].'">
                                <select name="clanID">'.$clan.'</select>
                                <input type="submit" value="Go" onclick="return confirm(\'Are you sure you want the selected team to leave the '.getladname($ds['ID']).' ladder?\');">
                              </form>';

                 $is_groups = ($ds['gs_start'] <= time() && $ds['gs_end'] >= time() ? 1 : 0);
                 $game = '<img src="images/games/'.$ds['game'].'.gif" border="0" align="left" width="20" height="20">';
                 $register = (isladparticipant($userID,$cupID) ? '(Already Entered)' : $detail);
                 $autocheckin = (isladparticipant($userID,$cupID,$checkin=1) ? '(Already Checked)' : $autocheckin);
                 $leavecup = (isladparticipant($userID,$cupID,$checkin=2) ? $leavecup : '<img src="images/cup/error.png" width="16" height="16">');    
                 $cupalpha = getalphaladname($cupID);
                       
                 
                 if(isset($_GET['cup']) && $_GET['cup']==$cupalpha && !isset($_GET['bm'])){
                 redirect('?site=quicknavi&type=ladders&cup='.$cupalpha.'&bm=#'.getladname($cupID), '', 0); }
	
                 if(isset($_GET['cup']) && $_GET['cup']==$cupalpha) 
                 {
                    $bgcolor4 = $selected_cup_quicknavi; 
                 }
                 else
                 { 
                    $bgcolor4 = $bg1; 
                 }
       
		 $str_qn = strlen(getladname($cupID))-$length_qn;
		 
	         if(strlen(getladname($cupID)) > $length_qn) {
	                 $cupname_qn = substr(getladname($cupID), 0, -$str_qn).'...';
	         }
	         else{
	                 $cupname_qn = getladname($cupID);
	         }
            
                 $cups .= '<tr>
			                   <td bgcolor="'.($bgcolor4 ? $bgcolor4 : $bg1).'" align="center">'.$game.' <a name="'.getladname($cupID).'" href="?site=ladders&ID='.$cupID.'">'.$cupname_qn.'</a></td>
			                   <td bgcolor="'.($bgcolor4 ? $bgcolor4 : $bg1).'" align="center">'.$register.'</td>	
			                   <td bgcolor="'.($bgcolor4 ? $bgcolor4 : $bg1).'" align="center">'.$autocheckin.'</td>
			                   <td bgcolor="'.($bgcolor4 ? $bgcolor4 : $bg1).'" align="center">'.$leavecup.'</td>	
			                   <td bgcolor="'.($bgcolor4 ? $bgcolor4 : $bg1).'" align="center">'.($is_groups ? "<img src=\"images/cup/icons/groups.png\">" : "<img src=\"images/cup/error.png\" width=\"16\" height=\"16\">").'</td> 
			                 </tr>';	
       
           }
           if(empty($b_rows)) $cups = '<tr><td bgcolor="'.$bg1.'" align="center" colspan="5">-- No current team ladders in signup-phase --</td>';
       }

          $enter_info = '<a name="entercup" style="text-decoration:none; cursor:help" onmouseover="showWMTT(\'entercup\')" onmouseout="hideWMTT()"><b>Enter Cup</b></a>';
          $checkin_info = '<a name="checkincup" style="text-decoration:none; cursor:help" onmouseover="showWMTT(\'checkincup\')" onmouseout="hideWMTT()"><b>Checkin Cup</b></a>';
          $leavecup_info = '<a name="leavecup" style="text-decoration:none; cursor:help" onmouseover="showWMTT(\'leavecup\')" onmouseout="hideWMTT()"><b>Leave Cup</b></a>';
          $gs_info = '<a name="gs" style="text-decoration:none; cursor:help" onmouseover="showWMTT(\'gs\')" onmouseout="hideWMTT()"><b>Groups?</b></a>';

             eval ("\$inctemp = \"".gettemplate("quicknavi")."\";");
             echo $inctemp.($cpr ? ca_copyr() : die());

?>