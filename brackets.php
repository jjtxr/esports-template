 <?php
//include configuration and language
include ("config.php");
$_language->read_module('cup');
?>

<link href="cup.css" rel="stylesheet" type="text/css">

<script language="javascript">wmtt = null; document.onmousemove = updateWMTT; function updateWMTT(e) { x = (document.all) ? window.event.x + document.body.scrollLeft : e.pageX; y = (document.all) ? window.event.y + document.body.scrollTop  : e.pageY; if (wmtt != null) { wmtt.style.left=(x + 20) + "px"; wmtt.style.top=(y + 20) + "px"; } } function showWMTT(id) {	wmtt = document.getElementById(id);	wmtt.style.display = "block" } function hideWMTT() { wmtt.style.display = "none"; }</script>
<script type="text/javascript">
function SelectAll(id)
{
    document.getElementById(id).focus();
    document.getElementById(id).select();
}
</script>
<div class="tooltip" id="confirmed" align="left"><?php echo $_language->module['confirmed']; ?></div>
<div class="tooltip" id="admin_confirmed" align="left"><?php echo $_language->module['a_confirmed']; ?></div>
<div class="tooltip" id="awaiting" align="left"><?php echo $_language->module['wait']; ?></div>
<div class="tooltip" id="protest" align="left"><?php echo $_language->module['protest']; ?></div>

<?php
/* Cup SQL-Querys */
safe_query("UPDATE ".PREFIX."cups SET status='2' WHERE start<='".time()."'");
safe_query("UPDATE ".PREFIX."cups SET status='3' WHERE ende<='".time()."'");
safe_query("UPDATE ".PREFIX."cup_warnings SET expired='1' WHERE deltime<='".time()."'");
/**************/

$bg1=BG_1;
$bg2=BG_1;
$bg3=BG_1;
$bg4=BG_1;
$cpr=ca_copyr();

//automated functions

randomize_brackets($_GET['cupID']);
tournament_winners($_GET['cupID']);
shrink_tree($_GET['cupID']);
qualifiersToLeague($_GET['cupID']);
auto_wildcard();
getcuptimezone();
match_query_type();

//maintenance

$mode = safe_query("SELECT maintenance FROM ".PREFIX."cup_settings");
$ds = mysql_fetch_array($mode); 

    if($ds['maintenance']==0) $maintenance = true;
elseif($ds['maintenance']==1 && issuperadmin($userID)) $maintenance = true;
elseif($ds['maintenance']==2 && (issuperadmin($userID) || iscupadmin($userID))) $maintenance = true;
else $maintenance = false; 

if($maintenance==true) {

	if($_GET['action'] == 'tree'){
		
		$cupID = $_GET['cupID'];
		$ergebnis = safe_query("SELECT * FROM ".PREFIX."cups WHERE ID = '".$cupID."'");
		$ds = mysql_fetch_array($ergebnis);
		
		$cupname = getcupname($ds['ID']);
		if(!$cpr || !ca_copyr()) die();
		
        include ("title_cup.php");if(is1on1($cupID)) $participants = 'Players';		
		else $participants = 'Teams';
		
		eval ("\$title_cup = \"".gettemplate("title_cup")."\";");
		echo $title_cup;
		
		$ergebnis = safe_query("SELECT * FROM ".PREFIX."cup_baum WHERE cupID= '".$cupID."'");
		$dd=mysql_fetch_array($ergebnis);
		
		$flag_type = (is1on1($cupID) ? "getusercountry" : "getclancountry");
		
		//first winner
		if($dd['wb_winner']==2147483647)	
		{	
		   $first_name = $_language->module['wildcard'];
		   $first_link = '';
		   $first_link2= '';
		   $first_flag = '';
		   $wildcard_first = 1;
		}
		else{
		   $first_name = ($dd['wb_winner'] ? getname1($dd['wb_winner'],$cupID,0,'cup',1) : '');
		   $first_link = '<a href="'.getname3($dd['wb_winner'],$cupID,0,'cup',1).'">';
		   $first_link2= '</a>';
		   $first_flag = flags('[flag]'.$flag_type($dd['wb_winner']).'[/flag]');
                   $wildcard_first = 0;			   
		}
		
		//second winner
		if($dd['lb_winner']==2147483647)	{	
		   $second_name = $_language->module['wildcard'];
		   $second_link = '';
		   $second_link2= '';
		   $second_flag = '';
		   $wildcard_second = 1;
		}
		else{
		   $second_name = ($dd['lb_winner'] ? getname1($dd['lb_winner'],$cupID,0,'cup',1) : '');
		   $second_link = '<a href="'.getname3($dd['lb_winner'],$cupID,0,'cup',1).'">';
		   $second_link2= '</a>';
		   $second_flag = flags('[flag]'.$flag_type($dd['lb_winner']).'[/flag]');
		   $wildcard_second = 0;
		}
		
		//third winner
		if($dd['third_winner']==2147483647)	{	
		   $third_name = $_language->module['wildcard'];
		   $third_link = '';
		   $third_link2= '';
		   $third_flag = '';
		   $wildcard_third = 1;
		}
		else
		{
		   $third_name = ($dd['third_winner'] ? getname1($dd['third_winner'],$cupID,0,'cup',1) : '');
		   $third_link = '<a href="'.getname3($dd['third_winner'],$cupID,0,'cup',1).'">';
		   $third_link2= '</a>';
		   $third_flag = flags('[flag]'.$flag_type($dd['third_winner']).'[/flag]');
		   $wildcard_third = 0;
		}
		
		$empty_row = '
		 	  <table width="100%" border="0" cellspacing="'.$cellspacing.'" cellpadding="'.$cellpadding.'">
                            <tr>
                              <td align="left" bgcolor="" width="10%">&nbsp;</td>
                              <td bgcolor="" width="90%">n/a</td>
                            </tr>
			  </table>';
		
		if($wildcard_first==1 || $first_name!='') {

			$clan['wb_winner'] = 
			
			$first_link.'
		 	  <table width="100%" border="0" cellspacing="'.$cellspacing.'" cellpadding="'.$cellpadding.'">
                            <tr>
                              <td align="left" bgcolor="" width="10%">'.$first_flag.'</td>
                              <td bgcolor="" width="90%">'.$first_name.'</td>
                            </tr>
			  </table>
			'.$first_link2;
		}
		else{
		
			$clan['wb_winner'] = $empty_row;
		}
			
		if($wildcard_second==1 || $second_name!='') {
			  
			$clan['lb_winner'] = 
			
			$second_link.'
			  <table width="100%" border="0" cellspacing="'.$cellspacing.'" cellpadding="'.$cellpadding.'">
                            <tr>
                              <td align="left" bgcolor="" width="10%">'.$second_flag.'</td>
                              <td bgcolor="" width="90%">'.$second_name.'</td>
                            </tr>
			  </table>
			'.$second_link2;
		}
                else{
			$clan['lb_winner'] = $empty_row;
                }		
                
			
		if($wildcard_third==1 || $third_name!='') {
			
			$clan['third_winner'] = 
			
			$third_link.'
			<a href="?site=profile&id='.$dd['third_winner'].'">
			  <table width="100%" border="0" cellspacing="'.$cellspacing.'" cellpadding="'.$cellpadding.'">
			    <tr>
                              <td align="left" bgcolor="" width="10%">'.$third_flag.'</td>
                              <td bgcolor="" width="90%">'.$third_name.'</td>
                            </tr>
			  </table>
			'.$third_link2;
		}
		else{
			$clan['third_winner'] = $empty_row;
		}
					
		$i2 = 1;
		for ($i=1; $i<128; $i++) {
			$matches=safe_query("SELECT matchID, clan1, clan2, score1, score2, confirmscore, inscribed, einspruch FROM ".PREFIX."cup_matches WHERE cupID = '$cupID' and matchno = '$i'");
			if(mysql_num_rows($matches)){
				while($db=mysql_fetch_array($matches)) {
					if($db['confirmscore']){
						$score1[$i] = $db[score1];
						$score2[$i] = $db[score2];
					}else{
						$score1[$i] = '&nbsp;';
						$score2[$i] = '&nbsp;';
					}
					
					// background colour for winner/looser
					
					if($db[clan1] && $db[clan2] && $db[confirmscore] && !$db[einspruch])
					{
					    //$c_bg1 = ($db[score1] > $db[score2] ? $wincolor : $loosecolor);
					    //$c_bg2 = ($db[score1] > $db[score2] ? $wincolor : $loosecolor);
					}
					else
					{
		                //$c_bg1 = $dd[bg1];
		                //$c_bg2 = $dd[bg2];
					}
		                $c_bg1 = $dd[bg1];
		                $c_bg2 = $dd[bg2];
					
					if(is1on1($cupID)){
						if($score1[$i] > $score2[$i]){
							$clan[$i2] = '	
													
							<a href="?site=profile&id='.$db[clan1].'">
							  <table width="100%" border="0" cellspacing="'.$cellspacing.'" cellpadding="'.$cellpadding.'">
                                <tr>
                                  <td align="left" bgcolor="" width="10%">'.flags('[flag]'.getusercountry($db['clan1']).'[/flag]').'</td>
                                  <td bgcolor="" width="90%"><a href="?site=profile&id='.$db['clan1'].'"><b>'.getnickname($db['clan1']).'</b></a></td>
                                </tr>
                              </table>
							</a>';
							
							$i2++; 
							$clan[$i2] = '
							
							<a href="?site=profile&id='.$db[clan2].'">
							  <table width="100%" border="0" cellspacing="'.$cellspacing.'" cellpadding="'.$cellpadding.'">
                                <tr>
                                  <td align="left" bgcolor="" width="10%">'.flags('[flag]'.getusercountry($db['clan2']).'[/flag]').'</td>
                                  <td bgcolor="" width="90%"><a href="?site=profile&id='.$db['clan2'].'"><b>'.getnickname($db['clan2']).'</b></a></td>
                                </tr>
                              </table>
                            </a>';
                            
						}elseif($score1[$i] < $score2[$i]){
							$clan[$i2] = '
							
							<a href="?site=profile&id='.$db[clan1].'">
							  <table width="100%" border="0" cellspacing="'.$cellspacing.'" cellpadding="'.$cellpadding.'">
                                <tr>
                                  <td align="left" bgcolor="" width="10%">'.flags('[flag]'.getusercountry($db['clan1']).'[/flag]').'</td>
                                  <td bgcolor="" width="90%"><a href="?site=profile&id='.$db['clan1'].'"><b>'.getnickname($db['clan1']).'</b></a></td>
                                </tr>
                              </table>
                            </a>';
                            
							$i2++; 
							$clan[$i2] = '
							
							<a href="?site=profile&id='.$db[clan2].'">
							  <table width="100%" border="0" cellspacing="'.$cellspacing.'" cellpadding="'.$cellpadding.'">
                                <tr>
                                  <td align="left" bgcolor="" width="10%">'.flags('[flag]'.getusercountry($db['clan2']).'[/flag]').'</td>
                                  <td bgcolor="" width="90%"><a href="?site=profile&id='.$db['clan2'].'"><b>'.getnickname($db['clan2']).'</b></a></td>
                                </tr>
                              </table>
                            </a>';
                            
                            
						}else{
						
						
							$clan[$i2] = '
							
							<a href="?site=profile&id='.$db[clan1].'">
							  <table width="100%" border="0" cellspacing="'.$cellspacing.'" cellpadding="'.$cellpadding.'">
                                <tr>
                                  <td align="left" bgcolor="" width="10%">'.flags('[flag]'.getusercountry($db['clan1']).'[/flag]').'</td>
                                  <td bgcolor="" width="90%"><a href="?site=profile&id='.$db['clan1'].'"><b>'.getnickname($db['clan1']).'</b></a></td>
                                </tr>
                              </table>
                            </a>';
                            
							$i2++; 
							$clan[$i2] = '
							
							<a href="?site=profile&id='.$db[clan2].'">
							  <table width="100%" border="0" cellspacing="'.$cellspacing.'" cellpadding="'.$cellpadding.'">
                                <tr>
                                  <td align="left" bgcolor="" width="10%">'.flags('[flag]'.getusercountry($db['clan2']).'[/flag]').'</td>
                                  <td bgcolor="" width="90%"><a href="?site=profile&id='.$db['clan2'].'"><b>'.getnickname($db['clan2']).'</b></a></td>
                                </tr>
                              </table>
                            </a>';
						}
					}else{
					
						if($score1[$i] > $score2[$i]){
							$clan[$i2] = '
							
							<a href="?site=clans&action=show&clanID='.$db[clan1].'&cupID='.$cupID.'">
							  <table width="100%" border="0" cellspacing="'.$cellspacing.'" cellpadding="'.$cellpadding.'">
                                <tr>
                                  <td align="left" bgcolor="" width="10%">'.flags('[flag]'.getclancountry($db[clan1],$img=1).'[/flag]').'</td>
                                  <td bgcolor="" width="90%"><a href="?site=clans&action=show&clanID='.$db[clan1].'&cupID='.$cupID.'"><b>'.getclanname2($db[clan1]).'</b></a></td>
                                </tr>
                              </table>
                            </a>';
                            
							$i2++;
							$clan[$i2] = '
							
							<a href="?site=clans&action=show&clanID='.$db[clan2].'&cupID='.$cupID.'">
							  <table width="100%" border="0" cellspacing="'.$cellspacing.'" cellpadding="'.$cellpadding.'">
							    <tr>
							      <td align="left" bgcolor="" width="10%">'.flags('[flag]'.getclancountry($db[clan2],$img=1).'[/flag]').'</td>
							      <td bgcolor="" width="90%"><a href="?site=clans&action=show&clanID='.$db[clan2].'&cupID='.$cupID.'"><b>'.getclanname2($db[clan2]).'</b></a></td>
							    </tr>
							  </table>
							</a>';
							
						}elseif($score1[$i] < $score2[$i]){
							$clan[$i2] = '
							
							<a href="?site=clans&action=show&clanID='.$db[clan1].'&cupID='.$cupID.'">
							  <table width="100%" border="0" cellspacing="'.$cellspacing.'" cellpadding="'.$cellpadding.'">
							    <tr>
							      <td align="left" bgcolor="" width="10%">'.flags('[flag]'.getclancountry($db['clan1'],$img=1).'[/flag]').'</td>
							      <td bgcolor="" width="90%"><a href="?site=clans&action=show&clanID='.$db[clan1].'&cupID='.$cupID.'"><b>'.getclanname2($db[clan1]).'</b></a></td>
							    </tr>
							  </table>
							</a>';
							
							$i2++;
							$clan[$i2] = '
							
							<a href="?site=clans&action=show&clanID='.$db[clan2].'&cupID='.$cupID.'">
							  <table width="100%" border="0" cellspacing="'.$cellspacing.'" cellpadding="'.$cellpadding.'">
							    <tr>
							      <td align="left" bgcolor="" width="10%">'.flags('[flag]'.getclancountry($db['clan2'],$img=1).'[/flag]').'</td>
							      <td bgcolor="" width="90%"><a href="?site=clans&action=show&clanID='.$db[clan2].'&cupID='.$cupID.'"><b>'.getclanname2($db[clan2]).'</b></a></td>
							    </tr>
							  </table>
							</a>';
							
						}else{
						
							$clan[$i2] = '
							
							<a href="?site=clans&action=show&clanID='.$db[clan1].'&cupID='.$cupID.'">
							  <table width="100%" border="0" cellspacing="'.$cellspacing.'" cellpadding="'.$cellpadding.'">
							    <tr>
							      <td align="left" bgcolor="" width="10%">'.flags('[flag]'.getclancountry($db['clan1'],$img=1).'[/flag]').'</td>
							      <td bgcolor="" width="90%"><a href="?site=clans&action=show&clanID='.$db[clan1].'&cupID='.$cupID.'"><b>'.getclanname2($db[clan1]).'</b></a></td>
							    </tr>
							  </table>
							</a>';
							
							$i2++;
							$clan[$i2] = '
							
							<a href="?site=clans&action=show&clanID='.$db[clan2].'&cupID='.$cupID.'">
							  <table width="100%" border="0" cellspacing="'.$cellspacing.'" cellpadding="'.$cellpadding.'">
							    <tr>
							      <td align="left" bgcolor="" width="10%">'.flags('[flag]'.getclancountry($db['clan2'],$img=1).'[/flag]').'</td>
							      <td bgcolor="" width="90%"><a href="?site=clans&action=show&clanID='.$db[clan2].'&cupID='.$cupID.'"><b>'.getclanname2($db[clan2]).'</b></a></td>
							    </tr>
							  </table>
							</a>';
						}
					}
//match reporting for 1on1 cups

if($userID==$db['clan1']) {
    if($db['einspruch']=='1' || $db['confirmscore']=='1') $action1 = ''; 
elseif($db['score1']=='0' && $db['score2']=='0' && $db['einspruch']=='0') $action1 = '<a href="?site=cupactions&amp;action=score&matchID='.$db['matchID'].'&amp;clan1='.$db['clan1'].'&amp;cupID='.$cupID.'&one=1"><img border="0" src="images/cup/icons/addresult.gif" align="right" width="10" height="10"></a>';
elseif($db['score1']!='0' && $db['score2']!='0' && $db['inscribed']==$db['clan2'] && $db['einspruch']=='0') $action1 = '<a href="?site=cupactions&amp;action=confirmscore&amp;matchID='.$db['matchID'].'&amp;clanID='.$db['clan1'].'&amp;cupID='.$cupID.'&one=1"><img border="0" src="images/cup/success.png" width="8" height="10"></a> <a href="?site=cupactions&amp;action=protest&amp;matchID='.$db['matchID'].'&clanID='.$db['clan1'].'&cupID='.$cupID.'" onclick="return confirm(\'Are you sure there was something wrong with the match?\');"><img border="0" src="images/cup/error.png" width="8" height="10"></a>'; 

}elseif($userID==$db['clan2']) {	
    if($db['einspruch']=='1' || $db['confirmscore']=='1') $action1 = '';
elseif($db['score1']=='0' && $db['score2']=='0' && $db['einspruch']=='0') $action1 = '<a href="?site=cupactions&amp;action=score&matchID='.$db['matchID'].'&amp;clan1='.$db['clan2'].'&amp;cupID='.$cupID.'&one=1"><img border="0" src="images/cup/icons/addresult.gif" align="right" width="10" height="10"></a>';
elseif($db['score1']!='0' && $db['score2']!='0' && $db['inscribed']==$db['clan1'] && $db['einspruch']=='0') $action1 = '<a href="?site=cupactions&amp;action=confirmscore&amp;matchID='.$db['matchID'].'&amp;clanID='.$db['clan2'].'&amp;cupID='.$cupID.'&one=1"><img border="0" src="images/cup/success.png" width="8" height="10"></a> <a href="?site=cupactions&amp;action=protest&amp;matchID='.$db['matchID'].'&clanID='.$db['clan2'].'&cupID='.$cupID.'" onclick="return confirm(\'Are you sure there was something wrong with the match?\');"><img border="0" src="images/cup/error.png" width="8" height="10"></a>';		
    }

//match reporting for team cups

if(isleader($userID,$db['clan1'])) {
    if($db['einspruch']=='1' || $db['confirmscore']=='1') $action = '';
elseif($db['score1']=='0' && $db['score2']=='0' && $db['einspruch']=='0') $action = '<a href="?site=cupactions&amp;action=score&matchID='.$db['matchID'].'&amp;clan1='.$db['clan1'].'&amp;cupID='.$cupID.'"><img border="0" src="images/cup/icons/addresult.gif" align="right" width="10" height="10"></a>';  
elseif($db['score1']!='0' && $db['score2']!='0' && $db['inscribed']==$db['clan2'] && $db['einspruch']=='0') $action = '<a href="?site=cupactions&amp;action=confirmscore&amp;matchID='.$db['matchID'].'&amp;clanID='.$db['clan1'].'&amp;cupID='.$cupID.'"><img border="0" src="images/cup/success.png" width="9" height="10"></a> <a href="?site=cupactions&amp;action=protest&amp;matchID='.$db['matchID'].'&clanID='.$db['clan1'].'&cupID='.$cupID.'" onclick="return confirm(\'Are you sure there was something wrong with the match?\');"><img border="0" src="images/cup/error.png" width="8" height="10"></a>';
    
}elseif(isleader($userID,$db['clan2'])) {
    if($db['einspruch']=='1' || $db['confirmscore']=='1') $action = '';
elseif($db['score1']=='0' && $db['score2']=='0' && $db['einspruch']=='0') $action = '<a href="?site=cupactions&amp;action=score&matchID='.$db['matchID'].'&amp;clan1='.$db['clan2'].'&amp;cupID='.$cupID.'"><img border="0" src="images/cup/icons/addresult.gif" align="right" width="10" height="10"></a>';
elseif($db['score1']!='0' && $db['score2']!='0' && $db['inscribed']==$db['clan1'] && $db['einspruch']=='0') $action = '<a href="?site=cupactions&amp;action=confirmscore&amp;matchID='.$db['matchID'].'&amp;clanID='.$db['clan2'].'&amp;cupID='.$cupID.'"><img border="0" src="images/cup/success.png" width="9" height="10"></a> <a href="?site=cupactions&amp;action=protest&amp;matchID='.$db['matchID'].'&clanID='.$db['clan2'].'&cupID='.$cupID.'" onclick="return confirm(\'Are you sure there was something wrong with the match?\');"><img border="0" src="images/cup/error.png" width="8" height="10"></a>';    
    }

    if($db['einspruch'] == '1') $details = '<a name="protest" style="text-decoration:none; cursor:help" onmouseover="showWMTT(\'protest\')" onmouseout="hideWMTT()"><img src="images/cup/icons/opened_protest.gif" align="right"></a>';		
elseif($db['confirmscore']=='0' && $db['inscribed']=='0') $details = '<a name="awaiting" style="text-decoration:none; cursor:help" onmouseover="showWMTT(\'awaiting\')" onmouseout="hideWMTT()"><img src="images/cup/icons/pending.gif" align="right"></a>';
elseif($db['confirmscore']=='0' && $db['inscribed']!='0') $details = '<a name="awaiting" style="text-decoration:none; cursor:help" onmouseover="showWMTT(\'awaiting\')" onmouseout="hideWMTT()"><img src="images/cup/icons/pending.gif" align="right"></a>';
elseif($db['confirmscore']=='1' && $db['inscribed']=='0') $details = '<a name="admin_confirmed" style="text-decoration:none; cursor:help" onmouseover="showWMTT(\'admin_confirmed\')" onmouseout="hideWMTT()"><img src="images/icons/online.gif" align="right"></a>';
elseif($db['confirmscore']=='1' && $db['inscribed']!='0' && $db['einspruch']=='0')  $details = '<a name="confirmed" style="text-decoration:none; cursor:help" onmouseover="showWMTT(\'confirmed\')" onmouseout="hideWMTT()"><img src="images/icons/online.gif" align="right"></a>';	 		
					
					if(is1on1($cupID)) {
					
					if(!$db['clan1'] || !$db['clan2'])
						$detail[$i]='vs.';     
						                 
					elseif($userID == $db['clan1'])
						$detail[$i]='<a href="?site=cup_matches&amp;match='.$i.'&amp;cupID='.$cupID.'"><strong>vs. (details)</strong></a> '.$details.'<br>
						             <a href="?site=matches&amp;action=viewmatches&clanID='.$db['clan1'].'&amp;cupID='.$cupID.'&type=1#'.$i.'">'.$_language->module['match_report'].'</a>'.$action1.'';
						
					elseif($userID == $db['clan2'])
						$detail[$i]='<a href="?site=cup_matches&amp;match='.$i.'&amp;cupID='.$cupID.'"><strong>vs. (details)</strong></a> '.$details.'<br>
						             <a href="?site=matches&amp;action=viewmatches&clanID='.$db['clan2'].'&amp;cupID='.$cupID.'&type=1#'.$i.'">'.$_language->module['match_report'].'</a>'.$action1.'';
						
					else	
						$detail[$i]='<a href="?site=cup_matches&amp;match='.$i.'&amp;cupID='.$cupID.'"><strong>vs. ('.$_language->module['details'].')</strong></a>'.$details.'';
						
					}elseif($cupID) {
					
					if(!$db['clan1'] || !$db['clan2'])
						$detail[$i]='vs.';     
						                 
					elseif(memin($userID,$db['clan1']))
						$detail[$i]='<a href="?site=cup_matches&amp;match='.$i.'&amp;cupID='.$cupID.'"><strong>vs. ('.$_language->module['details'].')</strong></a> '.$details.'<br>
						             <a href="?site=matches&amp;action=viewmatches&clanID='.$db['clan1'].'&amp;cupID='.$cupID.'#'.$i.'">'.$_language->module['match_report'].'</a>'.$action.'';
						
					elseif(memin($userID,$db['clan2']))
						$detail[$i]='<a href="?site=cup_matches&amp;match='.$i.'&amp;cupID='.$cupID.'"><strong>vs. ('.$_language->module['details'].')</strong></a> '.$details.'<br>
						             <a href="?site=matches&amp;action=viewmatches&clanID='.$db['clan2'].'&amp;cupID='.$cupID.'#'.$i.'">'.$_language->module['match_report'].'</a>'.$action.'';
						
					else	
						$detail[$i]='<a href="?site=cup_matches&amp;match='.$i.'&amp;cupID='.$cupID.'"><strong>vs. ('.$_language->module['details'].')</strong></a>'.$details.'';
						
						}
						
					if(!$db['clan1']) 
						$clan[$i2-1] = 'n/a';
					elseif($db['clan1'] == 2147483647){ 
						$clan[$i2-1] = $_language->module['wildcard']; 
						$score1[$i]='0';
						$score2[$i]='1';
						$detail[$i]='vs.';
					}
					if(!$db['clan2']) 
						$clan[$i2] = 'n/a';
					elseif($db['clan2'] == 2147483647){ 
						$clan[$i2] = $_language->module['wildcard']; 
						$score2[$i]='0';
						$score1[$i]='1';
						$detail[$i]='vs.';
					}
				}				
			}else{
				$detail[$i]='vs.';
				$score1[$i]='&nbsp;';
				$score2[$i]='&nbsp;';
				if(!$db['clan1']) $clan[$i2] = 'n/a';
				elseif($db['clan1'] == 2147483647){ 
					$clan[$i2] = $_language->module['wildcard'];  
					$score1[$i]='0';
				}
				$i2++;
				if(!$db['clan2']) $clan[$i2] = 'n/a';
				elseif($db['clan2'] == 2147483647){ 
					$clan[$i2] = $_language->module['wildcard']; 
					$score2[$i]='0';
				}
			}
			$i2++;
		}
		
		if(mb_substr(basename($_SERVER['REQUEST_URI']),0,9) != "popup.php") 
		    echo '<img src="images/cup/icons/go.png"> <a href="popup.php?site=brackets&action=tree&cupID='.$cupID.'">'.$_language->module['expand'].'</a>';
		else
		    echo '<img src="images/cup/icons/go.png"> <a href="index.php?site=brackets&action=tree&cupID='.$cupID.'">Contract Brackets</a>';
		
		eval ("\$inctemp = \"".gettemplate($ds['maxclan'])."\";");
		echo $inctemp.($cpr ? ca_copyr() : die());
	}
}else echo $_language->module['maintenance_mode'];
?>