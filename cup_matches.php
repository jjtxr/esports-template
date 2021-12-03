<link href="cup.css" rel="stylesheet" type="text/css">
<?php
$_language->read_module('cup_matches');

include ("config.php");

$bg1=BG_1;
$bg2=BG_1;
$bg3=BG_1;
$bg4=BG_1;
$cpr=ca_copyr();

/* Cup SQL-Querys */
safe_query("UPDATE ".PREFIX."cups SET status='2' WHERE start<='".time()."'");
safe_query("UPDATE ".PREFIX."cups SET status='3' WHERE ende<='".time()."'");
safe_query("UPDATE ".PREFIX."cup_warnings SET expired='1' WHERE deltime<='".time()."'");
/**************/

//date and timezone

if(!$cpr || !ca_copyr()) die();

  if($_GET['cupID']) {
      getcuptimezone();
      $thescore1 = "score1";
      $thescore2 = "score2";
      $t_name = "cupID";
      $t_name2=($_GET['matchID'] ? "ladderis1on1" : "is1on1");
      $t_name3 = "getcupname";
 }else{
      getladtimezone();
      $thescore1 = "map1_score1";
      $thescore2 = "map1_score2";
      $t_name = "laddID";
      $t_name2 = "ladderis1on1";
      $t_name3 = "getladname";
      include("title_ladder.php");
 }


if($_GET['cupID'] && $_GET['match'] && $_GET['type']!="gs") {

    $name = 'Cup';
    $typename = '<a href="?site=cups&action=details&cupID='.$_GET['cupID'].'">'.getcupname($_GET['cupID']).'</a>';

	$cupID=$_GET['cupID'];
	$matchno=$_GET['match'];
	
	$getname = safe_query("SELECT * FROM ".PREFIX."cups WHERE ID='$cupID'");
	while($dd = mysql_fetch_array($getname)) 
	$cupname = getcupname($cupID);
	
    include ("title_cup.php");
    
    if(is1on1($cupID)) $participants = 'Players';	
	else $participants = 'Teams';

	eval ("\$title_cup = \"".gettemplate("title_cup")."\";");
	echo $title_cup;

	$match = safe_query("SELECT * FROM ".PREFIX."cup_matches WHERE cupID='$cupID' AND matchno='$matchno' && (clan1 !='2147483647' || clan2 !='2147483647')");
	$dd=mysql_fetch_array($match);
	
	if(!$dd) 
		echo '<br /><br /><br /><br /><center><b>'.$_language->module['not_insert'];		
		
	else{

		$clan1_sql = safe_query("SELECT * FROM ".PREFIX."cup_all_clans WHERE ID='".$dd['clan1']."'");
		$c1=mysql_fetch_array($clan1_sql);

		$clan2_sql = safe_query("SELECT * FROM ".PREFIX."cup_all_clans WHERE ID='".$dd['clan2']."'");
		$c2=mysql_fetch_array($clan2_sql);

		$cup_sql = safe_query("SELECT * FROM ".PREFIX."cups WHERE ID='$cupID'");
		$cup=mysql_fetch_array($cup_sql);

		$map_sql = safe_query("SELECT * FROM ".PREFIX."cup_baum WHERE cupID='$cupID'");
		$map=mysql_fetch_array($map_sql);	
		
		$matchinfo = getround($cupID, $matchno);
		$round = $matchinfo['round'];
		$map_name = $matchinfo['map'];
		
        if($round) $stage = "Round";	
		
if(is1on1($cupID)) {
   $team1 = '<a href="?site=profile&id='.$dd['clan1'].'"><b>'.getnickname($dd['clan1']).'</b></a>';
   $team2 = '<a href="?site=profile&id='.$dd['clan2'].'"><b>'.getnickname($dd['clan2']).'</b></a>';
}else{
   $team1 = '<a href="?site=clans&action=show&clanID='.$dd['clan1'].'"><b>'.getclanname($dd['clan1']).'</b></a>';
   $team2 = '<a href="?site=clans&action=show&clanID='.$dd['clan2'].'"><b>'.getclanname($dd['clan2']).'</b></a>';
  } 

		
		if($dd['einspruch']==1)
			$match_status = '<font color="red">'.$_language->module['status_protest'].'</font>';
		elseif($dd['confirmscore']==1 && $dd['einspruch']==0)
			$match_status = $_language->module['status_closed'];
	    elseif(($dd[$thescore1] || $dd[$thescore2]) && $dd['inscribed']==$dd['clan1'])
	        $match_status = '<font color="#FF6600">Waiting for <b>'.$team2.'</b></font>';
	    elseif(($dd[$thescore1] || $dd[$thescore2]) && $dd['inscribed']==$dd['clan2'])
	        $match_status = '<font color="#FF6600">Waiting for <b>'.$team1.'</b></font>';
		elseif($dd['confirmscore']==0 && $dd['inscribed'] == 0)
			$match_status = $_language->module['status_open'];
		else
			$match_status = $_language->module['status_open'];

		if($dd['confirmscore']=='0'){
			$winner = $_language->module['no_winner'];
			$ergebnis = $_language->module['no_score'];
		}else{
			if(is1on1($cupID)){
				if($dd['score1'] > $dd['score2'])
					$winner = '<a href="?site=profile&id='.$dd['clan1'].'"><b>'.getnickname($dd['clan1']).'</b></a> '.$_language->module['won'];
				elseif($dd['score1'] == $dd['score2'])
					$winner = '<font color="#FF6600"><b>Draw</b></font>';
				else
					$winner = '<a href="?site=profile&id='.$dd['clan2'].'"><b>'.getnickname($dd['clan2']).'</b></a> '.$_language->module['won'];
			}else{
				if($dd['score1'] > $dd['score2'])
					$winner = '<a href="?site=clans&action=show&clanID='.$dd['clan1'].'&cupID='.$cupID.'"><b>'.getclanname2($dd['clan1']).'</b></a> '.$_language->module['won'];
				elseif($dd['score1'] == $dd['score2'])
					$winner = '<font color="#FF6600"><b>Draw</b></font>';
				else
					$winner = '<a href="?site=clans&action=show&clanID='.$dd['clan2'].'&cupID='.$cupID.'"><b>'.getclanname2($dd['clan2']).'</b></a> '.$_language->module['won'];
			}	
		
			if($dd['score1'] == 0 && $dd['score2'] == 0) $winner = $_language->module['no_winner'];
			$ergebnis = $dd['score1'].':'.$dd['score2'];
		}

		//if(empty($dd['screens']) && (!empty($dd['screen_upper']) OR !empty($dd['screen_name'])))
		//	  safe_query("UPDATE ".PREFIX."cup_matches SET screen_upper ='', screen_name ='' WHERE matchno='$matchno' AND cupID='$cupID'");

		$screens = array();
		$screen_upper = array();
		$screen_name = array();
		if(!empty($dd['screens'])) $screens=explode("|", $dd['screens']);
		if(!empty($dd['screen_upper'])) $screen_upper=explode("|", $dd['screen_upper']);
		if(!empty($dd['screen_name'])) $screen_name=explode("||", $dd['screen_name']);
		$screenshots = '';
		if($dd['screens'] && $dd['screen_upper'] && $dd['screen_name']) {
			$n=1;			
			foreach($screens as $screen) {
				if(!empty($screen)) {
					$screenshots.='<tr>
							  <td align="left" width="100%" bgcolor="'.$bg1.'"><a href="images/cup-screens/'.$screen.'" target="_blank"><b>'.$screen_name[$n].'</b></a> - '.$_language->module['uploaded_by'].' <a href="?site=profile&id='.$screen_upper[$n].'">'.getnickname($screen_upper[$n]).'</a> '.(iscupadmin($screen_upper[$n]) ? "(admin)" : "").'</td>
						       </tr> ';
					$n++;
				}
				unset($screen);
			}
		}
		else{
					$screenshots = '<tr>
							  <td align="center" width="100%" bgcolor="'.$bg1.'">- no files uploaded -</td>
						       </tr>';
		}
	
		$date=date('d/m/Y H:i', $dd['date']);
		if(is1on1($cupID)){
			if($loggedin){	
			
			if(isset($_GET['type']) && $_GET['type']=='gs') {
			   $report_link1 = '?site=groups&'.$t_name.'='.$laddID.'&match='.$dd['matchID'].'';
			   $report_link2 = '?site=groups&'.$t_name.'='.$laddID.'&match='.$dd['matchID'].'';
			}else{
			   $report_link1 = '?site=matches&action=viewmatches&clanID='.$dd['clan1'].'&'.$t_name.'='.$cupID.'&type=1#'.$dd['matchno'].'';
			   $report_link2 = '?site=matches&action=viewmatches&clanID='.$dd['clan2'].'&'.$t_name.'='.$cupID.'&type=1#'.$dd['matchno'].'';
			}
			
			  $ticket = safe_query("SELECT * FROM ".PREFIX."cup_tickets WHERE matchID='".$dd['matchID']."'");
			  $tic=mysql_fetch_array($ticket);
			  
			  if(mysql_num_rows($ticket)) $pt = '<img src="images/cup/icons/ticket.png"> <a href="?site=cupactions&action=mytickets&tickID='.$tic['ticketID'].'"><strong>Protest Ticket</strong></a>'; else $pt = '';
	
				if($userID == $dd['clan1'] || $userID == $dd['clan2']) {
				
				    if($userID==$dd['clan1']) {
				           $sTID = $dd['clan1'];
					   $sRPT = $report_link1;
				    }
				    else{
				           $sTID = $dd['clan1'];
					   $sRPT = $report_link2;
				    }
				
					$leaderoptions='<tr>
					                  <td class="title2"><img src="images/cup/icons/tchat.png"> <a href="javascript:void(0)" onclick="MM_openBrWindow(\'popup.php?site=shout&id='.$dd['matchID'].'&type=matchID\',\'Match Chat\',\'toolbar=no,status=no,scrollbars=no,width=550,height=340\');"><strong>'.$_language->module['match_chat'].'<strong></a></td>
                          				  <td class="title2"><img src="images/cup/icons/request.png"> <a href="?site=cupactions&action=mediarequest&matchID='.$dd['matchID'].'"><strong>'.$_language->module['request_matchmedia'].'<strong></a></td>
                 					  <td class="title2"><img src="images/cup/icons/addmedia.gif"> <a href="?site=cupactions&action=screenupload&cupID='.$cupID.'&matchID='.$dd['matchID'].'"><strong>'.$_language->module['upload_screen'].'</strong></a></td> 
							  <td class="title2"><img src="images/cup/icons/manage.gif"> <a href="?site=cupactions&action=match_edit&cupID='.$cupID.'&matchID='.$dd['matchID'].'"><strong>'.$_language->module['edit_matchdetails'].'</strong></a></td>
 							  <td class="title2"><img src="images/cup/icons/edit.png" width="16" height="16"> <a href="'.$sRPT.'"><strong>Match Report</strong></a></td>
							  <td class="title2">'.$pt.'</td> 
							  <td class="title2"><img src="images/cup/icons/comments.png"> <a href="?site=cupactions&action=comments&clanID='.$sTID.'&matchID='.$dd['matchID'].'"><strong>Comments</strong></a></td>
							</tr>'; 
				}
			}

			$report_admin_sh = htmloutput($dd['report']);
			$report_admin_sh = toggle($report_admin_sh, $dd['report']);
			
			if($dd['report'])
				$report_admin='<br />
								<table bgcolor="'.$border.'" cellpadding="'.$cellpadding.'" width="100%">
							     <tbody><tr>
											<td colspan="2" class="title"><div align="center">'.$_language->module['report_by'].' Admin:</div></td>
										</tr>
										<tr>
											<td colspan="2" bgcolor="'.$pagebg.'"></td>
										</tr>
										<tr>
											<td colspan="2" bgcolor="'.$bg1.'">
												<table bgcolor="'.$bg1.'" border="0" cellpadding="0" cellspacing="'.$cellspacing.'" width="100%">
													<tbody>
														<tr bgcolor="'.$bg1.'">
															<td valign="top" width="90%"><div style="margin: 2px;">'.$report_admin_sh.'</div></td>
														</tr>
													</tbody>
												</table>
											</td>
										</tr>
								 </tbody>
								</table>';
			else
				$report_admin = '';

			if($dd['report_team1'])
				$report_team1='<br />
								<table bgcolor="'.$border.'" cellpadding="'.$cellpadding.'" width="100%">
									<tbody>
										<tr>
											<td colspan="2" class="title"><div align="center">'.$_language->module['report_by'].' '.getnickname($dd['clan1']).':</div></td>
										</tr>
										<tr>
											<td colspan="2" bgcolor="'.$pagebg.'"></td>
										</tr>
										<tr>
											<td colspan="2" bgcolor="'.$bg1.'">
												<table bgcolor="'.$bg1.'" border="0" cellpadding="0" cellspacing="'.$cellspacing.'" width="100%">
													<tbody>
														<tr bgcolor="'.$bg1.'">
															<td valign="top" width="90%"><div style="margin: 2px;">'.$dd['report_team1'].'</div></td>
														</tr>
													</tbody>
												</table>
											</td>
										</tr>
									</tbody>
								</table>';
			else
				$report_team1 = '';
		
			if($dd['report_team2'])
				$report_team2='<br />
								<table bgcolor="'.$border.'" cellpadding="'.$cellpadding.'" width="100%">
									<tbody>
										<tr>
											<td colspan="2" class="title"><div align="center">'.$_language->module['report_by'].' '.getnickname($dd['clan2']).':</div></td>
										</tr>
										<tr>
											<td colspan="2" bgcolor="'.$pagebg.'"></td>
										</tr>
										<tr>
											<td colspan="2" bgcolor="'.$bg1.'">
												<table bgcolor="'.$bg1.'" border="0" cellpadding="0" cellspacing="'.$cellspacing.'" width="100%">
													<tbody>
														<tr bgcolor="'.$bg1.'">
															<td valign="top" width="90%"><div style="margin: 2px;">'.$dd['report_team2'].'</div></td>
														</tr>
													</tbody>
												</table>
											</td>
										</tr>
									</tbody>
								</table>';
			else
				$report_team2 = '';
		}else{
		
		
			  $ticket = safe_query("SELECT * FROM ".PREFIX."cup_tickets WHERE matchID='".$dd['matchID']."'");
			  $tic=mysql_fetch_array($ticket);
			  
			  if(mysql_num_rows($ticket)) $pt = '<img src="images/cup/icons/ticket.png"> <a href="?site=cupactions&action=mytickets&tickID='.$tic['ticketID'].'">Protest Ticket</a>'; else $pt = '';
		
			if(memin($userID,$dd['clan1']))
				$leaderoptions='<tr>
				                  <td class="title2"><img src="images/cup/icons/tchat.png"> <a href="javascript:void(0)" onclick="MM_openBrWindow(\'popup.php?site=shout&id='.$dd['matchID'].'&type=matchID\',\'Match Chat\',\'toolbar=no,status=no,scrollbars=no,width=550,height=340\');">'.$_language->module['match_chat'].'</a></td>
   						  <td class="title2">'.$pt.'</td> 
						  <td class="title2"><img src="images/cup/icons/addmedia.gif"> <a href="?site=cupactions&action=screenupload&cupID='.$cupID.'&matchID='.$dd['matchID'].'&clanID='.$dd['clan1'].'">'.$_language->module['upload_screen'].'</a></td>
						</tr>';
		        elseif(memin($userID,$dd['clan2']))          
		               $leaderoptions='<tr>
			                         <td class="title2"><img src="images/cup/icons/tchat.png"> <a href="javascript:void(0)" onclick="MM_openBrWindow(\'popup.php?site=shout&id='.$dd['matchID'].'&type=matchID\',\'Match Chat\',\'toolbar=no,status=no,scrollbars=no,width=550,height=340\');">'.$_language->module['match_chat'].'</a></td> 
						 <td class="title2">'.$pt.'</td>
						 <td class="title2"><img src="images/cup/icons/addmedia.gif"> <a href="?site=cupactions&action=screenupload&cupID='.$cupID.'&matchID='.$dd['matchID'].'&clanID='.$dd['clan2'].'">'.$_language->module['upload_screen'].'</a></td>
					       </tr>';

			if(isleader($userID,$dd['clan1']))
				$leaderoptions='<tr>
				                  <td class="title2"><img src="images/cup/icons/tchat.png"> <a href="javascript:void(0)" onclick="MM_openBrWindow(\'popup.php?site=shout&id='.$dd['matchID'].'&type=matchID\',\'Match Chat\',\'toolbar=no,status=no,scrollbars=no,width=550,height=340\');">'.$_language->module['match_chat'].'</a></td>
						  <td class="title2"><img src="images/cup/icons/request.png"> <a href="?site=cupactions&action=mediarequest&matchID='.$dd['matchID'].'&clanID='.$dd['clan1'].'">'.$_language->module['request_matchmedia'].'</a></td>
						  <td class="title2"><img src="images/cup/icons/addmedia.gif"> <a href="?site=cupactions&action=screenupload&cupID='.$cupID.'&matchID='.$dd['matchID'].'&clanID='.$dd['clan1'].'">'.$_language->module['upload_screen'].'</a></td>
						  <td class="title2"><img src="images/cup/icons/manage.gif"> <a href="?site=cupactions&action=match_edit&cupID='.$cupID.'&matchID='.$dd['matchID'].'&clanID='.$dd['clan1'].'">'.$_language->module['edit_matchdetails'].'</a></td>
						  <td class="title2"><img src="images/cup/icons/edit.png"> <a href="?site=matches&action=viewmatches&clanID='.$dd['clan1'].'&'.$t_name.'='.$cupID.'#'.$dd['matchno'].'">Match Report</a></td>
						  <td class="title2">'.$pt.'</td>
						  <td class="title2"><img src="images/cup/icons/comments.png"> <a href="?site=cupactions&action=comments&clanID='.$dd['clan1'].'&matchID='.$dd['matchID'].'">Comments</a></td>
						</tr>'; 
			elseif(isleader($userID,$dd['clan2']))
				$leaderoptions='<tr>
				                  <td class="title2"><img src="images/cup/icons/tchat.png"> <a href="javascript:void(0)" onclick="MM_openBrWindow(\'popup.php?site=shout&id='.$dd['matchID'].'&type=matchID\',\'Match Chat\',\'toolbar=no,status=no,scrollbars=no,width=550,height=340\');">'.$_language->module['match_chat'].'</a></td>
						  <td class="title2"><img src="images/cup/icons/request.png"> <a href="?site=cupactions&action=mediarequest&matchID='.$dd['matchID'].'&clanID='.$dd['clan2'].'">'.$_language->module['request_matchmedia'].'</a></td>
						  <td class="title2"><img src="images/cup/icons/addmedia.gif"> <a href="?site=cupactions&action=screenupload&cupID='.$cupID.'&matchID='.$dd['matchID'].'&clanID='.$dd['clan2'].'">'.$_language->module['upload_screen'].'</a></td>
						  <td class="title2"><img src="images/cup/icons/manage.gif"> <a href="?site=cupactions&action=match_edit&cupID='.$cupID.'&matchID='.$dd['matchID'].'&clanID='.$dd['clan2'].'">'.$_language->module['edit_matchdetails'].'</a></td>
						  <td class="title2"><img src="images/cup/icons/edit.png"> <a href="?site=matches&action=viewmatches&clanID='.$dd['clan2'].'&'.$t_name.'='.$cupID.'#'.$dd['matchno'].'">Match Report</a></td>
						  <td class="title2">'.$pt.'</td>
						  <td class="title2"><img src="images/cup/icons/comments.png"> <a href="?site=cupactions&action=comments&clanID='.$dd['clan2'].'&matchID='.$dd['matchID'].'">Comments</a></td>
						</tr>'; 

			$report_admin_sh = htmloutput($dd['report']);
			$report_admin_sh = toggle($report_admin_sh, $dd['report']);
				
			if($dd['report'])
				$report_admin='<br />
								<table bgcolor="'.$border.'" cellpadding="'.$cellpadding.'" width="100%">
									<tbody>
										<tr>
											<td colspan="2" class="title"><div align="center">'.$_language->module['report_by'].' Admin:</div></td>
										</tr>
										<tr>
											<td colspan="2" bgcolor="'.$pagebg.'"></td>
										</tr>
										<tr>
											<td colspan="2" bgcolor="'.$bg1.'">
												<table bgcolor="'.$bg1.'" border="0" cellpadding="0" cellspacing="'.$cellspacing.'" width="100%">
													<tbody>
														<tr bgcolor="'.$bg1.'">
															<td valign="top" width="90%"><div style="margin: 2px;">'.$report_admin_sh.'</div></td>
														</tr>
													</tbody>
												</table>
											</td>
										</tr>
									</tbody>
								</table>';
			else
				$report_admin = '';

			if($dd['report_team1'])
				$report_team1='<br />
								<table bgcolor="'.$border.'" cellpadding="'.$cellpadding.'" width="100%">
									<tbody>
										<tr>
											<td colspan="2" class="title"><div align="center">'.$_language->module['report_by'].'  '.getclanname2($dd['clan1']).':</div></td>
										</tr>
										<tr>
											<td colspan="2" bgcolor="'.$pagebg.'"></td>
										</tr>
										<tr>
											<td colspan="2" bgcolor="'.$bg1.'">
												<table bgcolor="'.$bg1.'" border="0" cellpadding="0" cellspacing="'.$cellspacing.'" width="100%">
													<tbody>
														<tr bgcolor="'.$bg1.'">
															<td valign="top" width="90%"><div style="margin: 2px;">'.$dd['report_team1'].'</div></td>
														</tr>
													</tbody>
												</table>
											</td>
										</tr>
									</tbody>
								</table>';
			else
				$report_team1 = '';
		
			if($dd['report_team2'])
				$report_team2='<br />
								<table bgcolor="'.$border.'" cellpadding="'.$cellpadding.'" width="100%">
									<tbody>
										<tr>
											<td class="title" align="center">'.$_language->module['report_by'].' '.getclanname2($dd['clan2']).':</td>
										</tr>
										<tr>
											<td bgcolor="'.$pagebg.'"></td>
										</tr>
										<tr>
											<td bgcolor="'.$bg1.'">
												<table bgcolor="'.$bg1.'" border="0" cellpadding="0" cellspacing="'.$cellspacing.'" width="100%">
													<tbody>
														<tr bgcolor="'.$bg1.'">
															<td valign="top" width="90%"><div style="margin: 2px;">'.$dd['report_team2'].'</div></td>
														</tr>
													</tbody>
												</table>
											</td>
										</tr>
									</table>';
			else
				$report_team2 = '';
		}
		$requests_sql = safe_query("SELECT * FROM ".PREFIX."cup_requests WHERE matchID='".$dd['matchID']."'");
		$requests = '';
		if(mysql_num_rows($requests_sql)){
			$requests='<br />
						<table bgcolor="'.$border.'" cellpadding="'.$cellpadding.'" width="100%">
							<tr>
								<td colspan="2" class="title" align="center">'.$_language->module['request_matchmedia_title'].':</td>
							</tr>
							<tr>
								<td colspan="2" bgcolor="'.$pagebg.'"></td>
							</tr>';
			while($du=mysql_fetch_array($requests_sql)){
				if(!is1on1($cupID)){
					if(isleader($userID,$dd['clan1']))
						$userteam='(<a href="?site=clans&action=show&clanID='.$dd['clan1'].'&cupID='.$cupID.'">'.getclanname($dd['clan1']).'</a>)';
					else
						$userteam='(<a href="?site=clans&action=show&clanID='.$dd['clan2'].'&cupID='.$cupID.'">'.getclanname($dd['clan2']).'</a>)';
				}
				$requests.='<tr bgcolor="'.$bg1.'">
								<td><b>'.$du['reason'].'</b> von <a href="?site=profile&id='.$du['userID'].'">'.getnickname($du['userID']).'</a> '.$userteam.' '.$_language->module['at'].' '.date("d/m/Y ".$_language->module['at']."h:i", $du['time']).'</td>
							</tr>';
			}
			$requests.='</table>';
		}	
		if(is1on1($cupID)){
			$team1_link='<a href="?site=profile&id='.$dd['clan1'].'"><img src="images/avatars/'.getavatar($dd['clan1']).'" border="0" height="100" width="100"><br />'.getnickname($dd['clan1']).'</a>';
			$team2_link='<a href="?site=profile&id='.$dd['clan2'].'"><img src="images/avatars/'.getavatar($dd['clan2']).'" border="0" height="100" width="100"><br />'.getnickname($dd['clan2']).'</a>';
		}else{
			$clanlogo=((!empty($c1['clanlogo'])  && $c1['clanlogo']!='http://') ? $c1['clanlogo'] : 'images/avatars/noavatar.gif');
			$clanlogo2=((!empty($c2['clanlogo'])  && $c2['clanlogo']!='http://') ? $c2['clanlogo'] : 'images/avatars/noavatar.gif');
			$team1_link='<a href="?site=clans&action=show&clanID='.$dd['clan1'].'&cupID='.$cupID.'"><img src="'.$clanlogo.'" border="0" height="100" width="100"><br />'.getclanname2($dd['clan1']).'</a>';
			$team2_link='<a href="?site=clans&action=show&clanID='.$dd['clan2'].'&cupID='.$cupID.'"><img src="'.$clanlogo2.'" border="0" height="100" width="100"><br />'.getclanname2($dd['clan2']).'</a>';
		}		
		
  if(empty($dd['inscribed'])) {
      $inscribed_by = "(no report yet)";
      $confirmed_by = "(no inscription yet)";
      
   }elseif($dd['inscribed']==$dd['clan1'] || $dd['inscribed']==$dd['clan2']){
 
      if($dd['inscribed']==$dd['clan1']) {
          $inscribed_by = getname1($dd['clan1'],getleagueID($dd['matchID']),0,league($dd['matchID']),1); 
          $inscribed_date = date("d/m/Y H:i", $dd['inscribed_date']);
        
      if($dd['confirmscore'] && !$dd['einspruch']) {
          $confirmed_by = getname1($dd['clan2'],getleagueID($dd['matchID']),0,league($dd['matchID']),1); 
          $confirmed_date = date("d/m/Y H:i", $dd['confirmed_date']);
           
      }else
          $confirmed_by = "(not confirmed yet)";
        
    }else{
    
      if($dd['inscribed']==$dd['clan2']) {
          $inscribed_by = getname1($dd['clan2'],getleagueID($dd['matchID']),0,league($dd['matchID']),1);
          $inscribed_date = date("d/m/Y H:i", $dd['inscribed_date']);
        
      if($dd['confirmscore'] && !$dd['einspruch']) {
          $confirmed_by = getname1($dd['clan1'],getleagueID($dd['matchID']),0,league($dd['matchID']),1);
          $confirmed_date = date("d/m/Y H:i", $dd['confirmed_date']);
           
      }else
          $confirmed_by = "(not confirmed yet)";
    }
   }
  }elseif($dd['confirmscore']) {
          $inscribed_by = "(admin)";
          $confirmed_by = "(admin)";
  }
  
  
 if($dd['server']) {
 
    $server_temp = '
 
                                                                                <tr>
											<td align="left" bgcolor="'.$bg1.'">Server:</td>
											<td align="left" bgcolor="'.$bg1.'">'.$dd['server'].'</td>
										</tr>';										
 }
 if($dd['server']) {
 
    $hltv_temp = '
 
                                                                                <tr>
											<td align="left" bgcolor="'.$bg1.'">HLTV:</td>
											<td align="left" bgcolor="'.$bg1.'">'.$dd['hltv'].'</td>
										</tr>';										
 }
 if($map_option1) {

    $map_temp = '

										<tr>
											<td align="left" width="35%" bgcolor="$bg1">Map 
											<br>$map_option1 $map_option2 
											$map_option3 $map_option4 
											$map_option5</td>
											<td align="left" bgcolor="$bg1" width="65%">$map_name 
											$map1 $map2 $map3 $map4 $map5</td>
										</tr>';    
 }
 
if(ismatchparticipant($userID,$dd['matchID'],$all=1)) {

$getrequests = safe_query("SELECT * FROM ".PREFIX."cup_requests WHERE matchID='".$dd['matchID']."'");
  if(mysql_num_rows($getrequests)) { 
  
  $mm_requests = '<table width="100%" bgcolor="'.$border.'" cellspacing="'.$cellspacing.'" cellpadding="'.$cellpadding.'">
	            <tr>
	             <td class="title" align="center" colspan="4">Matchmedia Requests</td>
	            </tr>
	             <tr>
	             <td class="title2" align="center">No</td>
	             <td class="title2">Info</td>
	             <td class="title2" align="center">Requested On</td>
	             <td class="title2" align="center">Requested By</td>
	            </tr>';
	    
   $no = 1;
    while($rq=mysql_fetch_array($getrequests)) {
     
   $date = date('d/m/Y H:i', $rq['time']);
   
   if($t_name2($laddID))
      $from = '<a href="?site=profile&id='.$rq['userID'].'"><b>'.getnickname($rq['userID']).'</b></a>';
   else
      $from = '<a href="?site=clans&action=show&clanID='.$rq['userID'].'"><b>'.getclanname($rq['userID']).'</b></a>';
      
   $mm_requests.='
	 <tr>
	  <td bgcolor="'.$bg1.'" align="center">#'.$no.'</td>
	  <td bgcolor="'.$bg1.'">'.$rq['reason'].'</td>
	  <td bgcolor="'.$bg1.'" align="center">'.$date.'</td>
	  <td bgcolor="'.$bg1.'" align="center">'.$from.'</td>
	</tr>';
	
	     $no++;
	
  }$mm_requests.='</table>';
 }
} 

       if($dd[clan1] && $dd[clan2])
       {
		eval ("\$cup_matches = \"".gettemplate("cup_matches")."\";");
		echo $cup_matches;
       }
       else
       {
 		echo "Both participants are not registered.";
       }
		
       if(in_array($dd['cupID'],$array))
          $ml = 'index.php?site=cup_matches&match='.$dd['matchID'].'&cupID='.$dd['matchno'].'&type=gs';
       elseif(in_array($dd['ladID'],$array))
          $ml = 'index.php?site=cup_matches&match='.$dd['matchID'].'&laddID='.$dd['matchno'].'&type=gs';
       elseif(!$dd['matchno'] && $dd['cupID'])
          $ml = '?index.phpsite=cup_matches&matchID='.$dd['matchID'].'&cupID='.$dd['cupID'].'';
       elseif(!$dd['matchno'] && $dd['ladID'])
          $ml = 'index.php?site=cup_matches&matchID='.$dd['matchID'].'&laddID='.$dd['ladID'].'';
       elseif(!in_array($dd['cupID'],$array) && $dd['cupID'])
          $ml = 'index.php?site=cup_matches&match='.$dd['matchno'].'&cupID='.$dd['cupID'].'';        
       elseif(!in_array($dd['ladID'],$array) && $dd['ladID'])
          $ml = 'index.php?site=cup_matches&match='.$dd['matchno'].'&laddID='.$dd['ladID'].'';

		$parentID = $dd['matchID'];
		$comments_allowed = $dd['comment'];
		$type = "cm";
		$referer = "$ml";

       if($dd[clan1] && $dd[clan2])
       {
		include("comments.php");	
       }
		echo $inctemp; 
    }
}elseif(isset($_GET[$t_name]) && ($_GET['match'] || $_GET['matchID'])) {

$laddID = $_GET[$t_name];
$cupID = $_GET[$t_name];
$ID = $_GET['match'];

    if(isset($_GET['type']) && $_GET['type']=='gs') {
       $name1 = "matchID='$ID'";
       $name2 = "matchno='$laddID'";
       $name3 = 'type=gs';
    }elseif($_GET['matchID']) { 
       $name1 = "ladID='$laddID'";
       $name2 = 'matchID='.$_GET['matchID'].'';
    }else{
       $name1 = "ladID='$laddID'";
       $name2 = "matchno='$ID'";
    }

$laddmatches = safe_query("SELECT * FROM ".PREFIX."cup_matches WHERE $name1 AND $name2 AND (clan1!='2147483647' || clan2!='2147483647')");
$dd = mysql_fetch_array($laddmatches);

$challenge = safe_query("SELECT * FROM ".PREFIX."cup_challenges WHERE chalID='$ID'");
$ch = mysql_fetch_array($challenge);

  if($_GET['type']=="gs") {
     $typename = '<a href="?site=groups&'.$t_name.'='.$laddID.'">'.$t_name3($laddID).'</a>';
     $name = "Group Stages";
  }else{
     $typename = '<a href="?site=standings&ladderID='.$laddID.'">'.$t_name3($laddID).'</a>';
     $name = 'Ladder';
  }

    $date=date('d/m/Y H:i', $dd['date']);
    $cupID = $laddID;   
        
		$clan1_sql = safe_query("SELECT * FROM ".PREFIX."cup_all_clans WHERE ID='".$dd['clan1']."'");
		$c1=mysql_fetch_array($clan1_sql);

		$clan2_sql = safe_query("SELECT * FROM ".PREFIX."cup_all_clans WHERE ID='".$dd['clan2']."'");
		$c2=mysql_fetch_array($clan2_sql);

		if($t_name2($laddID)){
			$team1_link='<a href="?site=profile&id='.$dd['clan1'].'"><img src="images/avatars/'.getavatar($dd['clan1']).'" border="0" height="100" width="100"><br />'.getnickname($dd['clan1']).'</a>';
			$team2_link='<a href="?site=profile&id='.$dd['clan2'].'"><img src="images/avatars/'.getavatar($dd['clan2']).'" border="0" height="100" width="100"><br />'.getnickname($dd['clan2']).'</a>';
		}else{
			$clanlogo=((!empty($c1['clanlogo'])  && $c1['clanlogo']!='http://') ? $c1['clanlogo'] : 'images/avatars/noavatar.gif');
			$clanlogo2=((!empty($c2['clanlogo'])  && $c2['clanlogo']!='http://') ? $c2['clanlogo'] : 'images/avatars/noavatar.gif');
			$team1_link='<a href="?site=clans&action=show&clanID='.$dd['clan1'].'&cupID='.$cupID.'"><img src="'.$clanlogo.'" border="0" height="100" width="100"><br />'.getclanname2($dd['clan1']).'</a>';
			$team2_link='<a href="?site=clans&action=show&clanID='.$dd['clan2'].'&cupID='.$cupID.'"><img src="'.$clanlogo2.'" border="0" height="100" width="100"><br />'.getclanname2($dd['clan2']).'</a>';
		}
		
		if(!$dd['confirmscore']){
			$winner = $_language->module['no_winner'];
			$ergebnis = $_language->module['no_score'];
		}else{
		
	      $off_score1 = $dd['score1'];
	      $off_score2 = $dd['score2'];

			if($t_name2($laddID)){ 
				if($off_score1 > $off_score2)
					$winner = '<a href="?site=profile&id='.$dd['clan1'].'"><b>'.getnickname($dd['clan1']).'</b></a> '.$_language->module['won'];
				elseif($off_score1 == $off_score2)
					$winner = '<font color="#FF6600"><b>Draw</b></font>';
				else
					$winner = '<a href="?site=profile&id='.$dd['clan2'].'"><b>'.getnickname($dd['clan2']).'</b></a> '.$_language->module['won'];
			}else{ 
				if($off_score1 > $off_score2)
					$winner = '<a href="?site=clans&action=show&clanID='.$dd['clan1'].'&cupID='.$cupID.'"><b>'.getclanname2($dd['clan1']).'</b></a> '.$_language->module['won'];
				elseif($off_score1 == $off_score2)
					$winner = '<font color="#FF6600"><b>Draw</b></font>';
				else
					$winner = '<a href="?site=clans&action=show&clanID='.$dd['clan2'].'&cupID='.$cupID.'"><b>'.getclanname2($dd['clan2']).'</b></a> '.$_language->module['won'];
			}	
		
			if(!$off_score1 && !$off_score2) $winner = $_language->module['no_winner'];
			$ergebnis = $off_score1.':'.$off_score2;
		}
		
if($t_name2($laddID)) {
   $team1 = '<a href="?site=profile&id='.$dd['clan1'].'"><b>'.getnickname($dd['clan1']).'</b></a>';
   $team2 = '<a href="?site=profile&id='.$dd['clan2'].'"><b>'.getnickname($dd['clan2']).'</b></a>';
}else{
   $team1 = '<a href="?site=clans&action=show&clanID='.$dd['clan1'].'"><b>'.getclanname($dd['clan1']).'</b></a>';
   $team2 = '<a href="?site=clans&action=show&clanID='.$dd['clan2'].'"><b>'.getclanname($dd['clan2']).'</b></a>';
  } 
  
  if(getleagueType($dd['matchID'])=="matchno") {
     $stage = "Group";
     $round = strtoupper(returnGroup($userID,getleagueID($dd['matchID'])));
     $map_name = "n/a";
  }
  elseif(getleagueType($dd['matchID'])=="ladID") {
  
     $check_c = safe_query("SELECT chalID FROM ".PREFIX."cup_challenges WHERE chalID='".$dd['matchno']."' && ladID='$laddID'");
     $cc = mysql_fetch_array($check_c);
     
     if($cc['chalID']==$dd['matchID'] && mysql_num_rows($check_c)) {
        $stage = 'Match Play';
	$round = 'Challenged on-site';
     }
     else{
        $stage = 'Match Play';
	$round = 'Scheduled off-site';
     }
  }
  else{
     $stage = "Round";
  }
  
		if($dd['einspruch']==1)
			$match_status = '<font color="red">'.$_language->module['status_protest'].'</font>';
		elseif($dd['confirmscore']==1 && $dd['einspruch']==0)
			$match_status = $_language->module['status_closed'];
	    elseif(($dd[$thescore1] || $dd[$thescore2]) && $dd['inscribed']==$dd['clan1'])
	        $match_status = '<font color="#FF6600">Waiting for <b>'.$team2.'</b></font>';
	    elseif(($dd[$thescore1] || $dd[$thescore2]) && $dd['inscribed']==$dd['clan2'])
	        $match_status = '<font color="#FF6600">Waiting for <b>'.$team1.'</b></font>';
		elseif($dd['confirmscore']==0 && $dd['inscribed'] == 0)
			$match_status = $_language->module['status_open'];
		else
			$match_status = $_language->module['status_open'];
		
//SCREENS

		//if(empty($dd['screens']) && (!empty($dd['screen_upper']) OR !empty($dd['screen_name'])))
		//	safe_query("UPDATE ".PREFIX."cup_matches SET screen_upper ='', screen_name ='' WHERE matchno='$matchno' AND $name1");

		$screens = array();
		$screen_upper = array();
		$screen_name = array();
		if(!empty($dd['screens'])) $screens=explode("|", $dd['screens']);
		if(!empty($dd['screen_upper'])) $screen_upper=explode("|", $dd['screen_upper']);
		if(!empty($dd['screen_name'])) $screen_name=explode("||", $dd['screen_name']);
		$screenshots = '';
		if($dd['screens'] && $dd['screen_upper'] && $dd['screen_name']) {
			$n=1;			
			foreach($screens as $screen) {
				if(!empty($screen)) {
					$screenshots.='<tr>
							  <td align="left" width="100%" bgcolor="'.$bg1.'"><a href="images/cup-screens/'.$screen.'" target="_blank"><b>'.$screen_name[$n].'</b></a> - '.$_language->module['uploaded_by'].' <a href="?site=profile&id='.$screen_upper[$n].'">'.getnickname($screen_upper[$n]).'</a> '.(iscupadmin($screen_upper[$n]) ? "(admin)" : "").'</td>
						       </tr> ';
					$n++;
				}
				unset($screen);
			}
		}
		else{
					$screenshots = '<tr>
							  <td align="center" width="100%" bgcolor="'.$bg1.'">- no files uploaded -</td>
						       </tr>';
		}
		
//USER LEADEROPTIONS

		$date=date('d/m/Y H:i', $dd['date']);
		if($t_name2($laddID)){
			if($loggedin) {
			
			if(isset($_GET['type']) && $_GET['type']=='gs') {
			   $report_link1 = '?site=groups&'.$t_name.'='.$laddID.'&match='.$dd['matchID'].'';
			   $report_link2 = '?site=groups&'.$t_name.'='.$laddID.'&match='.$dd['matchID'].'';
			}else{
			   $report_link1 = '?site=matches&action=viewmatches&clanID='.$dd['clan1'].'&'.$t_name.'='.$cupID.'&type=1#'.$dd['matchno'].'';
			   $report_link2 = '?site=matches&action=viewmatches&clanID='.$dd['clan2'].'&'.$t_name.'='.$cupID.'&type=1#'.$dd['matchno'].'';
			}
			
			  $ticket = safe_query("SELECT * FROM ".PREFIX."cup_tickets WHERE matchID='".$dd['matchID']."'");
			  $tic=mysql_fetch_array($ticket);
			  
			  if(mysql_num_rows($ticket)) $pt = '<img src="images/cup/icons/ticket.png"> <a href="?site=cupactions&action=mytickets&tickID='.$tic['ticketID'].'">Protest Ticket</a>'; else $pt = '';
			
				if($userID == $dd['clan1'])
					$leaderoptions='<tr>
					                  <td class="title2"><img src="images/cup/icons/tchat.png"> <a href="javascript:void(0)" onclick="MM_openBrWindow(\'popup.php?site=shout&id='.$dd['matchID'].'&type=matchID\',\'Match Chat\',\'toolbar=no,status=no,scrollbars=no,width=550,height=340\');">'.$_language->module['match_chat'].'</a></td>
							  <td class="title2"><img src="images/cup/icons/request.png"> <a href="?site=cupactions&action=mediarequest&matchID='.$dd['matchID'].'">'.$_language->module['request_matchmedia'].'</a></td>
							  <td class="title2"><img src="images/cup/icons/addmedia.gif"> <a href="?site=cupactions&action=screenupload&'.$t_name.'='.$cupID.'&matchID='.$dd['matchID'].'&'.$name3.'">'.$_language->module['upload_screen'].'</a></td>
							  <td class="title2"><img src="images/cup/icons/manage.gif"> <a href="?site=cupactions&action=match_edit&'.$t_name.'='.$cupID.'&matchID='.$dd['matchID'].'&'.$name3.'">'.$_language->module['edit_matchdetails'].'</a></td>
							  <td class="title2"><img src="images/cup/icons/edit.png" width="16" height="16"> <a href="'.$report_link1.'">Match Report</a></td>
							  <td class="title2">'.$pt.'</td>
							  <td class="title2"><img src="images/cup/icons/comments.png"> <a href="?site=cupactions&action=comments&clanID='.$dd['clan1'].'&matchID='.$dd['matchID'].'">Comments</a></td>
							</tr>'; 
				elseif($userID == $dd['clan2'])
					$leaderoptions='<tr>
					                  <td class="title2"><img src="images/cup/icons/tchat.png"> <a href="javascript:void(0)" onclick="MM_openBrWindow(\'popup.php?site=shout&id='.$dd['matchID'].'&type=matchID\',\'Match Chat\',\'toolbar=no,status=no,scrollbars=no,width=550,height=340\');">'.$_language->module['match_chat'].'</a></td>
							  <td class="title2"><img src="images/cup/icons/request.png"> <a href="?site=cupactions&action=mediarequest&matchID='.$dd['matchID'].'">'.$_language->module['request_matchmedia'].'</a></td>
							  <td class="title2"><img src="images/cup/icons/addmedia.gif"> <a href="?site=cupactions&action=screenupload&'.$t_name.'='.$cupID.'&matchID='.$dd['matchID'].'&'.$name3.'">'.$_language->module['upload_screen'].'</a></td>
							  <td class="title2"><img src="images/cup/icons/manage.gif"> <a href="?site=cupactions&action=match_edit&'.$t_name.'='.$cupID.'&matchID='.$dd['matchID'].'&'.$name3.'">'.$_language->module['edit_matchdetails'].'</a></td>
							  <td class="title2"><img src="images/cup/icons/edit.png" width="16" height="16"> <a href="'.$report_link2.'">Match Report</a></td>
							  <td class="title2">'.$pt.'</td>
							  <td class="title2"><img src="images/cup/icons/comments.png"> <a href="?site=cupactions&action=comments&clanID='.$dd['clan2'].'&matchID='.$dd['matchID'].'">Comments</a></td>
							</tr>'; 
			}
			
//USER ADMINREPORT	

			$report_admin_sh = htmloutput($dd['report']);
			$report_admin_sh = toggle($report_admin_sh, $dd['report']);
			
			if($dd['report'])
				$report_admin='<br />
								<table bgcolor="'.$border.'" cellpadding="'.$cellpadding.'" width="100%">
							     <tbody><tr>
											<td colspan="2" class="title"><div align="center">'.$_language->module['report_by'].' Admin:</div></td>
										</tr>
										<tr>
											<td colspan="2" bgcolor="'.$pagebg.'"></td>
										</tr>
										<tr>
											<td colspan="2" bgcolor="'.$bg1.'">
												<table bgcolor="'.$bg1.'" border="0" cellpadding="0" cellspacing="'.$cellspacing.'" width="100%">
													<tbody>
														<tr bgcolor="'.$bg1.'">
															<td valign="top" width="90%"><div style="margin: 2px;">'.$report_admin_sh.'</div></td>
														</tr>
													</tbody>
												</table>
											</td>
										</tr>
								 </tbody>
								</table>';
			else
				$report_admin = '';
				
//USER1 REPORT

			if($dd['report_team1'])
				$report_team1='<br />
								<table bgcolor="'.$border.'" cellpadding="'.$cellpadding.'" width="100%">
									<tbody>
										<tr>
											<td colspan="2" class="title"><div align="center">'.$_language->module['report_by'].' '.getnickname($dd['clan1']).':</div></td>
										</tr>
										<tr>
											<td colspan="2" bgcolor="'.$pagebg.'"></td>
										</tr>
										<tr>
											<td colspan="2" bgcolor="'.$bg1.'">
												<table bgcolor="'.$bg1.'" border="0" cellpadding="0" cellspacing="'.$cellspacing.'" width="100%">
													<tbody>
														<tr bgcolor="'.$bg1.'">
															<td valign="top" width="90%"><div style="margin: 2px;">'.$dd['report_team1'].'</div></td>
														</tr>
													</tbody>
												</table>
											</td>
										</tr>
									</tbody>
								</table>';
			else
				$report_team1 = '';
				
//USER2 REPORT
		
			if($dd['report_team2'])
				$report_team2='<br />
								<table bgcolor="'.$border.'" cellpadding="'.$cellpadding.'" width="100%">
									<tbody>
										<tr>
											<td colspan="2" class="title"><div align="center">'.$_language->module['report_by'].' '.getnickname($dd['clan2']).':</div></td>
										</tr>
										<tr>
											<td colspan="2" bgcolor="'.$pagebg.'"></td>
										</tr>
										<tr>
											<td colspan="2" bgcolor="'.$bg1.'">
												<table bgcolor="'.$bg1.'" border="0" cellpadding="0" cellspacing="'.$cellspacing.'" width="100%">
													<tbody>
														<tr bgcolor="'.$bg1.'">
															<td valign="top" width="90%"><div style="margin: 2px;">'.$dd['report_team2'].'</div></td>
														</tr>
													</tbody>
												</table>
											</td>
										</tr>
									</tbody>
								</table>';
			else
				$report_team2 = '';
		}else{
		
//TEAM LEADEROPTIONS	

			if(isset($_GET['type']) && $_GET['type']=='gs') {
			   $report_link1 = '?site=groups&'.$t_name.'='.$laddID.'#'.$dd['matchID'].'';
			   $report_link2 = '?site=groups&'.$t_name.'='.$laddID.'#'.$dd['matchID'].'';
			}else{
			   $report_link1 = '?site=matches&action=viewmatches&clanID='.$dd['clan1'].'&'.$t_name.'='.$cupID.'#'.$dd['matchno'].'';
			   $report_link2 = '?site=matches&action=viewmatches&clanID='.$dd['clan2'].'&'.$t_name.'='.$cupID.'#'.$dd['matchno'].'';
			}	
			
			  $ticket = safe_query("SELECT * FROM ".PREFIX."cup_tickets WHERE matchID='".$dd['matchID']."'");
			  $tic=mysql_fetch_array($ticket);
			  
			  if(mysql_num_rows($ticket)) $pt = '<img src="images/cup/icons/ticket.png"> <a href="?site=cupactions&action=mytickets&tickID='.$tic['ticketID'].'">Protest Ticket</a>'; else $pt = '';
		
			if(memin($userID,$dd['clan1']))
				$leaderoptions='<tr>
				                  <td class="title2"><img src="images/cup/icons/tchat.png"> <a href="javascript:void(0)" onclick="MM_openBrWindow(\'popup.php?site=shout&id='.$dd['matchID'].'&type=matchID\',\'Match Chat\',\'toolbar=no,status=no,scrollbars=no,width=550,height=340\');">'.$_language->module['match_chat'].'</a></td>
						  <td class="title2">'.$pt.'</td>
						  <td class="title2"><img src="images/cup/icons/addmedia.gif"> <a href="?site=cupactions&action=screenupload&laddID='.$cupID.'&matchID='.$dd['matchID'].'&clanID='.$dd['clan1'].'&'.$name3.'">'.$_language->module['upload_screen'].'</a></td>
						</tr>';
		    elseif(memin($userID,$dd['clan2']))          
		        $leaderoptions='<tr>
			                  <td class="title2"><img src="images/cup/icons/tchat.png"> <a href="javascript:void(0)" onclick="MM_openBrWindow(\'popup.php?site=shout&id='.$dd['matchID'].'&type=matchID\',\'Match Chat\',\'toolbar=no,status=no,scrollbars=no,width=550,height=340\');">'.$_language->module['match_chat'].'</a></td>
					  <td class="title2">'.$pt.'</td>
					  <td class="title2"><img src="images/cup/icons/addmedia.gif"> <a href="?site=cupactions&action=screenupload&laddID='.$cupID.'&matchID='.$dd['matchID'].'&clanID='.$dd['clan2'].'&'.$name3.'">'.$_language->module['upload_screen'].'</a></td>
					</tr>';

			if(isleader($userID,$dd['clan1']))
				$leaderoptions='<tr>
				                  <td class="title2"><img src="images/cup/icons/tchat.png"> <a href="javascript:void(0)" onclick="MM_openBrWindow(\'popup.php?site=shout&id='.$dd['matchID'].'&type=matchID\',\'Match Chat\',\'toolbar=no,status=no,scrollbars=no,width=550,height=340\');">'.$_language->module['match_chat'].'</a></td>
						  <td class="title2"><img src="images/cup/icons/request.png"> <a href="?site=cupactions&action=mediarequest&matchID='.$dd['matchID'].'&clanID='.$dd['clan1'].'">'.$_language->module['request_matchmedia'].'</a></td>
						  <td class="title2"><img src="images/cup/icons/addmedia.gif"> <a href="?site=cupactions&action=screenupload&laddID='.$cupID.'&matchID='.$dd['matchID'].'&clanID='.$dd['clan1'].'&'.$name3.'">'.$_language->module['upload_screen'].'</a></td>
						  <td class="title2"><img src="images/cup/icons/manage.gif"> <a href="?site=cupactions&action=match_edit&laddID='.$cupID.'&matchID='.$dd['matchID'].'&clanID='.$dd['clan1'].'&'.$name3.'">'.$_language->module['edit_matchdetails'].'</a></td>
						  <td class="title2"><img src="images/cup/icons/edit.png" width="16" height="16"> <a href="'.$report_link1.'">Match Report</a></td>
						  <td class="title2">'.$pt.'</td>
						  <td class="title2"><img src="images/cup/icons/comments.png"> <a href="?site=cupactions&action=comments&clanID='.$dd['clan1'].'&matchID='.$dd['matchID'].'">Comments</a></td>
						</tr>'; 
			elseif(isleader($userID,$dd['clan2']))
				$leaderoptions='<tr>
				                  <td class="title2"><img src="images/cup/icons/tchat.png"> <a href="javascript:void(0)" onclick="MM_openBrWindow(\'popup.php?site=shout&id='.$dd['matchID'].'&type=matchID\',\'Match Chat\',\'toolbar=no,status=no,scrollbars=no,width=550,height=340\');">'.$_language->module['match_chat'].'</a></td>
						  <td class="title2"><img src="images/cup/icons/request.png"> <a href="?site=cupactions&action=mediarequest&matchID='.$dd['matchID'].'&clanID='.$dd['clan2'].'">'.$_language->module['request_matchmedia'].'</a></td>
						  <td class="title2"><img src="images/cup/icons/addmedia.gif"> <a href="?site=cupactions&action=screenupload&laddID='.$cupID.'&matchID='.$dd['matchID'].'&clanID='.$dd['clan2'].'&'.$name3.'">'.$_language->module['upload_screen'].'</a></td>
						  <td class="title2"><img src="images/cup/icons/manage.gif"> <a href="?site=cupactions&action=match_edit&laddID='.$cupID.'&matchID='.$dd['matchID'].'&clanID='.$dd['clan2'].'&'.$name3.'">'.$_language->module['edit_matchdetails'].'</a></td>
						  <td class="title2"><img src="images/cup/icons/edit.png" width="16" height="16"> <a href="'.$report_link2.'">Match Report</a></td>
						  <td class="title2">'.$pt.'</td>
						  <td class="title2"><img src="images/cup/icons/comments.png"> <a href="?site=cupactions&action=comments&clanID='.$dd['clan2'].'&matchID='.$dd['matchID'].'">Comments</a></td>
						</tr>'; 

//TEAM ADMINREPORT

			$report_admin_sh = htmloutput($dd['report']);
			$report_admin_sh = toggle($report_admin_sh, $dd['report']);

			if($dd['report'])
				$report_admin='<br />
								<table bgcolor="'.$border.'" cellpadding="'.$cellpadding.'" width="100%">
									<tbody>
										<tr>
											<td colspan="2" class="title"><div align="center">'.$_language->module['report_by'].' Admin:</div></td>
										</tr>
										<tr>
											<td colspan="2" bgcolor="'.$pagebg.'"></td>
										</tr>
										<tr>
											<td colspan="2" bgcolor="'.$bg1.'">
												<table bgcolor="'.$bg1.'" border="0" cellpadding="0" cellspacing="'.$cellspacing.'" width="100%">
													<tbody>
														<tr bgcolor="'.$bg1.'">
															<td valign="top" width="90%"><div style="margin: 2px;">'.$report_admin_sh.'</div></td>
														</tr>
													</tbody>
												</table>
											</td>
										</tr>
									</tbody>
								</table>';
			else
				$report_admin = '';
				
//TEAM1 REPORT

			if($dd['report_team1'])
				$report_team1='<br />
								<table bgcolor="'.$border.'" cellpadding="'.$cellpadding.'" width="100%">
									<tbody>
										<tr>
											<td colspan="2" class="title"><div align="center">'.$_language->module['report_by'].'  '.getclanname2($dd['clan1']).':</div></td>
										</tr>
										<tr>
											<td colspan="2" bgcolor="'.$pagebg.'"></td>
										</tr>
										<tr>
											<td colspan="2" bgcolor="'.$bg1.'">
												<table bgcolor="'.$bg1.'" border="0" cellpadding="0" cellspacing="'.$cellspacing.'" width="100%">
													<tbody>
														<tr bgcolor="'.$bg1.'">
															<td valign="top" width="90%"><div style="margin: 2px;">'.$dd['report_team1'].'</div></td>
														</tr>
													</tbody>
												</table>
											</td>
										</tr>
									</tbody>
								</table>';
			else
				$report_team1 = '';
				
//TEAM2 REPORT
		
			if($dd['report_team2'])
				$report_team2='<br />
								<table bgcolor="'.$border.'" cellpadding="'.$cellpadding.'" width="100%">
									<tbody>
										<tr>
											<td class="title" align="center">'.$_language->module['report_by'].' '.getclanname2($dd['clan2']).':</td>
										</tr>
										<tr>
											<td bgcolor="'.$pagebg.'"></td>
										</tr>
										<tr>
											<td bgcolor="'.$bg1.'">
												<table bgcolor="'.$bg1.'" border="0" cellpadding="0" cellspacing="'.$cellspacing.'" width="100%">
													<tbody>
														<tr bgcolor="'.$bg1.'">
															<td valign="top" width="90%"><div style="margin: 2px;">'.$dd['report_team2'].'</div></td>
														</tr>
													</tbody>
												</table>
											</td>
										</tr>
									</table>';
			else
				$report_team2 = '';
		}
		
//MAPS
		
    if($ch['map1_final']) {
       $map1 = ''.$ch['map1_final'].'<br>';
       $map1_pic = mapPic($ch['map1_final'],$_GET[$t_name]);
    }if($ch['map2_final']) {
       $map_option2 = 'Map 2 #<br>';
       $map2 = ''.$ch['map2_final'].'<br>';
       $map2_pic = mapPic($ch['map2_final'],$_GET[$t_name]);
    }if($ch['map3_final']) {
       $map_option3 = 'Map 3 #<br>';
       $map3 = ''.$ch['map3_final'].'<br>';
       $map3_pic = mapPic($ch['map3_final'],$_GET[$t_name]);
    }if($ch['map4_final']) {
       $map_option4 = 'Map 4 #<br>';
       $map4_pic = mapPic($ch['map4_final'],$_GET[$t_name]);
    }
        
//V5 view matchmedia requests  

if(ismatchparticipant($userID,$dd['matchID'],$all=1)) {

$getrequests = safe_query("SELECT * FROM ".PREFIX."cup_requests WHERE matchID='".$dd['matchID']."'");
  if(mysql_num_rows($getrequests)) { 
  
  $mm_requests = '<table width="100%" bgcolor="'.$border.'" cellspacing="'.$cellspacing.'" cellpadding="'.$cellpadding.'">
	            <tr>
	             <td class="title" align="center" colspan="4">Matchmedia Requests</td>
	            </tr>
	             <tr>
	             <td class="title2" align="center">No</td>
	             <td class="title2">Info</td>
	             <td class="title2" align="center">Requested On</td>
	             <td class="title2" align="center">Requested By</td>
	            </tr>';
	    
   $no = 1;
    while($rq=mysql_fetch_array($getrequests)) {
     
   $date = date('d/m/Y H:i', $rq['time']);
   
   if($t_name2($laddID))
      $from = '<a href="?site=profile&id='.$rq['userID'].'"><b>'.getnickname($rq['userID']).'</b></a>';
   else
      $from = '<a href="?site=clans&action=show&clanID='.$rq['userID'].'"><b>'.getclanname($rq['userID']).'</b></a>';
      
   $mm_requests.='
	 <tr>
	  <td bgcolor="'.$bg1.'" align="center">#'.$no.'</td>
	  <td bgcolor="'.$bg1.'">'.$rq['reason'].'</td>
	  <td bgcolor="'.$bg1.'" align="center">'.$date.'</td>
	  <td bgcolor="'.$bg1.'" align="center">'.$from.'</td>
	</tr>';
	
	     $no++;
	
  }$mm_requests.='</table>';
 }
} 
 //show map rounds

  if($dd['confirmscore']) {

    if($dd['map4_score1'] || $dd['map4_score2']) {
       $round4_score1 = getname1($dd['clan1'],$dd['ladID'],0,'ladder',1).' +'.$dd['map4_score1'];
       $round4_score2 = getname1($dd['clan2'],$dd['ladID'],0,'ladder',1).' +'.$dd['map4_score2'];
       $round3_score1 = getname1($dd['clan1'],$dd['ladID'],0,'ladder',1).' +'.$dd['map3_score1'];
       $round3_score2 = getname1($dd['clan2'],$dd['ladID'],0,'ladder',1).' +'.$dd['map3_score2'];
       $round2_score1 = getname1($dd['clan1'],$dd['ladID'],0,'ladder',1).' +'.$dd['map2_score1'];
       $round2_score2 = getname1($dd['clan2'],$dd['ladID'],0,'ladder',1).' +'.$dd['map2_score2'];
       $round1_score1 = getname1($dd['clan1'],$dd['ladID'],0,'ladder',1).' +'.$dd['map1_score1'];
       $round1_score2 = getname1($dd['clan2'],$dd['ladID'],0,'ladder',1).' +'.$dd['map1_score2'];
       
       $round4_temp = '
       
							<tr>
								<td bgcolor="'.$bg1.'" colspan="2">&nbsp;</td>
							</tr>
							<tr>
								<td bgcolor="'.$bg1.'" align="center" rowspan="2">'.$map4_pic.'</td>
								<td width="30%" bgcolor="'.$bg1.'" align="center">
								'.$round4_score1.'</td>
							</tr>
							<tr>
								<td width="30%" bgcolor="'.$bg1.'" align="center">
								'.$round4_score2.'</td>
							</tr>';
       
    }elseif($dd['map3_score1'] || $dd['map3_score2']) {
       $round3_score1 = getname1($dd['clan1'],$dd['ladID'],0,'ladder',1).' +'.$dd['map3_score1'];
       $round3_score2 = getname1($dd['clan2'],$dd['ladID'],0,'ladder',1).' +'.$dd['map3_score2'];
       $round2_score1 = getname1($dd['clan1'],$dd['ladID'],0,'ladder',1).' +'.$dd['map2_score1'];
       $round2_score2 = getname1($dd['clan2'],$dd['ladID'],0,'ladder',1).' +'.$dd['map2_score2'];
       $round1_score1 = getname1($dd['clan1'],$dd['ladID'],0,'ladder',1).' +'.$dd['map1_score1'];
       $round1_score2 = getname1($dd['clan2'],$dd['ladID'],0,'ladder',1).' +'.$dd['map1_score2'];
       
       $round3_temp = '

							<tr>
								<td bgcolor="'.$bg1.'" colspan="2">&nbsp;</td>
							</tr>
							<tr>
								<td bgcolor="'.$bg1.'" align="center" rowspan="2">'.$map3_pic.'</td>
								<td width="30%" bgcolor="'.$bg1.'" align="center">
								'.$round3_score1.'</td>
							</tr>
							<tr>
								<td width="30%" bgcolor="'.$bg1.'" align="center">
								'.$round3_score2.'</td>
							</tr>';       
       
    }elseif($dd['map2_score1'] || $dd['map2_score2']) {
       $round2_score1 = getname1($dd['clan1'],$dd['ladID'],0,'ladder',1).' +'.$dd['map2_score1'];
       $round2_score2 = getname1($dd['clan2'],$dd['ladID'],0,'ladder',1).' +'.$dd['map2_score2'];
       $round1_score1 = getname1($dd['clan1'],$dd['ladID'],0,'ladder',1).' +'.$dd['map1_score1'];
       $round1_score2 = getname1($dd['clan2'],$dd['ladID'],0,'ladder',1).' +'.$dd['map1_score2'];
       
       $round2_temp = '
       
							<tr>
								<td bgcolor="'.$bg1.'" colspan="2">&nbsp;</td>
							</tr>
							<tr>
								<td bgcolor="'.$bg1.'" rowspan="2" align="center">'.$map2_pic.'</td>
								<td width="30%" bgcolor="'.$bg1.'" align="center">
								'.$round2_score1.'</td>
							</tr>
							<tr>
								<td width="30%" bgcolor="'.$bg1.'" align="center">
								'.$round2_score2.'</td>
							</tr>';
       
    }else{
       $round1_score1 = getname1($dd['clan1'],$dd['ladID'],0,'ladder',1).' +'.$dd['score1'].'';
       $round1_score2 = getname1($dd['clan2'],$dd['ladID'],0,'ladder',1).' +'.$dd['score2'].'';
       
       if($dd['map1_score1'] || $dd['map1_score2']) {
       
          $round1_temp = '
	  
	  							<tr>
								<td bgcolor="'.$bg1.'" rowspan="2" align="center">'.$map1_pic.'</td>
								<td width="30%" bgcolor="'.$bg1.'" align="center">
								'.$round1_score1.'</td>
							</tr>
							<tr>
								<td width="30%" bgcolor="'.$bg1.'" align="center">
								'.$round1_score2.'</td>
							</tr>';
       }
    }
 }
 
  if(empty($dd['inscribed'])) {
      $inscribed_by = "(no report yet)";
      $confirmed_by = "(no inscription yet)";
      
   }elseif($dd['inscribed']==$dd['clan1'] || $dd['inscribed']==$dd['clan2']){
 
      if($dd['inscribed']==$dd['clan1']) {
          $inscribed_by = getname1($dd['clan1'],getleagueID($dd['matchID']),0,league($dd['matchID']),1);
          $inscribed_date = date("d/m/Y H:i", $dd['inscribed_date']);
        
      if($dd['confirmscore'] && !$dd['einspruch']) {
          $confirmed_by = getname1($dd['clan2'],getleagueID($dd['matchID']),0,league($dd['matchID']),1);
          $confirmed_date = date("d/m/Y H:i", $dd['confirmed_date']);
           
      }else
          $confirmed_by = "(not confirmed yet)";
        
    }else{
    
      if($dd['inscribed']==$dd['clan2']) {
          $inscribed_by = getname1($dd['clan2'],getleagueID($dd['matchID']),0,league($dd['matchID']),1);
          $inscribed_date = date("d/m/Y H:i", $dd['inscribed_date']);
        
      if($dd['confirmscore'] && !$dd['einspruch']) {
          $confirmed_by = getname1($dd['clan1'],getleagueID($dd['matchID']),0,league($dd['matchID']),1);
          $confirmed_date = date("d/m/Y H:i", $dd['confirmed_date']);
           
      }else
          $confirmed_by = "(not confirmed yet)";
    }
   }
  }elseif($dd['confirmscore']) {
          $inscribed_by = "(admin)";
          $confirmed_by = "(admin)";
  }
  
 if($dd['server']) {
 
    $server_temp = '
 
                                                                                <tr>
											<td align="left" bgcolor="'.$bg1.'">Server:</td>
											<td align="left" bgcolor="'.$bg1.'">'.$dd['server'].'</td>
										</tr>';										
 }
 if($dd['server']) {
 
    $hltv_temp = '
 
                                                                                <tr>
											<td align="left" bgcolor="'.$bg1.'">HLTV:</td>
											<td align="left" bgcolor="'.$bg1.'">'.$dd['hltv'].'</td>
										</tr>';										
 }
 if($map_option1) {

    $map_temp = '

										<tr>
											<td align="left" width="35%" bgcolor="$bg1">Map 
											<br>$map_option1 $map_option2 
											$map_option3 $map_option4 
											$map_option5</td>
											<td align="left" bgcolor="$bg1" width="65%">$map_name 
											$map1 $map2 $map3 $map4 $map5</td>
										</tr>';    
 }
 
//echo template

       if($dd[clan1] && $dd[clan2])
       {
		eval ("\$cup_matches = \"".gettemplate("cup_matches")."\";");
		echo $cup_matches;
       }
       else
       {
 		echo "Both participants are not registered.";
       }
		
       if(in_array($dd['cupID'],$array))
          $ml = 'index.php?site=cup_matches&match='.$dd['matchID'].'&cupID='.$dd['matchno'].'&type=gs';
       elseif(in_array($dd['ladID'],$array))
          $ml = 'index.php?site=cup_matches&match='.$dd['matchID'].'&laddID='.$dd['matchno'].'&type=gs';
       elseif(!$dd['matchno'] && $dd['cupID'])
          $ml = '?index.phpsite=cup_matches&matchID='.$dd['matchID'].'&cupID='.$dd['cupID'].'';
       elseif(!$dd['matchno'] && $dd['ladID'])
          $ml = 'index.php?site=cup_matches&matchID='.$dd['matchID'].'&laddID='.$dd['ladID'].'';
       elseif(!in_array($dd['cupID'],$array) && $dd['cupID'])
          $ml = 'index.php?site=cup_matches&match='.$dd['matchno'].'&cupID='.$dd['cupID'].'';        
       elseif(!in_array($dd['ladID'],$array) && $dd['ladID'])
          $ml = 'index.php?site=cup_matches&match='.$dd['matchno'].'&laddID='.$dd['ladID'].'';

		$parentID = $dd['matchID'];
		$comments_allowed = $dd['comment'];
		$type = "cm";
		$referer = "$ml";

       if($dd[clan1] && $dd[clan2])
       {

		include("comments.php");
    }

}else
	echo 'invalid match!';
	
$cpr ? ca_copyr() : die();		
?>