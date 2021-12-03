<link href="cup.css" rel="stylesheet" type="text/css">
<script type="text/javascript">
            wmtt = null; document.onmousemove = updateWMTT; function updateWMTT(e) { x = (document.all) ? window.event.x + document.body.scrollLeft : e.pageX; y = (document.all) ? window.event.y + document.body.scrollTop  : e.pageY; if (wmtt != null) { wmtt.style.left=(x + 20) + "px"; wmtt.style.top=(y + 20) + "px"; } } function showWMTT(id) {	wmtt = document.getElementById(id);	wmtt.style.display = "block" } function hideWMTT() { wmtt.style.display = "none"; }
            $("#txt1").keyup(function () {
                $("#btn1").attr("disabled", $.trim($("#txt1").val()) != "" ? "" : "disabled");
            });
</script>

<div class="tooltip" id="league_selection" align="left"><div style="width: 100px; background-color: <?php echo $loosecolor; ?>; color: white; padding: 1px;">Unavailable</div><div style="width: 100px; background-color: <?php echo $drawcolor; ?>; color: white; padding: 1px;">Already Registered</div><div style="width: 100px; background-color: <?php echo $wincolor; ?>; color: white; padding: 1px;">Available</div></div>

<?php
include("config.php");

$bg1 = BG_1;
$bg2 = BG_2;
$bg3 = BG_3;
$bg4 = BG_4;

$sys_err = '<font color="'.$loosecolor.'"><strong>Fatal error: Please seek assistance</strong></font>';

$ID=($_GET['cupID'] ? $_GET['cupID'] : $_GET['ladderID']);
$type=($_GET['cupID'] ? 'cupID' : 'ladderID');
$table=($_GET['cupID'] ? 'cups' : 'cup_ladders');

if(isset($_POST['post'])) {

   if($loggedin) {
   
      $cupID= ($_GET['cupID'] ? $_GET['cupID'] : "0");
      $ladID=(!$_GET['cupID'] ? $_GET['ladderID'] : "0");
      $type=  ($_GET['cupID'] ? 'cupID' : 'ladderID');
      $table= ($_GET['cupID'] ? 'cups' : 'cup_ladders');
      
      $error_array = array();
      
      if(empty($userID)) {
         $error_array[] = "No userID defined.";
      }
      if(empty($cupID) && empty($ladID)) {
         $error_array[] = "No league ID defined.";
      }
      if(empty($_POST['avail'])) {
         $error_array[] = "No availability defined.";
      }
      if(empty($_POST['name'])) {
         $error_array[] = "No gametag/name defined.";
      }
      if(empty($_POST['play'])) {
         $error_array[] = "No 'why play' defined.";
      }
      if($_POST['avail']==1) {
         $error_array[] = "Sorry, you must be an available agent.";
      }
      if(empty($_POST['league'])) {
         $error_array[] = "No valid league defined.";
      }
      
	if(count($error_array)) 
	{
		$error=implode('<br />&#8226; ', $error_array);
		$showerror = '<div class="errorbox">
		  <b>Errors Detected:</b><br /><br />
		  &#8226; '.$error.'
		</div>';
		echo $showerror.'<br /><input type="button" class="button" onClick="javascript:history.back()" value="Go back">';

	}
	else
	    {
	
        	if(safe_query("INSERT INTO ".PREFIX."cup_agents (`userID`, `cupID`, `ladID`, `avail`, `name`, `play`, `info`, `time`) VALUES ('$userID', '$cupID', '$ladID', '".$_POST['avail']."', '".$_POST['name']."', '".$_POST['play']."', '".$_POST['info']."', '".time()."')")) 
        	{
                   redirect('?site=freeagents&agent='.mysql_insert_id(), 'You are now a free agent and being redirected...', 3);
        	}
        	else
        	{
           	   echo $sys_err;
        	}
	}
    }
}

echo '<table width="100%" cellspacing="'.$cellspacing.'" cellpadding="'.$cellpadding.'" bgcolor="'.$border.'">
        <tr>
	      <td class="title" colspan="6"><img src="images/cup/icons/freeagents.png" width="16" height="16"> Free Agents</title>
	     </tr>
	      <tr>';
	                      
                 if(isset($_GET['action']) && $_GET['action']=='new') {

                   echo '<td class="title2" colspan="6">Looking to Recruit</td>
	                  <tr>
	                   <td bgcolor="'.$bg1.'"  colspan="6"><input type="button" value="View Free Agents" onClick="window.location=\'?site=freeagents&action=view\'"></td>
	                 </tr>    ';

                 }
                 elseif(isset($_GET['action']) && $_GET['action']=='view') {

                   echo '<td class="title2" colspan="6">Looking to Join</td>
	                  <tr>
	                   <td bgcolor="'.$bg1.'"  colspan="6"><input type="button" value="Register as Free Agent" onClick="window.location=\'?site=freeagents&action=new\'"></td>
	                 </tr>';

                 }
                 else{

                   echo '<tr>
		           <td class="title2" width="50%">Looking to Join</td>
	                   <td class="title2" width="50%">Looking to Recruit</td>
	                 </tr>
	                  <tr>
	                   <td bgcolor="'.$bg1.'" width="50%"><input type="button" value="Register as Free Agent" onClick="window.location=\'?site=freeagents&action=new\'"></td>
	                   <td bgcolor="'.$bg1.'" width="50%"><input type="button" value="View Free Agents" onClick="window.location=\'?site=freeagents&action=view\'"></td>
	                 </tr>';
                 }

if(isset($_GET['action']) && $_GET['action']=='new') {

  $cin=mysql_fetch_array(safe_query("SELECT userID FROM ".PREFIX."cup_clan_members WHERE userID='$userID' && agent='1'"));

  if($loggedin && !is_array($cin)) {
  
   $cups = '<option value="0" selected">-- Select League --</option><optgroup label="Tournaments">';

    $query = safe_query("SELECT * FROM ".PREFIX."cups WHERE 1on1='0' ORDER BY ID DESC");
      while($cids1=mysql_fetch_array($query))
      {
      
        $db=mysql_fetch_array(safe_query("SELECT * FROM ".PREFIX."cup_agents WHERE userID='$userID' && cupID='".$cids1['ID']."'"));
      
        if(is_array($db)) 
	{
            $cups .= '<option value="0" id="btn1" disabled="disabled" STYLE="color: #FFFFFF; font-weight: bold; font-size: 12px; background-color: '.$drawcolor.';">'.getcupname($cids1['ID']).' '.($cids1['gs_start'] <= time() && $cids1['gs_end'] >= time() ? 'Group Stages' : '').'</option>';
	    $fa[] = false;
	}
        elseif(!is_array($db) && $cids1['status']==1 && $cids1['gs_start'] <= time() && $cids1['gs_end'] >= time() && !isgroupparticipant2($userID,$cids1['ID'],$var='cup')) 
        {
            $cups .= '<option value="?site=freeagents&action=new&cupID='.$cids1['ID'].'" STYLE="color: #FFFFFF; font-weight: bold; font-size: 12px; background-color: '.$wincolor.';">'.getcupname($cids1['ID']).' (Group Stages)</option>';
            $fa[] = true;
        }   
        elseif(!is_array($db) && $cids1['status']==1 && !iscupparticipant_memin($userID,$cids1['ID'],$checkin=0)) 
        {   
            $cups .= '<option value="?site=freeagents&action=new&cupID='.$cids1['ID'].'" STYLE="color: #FFFFFF; font-weight: bold; font-size: 12px; background-color: '.$wincolor.';">'.getcupname($cids1['ID']).'</option>';
            $fa[] = true;
        }
        else
        {
            $cups .= '<option value="0" id="btn1" disabled="disabled" STYLE="color: #FFFFFF; font-weight: bold; font-size: 12px; background-color: '.$loosecolor.';">'.getcupname($cids1['ID']).' '.($cids1['gs_start'] <= time() && $cids1['gs_end'] >= time() ? 'Group Stages' : '').'</option>';
	    $fa[] = false;
        }	
      }
      
    $cups .= '<optgroup label="Ladders">';
      
    $query = safe_query("SELECT * FROM ".PREFIX."cup_ladders WHERE 1on1='0' ORDER BY ID DESC");
      while($cids2=mysql_fetch_array($query))
      {
      
        $db=mysql_fetch_array(safe_query("SELECT * FROM ".PREFIX."cup_agents WHERE userID='$userID' && ladID='".$cids2['ID']."'"));
      
        if(is_array($db)) 
	{
            $cups .= '<option value="0" id="btn1" disabled="disabled" STYLE="color: #FFFFFF; font-weight: bold; font-size: 12px; background-color: '.$drawcolor.';">'.getladname($cids2['ID']).' '.($cids2['gs_start'] <= time() && $cids2['gs_end'] >= time() ? 'Group Stages' : '').'</option>';
	    $fa[] = false;
	}
        elseif(!is_array($db) && $cids2['status']==1 && $cids2['gs_start'] <= time() && $cids2['gs_end'] >= time() && !isgroupparticipant($userID,$cids2['ID'],$var='ladder')) 
        {   
            $cups .= '<option value="?site=freeagents&action=new&ladderID='.$cids2['ID'].'" STYLE="color: #FFFFFF; font-weight: bold; font-size: 12px; background-color: '.$wincolor.';">'.getladname($cids2['ID']).' (Group Stages)</option>';
            $fa[] = true;
        }
        elseif(!is_array($db) && $cids2['status']==1 && !isladparticipant_memin($userID,$cids2['ID'],$checkin=0)) 
        {   
            $cups .= '<option value="?site=freeagents&action=new&ladderID='.$cids2['ID'].'" STYLE="color: #FFFFFF; font-weight: bold; font-size: 12px; background-color: '.$wincolor.';">'.getladname($cids2['ID']).'</option>';
            $fa[] = true;
        }
        else
        {
            $cups .= '<option value="0" id="btn1" disabled="disabled" STYLE="color: #FFFFFF; font-weight: bold; font-size: 12px; background-color: '.$loosecolor.';">'.getladname($cids2['ID']).' '.($cids2['gs_start'] <= time() && $cids2['gs_end'] >= time() ? 'Group Stages' : '').'</option>';
	    $fa[] = false;
        }
      }     

	$cups=str_replace(' selected', '', $cups);
	$cups=str_replace('value="?site=freeagents&action=new&'.$type.'='.$ID.'"', 'value="?site=freeagents&action=new&'.$type.'='.$ID.'" selected', $cups);
	
	$dd_avail='<option value="1">No</option><option value="2">Maybe</option><option value="3">Yes</option>';
	$dd_avail=str_replace(' selected', '', $dd_avail);
	$dd_avail=str_replace('value="'.$_POST['avail'].'"', 'value="'.$_POST['avail'].'" selected', $dd_avail);
        
	$cup_info=mysql_fetch_array(safe_query("SELECT * FROM ".PREFIX."$table WHERE ID='$ID'"));
	
	if(!is_array($cup_info) && isset($_GET[$type])) {
         	die($sys_err);
	}
	
   if(!$fa) { echo 'There are no leagues in Sign-up phase.'; }

   echo '<tr>
           <td class="title" colspan="6"><img src="images/cup/icons/new_freeagent.png"> New Free Agent</td>
     	 </tr>
	 <form action="" name="post" method="post">
	      <tr>
	       <td bgcolor="'.$pagebg.'" colspan="6"><img src="images/cup/icons/contact_info.png"> Available leagues must be on sign-up phase.</td>
	     </tr>
	      <tr>
	       <td bgcolor="'.$bg1.'">League</td>
	       <td bgcolor="'.$bg1.'"><select name="league" ONCHANGE="location = this.options[this.selectedIndex].value;">'.$cups.'</select> <a name="league_selection" style="text-decoration:none; cursor:help" onmouseover="showWMTT(\'league_selection\')" onmouseout="hideWMTT()"><img border="0" src="images/cup/icons/faq.png"></a></td>
	     </tr>';
	     
	     if(isset($_GET[$type]))
	     
	     echo '<tr>
		     <td bgcolor="'.$bg1.'">Availability</td>
		     <td bgcolor="'.$bg1.'"><strong>'.date('l M dS Y \@\ g:i a', $cup_info['start']).'</strong><br />
		                            <select name="avail"><option value="0" selected="selected">-- Are you available on the above date/time? --</option>'.$dd_avail.'</select></td>
	           </tr>
		 <tr>
		   <td bgcolor="'.$bg1.'">Gamertag/name</td>
		   <td bgcolor="'.$bg1.'"><input type="text" name="name" value="'.$_POST['name'].'"></td>
	     </tr>
		 <tr>
		   <td bgcolor="'.$bg1.'">Why play?</td>
		   <td bgcolor="'.$bg1.'"><input type="text" name="play" value="'.$_POST['play'].'"></td>
	     </tr>
		 <tr>
		   <td bgcolor="'.$bg1.'">Additional Info</td>
		   <td bgcolor="'.$bg1.'"><input type="text" name="info" value="'.$_POST['info'].'"></td>
	     </tr>
	      <tr>
	       <td bgcolor="'.$loosecolor.'" colspan="6"><img src="images/cup/icons/warning.png"> You will be automatically removed if you are already a participant of the league.</td>
	     </tr>
		  <tr>
		   <td bgcolor="'.$bg1.'"></td>
		   <td bgcolor="'.$bg1.'"><input type="submit" name="post" value="Register as Free Agent"></td>
	     </tr>
	     </form>';
	
   }
   else{
  
      echo '<tr><td bgcolor="'.$loosecolor.'" colspan="6">&nbsp; &#8226; <strong>You are not logged in or already an agent for another team.</strong></td></tr>';
    
  }
}
elseif(isset($_GET['action']) && $_GET['action']=='view') {

   if(isset($_GET['cupID']) && $_GET['cupID']) {
      $query = 'WHERE cupID='.$_GET['cupID'].' && ladID=\'0\'';
   }
   elseif(isset($_GET['laddID']) && $_GET['laddID']) {
      $query = 'WHERE ladID='.$_GET['laddID'].' && cupID=\'0\'';
   }
   else{
      $query = '';
   }

   echo '<tr>
           <td class="title" colspan="6"><img src="images/cup/icons/yourteams.png" width="16" height="16"> Players looking to be recruited.</td>
	     </tr>
	      <tr>
	       <td class="title2">Agent</td>
	       <td class="title2" align="center">League</td>
	       <td class="title2" align="center">Availability</td>
	       <td class="title2" align="center">Awards</td>
	       <td class="title2" align="center">Skill</td>
	       <td class="title2" align="center">Details</td>
	     </tr>';
	     
	     $freeagents = safe_query("SELECT * FROM ".PREFIX."cup_agents $query");
	        while($ds= mysql_fetch_array($freeagents)) {
		
		   if($ds['cupID']==0 && $ds['ladID']!=0) {
		      $league_name = '<a href="?site=ladders&ID='.$ds['ladID'].'">'.getladdername($ds['ladID']).'</a>';
		      $league_type = "ladder";
		   }
		   elseif($ds['ladID']==0 && $ds['cupID']!=0) {
		      $league_name = '<a href="?site=cups&action=details&cupID='.$ds['cupID'].'">'.getcupname($ds['cupID']).'</a>';
		      $league_type = "cup";
		   }
		   
		   $cin=mysql_fetch_array(safe_query("SELECT userID FROM ".PREFIX."cup_clan_members WHERE userID='".$ds['userID']."' && agent='1'"));
		   
		   if(is_array($cin)) {
		      safe_query("DELETE FROM ".PREFIX."cup_agents WHERE userID='".$ds['userID']."'");
		   }		   
		   if($league_type=='ladder' && isladparticipant_memin($ds['userID'],$ds['ladID'])) {
                      safe_query("DELETE FROM ".PREFIX."cup_agents WHERE userID='".$ds['userID']."'");
                   }
		   if($league_type=='cup' && iscupparticipant_memin($ds['userID'],$ds['cupID'])) {
                      safe_query("DELETE FROM ".PREFIX."cup_agents WHERE userID='".$ds['userID']."'");
                   }
		   
		   
		   //tournament awards
		   
		   getclanawards($ds['userID'],1);
		   $award1 = '';
		   $award2 = '';
		   $award3 = '';
		   if($ar_awards[1]){
		   	   for($i=1; $i<=$ar_awards[1]; $i++)
			   	   $award1.='<a href="?site=brackets&action=tree&cupID='.$ar1_name[$i-1].'"><img src="images/cup/icons/award_gold.png" border="0" alt="Gold" title="'.getcupname($ar1_name[$i-1]).'" /></a>'; 
		   }
		   if($ar_awards[2]){
			   for($i=1; $i<=$ar_awards[2]; $i++)
				   $award2.='<a href="?site=brackets&action=tree&cupID='.$ar2_name[$i-1].'"><img src="images/cup/icons/award_silver.png" border="0" alt="'.$_language->module['silver'].'" title="'.getcupname($ar2_name[$i-1]).'" /></a>';
		   }
		   if($ar_awards[3]){
			   for($i=1; $i<=$ar_awards[3]; $i++)
				   $award3.='<a href="?site=brackets&action=tree&cupID='.$ar3_name[$i-1].'"><img src="images/cup/icons/award_bronze.png" border="0" alt="Bronze" title="'.getcupname($ar3_name[$i-1]).'" /></a>';
		   }		
		   $awards=$award1.$award2.$award3;
	
		   if(!empty($awards)) 
		   $tourn_awards.=$awards;
		   else $tourn_awards = '';
		   
		   //ladder awards
		   
		   getclanawards_lad($ds['userID'],1);
		   $award1 = '';
		   $award2 = '';
		   $award3 = '';
		   if($ar_awards[1]){
		   	   for($i=1; $i<=$ar_awards[1]; $i++)
			   	   $award1.='<a href="?site=standings&ladderID='.$ar1_name[$i-1].'"><img src="images/cup/icons/award_gold.png" border="0" alt="Gold" title="'.getladname($ar1_name[$i-1]).'" /></a>'; 
		   }
		   if($ar_awards[2]){
			   for($i=1; $i<=$ar_awards[2]; $i++)
				   $award2.='<a href="?site=standings&ladderID='.$ar2_name[$i-1].'"><img src="images/cup/icons/award_silver.png" border="0" alt="'.$_language->module['silver'].'" title="'.getladname($ar2_name[$i-1]).'" /></a>';
		   }
		   if($ar_awards[3]){
			   for($i=1; $i<=$ar_awards[3]; $i++)
				   $award3.='<a href="?site=standings&ladderID='.$ar3_name[$i-1].'"><img src="images/cup/icons/award_bronze.png" border="0" alt="Bronze" title="'.getladname($ar3_name[$i-1]).'" /></a>';
		   }		
		   $awards=$award1.$award2.$award3;
	
		   if(!empty($awards))
		   $ladd_awards.=$awards;
		   else $ladd_awards = '';
		   
		   if(empty($tourn_awards) && empty($ladd_awards)) {
		      $award_ck = "<img src='images/cup/icons/nok_32.png' width='16' height='16'>";
		   }
		   else{
		      $award_ck = "<img src='images/cup/icons/ok_32.png' width='16' height='16'>";
		   }
		   
		   
		   echo '<tr>
		           <td bgcolor="'.$bg1.'">'.getusercountry3($ds['userID']).' <a href="?site=profile&id='.$ds['userID'].'">'.getnickname($ds['userID']).'</a></td>
			   <td bgcolor="'.$bg1.'" align="center">'.$league_name.'</td>
			   <td bgcolor="'.$bg1.'" align="center">'.($ds['avail']==3 ? "Participant Available" : "Participant Maybe Available").'</td>
			   <td bgcolor="'.$bg1.'" align="center">'.$award_ck.'</td>
			   <td bgcolor="'.$bg1.'" align="center">'.ratio_level($ds['userID'],$one=1).'</td>
			   <td bgcolor="'.$bg1.'" align="center"><a href="?site=freeagents&agent='.$ds['ID'].'"><img border="0" src="images/icons/foldericons/newhotfolder.gif"></a></td>
			 </tr>';
		
		}
		
}elseif(isset($_POST['invite']) && $_POST['invite']) {

        $clanID = mysql_escape_string($_POST['clanID']);
	$invite = mysql_escape_string($_POST['invite']);
	
	if($clanID && $invite) {
	
	  $query = safe_query("SELECT userID FROM ".PREFIX."cup_clan_members WHERE userID='$invite' && agent='1'");	  

	    if(mysql_num_rows($query)) {
	        safe_query("DELETE FROM ".PREFIX."cup_agents WHERE userID='$invite'");	
                redirect('?site=freeagents', '<strong><font color="'.$loosecolor.'">Error:</font> This user is a already an agent of another team.</strong>', 4);
	    }
            elseif(isleader($userID,$clanID)) {	   
	        safe_query("INSERT INTO ".PREFIX."cup_clan_members (cupID, clanID, userID, function, reg, agent) VALUES ('0','".$clanID."','".$invite."','Member','".time()."','1')");	
                redirect('?site=clans&action=show&clanID='.$clanID.'#members', 'Successfully added as agent!', 2);		
	    }
	    else{	   
	        echo '<strong>Access denied.</strong>';	  
	    }
	}
		
}elseif(isset($_GET['agent']) && $_GET['agent']) {
	
	   
	   $ID = mysql_escape_string($_GET['agent']);
	   $ds=mysql_fetch_array(safe_query("SELECT * FROM ".PREFIX."cup_agents WHERE ID='$ID'"));	
	   $cupID = ($ds['cupID'] && $ds['ladID']==0 ? $ds['cupID'] : $ds['ladID']);
	   $cup_info=mysql_fetch_array(safe_query("SELECT * FROM ".PREFIX."$table WHERE ID='$cupID'"));
	   
	     if(is_array($ds)) {
	   
	        $agent = $ds['userID'];		
	        $logo = '<img src="images/avatars/'.getavatar($agent).'">';
		$country = getusercountry3($agent);
		$player = '<a href="?site=profile&id='.$agent.'">'.getnickname($agent).'</a>';
		$league = ($ds[cupID] && $ds[ladID]==0 ? '<a href="?site=cups&action=details&cupID='.$ds[cupID].'">'.getcupname($ds[cupID]).'</a>' : '<a href="?site=ladders&ID='.$ds[ladID].'">'.getladname($ds[ladID]).'</a>');
		$gamepic = gamepic($cupID,($ds[cupID] && $ds[ladID]==0 ? 'cup' : 'ladder'));
		$reg = date('l M dS Y \@\ g:i a', $ds['time']);
		
	    switch($ds['avail']) {		
		case 1: redirect('?site=freeagents&action=view', 'Sorry this agent is not available, redirection...', 3); break;
		case 2: $avail = 'Agent maybe available on <strong>'.date('l M dS Y \@\ g:i a', $cup_info['start']).'</strong>'; break;
		case 3: $avail = 'Agent available on <strong>'.date('l M dS Y \@\ g:i a', $cup_info['start']).'</strong>'; break;	
	    }
	    
		   //tournament awards
		   
		   getclanawards($agent,1);
		   $award1 = '';
		   $award2 = '';
		   $award3 = '';
		   if($ar_awards[1]){
		   	   for($i=1; $i<=$ar_awards[1]; $i++)
			   	   $award1.='<a href="?site=brackets&action=tree&cupID='.$ar1_name[$i-1].'"><img src="images/cup/icons/award_gold.png" border="0" alt="Gold" title="'.getcupname($ar1_name[$i-1]).'" /></a>'; 
		   }
		   if($ar_awards[2]){
			   for($i=1; $i<=$ar_awards[2]; $i++)
				   $award2.='<a href="?site=brackets&action=tree&cupID='.$ar2_name[$i-1].'"><img src="images/cup/icons/award_silver.png" border="0" alt="'.$_language->module['silver'].'" title="'.getcupname($ar2_name[$i-1]).'" /></a>';
		   }
		   if($ar_awards[3]){
			   for($i=1; $i<=$ar_awards[3]; $i++)
				   $award3.='<a href="?site=brackets&action=tree&cupID='.$ar3_name[$i-1].'"><img src="images/cup/icons/award_bronze.png" border="0" alt="Bronze" title="'.getcupname($ar3_name[$i-1]).'" /></a>';
		   }		
		   $awards=$award1.$award2.$award3;
	
		   if(!empty($awards)) 
		   $tourn_awards.=$awards;
		   else $tourn_awards = '';
		   
		   //ladder awards
		   
		   getclanawards_lad($agent,1);
		   $award1 = '';
		   $award2 = '';
		   $award3 = '';
		   if($ar_awards[1]){
		   	   for($i=1; $i<=$ar_awards[1]; $i++)
			   	   $award1.='<a href="?site=brackets&action=tree&cupID='.$ar1_name[$i-1].'"><img src="images/cup/icons/award_gold.png" border="0" alt="Gold" title="'.getcupname($ar1_name[$i-1]).'" /></a>'; 
		   }
		   if($ar_awards[2]){
			   for($i=1; $i<=$ar_awards[2]; $i++)
				   $award2.='<a href="?site=brackets&action=tree&cupID='.$ar2_name[$i-1].'"><img src="images/cup/icons/award_silver.png" border="0" alt="'.$_language->module['silver'].'" title="'.getcupname($ar2_name[$i-1]).'" /></a>';
		   }
		   if($ar_awards[3]){
			   for($i=1; $i<=$ar_awards[3]; $i++)
				   $award3.='<a href="?site=brackets&action=tree&cupID='.$ar3_name[$i-1].'"><img src="images/cup/icons/award_bronze.png" border="0" alt="Bronze" title="'.getcupname($ar3_name[$i-1]).'" /></a>';
		   }		
		   $awards=$award1.$award2.$award3;
	
		   if(!empty($awards))
		   $ladd_awards.=$awards;
		   else $ladd_awards = '';
		   
		   if(empty($tourn_awards) && empty($ladd_awards)) {
		      $no_awards = "<img src='images/cup/icons/nok_32.png' width='16' height='16'>";
		   }
		   else{
		      $no_awards = "";
		   }
		   
		   //ratio
		   
		   $skill = ratio_level($agent,$one=1);
		   
		   //leave
		   
		   if($loggedin && $userID==$agent && $userID!=0 && $userID!='') {
		   
		      $recruit = '<br /><a href="?site=freeagents&agent='.$_GET['agent'].'&depart=true" onclick="return confirm(\'Are you sure you want to depart as agent?\');"><img src="images/cup/icons/leave.gif"> <strong>Depart as Agent</strong></a>';
		   
		      if($_GET['depart']=='true') {
		            safe_query("DELETE FROM ".PREFIX."cup_agents WHERE userID='$userID'");
			    safe_query("DELETE FROM ".PREFIX."cup_clan_members WHERE userID='$userID' && agent='1'");
			    redirect('?site=freeagents', 'Success!', 2);
		      }
		   }
		   
		   //invite
		   
	           $agents_lp = safe_query("SELECT * FROM ".PREFIX."cup_clan_members WHERE userID = '".$userID."' AND function = 'Leader'");	           
		   if(!mysql_num_rows($agents_lp)) $no_team = '<a href="?site=clans&action=clanadd">(leading no team - create team now!)</a>';		
	           $clan = '<option value="" selected="selected"> - Select Team - </option>';	
	           while($dm=mysql_fetch_array($agents_lp)) {                     
	               $clan.= '<option name="clanID" value="'.$dm['clanID'].'">'.getclanname($dm['clanID']).'</option>'; 
		   }
		   
                   $invite_to_team = '<form action="?site=freeagents" method="post">
                                        <input type="hidden" name="invite" value="'.$agent.'">
                                      <select name="clanID">
				       '.$clan.'
				      </select>
                                        <input type="submit" value="Add as Agent" onclick="return confirm(\'This will add the selected player as an agent to your team. You are responsible for removing the agent when needed.\');">
                                     </form>'; 
				     
				     
                   $st=mysql_fetch_array(safe_query("SELECT status FROM ".PREFIX.($ds['cupID'] && $ds['ladID']==0 ? 'cups' : 'cup_ladders')." WHERE ID='$cupID'"));
			
                     if($st['status']==1) {
			
		       if($ds['cupID'] && $ds['ladID']==0 && iscupparticipant($userID,$ds['cupID']) && $userID!=$agent && !iscupparticipant($agent,$ds['cupID'])) {
		          $recruit = $invite_to_team;
		       }
		       elseif($ds['ladID'] && $ds['cupID']==0 && isladparticipant($userID,$ds['ladID']) && $userID!=$agent && !isladparticipant($agent,$ds['ladID'])) {
		          $recruit = $invite_to_team;
		       }
		       elseif($ds['ladID'] && $ds['cupID']==0 && isgroupparticipant3($userID,$ds['ladID'],'ladder') && $userID!=$agent) {
		          $recruit = $invite_to_team; 
		       }
		       elseif($ds['cupID'] && $ds['ladID']==0 && isgroupparticipant3($userID,$ds['cupID'],'cup') && $userID!=$agent) {
		          $recruit = $invite_to_team; 
		       }
		     }
	    
	    $play = ($ds['play'] ? getinput($ds['play']) : '(unspecified)');
	    $info = ($ds['info'] ? getinput($ds['info']) : '(unspecified)');
	    
	    $cin=mysql_fetch_array(safe_query("SELECT userID FROM ".PREFIX."cup_clan_members WHERE userID='$agent' && agent='1'"));
		   
	    if(is_array($cin)) {
	       safe_query("DELETE FROM ".PREFIX."cup_agents WHERE userID='$agent'");
	    }
	    if($ds['ladID'] && $ds['cupID']==0 && isladparticipant_memin($agent,$ds['ladID'])) {
               safe_query("DELETE FROM ".PREFIX."cup_agents WHERE userID='$agent'");
            }
	    if($ds['cupID'] && $ds['ladID']==0 && iscupparticipant_memin($ds['userID'],$ds['cupID'])) {
               safe_query("DELETE FROM ".PREFIX."cup_agents WHERE userID='$agent'");
            }
		
	eval ("\$freeagents_temp = \"".gettemplate("freeagent")."\";");
	echo $freeagents_temp;

    }
    else{
	echo '<tr><td bgcolor="'.$bg1.'" colspan="2" align="center">-- invalid agent --</td></tr>';
    }    	
}
	
echo '</table>';

?>