<?php
//include configuration and language
include ("config.php");
$_language->read_module('cup');
?>

<link href="cup.css" rel="stylesheet" type="text/css">

<style>
        span.link {
    	        position: relative;
        }

        span.link a span {
    	        display: none;
        }

        span.link a:hover {
    	        font-size: 99%;
    	        font-color: #000000;
        }

        span.link a:hover span { 
            display: block; 
    	    position: absolute; 
    	    margin-top: -100px; 
    	    margin-left: 90px; 
	    width: 165px; padding: 5px; 
    	    z-index: 100; 
    	    background: <?php echo $pagebg; ?>; 
    	    font: 12px "Arial", sans-serif;
    	    text-align: left; 
    	    text-decoration: none;   	
            border: 2px solid <?php echo $border; ?>;
            border-radius: 15px;

        }
	
	.show_hide_ts, .slidingDiv_dt  {
	        display:none;
        }
</style>

<script language="javascript">wmtt = null; document.onmousemove = updateWMTT; function updateWMTT(e) { x = (document.all) ? window.event.x + document.body.scrollLeft : e.pageX; y = (document.all) ? window.event.y + document.body.scrollTop  : e.pageY; if (wmtt != null) { wmtt.style.left=(x + 20) + "px"; wmtt.style.top=(y + 20) + "px"; } } function showWMTT(id) {	wmtt = document.getElementById(id);	wmtt.style.display = "block" } function hideWMTT() { wmtt.style.display = "none"; }</script>
<script type="text/javascript">
function SelectAll(id)
{
    document.getElementById(id).focus();
    document.getElementById(id).select();
}
</script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.js" type="text/javascript"></script>
<script type="text/javascript">

$(document).ready(function(){
	
        $(".slidingDiv_dt").show();
        $(".show_hide_dt").show();
	
	$('.show_hide_dt').click(function(){
	$(".slidingDiv_dt").slideToggle();
	});
	
        $(".slidingDiv_ts").hide();
        $(".show_hide_ts").show();
	
	$('.show_hide_ts').click(function(){
	$(".slidingDiv_ts").slideToggle();
	});

});

</script>

<div class="tooltip" id="unchecked" align="left"><?php echo $_language->module['unchecked']; ?></div>
<div class="tooltip" id="unchecked_notleader" align="left"><?php echo $_language->module['unchecked_notleader']; ?></div>
<div class="tooltip" id="inactive" align="left"><?php echo $_language->module['inactive']; ?></div>
<div class="tooltip" id="locked" align="left"><?php echo $_language->module['locked']; ?></div>
<div class="tooltip" id="inlog" align="left"><img src="images/cup/icons/warning.png"> <?php echo $_language->module['inlog']; ?></div>

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

if(!$cpr || !ca_copyr()) die();

//results by alphabet

$letter = $_GET['v'];

if(isset($_GET['v']) && in_array($_GET['v'],array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'))) {
   $alpha = "WHERE name LIKE '$letter%'";
}

//date and timezone

if($_GET['cupID']) {
   getcuptimezone();
}
elseif($_GET['laddID'] || $_GET['ladderID']) {
   getladtimezone();
}
else{
   gettimezone();
}

// match query type

match_query_type();

// settings and styles

$query = safe_query("SELECT * FROM ".PREFIX."cup_settings");	
$set = mysql_fetch_array($query); 

$settings=safe_query("SELECT * FROM ".PREFIX."settings");
$do=mysql_fetch_array($settings);
	
$styles=safe_query("SELECT * FROM ".PREFIX."styles");
$dt=mysql_fetch_array($styles);

// showing match & stats for team in cup

if($_GET['clanID'] && ($_GET['cupID'] || $_GET['laddID']) && $_GET['action']=='show'){
 
   $s_ms_name = ($_GET['cupID'] ? getcupname($_GET['cupID']) : getladname($_GET['laddID']));
    
   echo '<hr><center><font color="red"><strong>Showing matches/stats for: '.$s_ms_name.' cup</strong></font> (<a href="?site=clans&action=show&clanID='.$_GET['clanID'].'">Team-Home</a>)</center><hr>';
}

	  if($_GET['cupID']) { 
	      $sta_ext = "&cupID=$_GET[cupID]";
	  }
	  else{
	      $sta_ext = "";
	  }

//check group stages

    $cupID = mysql_escape_string($_GET['cupID']);
  
    $check_qualifyers = safe_query("SELECT qual, clanID FROM ".PREFIX."cup_clans WHERE groupID='$cupID' AND qual='1' AND ladID='0'");
    $is_qualifyers = mysql_num_rows($check_qualifyers); 
    
    while($iq = mysql_fetch_array($check_qualifyers)) {
       $qualified_ID[]=$iq['clanID']; 
    }
    
    $check_fcfs = safe_query("SELECT qual, clanID FROM ".PREFIX."cup_clans WHERE groupID='$cupID' AND qual='2' AND ladID='0'");
    $is_fcfs = mysql_num_rows($check_fcfs); 
    
    while($fs = mysql_fetch_array($check_fcfs)) {
       $fcfs_ID[]=$fs['clanID']; 
    }
    
    $c_qualifiers = count($qualified_ID);

    $gs=mysql_fetch_array(safe_query("SELECT * FROM ".PREFIX."cups WHERE ID='$cupID'"));
    
    if($gs['status']==1 && $gs['gs_start'] <= time() && $gs['gs_end'] >= time())
    { $is_groupstages = true; }else{ $is_groupstages = false; }
    
    if($gs['status']==1 && $gs['gs_start'] > time()) 
    { $is_groupreg = true; }else{ $is_groupreg = false; }

//check average

 $league = $_GET['cupID'] ? 'cup' : '';

 if($_GET['cupID'] && $_GET['clanID']) {

    $p_type = (is1on1($_GET['cupID']) ? 0 : 1);
    $user_p = user_cup_points($_GET['clanID'],$_GET['cupID'],$team=$p_type,$won=0,$lost=0,$type="confirmed_p",$league);
    $avg_points = round(average_cup_points($_GET['cupID']));
    $user_points=(empty($user_p) ? "+0" : "+$user_p");
				
    if(empty($user_p) || ($ds['tp']==$startupcredit && $ds['credit']==$startupcredit)) {
        $average = '<img src="images/cup/icons/na.png" width="16" height="16" align="right">';
    }
    elseif($user_p == round($avg_points)) {
        $average = '<img src="images/cup/icons/na.png" width="16" height="16" align="right"><img src="images/cup/icons/na.png" width="16" height="16" align="right">';
    }
    elseif($user_p <= round($avg_points/1.3)) {  
        $average = '<img src="images/cup/icons/nok_32.png" width="16" height="16" align="right"><img src="images/cup/icons/nok_32.png" width="16" height="16" align="right">';
    }
    elseif($user_p <= round($avg_points/1.1)) { 
        $average = '<img src="images/cup/icons/nok_32.png" width="16" height="16" align="right"><img id="myImage" src="images/cup/icons/nok_32.png" width="16" height="16" align="right">';
    }
    elseif($user_p >= round($avg_points*1.3)) {
        $average = '<img src="images/cup/icons/ok_32.png" width="16" height="16" align="right"><img src="images/cup/icons/ok_32.png" width="16" height="16" align="right">';
    }
    elseif($user_p >= round($avg_points*1.1)) {
        $average = '<img src="images/cup/icons/ok_32.png" width="16" height="16" align="right"><img id="myImage" src="images/cup/icons/ok_32.png" width="16" height="16" align="right">';
    }
    else{
        $average = '<img src="images/cup/icons/na.png" width="16" height="16" align="right"><img id="myImage" src="images/cup/icons/na.png" width="16" height="16" align="right">';
    }    
        $avg = '<tr>
                 <td><img src="images/cup/icons/ratio.png">'.$_language->module['average'].': '.$average.'</td>
                 <td align="center">'.$user_points.'</td>   
                </tr>';    
 }				
if(isset($_POST['addclan'])){

	if($loggedin) 
	{
		
	$sql_member = safe_query("SELECT * FROM ".PREFIX."cup_clan_members WHERE userID = $userID");	

	if(mysql_num_rows($sql_member)){
		$ds=mysql_fetch_array($sql_member);
		$sql_member2 = safe_query("SELECT * FROM ".PREFIX."cup_all_clans WHERE ID = ".$ds[clanID]."");	
	}
	
	$name = htmlspecialchars(mb_substr(trim($_POST['name']), 0, 30));
	$bracket = htmlspecialchars(mb_substr(trim($_POST['short']), 0, 30));
	$tag = htmlspecialchars(mb_substr(trim($_POST['clantag']), 0, 30));
	
        $checkname = safe_query("SELECT * FROM ".PREFIX."cup_all_clans WHERE name='$name'"); 
        $checkbracket = safe_query("SELECT * FROM ".PREFIX."cup_all_clans WHERE short='$bracket'");
        $checktag = safe_query("SELECT * FROM ".PREFIX."cup_all_clans WHERE clantag='$tag'");
    
        if(mysql_num_rows($checkname)) die('<div '.$error_box.'>'.$_language->module['name_exists'].'</div>');
        if(mysql_num_rows($checkbracket)) die('<div '.$error_box.'>'.$_language->module['bracket_name_exists'].'</div>');
        if(mysql_num_rows($checktag)) die('<div '.$error_box.'>'.$_language->module['tag_exists'].'</div>');
	
	$error_array = array();
	if(empty($_POST['name'])) $error_array[] = $_language->module['forgot_name'];
        if(empty($_POST['flag'])) $error_array[] = $_language->module['forgot_country'];
	if(empty($_POST['short'])) $error_array[] = $_language->module['forgot_short'];
	if(empty($_POST['clantag'])) $error_array[] = $_language->module['forgot_clantag'];
	if(empty($_POST['password'])) $error_array[] = $_language->module['forgot_pw'];   
	if(count($error_array)) 
	{
		$fehler=implode('<br />&#8226; ', $error_array);
		$showerror = '<div class="errorbox">
		  <b>'.$_language->module['errors_there'].':</b><br /><br />
		  &#8226; '.$fehler.'
		</div>';
		echo $showerror.'<br /><input type="button" class="button" onClick="javascript:history.back()" value="'.$_language->module['back'].'">';

	}
	else{
	
		safe_query("INSERT INTO ".PREFIX."cup_all_clans ( name, country, short, clantag, clanhp, clanlogo, leader, password, server, port, reg, status) VALUES ( '".$_POST['name']."', '".$_POST['flag']."', '".$_POST['short']."', '".$_POST['clantag']."', '".$_POST['clanhp']."', '".$_POST['clanlogo']."', '".$userID."', '".md5($_POST['password'])."', '".$_POST['server']."', '".$_POST['port']."', '".time()."', '1')");
		$clanID = mysql_insert_id();
		safe_query("INSERT INTO ".PREFIX."cup_clan_members (clanID, userID, function, reg) VALUES ('".$clanID."', '".$userID."', 'Leader', '".time()."')");
		redirect('?site=clans&action=show&clanID='.$clanID.'', '<div '.$error_box.'><b>'.$_language->module['team_created'].'</b> <img src="images/cup/success.png" width="16" height="16"></div>', 2);
	}
    }
    else{
                echo $_language->module['logged_in'];
    }    
}elseif(isset($_POST['joinclan'])){
	
	if($loggedin) 
	{
			
	        $teamlimitjoin = $set['cupteamjoin']; 	
		
	        $sql_member = safe_query("SELECT * FROM ".PREFIX."cup_clan_members WHERE userID = '$userID'");
		$sql_clan_p = safe_query("SELECT * FROM ".PREFIX."cup_all_clans WHERE password = '".md5($_POST['password'])."' AND ID = '".$_POST['clan']."'");
		$sql_member2= safe_query("SELECT * FROM ".PREFIX."cup_clan_members WHERE userID = '$userID' AND clanID = '".$_POST['clan']."'");	
		
	        $error_array = array();
	
	        if(mysql_num_rows($sql_member) >= $teamlimitjoin){
	              $error_array[] = 'You have reached the Maximum teams joined limit. ('.$teamlimitjoin.')';
	        } 
		
	        if(mysql_num_rows($sql_member2)) {
     	            $error_array[] = 'You are already in this team.'; 
                }	    
		
	        if(!empty($_POST['password']) && !mysql_num_rows($sql_clan_p )) {
		    $error_array[] = 'You have entered the wrong password.';
		}
	        
	        if(!iscupadmin($userID) && empty($_POST['password'])) {
		    $error_array[] = 'You forgot to enter the password.';
		}
		
	        if(!$_POST['clan']) {
		    $error_array[] = 'Team does not exist or has been removed.';
		}
      
	        if(count($error_array)) {
		        $fehler=implode('<br />&#8226; ', $error_array);
		        $showerror = '<div class="errorbox">
		          <b>'.$_language->module['errors_there'].':</b><br /><br />
		          &#8226; '.$fehler.'
		        </div>';
		        echo $showerror.'<br /><input type="button" class="button" onClick="javascript:history.back()" value="'.$_language->module['back'].'">';
	        }
	        else{
		        safe_query("INSERT INTO ".PREFIX."cup_clan_members (clanID, userID, function, reg) VALUES ('".$_POST['clan']."', '".$userID."', '".(iscupadmin($userID) ? 'Leader' : 'Member')."', '".time()."')");
		        redirect('?site=clans&action=show&clanID='.$_POST['clan'].'', '<div '.$error_box.'><b>'.$_language->module['team_joined'].'</b> <img src="images/cup/success.png" width="16" height="16"></div>', 2);
	        }
	
	}
	else{
	         echo $_language->module['logged_in'];
	}
}elseif(isset($_POST['editpwd'])){

	if($loggedin)
        {
	
	        if(!isleader($userID,$_POST['clanID'])) { 
		      $fehler[] = 'You must be a leader.';
		}
						
	        if(empty($_POST['password'])) { 
		      $fehler[] = 'You forgot to enter the password.'; 
                }
		      
	        if(count($fehler)) {
		
		        $wort = (count($fehler) == '1' ? 'error' : 'errors');
  
		        echo '<table width="100%" border="0" cellspacing="'.$cellspacing.'" cellpadding="'.$cellpadding.'" bgcolor="'.$border.'">
				<tr bgcolor="'.$bghead.'"> 
					<td class="title">&nbsp; &#8226; '.$_language->module['edit_team'].'</td>
				</tr>
				<tr><td bgcolor="'.$pagebg.'"></td></tr>
				<tr bgcolor="'.$bg1.'">
					<td>
						<table width="100%" border="0" cellspacing="'.$cellspacing.'" cellpadding="'.$cellpadding.'">
							<tr>
								<td><b><div '.$error_box.'>Oppz! <img src="images/smileys/bash.gif"> '.count($fehler).' '.$wort.' occured!</div></b>
								<ul><li>'.implode('<li>',$fehler).'</ul><br />
								<center>[ <b><a href="javascript:history.back()">'.$_language->module['back'].'</a></b> ]</center><br /></td>
							</tr>
						</table>
					</td>
				</tr>
		              </table>';
	        }
		else{
		        safe_query("UPDATE ".PREFIX."cup_all_clans SET password='".md5($_POST['password'])."' WHERE ID='".$_POST['clanID']."'");
		        redirect('?site=myteams', '<center><div '.$error_box.'><B>Team successfully updated!</b> <img src="images/cup/success.png"><br>You are now being redirected to your teams<img src="images/cup/period'.$period_dot.'_ani.gif"><div></center>', 2);
	        }
		
	}
	else{
	        echo $_language->module['logged_in'];
	}

}elseif(isset($_POST['editclan'])){
	if($loggedin) 
	{
		
	        $error_array = array();	
		
	        if(!isleader($userID,$_POST['clanID'])) {
		      $error_array[] = $_language->module['not_leader'];
		}
	        if(empty($_POST['name'])) {
		      $error_array[] = $_language->module['forgot_name'];
		}
                if(empty($_POST['flag'])) {
		      $error_array[] = $_language->module['forgot_country'];
		}
	        if(empty($_POST['short'])) {
		      $error_array[] = $_language->module['forgot_short'];
		}
                if(empty($_POST['clantag'])) {
		      $error_array[] = $_language->module['forgot_clantag'];
		}
		
	        if(count($error_array)) 
	        {
		        $fehler=implode('<br />&#8226; ', $error_array);
		        $showerror = '<div class="errorbox">
		          <b>'.$_language->module['errors_there'].':</b><br /><br />
		          &#8226; '.$fehler.'
		        </div>';
		        echo $showerror.'<br /><input type="button" class="button" onClick="javascript:history.back()" value="'.$_language->module['back'].'">';
	        }
		else{
		        safe_query("UPDATE ".PREFIX."cup_all_clans SET name='".$_POST['name']."', country='".$_POST['flag']."', short='".$_POST['short']."', clantag='".$_POST['clantag']."', clanhp='".$_POST['clanhp']."', clanlogo='".$_POST['clanlogo']."', leader='".$userID."', server='".$_POST['server']."', port='".$_POST['port']."' WHERE ID='".$_POST['clanID']."'");
		        redirect('?site=clans&action=show&clanID='.$_POST['clanID'].'', '<div '.$error_box.'>'.$_language->module['team_edited'].'</div>', 2);
	        }
	
	}
	else{
	        echo $_language->module['logged_in'];
	}
}

if(isset($_GET['action']) && $_GET['action'] == 'clanadd'){

	if($loggedin)
        {	
	
	        $query = safe_query("SELECT * FROM ".PREFIX."cup_all_clans WHERE leader='$userID'");
	        
	        $teamlimitadd = $set['cupteamadd'];
		$teamlimitjoin = $set['cupteamjoin'];
                $value = $set['cupteamjoin']-$set['cupteamadd'];
    
 
	        if(mysql_num_rows($query) >= $teamlimitadd) {
		        echo 'You have reached the Maximum teams created limit. ('.$teamlimitadd.')';
		}
	        else{
		        $clan_title = $_language->module['add_team'];
		        $flag = '[flag]'.$db['country'].'[/flag]';
		        $country = flags($flag);
		        $country = str_replace("<img","<img id='county'",$country);
		        $countries = str_replace(" selected=\"selected\"", "", $countries);
		        $countries = str_replace('value="'.$db['country'].'"', 'value="'.$db['country'].'" selected="selected"', $countries);
		        $typ = 'addclan';
		        $button = $_language->module['submit'];
		        $homepage = '';
		        $logo = '';
		        $delteam = '';
		        $leadmember = '';
			$dbport = (empty($db[port]) ? ' ' : $db[port]);
					
			$show_note = 'You can create <font color="red"><b>'.$teamlimitadd.'</b></font> team'.($teamlimitadd==1 ? '' : 's').' 
			and be in <font color="red"><b>'.$value.'</b></font> additional team'.($value==1 ? '' : 's').'. 
			('.$teamlimitjoin.' max)';

		        eval ("\$inctemp = \"".gettemplate("clans_add")."\";");
		        echo $inctemp; echo base64_decode('');
	        }

	}
	else{
	        echo $_language->module['logged_in'];
	}

}elseif(isset($_GET['action']) && $_GET['action'] == 'invite'){

	if($loggedin)
        {

                $clanID = mysql_real_escape_string($_GET['clanID']);
                $cupID = mysql_real_escape_string($_GET['cupID']);
    
                $db=mysql_fetch_array(safe_query("SELECT * FROM ".PREFIX."cup_all_clans WHERE ID='$clanID'"));
		$dc=mysql_fetch_array(safe_query("SELECT * FROM ".PREFIX."cups WHERE ID='$cupID'"));
		
                $clanID = $db['ID']; 
                $clantag = $db['clantag']; 
                $clanname = $db['name']; 
                $password = $db['password']; 
		
		$members=safe_query("SELECT userID FROM ".PREFIX."cup_clan_members WHERE clanID = '$clanID' AND userID = '$userID'");		
		  while($dv =mysql_fetch_array($members)) {
                    $member.=getnickname($dv['userID']); 
                }
                     
	        if(!$clanID) {
	            die('<div '.$error_box.'>'.$_language->module['invalid_clanid'].'</div>');
	        }
		elseif(!isleader($userID,$_GET['clanID'])) {
	            die('<div '.$error_box.'>'.$_language->module['not_leader'].'</div>');
	        }
		elseif(islocked($clanID)) {
	            die('<div '.$error_box.'>'.$_language->module['team_locked'].'</div>');
                }
		elseif(!$db['status']) { 
                    die('<div '.$error_box.'>'.$_language->module['register_inactive'].'</div>');
                }
		elseif($cupID) { 
		
                    echo $_language->module['cup_invite_info']; 
        
 	            $begin = date('d.m.Y \a\t H:i', $dc['start']).'';
                    $alphacupname = getalphacupname($dc['ID']);
                    $cupname =  $dc['name'];
                    $game = $dc['game']; 
                    $type = $dc['typ'];                               
                    $invited = $member;
                    $joinsub = 'Join the cup at http://'.getinput($do['hpurl']).'?site=clans&action=clanjoin&clanID='.$clanID.'';
                    $joinmess = 'You have been invited to join the '.$type.' '.$cupname.' cup for '.$game.' by '.$member.' on '.$begin.' '.$gmt.' @ '.getinput($dt['title']).' and you can do so by following this link: http://'.getinput($do['hpurl']).'/?site=quicknavi&amp;cup='.$alphacupname.' -- or follow this link: http://'.getinput($do['hpurl']).'/?site=cups&action=details&cupID='.$_GET['cupID'].' to view cup details.';
                    $subject = 'Join the '.$cupname.' Cup'; 
               
 	            echo '<a href="?site=cups&action=details&cupID='.$_GET['cupID'].'">'.$_language->module['view_cup_details'].'</a>'; 
             
                }
		else{    
		
                        $invited = $member;
                        $joinsub = $_language->module['join_my_team'].' http://'.getinput($do['hpurl']).'?site=clans&action=clanjoin&clanID='.$clanID;
                        $joinmess = $_language->module['invited_to_join'].$clanname.' ('.$clantag.') by '.$member.' on '.getinput($dt['title']).' and you can do so by following this link: http://'.getinput($do['hpurl']).'/?site=clans&action=clanjoin&clanID='.$clanID.'&password='.$password.' -- or follow this link: http://'.getinput($do['hpurl']).'/?site=clans&action=show&clanID='.$clanID.' to view clan details.';
                        $subject = 'Join Cup Team '.$clantag;
                }
             
		eval ("\$inctemp_invite = \"".gettemplate("invite")."\";");
		echo $inctemp_invite.'<a href="?site=clans&action=show&clanID='.$clanID.'">'.$_language->module['view_manage_team'].'</a>';                
	
	}
	else{
	        echo $_language->module['logged_in'];
	}
	
}elseif(isset($_GET['action']) && $_GET['action'] == 'delclan'){

        if($loggedin)
	{

                $clanID = mysql_real_escape_string($_GET['clanID']);
                $cupID = mysql_real_escape_string($_GET['cupID']);
	
	
	        $query = safe_query("SELECT * FROM ".PREFIX."cup_clans WHERE clanID='$clanID' && cupID='$cupID' && 1on1='1'");
    
	        $ergebnis3 = safe_query("SELECT * FROM ".PREFIX."cup_clans WHERE clanID='".$_GET['clanID']."' && cupID='".$_GET['cupID']."' && 1on1='1'");
	        $notin=mysql_num_rows($ergebnis3);
	
	        $ergebnis3 = safe_query("SELECT * FROM ".PREFIX."cup_clans WHERE clanID='".$_GET['clanID']."' && cupID='".$_GET['cupID']."' && 1on1='1' && checkin='1'");
	        $notchecked=mysql_num_rows($ergebnis3);
	
                if(!$userID) echo $_language->module['logged_in'];
		
        if($_GET['one']==1) {
    

	        $getstatus=safe_query("SELECT status FROM ".PREFIX."cups WHERE ID='".$_GET['cupID']."'");
	        $st=mysql_fetch_array($getstatus); 
		
		switch($ds['status']) {
		       case 1: $status = '<font color="'.$wincolor.'">Signup phase</font>';
		       break;
		       case 2: $status = '<font color="'.$drawcolor.'">Started</font';
		       break;
		       case 3: $status = '<font color="'.$loosecolor.'">Closed</font>';
		       break;	
		}
	
    
		if(!$notin) {            
                   echo $_language->module['you_not_in_cup'];
                }
		elseif($notchecked){		
                   echo $_language->module['already_checked'];
                }
		elseif($st['status']=='2' || $st['status']=='3') {
                   echo '<div '.$error_box.'><b>'.$_language->module['not_signup_phase'].'</b><img src="images/cup/error.png" width="16" height="16"><br>Cup: '.$status.', '.$_language->module['cannot_leave'].'.</div>';	
                }
		else{
	
		        safe_query("DELETE FROM ".PREFIX."cup_clans WHERE clanID='".$userID."' && cupID='".$_GET['cupID']."' && 1on1='1'");
                        redirect('?site=cups', $_language->module['cup_leaved'], 2);
	        }
		
	}
	else{

		
		    $checkmatch = safe_query("SELECT * FROM ".PREFIX."cup_matches WHERE (clan1='".$_GET['clanID']."' || clan2='".$_GET['clanID']."') && 1on1='0'");
		
   	            if(!isfounder($userID,$_GET['clanID'])) {
   	                  echo $_language->module['not_creator'];
   	            }
		    elseif(islocked($clanID)) {
   	                  echo $_language->module['team_locked_cup']; 
   	            }
		    elseif(isdisabled($clanID)) {
   	                  echo '<font color="red"><strong>This team is disabled, please contact an admin to reinstate your team.</strong></font>'; 
   	            }
		    elseif(mysql_num_rows($checkmatch)) {
   	                  echo '<div '.$error_box.'><b>'.$_language->module['cannot_delete'].' </b> <img src="images/cup/error.png" width="16" height="16"></div>';
		    }
		    else{		 
			  safe_query("DELETE FROM ".PREFIX."cup_all_clans WHERE ID='".$_GET['clanID']."'");
			  safe_query("DELETE FROM ".PREFIX."cup_clans WHERE clanID='".$_GET['clanID']."' && 1on1='0'");
			  safe_query("DELETE FROM ".PREFIX."cup_clan_members WHERE clanID='".$_GET['clanID']."'");
			  safe_query("DELETE FROM ".PREFIX."cup_clan_lineup WHERE clanID='".$_GET['clanID']."'");
			  safe_query("DELETE FROM ".PREFIX."cup_challenges WHERE (challenger='".$_GET['clanID']."' || challenged='".$_GET['clanID']."') && 1on1='0'");
			  redirect('?site=myteams', $_language->module['team_deleted'], 2);
		    }
            }
	}
	else{
	        echo $_language->module['logged_in'];
	} 
  
}elseif(isset($_GET['action']) && $_GET['action'] == 'leavecup'){

   $clanID = mysql_real_escape_string($_GET['clanID']);
   $cupID = mysql_real_escape_string($_GET['cupID']);

	$getstatus=safe_query("SELECT status FROM ".PREFIX."cups WHERE ID='".$_GET['cupID']."'");
	$st=mysql_fetch_array($getstatus);
	
	if($st['status']==1) {
	   $status = '<font color="#1cac00">Signup phase</font>';
	}
	elseif($st['status']==2) {
	   $status = '<font color="#FF6600">Started</font>';
	}   
	elseif($st['status']==3) {
	   $status = '<font color="#DD0000">Closed</font>';
	}

	if(!$userID) {
	    echo $_language->module['logged_in'];
	}           
        elseif(!$clanID) {
	    echo $_language->module['invalid_clanid'];
	}	
 	elseif(!isleader($userID,$_GET['clanID'])) {
	    echo $_language->module['not_leader'];
	}
        elseif(iscupparticipant($userID,$cupID,$checkin=1)) {
	    echo "You are already checked.";
	}
	elseif($st['status']=='2' || $st['status']=='3') {
	    echo '<div '.$error_box.'><b>'.$_language->module['not_signup_phase'].' </b><img src="images/cup/error.png" width="16" height="16"><br>Cup: '.$status.', '.$_language->module['cannot_leave'].'.</div>';
	}
	elseif((!is1on1($cupID) && isleader($userID,$_GET['clanID'])) || is1on1($cupID) && $userID==$clanID) {

		safe_query("DELETE FROM ".PREFIX."cup_clans WHERE clanID='".$_GET['clanID']."' && cupID='".$_GET['cupID']."' && 1on1='0'");
		safe_query("UPDATE ".PREFIX."cup_clan_members SET cupID = '0' WHERE clanID='".$_GET['clanID']."'");
		redirect('?site=cups', $_language->module['cup_leaved'], 2);

	}
	else{
		echo "Error: Unable to leave cup, please contact admin.";
        }
}elseif(isset($_GET['action']) && $_GET['action'] == 'delmember') {
	if(isleader($userID,$_GET['clanID'])){
		safe_query("DELETE FROM ".PREFIX."cup_clan_members WHERE userID = '".$_GET['memberID']."' && clanID = '".$_GET['clanID']."'");	
		redirect('?site=clans&action=clanedit&clanID='.$_GET['clanID'], $_language->module['member_deleted'], 2);	
}
}elseif(isset($_GET['action']) && $_GET['action'] == 'leadmember') {
	if(isfounder($userID,$_GET['clanID']) && mysql_num_rows(safe_query("SELECT ID FROM ".PREFIX."cup_all_clans WHERE leader = '".$userID."' AND ID = '".$_GET['clanID']."'"))){
		safe_query("UPDATE ".PREFIX."cup_clan_members SET function = 'Leader' WHERE userID = '".$_GET['memberID']."' && clanID = '".$_GET['clanID']."'");	
		redirect('?site=clans&action=clanedit&clanID='.$_GET['clanID'], $_language->module['member_edited'], 2);		
	}

}elseif(isset($_GET['action']) && $_GET['action'] == 'demotemember') {
	if(isfounder($userID,$_GET['clanID']) && mysql_num_rows(safe_query("SELECT ID FROM ".PREFIX."cup_all_clans WHERE leader = '".$userID."' AND ID = '".$_GET['clanID']."'"))){
		safe_query("UPDATE ".PREFIX."cup_clan_members SET function = 'Member' WHERE userID = '".$_GET['memberID']."' && clanID = '".$_GET['clanID']."'");	
		redirect('?site=clans&action=clanedit&clanID='.$_GET['clanID'], $_language->module['member_demoted'], 2);		
	}

}elseif(isset($_GET['action']) && $_GET['action'] == 'ownmember') {
$clanID = $_GET['clanID'];

    if(islocked($clanID)) echo $_language->module['team_locked_cup'];
	elseif(isfounder($userID,$clanID) && mysql_num_rows(safe_query("SELECT ID FROM ".PREFIX."cup_all_clans WHERE leader = '".$userID."' AND ID = '".$_GET['clanID']."'"))){
		safe_query("UPDATE ".PREFIX."cup_all_clans SET leader = '".$_GET['memberID']."' WHERE ID = '".$_GET['clanID']."'");	
		redirect('?site=clans&action=clanedit&clanID='.$_GET['clanID'], $_language->module['member_edited'], 2);		
	}
	
}elseif(isset($_GET['action']) && $_GET['action'] == 'chat') {
$clanID = $_GET['clanID'];

    if(islocked($clanID)) echo $_language->module['team_locked_cup'];
	elseif(isleader($userID,$clanID) && safe_query("SELECT chat FROM ".PREFIX."cup_all_clans WHERE ID='".$_GET['clanID']."'")){
		safe_query("UPDATE ".PREFIX."cup_all_clans SET chat='".$_GET['chataccess']."' WHERE ID = '".$_GET['clanID']."'");	
		redirect('?site=clans&action=clanedit&clanID='.$_GET['clanID'], '<center><b>Chat accessibility status successfully changed<img src="images/cup/period'.$period_dot.'_ani.gif"></center>', 1);		
	}	
	
}elseif(isset($_GET['action']) && $_GET['action'] == 'comments') {
$clanID = $_GET['clanID'];

    if(islocked($clanID)) echo $_language->module['team_locked_cup'];
	elseif(isleader($userID,$clanID) && safe_query("SELECT comment FROM ".PREFIX."cup_all_clans WHERE ID='".$_GET['clanID']."'")){
		safe_query("UPDATE ".PREFIX."cup_all_clans SET comment='".$_GET['access']."' WHERE ID = '".$_GET['clanID']."'");	
		redirect('?site=clans&action=clanedit&clanID='.$_GET['clanID'], '<center><b>Comments accessibility status successfully changed<img src="images/cup/period'.$period_dot.'_ani.gif"></center>', 1);		
	}
	
}elseif(isset($_GET['action']) && $_GET['action'] == 'status') {
$teamstatus = $_GET['status'];
$clanID = $_GET['clanID'];
    
    if(islocked($clanID)) echo $_language->module['team_locked_cup'];
	elseif(isfounder($userID,$_GET['clanID'])){
		safe_query("UPDATE ".PREFIX."cup_all_clans SET `status`='".$teamstatus."' WHERE ID = '".$_GET['clanID']."'");	
		redirect('?site=clans&action=clanedit&clanID='.$_GET['clanID'], '<center><img src="images/cup/period'.$period_dot.'_ani.gif"></center>', 2);		
	}
	
}elseif(isset($_GET['action']) && $_GET['action'] == 'clanleave'){

	$clanID = mysql_real_escape_string($_GET['clanID']);
	
	$ergebnis2 = safe_query("SELECT * FROM ".PREFIX."cup_clan_members WHERE userID = '$userID'");
	$notinteam = safe_query("SELECT * FROM ".PREFIX."cup_clan_members WHERE userID = '$userID' AND clanID = '".$clanID."'");

	if(!$userID) {
	    echo $_language->module['logged_in'];
	}
	elseif(mysql_num_rows($ergebnis2)==0) {
	    echo $_language->module['no_team'];
	}
	elseif(!mysql_num_rows($notinteam)) {
	    echo $_language->module['not_in_team'];
	}
        elseif(islocked($clanID) || isdisabled($clanID)) { 
	    echo '<font color="red"><strong>This team is locked or disabled. Please contact an admin.</strong></font>';
	}
        elseif(isfounder($userID,$clanID)) { 
	    echo '<div '.$error_box.'><B>'.$_language->module['not_owner'].'</b> <img src="images/cup/error.png" width="16" height="16"><br>'.$_language->module['to_leave'].' <a href="?site=clans&action=clanedit&clanID='.$clanID.'">'.$_language->module['here'].'</a>.</div>';
	}	
	else{
		safe_query("DELETE FROM ".PREFIX."cup_clan_members WHERE userID='$userID' AND clanID = '".$clanID."'");
		redirect('?site=clans&action=show&clanID='.$clanID, $_language->module['team_leaved'], 2);
	}
	
}elseif(isset($_GET['action']) && $_GET['action'] == 'clanregister'){
	$clanID=isset($_GET['clanID']) ? $_GET['clanID'] : 0;
	$cupID=mysql_real_escape_string($_GET['cupID']);	

  //if not logged in

	if(!$userID) echo $_language->module['logged_in'];
	
  //if 1on1 cup	

	elseif(is1on1($cupID)){
		$ergebnis = safe_query("SELECT * FROM ".PREFIX."cups WHERE ID = '$cupID ' ");
		$ds=mysql_fetch_array($ergebnis);

		$ergebnis2 = safe_query("SELECT count(*) as anzahl FROM ".PREFIX."cup_clans WHERE cupID='".$cupID."' && checkin='1'");
		$dv=mysql_fetch_array($ergebnis2);

		$ergebnis3 = safe_query("SELECT * FROM ".PREFIX."cup_clans WHERE clanID= '".$userID."' && 1on1='1' && cupID= '".$cupID."'");
		$clannum=mysql_num_rows($ergebnis3);
		
  //check gameaccount
    	
		$checkgameacc=mysql_num_rows(safe_query("SELECT * FROM ".PREFIX."user_gameacc WHERE type = '".$ds['gameaccID']."' && userID = '$userID' && log='0'"));
		$gameacc_sql2 = safe_query("SELECT type FROM ".PREFIX."gameacc WHERE gameaccID = '".$ds['gameaccID']."'");
	    $dr=mysql_fetch_array($gameacc_sql2);
	    
  //check max limits for cups

		if($ds['maxclan'] == 80 || $ds['maxclan']== 8)
			$max = 8;
		elseif($ds['maxclan'] == 160 || $ds['maxclan']== 16)
			$max = 16;
		elseif($ds['maxclan'] == 320 || $ds['maxclan']== 32)
			$max = 32;
		elseif($ds['maxclan'] == 640 || $ds['maxclan']== 64)
			$max = 64;		
			
  //1on1 backup register validations

  		if($is_groupreg) {
  		    echo '<div '.$error_box.'>To be an eligible contestant in this tournament you must first signup and qualify to the <a href="?site=groups&cupID='.$cupID.'">group stages</a>.</div>';
		    redirect('?site=groups&cupID='.$cupID, '', 3);
  	       }elseif($is_groupstages)
  		    echo '<div '.$error_box.'>Group stages is currently running, to be an eligible contestant in this tournament you must first qualify to the <a href="?site=groups&cupID='.$cupID.'">group stages</a>.</div>';	
                elseif($is_qualifyers && $c_qualifiers > $dv['anzahl'] && in_array($userID,$fcfs_ID))
                    echo '<div '.$error_box.'><b>Sorry, you must wait for qualified participants first.</b></div>';
                elseif($is_qualifyers && !in_array($userID,$qualified_ID) && !in_array($userID,$fcfs_ID))
                    echo '<div '.$error_box.'><b>Sorry, you are not a qualified participant.</b></div>';
		elseif($clannum)
		    echo $_language->module['already_in_cup_1on1'];
		elseif(userislocked($userID))
		    echo '<div '.$error_box.'><b>'.$_language->module['profile_locked'].'</b> <img src="images/cup/error.png" width="16" height="16"><br>'.$_language->module['view_pp'].' <a href="?site=profile&userID='.$userID.'">'.$_language->module['your_profile'].'</a>.</div>';
		elseif($ds['clanmembers']==1 && !isclanmember($userID))
		    echo '<div '.$error_box.'><b>'.$_language->module['cm_only'].'</b> <img src="images/cup/error.png" width="16" height="16"></div>';
		elseif(($ds['ratio_low'] || $ds['ratio_high']) AND userscoreratio($userID) < $ds['ratio_low'] || userscoreratio($userID) > $ds['ratio_high'])
		    echo '<div '.$error_box.'><b>'.$_language->module['ratio_no'].'</b> <img src="images/cup/error.png" width="16" height="16"><br>'.$_language->module['ratio_range'].' <font color="red"><b>'.$ds['ratio_low'].'%</b></font> to <font color="red"><b>'.$ds['ratio_high'].'%</b></font> '.$_language->module['your_ratio'].' <font color="red"><b>'.userscoreratio($userID).'%</b></font></div>';
		elseif($ds['status'] == 2)
			echo $_language->module['already_cup_started'];
		elseif($ds['status'] == 3)
			echo $_language->module['already_cup_finished'];
		elseif($ds['gameacclimit'] && !$checkgameacc)
			echo '<center><div '.$error_box.'>'.$_language->module['must_register'].' <b>'.$dr['type'].'</b> '.$_language->module['gameaccount'].'. <img border="0" src="images/cup/error.png" width="16" height="16"> (<a href="?site=myprofile&action=gameaccounts">'.$_language->module['this_link'].'</a>)</div></center>';
		elseif($dv['anzahl'] >= $max)
			echo $_language->module['too_much_player'];
			
  //to be unchecked as backup
		else{
			safe_query("INSERT INTO ".PREFIX."cup_clans (cupID, clanID, 1on1) VALUES ('$cupID', '$userID', '1')");
			redirect('?site=cups&action=details&cupID='.$cupID, $_language->module['sucess_cup_entered'], 2);
		}
		
  //if team cups
  		
	}else{
		$ergebnis = safe_query("SELECT * FROM ".PREFIX."cups WHERE ID = '$cupID ' ");
		$ds=mysql_fetch_array($ergebnis);

		$ergebnis2 = safe_query("SELECT count(*) as anzahl FROM ".PREFIX."cup_clans WHERE cupID='".$cupID."' && checkin='1'");
		$dv=mysql_fetch_array($ergebnis2);

		$ergebnis3 = safe_query("SELECT * FROM ".PREFIX."cup_clans WHERE clanID= '".$clanID."' && cupID= '".$cupID."'");
		$clannum=mysql_num_rows($ergebnis3);
		
  //check max limits for cups

		if($ds['maxclan'] == 80 || $ds['maxclan']== 8)
			$max=8;
		elseif($ds['maxclan'] == 160 || $ds['maxclan']== 16)
			$max=16;
		elseif($ds['maxclan'] == 320 || $ds['maxclan']== 32)
			$max=32;
		elseif($ds['maxclan'] == 640 || $ds['maxclan']== 64)
			$max = 64;

  //Enough members in team?

        $ergebnis = safe_query("SELECT typ FROM ".PREFIX."cups WHERE ID = '".$cupID."'");
		$dl=mysql_fetch_array($ergebnis);	
		$min_anz = strstr($dl['typ'], 'on', true);
		
		$ergebnis2 = safe_query("SELECT clanID FROM ".PREFIX."cup_clan_members WHERE clanID = '".$clanID."'");
		$anz_mem = mysql_num_rows($ergebnis2);	

        $sql_members = safe_query("SELECT * FROM ".PREFIX."cup_clan_members WHERE clanID = '".$clanID."'");	
		$members = mysql_num_rows($sql_members);
		
  //takeaway members in count

		$ergebnis = safe_query("SELECT cupgalimit FROM ".PREFIX."cups WHERE ID='$cupID'");	
		while($db = mysql_fetch_array($ergebnis)) {

        $membersin = $db['cupgalimit']; 
        if($db['cupgalimit'] <= 0)
        $membersin='';

        elseif($db['cupgalimit'] >= 1)
        $membersin = $db['cupgalimit']; }
        
        $needed = $ds['typ']-$members-$membersin;   
        
  //check gameaccounts

        $checkgameacc = mysql_num_rows(safe_query("SELECT * FROM ".PREFIX."user_gameacc WHERE type = '".$db['gameaccID']."' && userID = '$userID' && log='0'"));
	    $ergebnis2 = safe_query("SELECT status, password FROM ".PREFIX."cup_all_clans WHERE ID = '".$clanID."'");		
		$dl=mysql_fetch_array($ergebnis2);
		$password = $dl['password'];
		
		$gameacc_sql2 = safe_query("SELECT type FROM ".PREFIX."gameacc WHERE gameaccID = '".$ds['gameaccID']."'");
		$dr=mysql_fetch_array($gameacc_sql2);

  //can not register team to cup if your team is already in cup
 
    $myteams=safe_query("SELECT clanID, userID FROM ".PREFIX."cup_clan_members WHERE userID='$userID'");
    while($tt=mysql_fetch_array($myteams)) { 

    $cupclans=safe_query("SELECT * FROM ".PREFIX."cup_clans WHERE cupID='$cupID' AND clanID='".$tt['clanID']."'");
    while($ee=mysql_fetch_array($cupclans))  {

  //team backup register validations

		if(count($ee)>1);
			echo '<div '.$error_box.'><img src="images/cup/error.png" width="16" height="16"> <b>You are already in this cup!<br>You can not register more teams or the same one in one cup.</b></div>'; exit; }   
	   }if(!$clanID)
			echo $_language->module['invalid_clanid'];
  		elseif($is_groupreg) {
  		        echo '<div '.$error_box.'>To be an eligible contestant in this tournament you must first signup and qualify to the <a href="?site=groups&cupID='.$cupID.'">group stages</a>.</div>';
		        redirect('?site=groups&cupID='.$cupID, '', 3);
  	       }elseif($is_groupstages)
  		        echo '<div '.$error_box.'>Group stages is currently running, to be an eligible contestant in this tournament you must first qualify to the <a href="?site=groups&cupID='.$cupID.'">group stages</a>.</div>';
                elseif($is_qualifyers && $c_qualifiers > $dv['anzahl'] && in_array($clanID,$fcfs_ID))
                        echo '<div '.$error_box.'><b>Sorry, you must wait for qualified participants first.</b></div>';
                elseif($is_qualifyers && !in_array($clanID,$qualified_ID) && !in_array($clanID,$fcfs_ID))
                        echo '<div '.$error_box.'><b>Sorry, you are not a qualified participant.</b></div>';
		elseif($cm['clanmembers']==1 && !isclanmember($userID))
		    echo '<div '.$error_box.'><b>'.$_language->module['cm_only'].'</b> <img src="images/cup/error.png" width="16" height="16"></div>';
		elseif(($ds['ratio_low'] || $ds['ratio_high']) AND clanscoreratio($clanID) < $ds['ratio_low'] || clanscoreratio($clanID) > $ds['ratio_high']) 		
		    echo '<div '.$error_box.'><b>'.$_language->module['ratio_no'].'</b> <img src="images/cup/error.png" width="16" height="16"><br>'.$_language->module['ratio_range'].' <font color="red"><b>'.$ds['ratio_low'].'%</b></font> to <font color="red"><b>'.$ds['ratio_high'].'%</b></font> '.$_language->module['your_ratio'].' <font color="red"><b>'.clanscoreratio($clanID).'%</b></font></div>';
		elseif(!isleader($userID,$clanID))
			echo $_language->module['not_leader'];
		elseif($clannum)
			echo $_language->module['already_in_cup'];
		elseif($dv['anzahl'] >= $max)
			echo $_language->module['too_much_teams'];
		elseif(islocked($clanID))
			echo $_language->module['team_locked'];
        elseif(!$dl['status'])
			echo $_language->module['register_inactive'];
		elseif($ds['status'] == 2)
			echo $_language->module['already_cup_started'];
		elseif($ds['status'] == 3)
			echo $_language->module['already_cup_finished'];
		elseif($anz_mem+1+$membersin <= $ds['typ'])
			echo '<center><div '.$error_box.'><b>'.$_language->module['you_have'].' <font color="red"><b>'.$members.'</b></font> '.$_language->module['this_is_a'].' '.$ds['typ'].' cup! <img border="0" src="images/cup/error.png" width="16" height="16"></b></font><br/>'.$_language->module['you_need'].' <font color="red"><b>'.$needed.'</b></font> '.$_language->module['in_your_team'].' <br><br>'.$_language->module['users_can'].' <img src="images/cup/icons/join.png"> <a href="?site=clans&action=clanjoin&clanID='.$clanID.'&password='.$password.'">'.$_language->module['join_this_team'].'</a> or you can <img src="images/cup/icons/invite.gif"> <a href="?site=clans&action=invite&clanID='.$clanID.'">'.$_language->module['send_invite'].'</a></div></center>';
		elseif(!validgameacc($clanID, $cupID, $gameacc))
			echo '<center><div '.$error_box.'>'.$_language->module['not_all'].' <B>'.$anz_mem.'</B> '.$_language->module['members_entered'].' <b>'.$dr['type'].'</b> '.$_language->module['gameaccount'].'.<img border="0" src="images/cup/error.png" width="16" height="16"></b></font><br />'.$_language->module['provide_them'].' <a href="?site=myprofile&action=gameaccounts">'.$_language->module['this_link'].'</a> '.$_language->module['so_they_can'].'</div></center>';
			
  //to be unchecked as backup
		else{
			safe_query("INSERT INTO ".PREFIX."cup_clans (cupID, clanID, 1on1, checkin) VALUES ('$cupID', '$clanID', '0', '0')");
			redirect('?site=cups&action=details&cupID='.$cupID, $_language->module['sucess_cup_entered'], 2);
		}
	}

/* V4.1.5 LINEUP */

}elseif(isset($_GET['action']) && $_GET['action'] == 'lineup'){

    $cupID = mysql_real_escape_string($_GET['cupID']);

    include("title_cup.php");
    $participants = 'Teams';
    
    eval ("\$title_cup = \"".gettemplate("title_cup")."\";");
    echo $title_cup;

      $members = safe_query("SELECT clanID FROM ".PREFIX."cup_clan_members WHERE userID='$userID'");
         while($dd=mysql_fetch_array($members)) {


                  $registered = safe_query("SELECT clanID FROM ".PREFIX."cup_clans WHERE cupID='".$_GET['cupID']."' && clanID='".$dd['clanID']."' && 1on1='0'");
    
                  $checked = safe_query("SELECT * FROM ".PREFIX."cup_clans WHERE clanID='".$dd['clanID']."' && cupID='".$_GET['cupID']."' && 1on1='0' && checkin='1'");
                  $checkedinlined=mysql_num_rows($checked); 
		  
		  if($checkedinlined) { 
		        echo '<div '.$error_box.'><b>You have already lined your team and checked in.</b> <img src="images/cup/error.png" width="16" height="16"><br> Click <a href="?site=clans&action=show&clanID='.$dd['clanID'].'&cupID='.$_GET['cupID'].'#members">here</a> to view your lineup in this cup.</div>'; 
		  }
		  else

                      while($dm=mysql_fetch_array($registered)) {
    
                          $clanID = $dm['clanID'];

                             $query_lineup = safe_query("SELECT userID FROM ".PREFIX."cup_clan_lineup WHERE cupID='".$_GET['cupID']."' && clanID = '$clanID'");
      
                             if(!mysql_num_rows($query_line)) {
                                  $members=safe_query("SELECT userID FROM ".PREFIX."cup_clan_members WHERE cupID!='".$_GET['cupID']."'  && clanID = '$clanID'"); 
                             }
          
	                     while($ql = mysql_fetch_array($query_lineup)) {
			     
                                $all_userIDs = $ql['userID'];
                                $userIDs .= "userID != '$all_userIDs' && ";

		                $members=safe_query("SELECT userID FROM ".PREFIX."cup_clan_members WHERE $userIDs userID != '0' && cupID!='".$_GET['cupID']."' && clanID = '$clanID'"); 
	                     }
			     
		        while($dv=mysql_fetch_array($members)) {
                    
		            if($dv['userID']==$userID) { 
			          $nickname = '(You)'; 
		            }
			    else{ 
			          $nickname = getnickname($dv['userID']); 
		            }	    
			  $member.='<option value="'.$dv['userID'].'">'.$nickname.'</option>';
                        }			    

		if(isleader($userID,$clanID))	{
		 $form = '<font color="#DD0000"><b>Not entered in the Cup:</b></font><br> Hold CTRL and left click to select/deselect members in your team.<br><br>
		          <img src="images/cup/icons/info.gif" width="16" height="16"> Who will participate in this cup?
		  <form method="post" action="index.php?site=clans&action=lineup&cupID='.$_GET['cupID'].'&clanID='.$clanID.'&do=insert">
                    <select multiple name="member[]" size="5">
                    '.$member.'
                    </select><br>
                   <input type="submit" value="Enter Selected" onclick="return confirm(\'Are you sure with your selection? \');">
		  </form>';
		  
  if(isset($_GET['do']) && $_GET['do'] == 'insert'){	

    $allIDs=$_POST['member'];
      foreach ($allIDs as $s) {
        mysql_query("INSERT INTO ".PREFIX."cup_clan_lineup (cupID, clanID, userID) VALUES ('".$_GET['cupID']."', '".$clanID."', '".$s."')"); 
        //mysql_query("UPDATE ".PREFIX."cup_clan_members SET cupID = '".$_GET['cupID']."' WHERE userID = '".$s."' && clanID = '".$clanID."'");
      redirect('?site=clans&action=lineup&cupID='.$_GET['cupID'].'&clanID='.$_GET['clanID'], '', 0);
      }
    }
  }   
  
  //get members

		$members=safe_query("SELECT userID FROM ".PREFIX."cup_clan_lineup WHERE cupID='".$_GET['cupID']."' && clanID = '$clanID'");
		while($dr=mysql_fetch_array($members)) {
                    if($dr['userID']==$userID) { $nickname = '(You)'; }else{ $nickname = getnickname($dr['userID']); }
			$member2.='<option value="'.$dr['userID'].'">'.$nickname.'</option>';

  //dropdown
  
    $getstart = safe_query("SELECT start FROM ".PREFIX."cups WHERE ID='".$_GET['cupID']."'");
    $st = mysql_fetch_array($getstart); $start = date('d.m.Y \a\t H:i', $st['start']).'';	 

		if(isleader($userID,$clanID))	{
		 $form2 = '<hr><font color="#00FF00"><b>Entered in the Cup:</b></font><br> Hold CTRL and left click to select/deselect members in your team.<br><br>
		           <img src="images/cup/icons/info.gif" width="16" height="16"> Who will not participate in this cup?
		  <form method="post" action="index.php?site=clans&action=lineup&cupID='.$_GET['cupID'].'&clanID='.$clanID.'&do=remove">
                    <select multiple name="member2[]" size="5">
                    '.$member2.'
                    </select><br>   
                   <input type="submit" value="Remove Selected" onclick="return confirm(\'Are you sure with your selection? \');">
		  </form><a href="?site=clans&action=autocheckin&cupID='.$_GET['cupID'].'&clanID='.$clanID.'" onclick="return confirm(\'This will check your selected team in the '.getcupname($_GET['cupID']).' cup. Upon success, you will be unable to leave this cup or change your lineup. Will your team be ready by '.$start.' '.$gmt.'? \');">Ready to checkin?</a>';   	

  if(isset($_GET['do']) && $_GET['do'] == 'remove'){	

    $allIDs=$_POST['member2'];
      foreach ($allIDs as $s) {
        safe_query("UPDATE ".PREFIX."cup_clan_members SET cupID = '0' WHERE userID = '".$s."' && clanID = '".$clanID."'");
        safe_query("DELETE FROM ".PREFIX."cup_clan_lineup WHERE clanID='".$clanID."' && cupID='".$_GET['cupID']."' && userID='".$s."'");
      redirect('?site=clans&action=lineup&cupID='.$_GET['cupID'].'&clanID='.$_GET['clanID'], '', 0);
      }
     }
    } 
   }
    } 
     }echo $form.$form2; 
                  
/* END V4.1.5 */
	
}elseif(isset($_GET['action']) && $_GET['action'] == 'autocheckin'){

  //if not logged in

	if(!$userID) die($_language->module['logged_in']);
  
	$clanID = (isset($_GET['clanID']) ? $_GET['clanID'] : 0);
	$cupID=mysql_real_escape_string($_GET['cupID']);
	
  //for 1on1 cups

	if(is1on1($cupID)){
		if(mysql_num_rows(safe_query("SELECT * FROM ".PREFIX."cup_clans WHERE cupID = '$cupID' && clanID = '$userID' && 1on1 = '1'"))){
			$anzclans = mysql_num_rows(safe_query("SELECT * FROM ".PREFIX."cup_clans WHERE cupID = '$cupID' && checkin = '1' && 1on1 = '1'"));
			$dv=mysql_fetch_array(safe_query("SELECT * FROM ".PREFIX."cups WHERE ID = '$cupID'"));
			$checkintime = $dv['start']-($dv['checkin']*60); }
			
			$ergebnis3 = safe_query("SELECT * FROM ".PREFIX."cup_clans WHERE cupID='$cupID' && clanID='$userID' && 1on1='1' && checkin='0'");
		    $userin=mysql_num_rows($ergebnis3);
		    
		    $ergebnis4 = safe_query("SELECT * FROM ".PREFIX."cup_clans WHERE clanID= '".$userID."' && 1on1='1' && cupID= '".$cupID."' && checkin='1'");
		    $userchecked=mysql_num_rows($ergebnis4);
		    
		    $ergebnis2 = safe_query("SELECT count(*) as number FROM ".PREFIX."cup_clans WHERE cupID='".$cupID."' && 1on1='1' && checkin='1'");
		    $dm=mysql_fetch_array($ergebnis2);
		
			$ergebnis = safe_query("SELECT * FROM ".PREFIX."cups WHERE ID = '$cupID ' ");
		    $ds=mysql_fetch_array($ergebnis);
		    
 //check gameaccount		    
 		    
		    $checkgameacc=mysql_num_rows(safe_query("SELECT * FROM ".PREFIX."user_gameacc WHERE type = '".$ds['gameaccID']."' && userID = '$userID' && log='0'"));
		    $gameacc_sql2 = safe_query("SELECT type FROM ".PREFIX."gameacc WHERE gameaccID = '".$ds['gameaccID']."'");
	        $dr=mysql_fetch_array($gameacc_sql2);
	        
  //check max limits for cups

			if($ds['maxclan'] == 80 || $ds['maxclan']== 8)
				$max=8;
			elseif($ds['maxclan'] == 160 || $ds['maxclan']== 16)
				$max=16;
			elseif($ds['maxclan'] == 320 || $ds['maxclan']== 32)
				$max=32;
		    elseif($ds['maxclan'] == 640 || $ds['maxclan']== 64)
			    $max = 64;
				
  //checkin times
  
        $start = date('d.m.Y \a\t H:i', $ds['start']).'';
  		$checkindate = date('H:i', ($ds['start']-($ds['checkin']*60))).'';
		$checkinstart = $ds['checkin'];
		$checkintime = $ds['start']-($ds['checkin']*60);  
				
  //1on1 checkin register validations
  
  		    if($is_groupreg) {
  		        echo '<div '.$error_box.'>To be an eligible contestant in this tournament you must first signup and qualify to the <a href="?site=groups&cupID='.$cupID.'">group stages</a>.</div>';
		        redirect('?site=groups&cupID='.$cupID, '', 3);
  		   }elseif($is_groupstages)
  		        echo '<div '.$error_box.'>Group stages is currently running, to be an eligible contestant in this tournament you must first qualify to the <a href="?site=groups&cupID='.$cupID.'">group stages</a>.</div>';
                    elseif($is_qualifyers && $c_qualifiers > $dm['number'] && in_array($userID,$fcfs_ID))
                        echo '<div '.$error_box.'><b>Sorry, you must wait for qualified participants first.</b></div>';
                    elseif($is_qualifyers && !in_array($userID,$qualified_ID) && !in_array($userID,$fcfs_ID))
                        echo '<div '.$error_box.'><b>Sorry, you are not a qualified participant.</b></div>';
		    elseif($userchecked)
			echo $_language->module['already_in_cup_c1on1']; 
		    elseif(userislocked($userID))
		        echo '<div '.$error_box.'><b>Sorry, your cup profile is locked!</b> <img src="images/cup/error.png" width="16" height="16"><br>View your penalty points at <a href="?site=profile&userID='.$userID.'">your profile</a>.</div>'; 
		    elseif($ds['clanmembers']==1 && !isclanmember($userID))
		        echo '<div '.$error_box.'><b>Sorry, this is a clanmembers cup only!</b> <img src="images/cup/error.png" width="16" height="16"><br>To become a clanmember and assigned to the members roster, contact a squad admin.</div>'; 
		    elseif(($ds['ratio_low'] || $ds['ratio_high']) AND userscoreratio($userID) < $ds['ratio_low'] || userscoreratio($userID) > $ds['ratio_high'])
		        echo '<div '.$error_box.'><b>Sorry, your score ratio is not for this cup.</b> <img src="images/cup/error.png" width="16" height="16"><br>Your score ratio must range from <font color="red"><b>'.$ds['ratio_low'].'%</b></font> to <font color="red"><b>'.$ds['ratio_high'].'%</b></font> for this cup. Your score ratio right now is <font color="red"><b>'.userscoreratio($userID).'%</b></font></div>';
		    elseif($db['status'] == 2)
			echo '<center><b>'.$_language->module['already_cup_started'].'</b></center>';
		    elseif($db['status'] == 3)
			echo '<center><b>'.$_language->module['already_cup_finished'].'</b></center>';
		    elseif($dm['number'] >= $max) 
			redirect('?site=cups&action=details&cupID='.$cupID, '<center>'.$_language->module['cup_full'].'</center>', 4); 
		    elseif($ds['cgameacclimit'] && !$checkgameacc)
			echo '<div '.$error_box.'>You must register the <b>'.$dr['type'].'</b> gameaccount.<img border="0" src="images/cup/error.png" width="16" height="16"></b></font><br />You can do so anytime from <a href="?site=myprofile&action=gameaccounts">this link</a> to enter your accounts.</div>';
		    elseif(!(time() >= $checkintime))
			echo '<div '.$error_box.'><b>The cup is not in check-in phase!</b> <img src="images/cup/error.png" width="16" height="16"><br>Please check cup-details for start and checkin times.</div>';	

  //if not checked
				
			elseif($userin){
			    safe_query("UPDATE ".PREFIX."cup_clans SET checkin='1' WHERE cupID='$cupID' && clanID='$userID' && 1on1='1'");
                redirect('?site=cups&action=details&cupID='.$cupID, $_language->module['checked_in'], 2);
  //if checked                
                
			}else{
				safe_query("INSERT INTO ".PREFIX."cup_clans (cupID, clanID, 1on1, checkin) VALUES ('$cupID', '$userID', '1', '1')");
			redirect('?site=cups&action=details&cupID='.$cupID, $_language->module['sucess_cup_entered'], 2);
		}
		
  //if team cups
	
	}else{
		if(isleader($userID,$clanID)){
			$anzclans = mysql_num_rows(safe_query("SELECT * FROM ".PREFIX."cup_clans WHERE cupID = '$cupID' && checkin = '1' && 1on1 = '0'"));
			$dv=mysql_fetch_array(safe_query("SELECT * FROM ".PREFIX."cups WHERE ID = '$cupID'"));
			$checkintime=$dv['start']-($dv['checkin']*60); }

		$ergebnis = safe_query("SELECT * FROM ".PREFIX."cups WHERE ID = '$cupID ' ");
		$ds=mysql_fetch_array($ergebnis);

		$ergebnis3 = safe_query("SELECT * FROM ".PREFIX."cup_clans WHERE clanID='".$_GET['clanID']."' && cupID='".$_GET['cupID']."' && checkin='0'");
		$clannum=mysql_num_rows($ergebnis3);

		$ergebnis4 = safe_query("SELECT * FROM ".PREFIX."cup_clans WHERE clanID='".$_GET['clanID']."' && cupID='".$_GET['cupID']."' && checkin='1'");
		$isin=mysql_num_rows($ergebnis4);
		
    //check max limits for cups
    
		$ergebnis2 = safe_query("SELECT count(*) as anzahl FROM ".PREFIX."cup_clans WHERE cupID='".$cupID."' && checkin='1'");
		while($dv=mysql_fetch_array($ergebnis2))	

			if($ds['maxclan'] == 80 || $ds['maxclan']== 8)
				$max=8;
			elseif($ds['maxclan'] == 160 || $ds['maxclan']== 16)
				$max=16;
			elseif($ds['maxclan'] == 320 || $ds['maxclan']== 32)
		 		$max=32;
		    elseif($ds['maxclan'] == 640 || $ds['maxclan']== 64)
			    $max = 64;

  //Enough members in team?

           $ergebnis = safe_query("SELECT typ FROM ".PREFIX."cups WHERE ID = '".$cupID."'");
		   $dl=mysql_fetch_array($ergebnis);	
		   $min_anz = strstr($dl['typ'], 'on', true);
		
		   $ergebnis2 = safe_query("SELECT clanID FROM ".PREFIX."cup_clan_members WHERE clanID = '".$clanID."'");
		   $anz_mem = mysql_num_rows($ergebnis2);	

           $sql_members = safe_query("SELECT * FROM ".PREFIX."cup_clan_members WHERE clanID = '".$clanID."'");	
	       $members = mysql_num_rows($sql_members);
           
  //check gameaccounts

           $checkgameacc = mysql_num_rows(safe_query("SELECT * FROM ".PREFIX."user_gameacc WHERE type = '".$db['gameaccID']."' && userID = '$userID' && log='0'"));
		   $ergebnis2 = safe_query("SELECT status, password FROM ".PREFIX."cup_all_clans WHERE ID = '".$clanID."'");		
		   $dl=mysql_fetch_array($ergebnis2);
		   $password = $dl['password'];

		   $gameacc_sql2 = safe_query("SELECT type FROM ".PREFIX."gameacc WHERE gameaccID = '".$ds['gameaccID']."'");
		   $dr=mysql_fetch_array($gameacc_sql2);
		   
  //checkin times
  
        $start = date('d.m.Y \a\t H:i', $ds['start']).'';
  		$checkindate = date('H:i', ($ds['start']-($ds['checkin']*60))).'';
		$checkinstart = $ds['checkin'];
		$checkintime = $ds['start']-($ds['checkin']*60);  
  
  //takeaway members in count
              
		$ergebnis = safe_query("SELECT cupaclimit FROM ".PREFIX."cups WHERE ID='$cupID'");	
		while($db = mysql_fetch_array($ergebnis)) {

        $membersin = $db['cupaclimit']; 
        if($db['cupaclimit'] <= 0)
        $membersin='';

        elseif($db['cupaclimit'] >= 1)
        $membersin = $db['cupaclimit']; }
           
        $needed = $ds['typ']-$members-$membersin;
        
  //can not register team to cup if lineup is not selected
    
  //$lineup = safe_query("SELECT * FROM ".PREFIX."cup_clan_members WHERE cupID='$cupID' && clanID='$clanID'");
  $lineup = safe_query("SELECT * FROM ".PREFIX."cup_clan_lineup WHERE cupID='$cupID' && clanID='$clanID'");
  $checklineup = mysql_num_rows($lineup); $thelineup = mysql_num_rows($lineup)+$membersin+1;
  
  $lineneeded = $ds['typ']-$checklineup-$membersin;
		   
  //can not register team to cup if your team is already in cup
 
    $myteams=safe_query("SELECT clanID, userID FROM ".PREFIX."cup_clan_members WHERE userID='$userID'");
    while($tt=mysql_fetch_array($myteams)) {

    $cupclans=safe_query("SELECT * FROM ".PREFIX."cup_clans WHERE cupID='$cupID' AND clanID='".$tt['clanID']."' AND checkin='1'");
    while($ee=mysql_fetch_array($cupclans)) {

  //team checkin register validations

		if(count($ee)>1);
			echo '<div '.$error_box.'><img src="images/cup/error.png" width="16" height="16"> <b>You are already in this cup!<br>You can not register more teams or the same one in one cup.</b></div>'; exit; }   
	   }if($isin)
			echo '<center>'.$_language->module['team_checked'].'</center>';		
  		elseif($is_groupstages)
  		    echo '<div '.$error_box.'>Group stages is currently running, to be an eligible contestant in this tournament you must first qualify to the <a href="?site=groups&cupID='.$cupID.'">group stages</a>.</div>';		
  		elseif($is_groupreg) {
  		    echo '<div '.$error_box.'>To be an eligible contestant in this tournament you must first signup and qualify to the <a href="?site=groups&cupID='.$cupID.'">group stages</a>.</div>';
		    redirect('?site=groups&cupID='.$cupID, '', 3);		
	       }elseif($is_qualifyers && $c_qualifiers > $dv['anzahl'] && in_array($clanID,$fcfs_ID))
                    echo '<div '.$error_box.'><b>Sorry, you must wait for qualified teams first.</b></div>';
                elseif($is_qualifyers && !in_array($clanID,$qualified_ID) && !in_array($clanID,$fcfs_ID))
                   echo '<div '.$error_box.'><b>Sorry, you are not a qualified team.</b></div>';
		elseif($ds['clanmembers']==1 && !isclanmember($userID))
		    echo '<div '.$error_box.'><b>Sorry, this is a clanmembers cup only!</b> <img src="images/cup/error.png" width="16" height="16"><br>To become a clanmember and assigned to the members roster, contact a squad admin.</div>';
		elseif(($ds['ratio_low'] || $ds['ratio_high']) AND clanscoreratio($clanID) < $ds['ratio_low'] || clanscoreratio($clanID) > $ds['ratio_high'])
		    echo '<div '.$error_box.'><b>Sorry, your team score ratio is not for this cup.</b> <img src="images/cup/error.png" width="16" height="16"><br>Your score ratio must range from <font color="red"><b>'.$ds['ratio_low'].'%</b></font> to <font color="red"><b>'.$ds['ratio_high'].'%</b></font> for this cup. Your score ratio right now is <font color="red"><b>'.clanscoreratio($clanID).'%</b></font></div>';
		elseif($dv['anzahl'] >= $max)
			echo $_language->module['too_much_teams'];
		elseif(!isleader($userID,$clanID))
			echo '<center>'.$_language->module['not_leader'].'</center>';
		elseif($anzclans>=$max) 
			redirect('?site=cups&action=clans&cupID='.$cupID, $_language->module['cup_full'], 2); 
		elseif(islocked($clanID))
			echo '<center>'.$_language->module['team_locked'].'</center>';
        elseif(!$dl['status'])
			echo '<center>'.$_language->module['register_inactive'].'</center>';
		elseif(!(time() >= $checkintime))
			echo '<div '.$error_box.'><b>The cup is not yet in check-in phase!</b> <img src="images/cup/error.png" width="16" height="16"><br>Please retry at <b>'.$checkindate.'</b>, <font color="red"><b>'.$checkinstart.'</b></font> minutes before <b>'.$start.' '.$gmt.'</B></div>';
		elseif($ds['status'] == 2)
			echo '<center>'.$_language->module['already_cup_started'].'</center>';
		elseif($ds['status'] == 3)
			echo '<center>'.$_language->module['already_cup_finished'].'</center>';
		elseif($anz_mem+1+$membersin <= $ds['typ'])
			echo '<center><div '.$error_box.'><b>'.$_language->module['you_have'].' <font color="red"><b>'.$members.'</b></font> '.$_language->module['this_is_a'].' '.$ds['typ'].' cup! <img border="0" src="images/cup/error.png" width="16" height="16"></b></font><br/>'.$_language->module['you_need'].' <font color="red"><b>'.$needed.'</b></font> '.$_language->module['in_your_team'].' <br><br>'.$_language->module['users_can'].' <img src="images/cup/icons/join.png"> <a href="?site=clans&action=clanjoin&clanID='.$clanID.'&password='.$password.'">'.$_language->module['join_this_team'].'</a> or you can <img src="images/cup/icons/invite.gif"> <a href="?site=clans&action=invite&clanID='.$clanID.'">'.$_language->module['send_invite'].'</a></div></center>';			
		elseif(!checkinvalidgameacc($clanID, $cupID, $gameacc))
			echo '<center><div '.$error_box.'>'.$_language->module['not_all'].' <B>'.$checklineup.'</B> '.$_language->module['members_entered'].' <b>'.$dr['type'].'</b> gameaccount.<img border="0" src="images/cup/error.png" width="16" height="16"></b></font><br />'.$_language->module['provide_them'].' <a href="?site=myprofile&action=gameaccounts">'.$_language->module['this_link'].'</a> '.$_language->module['so_they_can'].'</div></center>';
		
  //check lineup...
  
		elseif($thelineup <= $ds['typ'] && !$clannum) {
			safe_query("INSERT INTO ".PREFIX."cup_clans (cupID, clanID, 1on1, checkin) VALUES ('$cupID', '$clanID', '0', '0')");
			redirect('?site=clans&action=lineup&cupID='.$_GET['cupID'].'&clanID='.$_GET['clanID'], '<div '.$error_box.'><b>You have <font color="red">'.$checklineup.'</font> and need <font color="red">'.$lineneeded.'</font> more member(s) in your lineup in this '.$ds['typ'].' cup</b> <img src="images/cup/error.png" width="16" height="16"><br>Please wait while you are being redirected in 5 (<a href="?site=clans&action=lineup&cupID='.$_GET['cupID'].'&clanID='.$clanID.'">go now</a>)<img src="images/cup/period'.$period_dot.'_ani.gif"><img src="images/cup/period'.$period_dot.'_ani.gif"></div>', 5); 
  
  		}elseif($thelineup <= $ds['typ']) {
			redirect('?site=clans&action=lineup&cupID='.$_GET['cupID'].'&clanID='.$_GET['clanID'], '<div '.$error_box.'><b>You have <font color="red">'.$checklineup.'</font> and need <font color="red">'.$lineneeded.'</font> more member(s) in your lineup in this '.$ds['typ'].' cup</b> <img src="images/cup/error.png" width="16" height="16"><br>Please wait while you are being redirected in 5 (<a href="?site=clans&action=lineup&cupID='.$_GET['cupID'].'&clanID='.$clanID.'">go now</a>)<img src="images/cup/period'.$period_dot.'_ani.gif"><img src="images/cup/period'.$period_dot.'_ani.gif"></div>', 5);
  
        }elseif($checklineup > $ds['typ']) {
			redirect('?site=clans&action=lineup&cupID='.$_GET['cupID'].'&clanID='.$_GET['clanID'], '<div '.$error_box.'><b>You have <font color="red">'.$checklineup.'</font> in your lineup for a  <font color="red">'.$ds['typ'].'</font> cup. Please remove some</b> <img src="images/cup/error.png" width="16" height="16"><br>You are now being redirected to your lineup (<a href="?site=clans&action=lineup&cupID='.$_GET['cupID'].'">go now</a>)<img src="images/cup/period'.$period_dot.'_ani.gif"><img src="images/cup/period'.$period_dot.'_ani.gif"></div>', 5);   
			
  //if not checked
  
		}elseif($clannum){
			safe_query("UPDATE ".PREFIX."cup_clans SET checkin='1' WHERE cupID='$cupID' && clanID='$clanID' && 1on1='0'");
            redirect('?site=cups&action=details&cupID='.$cupID, $_language->module['checked_in'], 2);                                           
                        
  //if checked                                   
		}else{
			safe_query("INSERT INTO ".PREFIX."cup_clans (cupID, clanID, 1on1, checkin) VALUES ('$cupID', '$clanID', '0', '1')");
			redirect('?site=cups&action=details&cupID='.$cupID, $_language->module['sucess_cup_entered'], 2);
	  	}
    }
}elseif(isset($_GET['action']) && $_GET['action'] == 'clanjoin'){
  
        $clanID = mysql_real_escape_string($_GET['clanID']);

	$dv=mysql_fetch_array(safe_query("SELECT status FROM ".PREFIX."cup_all_clans WHERE ID = '".$clanID."'"));
        $db=mysql_fetch_array(safe_query("SELECT * FROM ".PREFIX."cup_settings"));

        $teamlimitadd = $db['cupteamadd'];	
	$teamlimitjoin = $db['cupteamjoin'];
        $value = $teamlimitjoin-$teamlimitadd;
        

    if($loggedin) {

      if($clanID && $_GET['password']) {

         $ID = mysql_real_escape_string($_GET['clanID']);
         $pass = mysql_real_escape_string($_GET['password']);

         $check = safe_query("SELECT password FROM ".PREFIX."cup_all_clans WHERE ID='$ID'");
         $ds=mysql_fetch_array($check); $password = $ds['password'];
      
         $sql_member2 = safe_query("SELECT * FROM ".PREFIX."cup_clan_members WHERE userID='$userID' AND clanID='$ID'");	
	 
	 if(!$dv['status']) {
	        echo $_language->module['team_inactive']; 
	 }
	 elseif(islocked($clanID)) {
	        echo '<div '.$error_box.'>'.$_language->module['team_locked_join'].'</div>'; 
	 }
	 elseif(mysql_num_rows($sql_member2)) { 
	        echo '<div '.$error_box.'><b>You are already in team <a href="?site=clans&action=show&clanID='.$ID.'">'.getclanname($ID).'</a>!</b>  <img src="images/cup/error.png" width="16" height="16"></div>';
         }
         elseif($pass==$password || iscupadmin($userID)) {
	 
	        safe_query("INSERT INTO ".PREFIX."cup_clan_members (clanID, userID, function, reg) VALUES ('".$_GET['clanID']."', '".$userID."', 'Member', '".time()."')");
	        redirect('?site=clans&action=show&clanID='.$ID.'', $_language->module['team_joined'], 2);
         }
         else{
	        echo '<div '.$error_box.'><b>The password <font color="red">'.$pass.'</font> is incorrect!</b> <img src="images/cup/error.png" width="16" height="16"><br>Please contact the leader. (<a href="?site=clans&action=show&clanID='.$ID.'">clan-details</a>)</div>';
         }
		
      }
      elseif($clanID) { 
    
	        if(!$dv['status']) {
		      echo $_language->module['team_inactive']; 
		}
	        elseif(islocked($clanID)) {
		      echo '<div '.$error_box.'>'.$_language->module['team_locked_join'].'</div>'; 
                }
                else{     
		
		      $ds=mysql_fetch_array(safe_query("SELECT * FROM ".PREFIX."cup_all_clans WHERE ID='$clanID'"));					

		      $teamname = $ds['name'];
                      $password = mysql_real_escape_string($_GET['password']);
		      $clan = '<option value="'.$clanID.'" selected="selected">'.$ds['name'].'</option>'; 
		     
		   if(iscupadmin($userID)) { echo '<div '.$error_box.'><strong>Admin Notice:</strong> You can join teams without a password.</div>'; }
		
		   eval ("\$inctemp = \"".gettemplate("clans_join_selected")."\";");
		   echo $inctemp; 
		   
                }

      }
      else{
		
		$ergebnis = safe_query("SELECT * FROM ".PREFIX."cup_all_clans ORDER BY name ASC");		
		$clan = '<option value="0" selected="selected"> - '.$_language->module['choose_team'].' - </option>';	
		
		while($ds=mysql_fetch_array($ergebnis)) {
			$clan .= '<option value="'.$ds['ID'].'">'.$ds['name'].'</option>';
                }
	    
		$show_info = '&nbsp;You can be in <font color="red"><b>'.$value.'</b></font> 
	                      additional team'.($value==1 ? '' : 's').' and create <font color="red"><b>'.$teamlimitadd.'</b></font> team'.($teamlimitadd==1 ? '' : 's').'. 
	                      ('.$teamlimitjoin.' max)';
			      
		if(iscupadmin($userID)) { echo '<div '.$error_box.'><strong>Admin Notice:</strong> You can join teams without a password.</div>'; }	      

		eval ("\$inctemp = \"".gettemplate("clans_join")."\";");
		echo $inctemp; echo base64_decode('');
	    }
  }
  else{
                echo $_language->module['logged_in'];         
  }
}
elseif($_GET['action'] == 'editpwd'){
  
        $clanID = mysql_real_escape_string($_GET['clanID']);

	$dl=mysql_fetch_array(safe_query("SELECT status FROM ".PREFIX."cup_all_clans WHERE ID='".$_GET['clanID']."'"));		
			
        if(!isleader($userID,$clanID)) {
	     echo $_language->module['not_leader'];
	}
	elseif(islocked($clanID)) {
	     echo $_language->module['team_locked_cup'];
	}
        elseif(!$dl['status']) {
	     echo $_language->module['register_inactive'];
	}
	else{
		$ergebnis = safe_query("SELECT * FROM ".PREFIX."cup_all_clans WHERE ID = '$clanID'");	
		$db=mysql_fetch_array($ergebnis);

		$clan_title = $_language->module['edit_password'];
		$typ = 'editpwd';
		$button = $_language->module['change_password'];
                $password = $db['password'];		

		eval ("\$inctemp = \"".gettemplate("clan_password")."\";");
		echo $inctemp; echo base64_decode('');
	}

}elseif(isset($_GET['action']) && $_GET['action'] == 'clanedit'){
	$clanID = $_GET['clanID'];

	$ergebnis2=safe_query("SELECT status FROM ".PREFIX."cup_all_clans WHERE ID = '".$clanID."'");
	$dv=mysql_fetch_array($ergebnis2);
	
  //validations	

	if(!isleader($userID,$clanID)) 
		echo $_language->module['not_leader'];

		elseif(islocked($clanID)) echo '<div '.$error_box.'>'.$_language->module['team_locked_join'].'</div>';
        elseif(!$dv['status']) echo $_language->module['status_updated'];
        
  //only leader
	
	else{
		$ergebnis = safe_query("SELECT * FROM ".PREFIX."cup_all_clans WHERE ID = '$clanID' && password!='Leader'");	
		$db=mysql_fetch_array($ergebnis);

		$clan_title = $_language->module['edit_team'];
		$typ = 'editclan';
		$button = $_language->module['edit'];
        $flag = '[flag]'.$db['country'].'[/flag]';
		$country = flags($flag);
		$country = str_replace("<img","<img id='county'",$country);
		$countries = str_replace(" selected=\"selected\"", "", $countries);
		$countries = str_replace('value="'.$db['country'].'"', 'value="'.$db['country'].'" selected="selected"', $countries);
		$homepage = $db['clanhp'];
		$logo = $db['clanlogo'];

		$member = '<option value="0">- '.$_language->module['choose_member'].' -</option>';
		$members=safe_query("SELECT userID FROM ".PREFIX."cup_clan_members WHERE clanID = '$clanID' AND userID != '$userID'");
		while($dv=mysql_fetch_array($members)) {
			if(mysql_num_rows(safe_query("SELECT ID FROM ".PREFIX."cup_all_clans WHERE leader = '".$dv['userID']."' AND ID = '".$clanID."'"))) continue;
			$member.='<option value="'.$dv['userID'].'">'.getnickname($dv['userID']).'</option>';
		}
		
  //team deletion & back link
		
		$delteam = '<tr bgcolor="'.$bg1.'">
					  <td align="right" bgcolor="'.$bg1.'">'.$_language->module['kick_member'].':</td>
					  <td bgcolor="'.$bg2.'"><select name="member" onChange="MM_confirm(\''.$_language->module['kick_member2'].'\', \'?site=clans&action=delmember&clanID='.$clanID.'&memberID=\'+this.value)">'.$member.'</select></td>
					</tr>
					<tr bgcolor="'.$bg1.'">
					  <td align="right" bgcolor="'.$bg1.'"><img src="images/cup/new_message_inv.gif"></td>
					  <td bgcolor="'.$bg2.'"><a href="?site=clans&action=show&clanID='.$clanID.'">View/Manage Team</a></td>
					</tr>';
					
  //get team info
		$ergebnis2=safe_query("SELECT name, short, clantag, clanhp, clanlogo, status FROM ".PREFIX."cup_all_clans WHERE ID = '".$clanID."'");
		$dv=mysql_fetch_array($ergebnis2);

  //promotion	
		$member2 = '<option value="0">- '.$_language->module['choose_member'].' -</option>';
		$members2=safe_query("SELECT userID FROM ".PREFIX."cup_clan_members WHERE clanID = '$clanID' && function!='Leader'");
		while($dr=mysql_fetch_array($members2)) {
			$member2.='<option value="'.$dr['userID'].'">'.getnickname($dr['userID']).'</option>';
		}
		
  //chat	
		$chat=safe_query("SELECT chat FROM ".PREFIX."cup_all_clans WHERE ID='$clanID'");
		while($dr=mysql_fetch_array($chat)) {
		
		if($dr['chat']==0) { $chatname = 'Team chat is disabled'; }
		if($dr['chat']==1) { $chatname = 'Team chat private only'; }
		if($dr['chat']==2) { $chatname = 'Public team chat'; }
		
			$chat = '<option selected value="'.$dr['chat'].'">-- Select Access --</option>
			         <option value="0">Disable Chat</option>
			         <option value="1">Private Only</option>
			         <option value="2">Public Chat</option>';	         
		}
		
  //comments

		$comments=safe_query("SELECT comment FROM ".PREFIX."cup_all_clans WHERE ID='$clanID'");
		while($dr=mysql_fetch_array($comments)) {	
			
		if($dr['comment']==0) { $comment = 'Comments disabled for this team'; }
		if($dr['comment']==1) { $comment = 'Comments only for logged in users'; }
		if($dr['comment']==2) { $comment = 'Comments opened for all'; }
		if($dr['comment']==3) { $comment = 'Comments only for members in team'; }
		
		$commentsaccess = '<option selected value="'.$dr['comment'].'">-- Select Access --</option>
			               <option value="0">Disable Comments</option>
			               <option value="3">Members Only</option>
			               <option value="1">Logged-in Users</option>
			               <option value="2">Users & Guests</option>'; }

  //give ownership
		$member3 = '<option value="0">- '.$_language->module['choose_member'].' -</option>';
		$members3=safe_query("SELECT userID FROM ".PREFIX."cup_clan_members WHERE clanID = '$clanID' && function ='Leader' && userID != '$userID'");
		while($dm=mysql_fetch_array($members3)) {
			$member3.='<option value="'.$dm['userID'].'">'.getnickname($dm['userID']).'</option>';
		}

  //Demotion
		$member4 = '<option value="0">- '.$_language->module['choose_member'].' -</option>';
		$members4=safe_query("SELECT userID FROM ".PREFIX."cup_clan_members WHERE clanID = '$clanID' && function ='Leader' && userID != '$userID'");
		while($dp=mysql_fetch_array($members4)) {
			$member4.='<option value="'.$dp['userID'].'">'.getnickname($dp['userID']).'</option>';
		}
  
  //disable or delete team

		$isfounder = mysql_num_rows(safe_query("SELECT ID FROM ".PREFIX."cup_all_clans WHERE leader = '".$userID."' AND ID = '".$clanID."'"));
		$leadmember = '';
		if($isfounder) {
		

		$activity = '
		  <tr bgcolor="'.$bg1.'">
		    <td align="right" bgcolor="'.$bg1.'">Team Remove:</td>
		    <td bgcolor="'.$bg2.'"><a href="?site=clans&action=delclan&clanID='.$clanID.'" onclick="return confirm(\'This will delete all your team data! \r Are you sure you want to delete this team? If you want to leave this team instead, promote a leader as owner.\');">Delete Team</td>
		  </tr>';
		
		if($dv['status']) 
		$activity .= '
		  <tr bgcolor="'.$bg1.'">
		    <td align="right" bgcolor="'.$bg1.'">Team Status:</td>
		    <td bgcolor="'.$bg2.'"><a href="?site=clans&action=status&teamstatus=0&clanID='.$clanID.'" onclick="return confirm(\'Important: This will set your team status to inactive. If you do so, you are unable to manage your team or enter cups unless an admin reinstates your team.\');">Disable Team</td>
		  </tr>';
		  
		}
		  
  //team chat 

		if(isleader($userID,$clanID))	  
		$chat = '
		  <tr bgcolor="'.$bg1.'">
		    <td align="right" bgcolor="'.$bg1.'">Chat Access:</td>
		    <td bgcolor="'.$bg2.'"><select name="member" onChange="MM_confirm(\'Confirm?\', \'?site=clans&action=chat&clanID='.$clanID.'&chataccess=\'+this.value)">'.$chat.'</select> '.$chatname.'</td>
		</tr>';
		
  //team comments
		
		if(isleader($userID,$clanID))
		$comments = '
		  <tr bgcolor="'.$bg1.'">
		    <td align="right" bgcolor="'.$bg1.'">Comments Access:</td>
		    <td bgcolor="'.$bg2.'"><select name="comment" onChange="MM_confirm(\'Confirm?\', \'?site=clans&action=comments&clanID='.$clanID.'&access=\'+this.value)">'.$commentsaccess.'</select> '.$comment.'</td>
		</tr>'; 
 
  //only founder can promote members

		if($isfounder)	
		$leadmember = '
		  <tr bgcolor="'.$bg1.'">
		    <td align="right" bgcolor="'.$bg1.'">Promote member:</td>
		    <td bgcolor="'.$bg2.'"><select name="member" onChange="MM_confirm(\''.$_language->module['give_leader_rights'].'\', \'?site=clans&action=leadmember&clanID='.$clanID.'&memberID=\'+this.value)">'.$member2.'</select> '.$_language->module['as_leader'].'</td>
		  </tr>';	
		  
  //only founder can demote leaders 

		if($isfounder)	
		$demotemember = '
		  <tr bgcolor="'.$bg1.'">
		    <td align="right" bgcolor="'.$bg1.'">Demote leader:</td>
		    <td bgcolor="'.$bg2.'"><select name="member" onChange="MM_confirm(\''.$_language->module['demote_leader_rights'].'\', \'?site=clans&action=demotemember&clanID='.$clanID.'&memberID=\'+this.value)">'.$member4.'</select> '.$_language->module['as_member'].'</td>
		</tr>';	

  //only founder can give ownership
  
		if($isfounder)	  
		$ownership = '
		  <tr bgcolor="'.$bg1.'">
		    <td align="right" bgcolor="'.$bg1.'">Grant leader:</td>
		    <td bgcolor="'.$bg2.'"><select name="member" onChange="MM_confirm(\''.$_language->module['give_owner_rights'].'\', \'?site=clans&action=ownmember&clanID='.$clanID.'&memberID=\'+this.value)">'.$member3.'</select> '.$_language->module['as_owner'].'</td>
		</tr>';

		eval ("\$inctemp = \"".gettemplate("clans_form")."\";");
		echo $inctemp; echo base64_decode(''); 
    }
    
}elseif(isset($_GET['action']) && $_GET['action'] == 'show'){

	$clanID = $_GET['clanID'];
	$cupID = (isset($_GET['cupID']) ? $_GET['cupID'] : 0);
	$laddID = (isset($_GET['laddID']) ? $_GET['laddID'] : 0);
	
	if($cupID){
	
           include ("title_cup.php");
	   
	   if(is1on1($cupID)) $participants = 'Players';	
	   else $participants = 'Teams';
	
	   eval ("\$title_cup = \"".gettemplate("title_cup")."\";");
	   echo $title_cup;
	}
	elseif($laddID) {
	
	   if(ladderis1on1($laddID)) $participants = 'Players';	
	   else $participants = 'Teams';

           include ("title_ladder.php");
	   echo '<br />';
	}
	
  //check if clan exists, if so show team is locked if locked
	
	if(!$clanID) die($_language->module['invalid_clanid']);
	
    $checkteam = safe_query("SELECT * FROM ".PREFIX."cup_all_clans WHERE ID= $clanID");		
	
	if(!mysql_num_rows($checkteam)) die($_language->module['invalid_clanid']);
	elseif(islocked($clanID)) echo '<div '.$error_box.'>'.$_language->module['team_locked_big'].'</div>';
	
//new team details page	
	
	$ergebnis = safe_query("SELECT * FROM ".PREFIX."cup_all_clans WHERE ID = $clanID");		
	$ds=mysql_fetch_array($ergebnis); {
	
	if($_GET['cupID']) echo "<br />";
	
	  eval ("\$clans_details_title = \"".gettemplate("clans_details_title")."\";");
	  echo $clans_details_title;
	
	    $clanID = $ds[ID];
	    $clanlogo = clanlogo($clanID);
	    $country = getclancountry3($clanID);
	    $clanname = getclanname2($clanID);
	    $short = getclanname($clanID);
	    $clantag = getclantag($clanID);
	    
	    $rowspan_temp = 4;
	    
	    if($ds['clanhp']) {	  
	    
	        $rowspan_temp = $rowspan_temp+1;		
	        $clanhp_temp = '<tr>
                                  <td bgcolor="'.$bg1.'"><strong>Homepage</strong></td>
                                  <td bgcolor="'.$bg1.'" ><a href="'.$ds['clanhp'].'">'.$ds['clanhp'].'</a></td>
                                </tr>';		
	    }
	    
	    if($ds['server']) {
	    
	        $rowspan_temp = $rowspan_temp+1;
	        $server_temp = '<tr>
                                  <td bgcolor="'.$bg1.'" ><strong>Server</strong></td>
                                  <td bgcolor="'.$bg2.'" >'.$ds['server'].':'.$ds['port'].'</td>
                                </tr>';
	    }
	    
	    $status = ($ds['status']==1 ? '<img src="images/icons/online.gif">' : '<img src="images/icons/offline.gif">');
	    
	    //server
	    
	    if($ds['server'] && $ds['server'] != 'http://' && $ds['server'] != '0') {
	                $br_ext = '<br />';
	    	        $serverinfo = '<br /><b>Server Connect:</b><br><a href="steam://connect:'.$ds['server'].':'.$ds['port'].'"><img src="images/cup/icons/steam.gif" border="0"></a> <a href="hlsw://'.$ds['server'].':'.$ds['port'].'?Connect"><img src="images/cup/icons/hlsw.jpg" border="0"></a> <a href="http://www.gametracker.com/server_info/'.$ds['server'].':'.$ds['port'].'/"><img border="0" src="images/cup/icons/gt.png"></a>';
	    }
            elseif(isleader($userID,$ds['ID'])) {
		        $br_ext = '<br />';
			$serverinfo = '<br /><b>Add Server:</b><br><a href="?site=clans&action=clanedit&clanID='.$ds['ID'].'#editserver"><img border="0" src="images/cup/icons/server_edit.gif"></a>';
	    }
	    
	    
            //management
	    
	    if($ds['status']==1 && !islocked($ds['ID'])) {
	    
	        if(isleader($userID,$ds['ID'])) {
	            $clan_leave = '<a href="?site=clans&action=clanleave&clanID='.$ds['ID'].'" onclick="return confirm(\'Are you sure you want to leave this team?\');"><img src="images/cup/icons/leave.gif"></a>';
	        }
	        elseif(ismember($userID,$ds['ID'])) {
	            $clan_leave = $br_ext.'<a href="?site=clans&action=clanleave&clanID='.$ds['ID'].'" onclick="return confirm(\'Are you sure you want to leave this team?\');"><img src="images/cup/icons/leave.gif"> <strong>Leave Team</strong></a>';
	        }
	        else{
	            $clan_leave = $br_ext.'<a href="?site=clans&action=clanjoin&clanID='.$ds['ID'].'"><img src="images/cup/icons/join.png"> <strong>Join Team</stron></a>';
	        }
	    }
	    
            if(!isleader($userID,$ds['ID'])) {	   
	            $admin = '';
            }
            elseif(islocked($ds['ID'])) {
	    
	            $admin = '<b>Manage:</b><br>
                               <a href="?site=clans&action=show&clanID='.$ds['ID'].'" style="text-decoration:none; cursor:help" onmouseover="showWMTT(\'locked\')" onmouseout="hideWMTT()">
                                <img id="myImage" border="0" src="images/cup/icons/manage.gif"> 
                                <img id="myImage" border="0" src="images/cup/icons/key.png">
                                <img id="myImage" border="0" src="images/cup/icons/invite.gif">
				<img id="myImage" border="0" src="images/cup/icons/leave.gif">
                               </a>';
      

            }
	    elseif($ds['status'] == 0) {
	    
	            $admin = '<b>Manage:</b><br>
                               <a style="text-decoration:none; cursor:help" onmouseover="showWMTT(\'inactive\')" onmouseout="hideWMTT()">
                                <img id="myImage" border="0" src="images/cup/icons/manage.gif"> 
                                <img id="myImage" border="0" src="images/cup/icons/key.png">
                                <img id="myImage" border="0" src="images/cup/icons/invite.gif">
				<img id="myImage" border="0" src="images/cup/icons/leave.gif">
                               </a>';
            }
	    else{	   

	            $admin = '<b>Manage:</b><br>
                               <a href="?site=clans&action=clanedit&clanID='.$ds['ID'].'"><img border="0" src="images/cup/icons/manage.gif"></a> 
                               <a href="?site=clans&action=editpwd&clanID='.$ds['ID'].'"><img border="0" src="images/cup/icons/key.png"></a>
                               <a href="?site=clans&action=invite&clanID='.$ds['ID'].'"><img border="0" src="images/cup/icons/invite.gif"></a>';
	    }
	    
	    //auto join
	    
	   
            if(isleader($userID,$ds['ID'])) {
	    
	        $rowspan_temp = $rowspan_temp+1;
	    
                $join = '<tr>
                           <td bgcolor="'.$bg1.'" ><strong>Team Auto-Join</strong></td>
                           <td bgcolor="'.$bg2.'" ><input type="text" id="txtfld" onClick="SelectAll(\'txtfld\');" style="width:200px" value="http://'.getinput($do['hpurl']).'/?site=clans&amp;action=clanjoin&amp;clanID='.$clanID.'&amp;password='.$ds[password].'"></td>
                         </tr>';	    
	    }
	    
	    //!auto join
	    
	    $skill = ratio_level($ds['ID'],0);
	    
	    if($ds['reg']) {
	    
	        $rowspan_temp = $rowspan_temp+1;
	    
		$reg_temp='<tr>
			<td bgcolor="'.$bg1.'" valign="top"><strong>Joined</strong></td>
			<td bgcolor="'.$bg2.'" >'.date('d/m/Y/', $ds['reg']).'</td>
                      </tr>';
	    }
	    
	       if($_GET['display']!='stats' && $_GET['display']!='leagues') {    
	
		  eval ("\$clans_details = \"".gettemplate("clans_details")."\";");
		  echo $clans_details;
	       }
	    $rowspan_temp++;          
	}	

/* CLAN STATISTICS & AWARDS */

    $checkmatches = safe_query("SELECT * FROM ".PREFIX."cup_matches WHERE (clan1='$clanID' || clan2='$clanID') AND (clan1 != '0' AND clan2 != '0') AND (clan1 != '2147483647' AND clan2 != '2147483647') && 1on1='0'");
    
      if($_GET['display']=='stats') {
      
       if(mysql_num_rows($checkmatches)) {
    
		getclanawards($clanID);
		$award1 = '';
		$award2 = '';
		$award3 = '';
		if($ar_awards[1]){
			for($i=1; $i<=$ar_awards[1]; $i++)
				$award1.='<a href="?site=cups&action=tree&cupID='.$ar1_name[$i-1].'"><img src="images/cup/icons/award_gold.png" border="0" alt="Gold" title="'.getcupname($ar1_name[$i-1]).'" /></a>'; 
		}
		if($ar_awards[2]){
			for($i=1; $i<=$ar_awards[2]; $i++)
				$award2.='<a href="?site=cups&action=tree&cupID='.$ar2_name[$i-1].'"><img src="images/cup/icons/award_silver.png" border="0" alt="'.$_language->module['silver'].'" title="'.getcupname($ar2_name[$i-1]).'" /></a>';
		}
		if($ar_awards[3]){
			for($i=1; $i<=$ar_awards[3]; $i++)
				$award3.='<a href="?site=cups&action=tree&cupID='.$ar3_name[$i-1].'"><img src="images/cup/icons/award_bronze.png" border="0" alt="Bronze" title="'.getcupname($ar3_name[$i-1]).'" /></a>';
		}		
		$awards=$award1.$award2.$award3;
		
		getclanawards_lad($clanID);
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
		$awards_lad=$award1.$award2.$award3;
		
		if(empty($awards) && empty($awards_lad)) {
		         $cups = "<strong>0</strong>";
		}
		else{
		         $cups = $awards.$awards_lad;
		}	
		
		
                if($_GET['cupID']) {
			$league = 'cup';
			$ID = $_GET['cupID'];
			$cup_stats = 1;
			$league_name = getcupname($ID);
                }   
                elseif($_GET['laddID']) {
			$league = 'ladder';
			$ID = $_GET['laddID'];
			$cup_stats = 1;
			$league_name = getladname($ID);
                }
                elseif($_GET['groupID']) {
			$league = 'gs';
			$ID = $_GET['groupID'];
			$cup_stats = 1;
			$league_name = "Group Stages";
                }
                else{
			$league = false;
			$ID = false;
			$cup_stats = 0;
			$league_name = false;
                }    
    
		
	    $totaltotalusermatches = user_cup_points($clanID,$ID,$team=1,$won=0,$lost=0,$type="",$league);
	    $openmatches = user_cup_points($clanID,$ID,$team=1,$won=0,$lost=0,$type="open",$league);
	    $matcheswon = user_cup_points($clanID,$ID,$team=1,$won=1,$lost=0,$type="confirmed",$league);
	    $matcheslost = user_cup_points($clanID,$ID,$team=1,$won=0,$lost=1,$type="confirmed",$league);
	    $confirmedmatches = user_cup_points($clanID,$ID,$team=1,$won=0,$lost=0,$type="confirmed",$league);
	    $matchprotests = user_cup_points($clanID,$ID,$team=1,$won=0,$lost=0,$type="protest",$league);
	    $pendingmatches = user_cup_points($clanID,$ID,$team=1,$won=0,$lost=0,$type="pending",$league);
	    $totalwonpoints = user_cup_points($clanID,$ID,$team=1,$won=1,$lost=0,$type="confirmed_p",$league);
	    $totallostpoints = user_cup_points($clanID,$ID,$team=1,$won=0,$lost=1,$type="confirmed_p",$league);
	    $totaltotalpoints = user_cup_points($clanID,$ID,$team=1,$won=0,$lost=0,$type="confirmed_p",$league);
	    
	    $totalwonperc=percent($confirmedmatches, $totaltotalusermatches, 2);
	    if($totalwonperc) $totalconfirmed=$totalwonperc.'%<br /><img src="images/icons/won.gif" width="30" height="'.round($totalwonperc, 0).'" border="1" alt="'.$_language->module['won'].'" />';
	    else $totalconfirmed=0;
	    
	    $totalloseperc=percent($matchprotests, $totaltotalusermatches, 2);
	    if($totalloseperc) $totalprotest=$totalloseperc.'%<br /><img src="images/icons/lost.gif" width="30" height="'.round($totalloseperc, 0).'" border="1" alt="'.$_language->module['lost'].'" />';
	    else $totalprotest=0;
	    
	    $totaldrawperc=percent($pendingmatches, $totaltotalusermatches, 2);
	    if($totaldrawperc) $totalpending=$totaldrawperc.'%<br /><img src="images/icons/draw.gif" width="30" height="'.round($totaldrawperc, 0).'" border="1" alt="'.$_language->module['draw'].'" />';
	    else $totalpending=0;
	    
	    $totalwonperc=percent($confirmedmatches, $totaltotalusermatches, 2);
	    if($totalwonperc) $totalconfirmed=$totalwonperc.'%<br /><img src="images/icons/won.gif" width="30" height="'.round($totalwonperc, 0).'" border="1" alt="'.$_language->module['won'].'" />';
	    else $totalconfirmed=0;
	    
	    $totalloseperc=percent($matcheslost, $totaltotalusermatches, 2);
	    if($totalloseperc) $totallost=$totalloseperc.'%<br /><img src="images/icons/lost.gif" width="30" height="'.round($totalloseperc, 0).'" border="1" alt="'.$_language->module['lost'].'" />';
	    else $totallost=0;
	    
	    $totaldrawperc=percent($totalwonpoints, $totaltotalpoints, 2);
	    if($totaldrawperc) $scoreratio=$totaldrawperc.'%<br /><img src="images/icons/draw.gif" width="30" height="'.round($totaldrawperc, 0).'" border="1" alt="'.$_language->module['draw'].'" />';
	    else $scoreratio=0;
	    
	    $totalwonperc=percent($matcheswon, $totaltotalusermatches, 2);
	    if($totalwonperc) $totalwon=$totalwonperc.'%<br /><img src="images/icons/won.gif" width="30" height="'.round($totalwonperc, 0).'" border="1" alt="'.$_language->module['won'].'" />';
	    else $totalwon=0;
	    
	    if(mb_substr(basename($_SERVER['REQUEST_URI']),0,9) != "popup.php") {
	            $exp_cnt = '<a href="popup.php?'.pageURL().'"><img src="images/cup/icons/arrow_up_down.gif" align="right"></a>
		                <img id="last_days" src="admin/visitor_statistic_image.php?last=clan&amp;id='.$clanID.'&amp;count=14" width="99%" height="200px" alt="" />';
	    }
	    else{
	            $exp_cnt = '<a href="index.php?'.pageURL().'"><img src="images/cup/icons/arrow_up_down.gif" align="right"></a>
		                <img id="last_days" src="admin/visitor_statistic_image.php?last=clan&amp;id='.$clanID.'&amp;count=31" width="99%" height="200px" alt="" />';
	    }
	    
	      eval ("\$clanwars_stats_total = \"".gettemplate("team_stats")."\";");
	      echo $clanwars_stats_total;		
       }
       else{
              echo 'No match records.';
       }
     }
/* SHOW LEAGUES */

        if($_GET['display']=='leagues') {

              $cups_ft = safe_query("SELECT * FROM ".PREFIX."cup_clans WHERE clanID='$clanID' && 1on1='0' && (cupID!='0' || ladID!='0')");
	        if(mysql_num_rows($cups_ft)) {
		
		          echo '<table width="100%" cellspacing="'.$cellspacing.'" cellpadding="'.$cellpadding.'" bgcolor="'.$border.'">
			         <tr>
			          <td bgcolor="'.$bg2.'" colspan="2"><strong>League</strong></td>
			          <td bgcolor="'.$bg2.'" colspan="2" align="center"><strong>Matches</strong></td>
			          <td bgcolor="'.$bg2.'" colspan="3" align="center"><strong>Activity</strong></td>
			        </tr>';
		}
		else{
		
		          echo '<tr>
			          <td bgcolor="'.$bg1.'" colspan="4">No leagues</td>
			        </tr>';
		}
		
	         while($ds = mysql_fetch_assoc($cups_ft)) {
		 
		    if($ds['type']=='ladder') {
		            $tit = "Ladder";
		            $cupID = $ds['ladID'];
			    $typee = 'laddID';
			    $type5 = 'ID';
			    $pic = '<img src="images/cup/icons/ladder.png">';			    
		            $mtc_league1 = '<a href="?site=matches&action=viewmatches&'.$typee.'='.$cupID.'">';
			    $mtc_league2 = '<a href="?site=matches&action=viewmatches&'.$typee.'='.$cupID.'&clanID='.$clanID.'">';			    
			    $league = '<a href="?site=ladders&ID='.$cupID.'">'.getladname($cupID).'</a>';
		    }
		    elseif($ds['type']=='cup') {
		            $tit = "Tournament";
		            $cupID = $ds['cupID'];
			    $typee = 'cupID';
			    $type5 = 'cupID';
			    $pic = '<img src="images/cup/icons/tournament.png">';			    
		            $mtc_league1 = '<a href="?site=matches&action=viewmatches&'.$typee.'='.$cupID.'">';
			    $mtc_league2 = '<a href="?site=matches&action=viewmatches&'.$typee.'='.$cupID.'&clanID='.$clanID.'">';			    
			    $league = '<a href="?site=cups&action=details&cupID='.$cupID.'">'.getcupname($cupID).'</a>';
		    }
		    elseif($ds['type']=='gs' && $ds['ladID'] && $ds['cupID']==0) {
		            $tit = "Group";
		            $cupID = $ds['groupID'];
			    $typee = 'laddID';
			    $type5 = 'ID';
			    $pic = '<img src="images/cup/icons/groups.png">';			    
		            $mtc_league1 = '<a href="?site=groups&cupID='.$cupID.'">';
			    $mtc_league2 = '<a href="?site=matches&action=viewmatches&clanID='.$clanID.'&type=gs">';			    
			    $league = '<a href="?site=ladders&ID='.$cupID.'">'.getladname($cupID).'</a>';
		    }
		    elseif($ds['type']=='gs' && $ds['cupID'] && $ds['ladID']==0) {
		            $tit = "Group";
		            $cupID = $ds['groupID'];
			    $typee = 'cupID';
			    $type5 = 'cupID';
			    $pic = '<img src="images/cup/icons/groups.png">';			    
		            $mtc_league1 = '<a href="?site=groups&cupID='.$cupID.'">';
			    $mtc_league2 = '<a href="?site=matches&action=viewmatches&clanID='.$clanID.'&type=gs">';			    
			    $league = '<a href="?site=cups&action=details&cupID='.$cupID.'">'.getcupname($cupID).'</a>';
		    }
		    
		    if($cupID AND $typee AND $pic AND $league AND $type5)		 
	
	              echo '<tr>
			      <td bgcolor="'.$bg1.'" colspan="2">'.$pic.' '.$league.'</td>
			      <td bgcolor="'.$bg1.'" align="center"><img src="images/cup/icons/add_result.gif" width="16" height="16"> '.$mtc_league1.' '.$tit.' Matches</a></td>
			      <td bgcolor="'.$bg1.'" align="center"><img src="images/cup/icons/add_result.gif" width="16" height="16"> '.$mtc_league2.' Team Matches</a></td>
			      <td bgcolor="'.$bg1.'" colspan="3" align="center"><a href="?site=clans&action=show&clanID='.$clanID.'&'.$typee.'='.$cupID.'&display=stats"><img border="0" src="images/cup/icons/points.png" border="0"> Stats</a> <a href="?site=clans&action=show&clanID='.$clanID.'&'.$type5.'='.$cupID.'"><img src="images/icons/foldericons/folder.gif"> Details</a></td>
			    </tr>';
			
		    	    
		 } 
              echo '</table><br />'; 
        }
		
/* TEAM MEMBERS */

if($_GET['display']!='stats' && $_GET['display']!='leagues') {

$sql_members = safe_query("SELECT * FROM ".PREFIX."cup_clan_members WHERE clanID = '$clanID' ORDER BY function ASC");
  if(mysql_num_rows($sql_members)) {
  
     if($_GET['cupID']) {
        $mylineup_show_hd = '<td class="title" align="center"><img src="images/cup/icons/random.png"> Lineup</td>';
     }
  
     echo '
       <table border="0" width="100%" cellspacing="'.$cellspacing.'" cellpadding="'.$cellpadding.'" bgcolor="'.$border.'">	
         <tr>
           <td class="title"><img src="images/cup/icons/yourteams.png" width="16" height="16"> Members</td>
           <td class="title"><img src="images/cup/icons/support.png" width="16" height="16"> Gameaccounts</td>
           <td class="title" align="center"><img src="images/cup/icons/date.png" width="16" height="16"> Joined</td>
           <td class="title" align="center"><img src="images/cup/icons/rank.gif"> Rank</td>
	   '.$mylineup_show_hd.'
         </tr>';
  }
	
    while($dv=mysql_fetch_array($sql_members)) {
	
	$up= mysql_fetch_array(safe_query("SELECT leader FROM ".PREFIX."cup_all_clans WHERE ID='".$clanID."'"));
	$up2=mysql_fetch_array(safe_query("SELECT registerdate FROM ".PREFIX."user WHERE userID='".$dv['userID']."'"));
	
	    $player = getusercountry($dv['userID'],0).'&nbsp;<a href="?site=profile&id='.$dv['userID'].'"><strong>'.getnickname($dv['userID']).'</strong></a>';
	    $joined = ($dv['reg'] ? date('d/m/Y', $dv['reg']).' *' : date('d/m/Y', $up2[registerdate]));

            if(isonline($dv['userID'])=='offline') {
                  $player.='<div style="margin-top: -24px;"><img title="Offline" src="images/icons/offline.gif" align="right"></div>';
            }
            else{
                  $player.='<div style="margin-top: -24px;"><img title="Online" src="images/icons/online.gif" align="right"></div>';
            }	    
	    
	    if($dv['agent']==1) {
	                $rank = '<font color="'.$loosecolor.'"><strong>Agent</strong></font>';
	    }	    
	    elseif($up['leader']==$dv['userID']) {
	                $rank = 'Owner';
	    }
	    elseif(isleader($dv['userID'],$clanID)) {
	                $rank = 'Leader';
	    }
	    elseif(ismember($dv['userID'],$clanID)) {
	                $rank = 'Member';
	    }

	    $gameacclog = safe_query("SELECT * FROM ".PREFIX."user_gameacc WHERE userID='".$dv['userID']."' && log='1'");
            
	    if(mysql_num_rows($gameacclog)){ 
			$dp=mysql_fetch_array($gameacclog);  
                        $islog = '<a href="?site=profile&id='.$dv['userID'].'&gameacc=changelog#seegameaccounts" style="text-decoration:none; cursor:help" onmouseover="showWMTT(\'inlog\')" onmouseout="hideWMTT()"><img src="images/cup/icons/warning.png" border="0" width="16" height="16" align="right"></a>'; 
            }
	    else
                        $islog = '';
    
		$gameacc_sql = safe_query("SELECT type, value FROM ".PREFIX."user_gameacc WHERE userID = '".$dv['userID']."' && log='0'");
		$game_acc = '';
		if(mysql_num_rows($gameacc_sql)){
			$num=1;
			while($db=mysql_fetch_array($gameacc_sql)){            				
				$gameacc_sql2 = safe_query("SELECT * FROM ".PREFIX."gameacc WHERE gameaccID = '".$db['type']."'");
				$dr=mysql_fetch_array($gameacc_sql2);
							
				if($num==1)
					$game_acc.=' - '.$dr['type'].': '.$db['value'];
				else
					$game_acc.='<br /> - '.$dr['type'].': '.$db['value'];
				$num++;
			}
		}
		else
			$game_acc='Not Entered';
	
		$gameaccount = $game_acc;		
		$bg1_sh = ($userID==$dv['userID'] ? $bg2 : $bg1);
		
		if($_GET['cupID']) {
                       $dz=mysql_fetch_array(safe_query("SELECT userID FROM ".PREFIX."cup_clan_lineup WHERE userID='".$dv['userID']."' && cupID='".$_GET['cupID']."' && clanID = '".$ds['ID']."'"));
                       $in_lineup = ($dv['userID']==$dz['userID'] ? '<img src="images/cup/success.png">' : '<img src="images/cup/error.png" width="16" height="16">');				        
		       $mylineup_show = '<td bgcolor="'.$bg1_sh.'" align="center">'.$in_lineup.'</td>'; 
	        }			
		
		eval ("\$clans_member = \"".gettemplate("clans_member")."\";");
		echo $clans_member;
		unset($game_acc);
    }
                echo '</table><BR />';
}	
       
/* PENALTY POINTS (AT TEAM SHOW -- UNDER CLANDETAILS*/
	
	$all_points = getteampenaltypoints($clanID);	
	$ergebnis2 = safe_query("SELECT * FROM ".PREFIX."cup_warnings WHERE clanID = '$clanID' && expired='0' && 1on1='0' ORDER BY time DESC");	
	$warn_num = mysql_num_rows($ergebnis2); 
	if($warn_num){
		echo '<a name="points"></a>
		  <table width="100%" bgcolor="'.$border.'">
			 <tr>
			  <td class="title" colspan="8"><img src="images/cup/icons/deduct.png"> Penalty Points ('.$all_points.' Total Points)</td>
		    </tr>    
		     <tr>
		      <td class="title2" align="center">Points</td>
		      <td class="title2" align="center">Added by</td>
		      <td class="title2" align="center">Title</td>
		      <td class="title2" align="center">Description</td>
		      <td class="title2" align="center">Match Link</td>
		      <td class="title2" align="center">Added</td>
		      <td class="title2" align="center">Expires</td>
		    </tr>';
	
		$n=1;
		while($dr=mysql_fetch_array($ergebnis2)) {
			echo '
					<tr>
						<td align="center" bgcolor="'.$bg1.'">'.$dr['points'].'</td>
						<td align="center" bgcolor="'.$bg2.'"><a href="?site=profile&id='.$dr['adminID'].'">'.getnickname($dr['adminID']).'</a></td>
						<td align="center" bgcolor="'.$bg1.'">'.$dr['title'].'</td>
						<td align="center" bgcolor="'.$bg2.'">'.$dr['desc'].'</td>
						<td align="center" bgcolor="'.$bg1.'"><a href="'.$dr['matchlink'].'">(URL)</a></td>
						<td align="center" bgcolor="'.$bg2.'">'.date('d/m/Y H:i', $dr['time']).'</td>
						<td align="center" bgcolor="'.$bg1.'">'.date('d/m/Y H:i', $dr['deltime']).'</td>			
					</tr>
				 ';
			$n++;
		}
		echo '</table><br />';
	}
	
//team comments (v4.1.5a)

  $comments = safe_query("SELECT ID, comment FROM ".PREFIX."cup_all_clans WHERE ID='$clanID'");
  while($dd=mysql_fetch_array($comments)) {
	
		$parentID = $clanID;
		$comments_allowed = $dd['comment'];
		$type = "tc";
		$referer = "index.php?site=clans&action=show&clanID=$clanID";

		include("comments.php");
	  
	    echo $inctemp; 
   }
}else{

/* SHOWING TEAMS FROM CUP (?site=clans&cupID=)*/

	$cupID = (isset($_GET['cupID']) ? mysql_real_escape_string($_GET['cupID']) : 0);
	
	if($cupID){
	 
	        $cupname = getcupname($cupID);

                include ("title_cup.php");
		
		if(is1on1($cupID)) $participants = 'Players';	
		else $participants = 'Teams';
	
		eval ("\$title_cup = \"".gettemplate("title_cup")."\";");
		echo $title_cup;
		
		if(is1on1($cupID)){

        
                    $temp_1on1 = 'Players';
    
			eval ("\$one_head = \"".gettemplate("1on1_head")."\";");
			echo $one_head;
			
			$ergebnis = safe_query("SELECT * FROM ".PREFIX."cup_clans WHERE cupID = '$cupID' && 1on1='1' ORDER BY ID ASC");
			$i = 1;
			
			while($db=mysql_fetch_array($ergebnis)) {
				if($n%2){
					$bg1=BG_1;
					$bg2=BG_2;
				}else{
					$bg1=BG_3;
					$bg2=BG_4;
				}
				
				$ergebnis2 = safe_query("SELECT * FROM ".PREFIX."user WHERE userID='".$db['clanID']."'");
				$ds=mysql_fetch_array($ergebnis2);
				
				
				$country='[flag]'.getcountry($ds['userID']).'[/flag]';
				$country=flags($country);
				$nickname='<a href="?site=profile&id='.$ds['userID'].'"><b>'.getnickname($ds['userID']).'</b></a>';
				
				$ergebnis2 = safe_query("SELECT * FROM ".PREFIX."cups WHERE ID = $cupID");		
			        $cup=mysql_fetch_array($ergebnis2);
				
				$gameacclog = safe_query("SELECT * FROM ".PREFIX."user_gameacc WHERE type='".$cup['gameaccID']."' && userID='".$ds['userID']."' && log='1'");
                
		                if(mysql_num_rows($gameacclog)){ 
					$dp=mysql_fetch_array($gameacclog);  
                                        $islog = '<a href="?site=profile&id='.$ds['userID'].'&gameacc=changelog#seegameaccounts" style="text-decoration:none; cursor:help" onmouseover="showWMTT(\'inlog\')" onmouseout="hideWMTT()"><img src="images/cup/icons/warning.png" border="0" width="16" height="16" align="right"></a>'; 
                                }
				else
                                        $islog = '';
	
				$gameacc_sql = safe_query("SELECT * FROM ".PREFIX."user_gameacc WHERE type='".$cup['gameaccID']."' AND userID='".$ds['userID']."' && log='0'");
				
				if(mysql_num_rows($gameacc_sql)){
					$dl=mysql_fetch_array($gameacc_sql);
					$game_acc = $dl['value'];
				}else
					$game_acc = $_language->module['not_registered'];
							
				if($db['checkin'])
					$checkin = 'Checked-In';
				else
					$checkin = 'Not Checked-In';

                                $userpoints = '<a href="?site=profile&id='.$ds['userID'].'#points">'.getuserpenaltypoints($ds['userID']).'</a>';
				
				eval ("\$one_content = \"".gettemplate("1on1_content")."\";");
				echo $one_content;
				$i++;
			}
			eval ("\$one_foot = \"".gettemplate("1on1_foot")."\";");
			echo $one_foot;
		  
		}
		else
		    {
		
			$ergebnis = safe_query("SELECT * FROM ".PREFIX."cups WHERE ID='$cupID'");
                        $ds=mysql_fetch_array($ergebnis);
			
                        $status = $ds['status'];
            
                        $notchecked = safe_query("SELECT * FROM ".PREFIX."cup_clans WHERE cupID='$cupID' && checkin='0'");
	                $num=mysql_num_rows($notchecked);
            
                        $start = date('d/m/Y \a\t H:i', $ds['start']).'';
                        $checkin = $ds['checkin'];
                        $checkindate = date('H:i', ($ds['start']-($ds['checkin']*60)));			
			$checkin_begin = date('d/m/Y H:i', ($ds['start']-($ds['checkin']*60)));
			
        $participant_ent = safe_query("SELECT cupID FROM ".PREFIX."cup_clans WHERE groupID='".$cupID."' AND type='gs' AND ladID='0'");
        $ent_part = mysql_num_rows($participant_ent);
	
	if($ent_part) {
	     echo '<div '.$error_box.'><img src="images/cup/icons/info.gif" width="16" height="16"> Click <a href="?site=groups&cupID='.$cupID.'">here</a> to view group league participants.</div>';	
        }
	elseif($status == 1 && $num && $ds['status']==1 && $ds['gs_start'] <= time()) {
             echo '<div '.$error_box.'><img src="images/cup/icons/info.gif" width="16" height="16"> Checkin begins at <b>'.$checkin_begin.'</b> '.$gmt.'</b></div>'; 
        }
    
        $temp_1on1 = 'Teams';

	$ergebnis = safe_query("SELECT * FROM ".PREFIX."cup_clans WHERE cupID = '$cupID' ORDER BY ID ASC");		
	$i = 1;
	
		if(mysql_num_rows($ergebnis)) {
		
		       $show_lineup_hd = '<td bgcolor="'.$bg1.'" align="center"><b>Lineup</b></td>';
		
	               eval ("\$team_head = \"".gettemplate("1on1_head")."\";");
	               echo $team_head;
		}
		else{
	               echo '<strong>No registered participants.</strong>';	
		}
			
			while($db=mysql_fetch_array($ergebnis)) {
				
				$ergebnis2 = safe_query("SELECT * FROM ".PREFIX."cup_all_clans WHERE ID = '".$db['clanID']."' ORDER BY name ASC");		
				$ds=mysql_fetch_array($ergebnis2);


				$country = '<a href="?site=clans&action=show&clanID='.$ds['ID'].'&cupID='.$cupID.'">'.flags('[flag]'.getclancountry($ds['ID']).'[/flag]').'</a>';
				$nickname = '<a href="?site=clans&action=show&clanID='.$ds['ID'].'&cupID='.$cupID.'">'.$ds['name'].'</a>';   
		        	        

				if($ds['server'] && $ds['server'] != 'http://' && $ds['server'] != '0')
					$serverinfo = '<b>Server Connect:</b><br><a href="xfire:join?game=cod4mp&server='.$ds['server'].':'.$ds['port'].'"><img src="images/cup/icons/xfire.png" border="0"></a> <a href="steam://connect:'.$ds['server'].':'.$ds['port'].'"><img src="images/cup/icons/steam.gif" border="0"></a> <a href="hlsw://'.$ds['server'].':'.$ds['port'].'?Connect"><img src="images/cup/icons/hlsw.jpg" border="0"></a> <a href="http://www.gametracker.com/server_info/'.$ds['server'].':'.$ds['port'].'/"><img border="0" src="images/cup/icons/gt.png"></a>';
				elseif(!isleader($userID,$ds['ID'])) 
					$serverinfo = '';
                                else
					$serverinfo = '<b>Add Server:</b><br><a href="?site=clans&action=clanedit&clanID='.$ds['ID'].'#editserver"><img border="0" src="images/cup/icons/server_edit.gif"></a>';
					
		                $password = $ds[password];
		                $clanhp = $ds[clanhp];

                                $sql_members = safe_query("SELECT * FROM ".PREFIX."cup_clan_members WHERE clanID = '".$db['clanID']."'");	
		                $members = mysql_num_rows($sql_members);

    if(ismember($userID,$ds['ID'])) $details = '
      <a href="?site=clans&action=show&clanID='.$ds['ID'].'&cupID='.$cupID.'"><img border="0" src="images/icons/foldericons/folder.gif" border="0"></a>
      <a href="?site=messenger&action=touser&touser='.$ds['leader'].'"><img src="images/cup/icons/pm.png" border="0"></a>
      <a href="?site=matches&action=viewmatches&clanID='.$ds['ID'].'&cupID='.$cupID.'"><img src="images/cup/icons/add_result.gif" border="0" width="16" height="16"></a>
      <a href="?site=clans&action=clanleave&clanID='.$ds['ID'].'" onclick="return confirm(\'Are you sure you want to leave this team?\');"><img src="images/cup/error.png" width="16" height="16" border="0"></a>';

    elseif(isleader($userID,$ds['ID'])) $details = '
      <a href="?site=clans&action=show&clanID='.$ds['ID'].'&cupID='.$cupID.'"><img border="0" src="images/icons/foldericons/folder.gif"></a> 
      <a href="?site=clans&action=clanedit&clanID='.$ds['ID'].'"><img border="0" src="images/cup/icons/manage.gif"></a> 
      <a href="?site=clans&action=editpwd&clanID='.$ds['ID'].'"><img border="0" src="images/cup/icons/key.png"></a>
      <a href="?site=clans&action=invite&clanID='.$ds['ID'].'&cupID='.$cupID.'"><img border="0" src="images/cup/icons/invite.gif"></a>
      <a href="?site=matches&action=viewmatches&clanID='.$ds['ID'].'&cupID='.$cupID.'"><img src="images/cup/icons/add_result.gif" border="0" width="16" height="16"></a>
      <a href="?site=clans&action=leavecup&cupID='.$cupID.'&clanID='.$ds['ID'].'" onclick="return confirm(\'Are you sure you want '.getclanname($ds[ID]).' to leave the '.getcupname($cupID).' cup?\');"><img border="0" src="images/cup/error.png" width="16" height="16"></a>';

    else $details = '
      <a href="?site=matches&action=viewmatches&clanID='.$ds['ID'].'&cupID='.$cupID.'"><img src="images/cup/icons/add_result.gif" border="0" width="16" height="16"></a>
      <a href="?site=clans&action=show&clanID='.$ds['ID'].'&cupID='.$cupID.'"><img border="0" src="images/icons/foldericons/folder.gif"></a> 
      <a href="?site=clans&action=clanjoin&clanID='.$ds['ID'].'"><img border="0" src="images/cup/icons/join.png" width="16" height="16"></a>';

	$ergebnis2=safe_query("SELECT status FROM ".PREFIX."cup_all_clans WHERE ID = '".$ds[ID]."'");
	$dm=mysql_fetch_array($ergebnis2);

        if(!$dm['status'])
		$details = $_language->module['team_inactive'];
		
  //check team if locked
		
		elseif(islocked($ds['ID']))
		$details = $_language->module['team_locked_cup'];
		
  //how many in lineup
  
  $lineup = safe_query("SELECT * FROM ".PREFIX."cup_clan_lineup WHERE cupID='$cupID' && clanID='".$ds[ID]."'");
  $checklineup = mysql_num_rows($lineup); $thelineup = mysql_num_rows($lineup)+$membersin+1;
  
  $linedmembers = '<a href="?site=clans&action=show&clanID='.$ds['ID'].'&cupID='.$cupID.'#members">'.$checklineup.'</a>';
		
  //leader's live contact and team logo
                      
                $id=$ds['leader'];
                include ("livecontact.php");
		        $leader = '<a href="?site=profile&id='.$ds[leader].'">'.getnickname($ds['leader']).'</a><br><a href="?site=profile&id='.$ds['leader'].'">'.$email.'</a> '.$pm .$buddy .$icq .$skype .$xfirec .$steam .$msn .$aim .$yahoo.' <a href="index.php?site=matches&action=viewmatches&memberID='.$id.'" target="_top"><img border="0" src="images/cup/icons/add_result.gif" width="18" height="18"></a>';
		        
				if($ds['clanlogo'] && $ds['clanlogo']!='http://' && $ds['clanlogo']!='http://google.com')
					$clanlogo = '<img src="'.$ds['clanlogo'].'" alt="n/a" border="0" height="100" vspace="3" width="100">';
				else
					$clanlogo = '<img src="images/avatars/noavatar.gif" alt="n/a" border="0" height="100" vspace="5" width="100">';
				
  //cup and team status
  				
				if($db['checkin'])
					$status2 = '<img src="images/icons/online.gif" border="0" alt="" title="Accepted" />';
				elseif(isleader($userID,$ds['ID']) && $dm['status']== 1)
					$status2 = '<a href="?site=clans&action=autocheckin&cupID='.$cupID.'&clanID='.$ds['ID'].'" onclick="return confirm(\'Are you sure you want '.getclanname($ds[ID]).' to be checked in the '.getcupname($cupID).' cup? If success, you will be unable to leave the cup. Make sure your team is ready by '.$start.' '.$gmt.'\');" name="pending" style="text-decoration:none; cursor:help" onmouseover="showWMTT(\'unchecked\')" onmouseout="hideWMTT()"><img src="images/icons/offline.gif" border="0" alt="" title="Not Accepted"  /> <font color="black"><b>(?)</b></font></a>'; 
                else
                    $status2 = '<a name="pending" style="text-decoration:none; cursor:help" onmouseover="showWMTT(\'unchecked_notleader\')" onmouseout="hideWMTT()"><img src="images/icons/offline.gif" border="0" alt="" title="Not Accepted"  /> <font color="black"><b>(?)</b></font></a>';

				if($dm['status']== 0)
		            $status1 = '<font color="#ff0000"><b>Team Inactive</b></font>';
				elseif(isleader($userID,$ds['ID']) && $db['checkin'] && !islocked($ds['ID']))
					$status1 = '<p class="textborder"><font color="#1cac00"><b>Competing</b> - your team is entered and checked, goodluck! <img src="images/smileys/aug.gif"></font></p>';
				elseif(ismember($userID,$ds['ID']) && $db['checkin'] && !islocked($ds['ID']))
					$status1 = '<p class="textborder"><font color="#1cac00"><b>Competing</b> - your team is entered and checked, goodluck! <img src="images/smileys/aug.gif"></font></p>';
				elseif(islocked($ds['ID']))
				    $status1 = '<font color="red"><b>Team Locked</b></font> <a href="?site=clans&action=show&clanID='.$ds['ID'].'#points" name="locked" style="text-decoration:none; cursor:help" onmouseover="showWMTT(\'locked\')" onmouseout="hideWMTT()"><font color="black"><b><blink>(?)</blink></b></font></a>';		   
				elseif(!islocked($ds['ID']) && $db['checkin'])
					$status1 = '<font color="#1cac00"><b>Competing</b> - team entered and checked! <img src="images/smileys/aug.gif"></font>';					
                else
					$status1 = '<font color="#FF6600"><b>Pending</b> - team not checked into this cup yet.</font>';  
                                if(ismember($userID,$ds['ID']) && $dm['status']== 1 && $db['checkin']==0 && !islocked($ds['ID']))
                                        $status1 = '<p class="tb_pending"><font color="#FF6600"><b>Pending</b> - your team is not checked in yet. </font></p>'; 
                                elseif(isleader($userID,$ds['ID']) && $dm['status']== 1 && $db['checkin']==0 && !islocked($ds['ID'])) 
                                        $status1 = '<p class="tb_pending"><font color="#FF6600"><b>Pending</b> - your team is not checked in yet. </font><a href="?site=clans&action=autocheckin&cupID='.$cupID.'&clanID='.$ds['ID'].'" onclick="return confirm(\'Are you sure you want '.getclanname($ds[ID]).' to be checked in the '.getcupname($cupID).' cup? If success, you will be unable to leave the cup. Make sure your team is ready by '.$start.' '.$gmt.'\');"><blink>(try auto-checkin?)</blink></a></p>';
                                elseif(isleader($userID,$ds['ID']) && $dm['status']== 0) 
                                        $status1 = '<a href="?site=clans&action=leavecup&cupID='.$cupID.'&clanID='.$ds['ID'].'"><img border="0" src="images/cup/error.png" width="16" height="16"></a> Please contact an admin to reinstate your team.';			

//check average

 if($_GET['cupID']) {
 
    if(is1on1($_GET['cupID']))
    $p_typ = 0; else $p_type = 1;

	$user_p = user_cup_points($ds['ID'],$_GET['cupID'],$team=$p_type,$won=0,$lost=0,$type="confirmed_p",$league);
	$avg_points = round(average_cup_points($_GET['cupID']));
	
	$user_points=(empty($user_p) ? "+0" : "+$user_p");
				
    if(empty($user_p) || ($ds['tp']==$startupcredit && $ds['credit']==$startupcredit))
        $average = '<img src="images/cup/icons/na.png" width="16" height="16">';
    elseif($user_p == round($avg_points))  
        $average = '<img src="images/cup/icons/na.png" width="16" height="16"><img src="images/cup/icons/na.png" width="16" height="16">';
    elseif($user_p <= round($avg_points/1.3))  
        $average = '<img src="images/cup/icons/nok_32.png" width="16" height="16"><img src="images/cup/icons/nok_32.png" width="16" height="16">';
    elseif($user_p <= round($avg_points/1.1))  
        $average = '<img src="images/cup/icons/nok_32.png" width="16" height="16"><img id="myImage" src="images/cup/icons/nok_32.png" width="16" height="16">';
    elseif($user_p >= round($avg_points*1.3))
        $average = '<img src="images/cup/icons/ok_32.png" width="16" height="16"><img src="images/cup/icons/ok_32.png" width="16" height="16">';
    elseif($user_p >= round($avg_points*1.1))
        $average = '<img src="images/cup/icons/ok_32.png" width="16" height="16"><img id="myImage" src="images/cup/icons/ok_32.png" width="16" height="16">';
    else
        $average = '<img src="images/cup/icons/na.png" width="16" height="16"><img id="myImage" src="images/cup/icons/na.png" width="16" height="16">';
        
        $avg = '<tr>
                 <td bgcolor="'.$bg1.'"><strong>Average:</strong></td>
                 <td bgcolor="'.$bg1.'">'.$average.' ('.$user_points.')</td>  
                </tr>';    
			
 }
				if($db['checkin'])
					$checkin = 'Checked-In';
				else
					$checkin = 'Not Checked-In';
					
                                $members=safe_query("SELECT userID FROM ".PREFIX."cup_clan_members WHERE clanID = '".$ds['ID']."'");
		                  while($dv=mysql_fetch_array($members)) { 
				    $ds3=mysql_fetch_array(safe_query("SELECT count(*) as usergameacc FROM ".PREFIX."user_gameacc WHERE userID='".$dv['userID']."' && log='0'"));
                                       $cnt_usergameacc = $ds3['usergameacc'];				    
				}
				
				$show_lineup_cnt = '<td bgcolor="'.$bg1.'" align="center">'.$linedmembers.' / '.mysql_num_rows($members).'</td>';
					
				$userpoints = getteampenaltypoints($ds['ID']);
				$game_acc = (empty($cnt_usergameacc) ? "0" : $cnt_usergameacc)." / ".mysql_num_rows($members);
					
				eval ("\$clans = \"".gettemplate("1on1_content")."\";");
				echo $clans;
				if(!($i%4))
					echo '';
				$i++;
			}
			echo '</table>';
		}  
		echo $inctemp;
		
/* SHOWING TEAMS FROM CLANS PAGE (?site=clans)*/
 		
	}else{
		if(!$_POST){

  //if cup admin, show add clan button

        if(iscupadmin($userID)) echo'<input type="button" onclick="MM_openBrWindow(\'admin/admincenter.php?site=clans&action=addclan&cupID='.$cupID.'\',\'New Team\',\'toolbar=no,status=no,scrollbars=yes,width=800,height=550\');" value="Create Team" /><br>';

  //echo table
			echo '<table width="100%" cellspacing="'.cellspacing.'" cellpadding="'.$cellpadding.'" bgcolor="'.$border.'">';
			$ergebnis2 = safe_query("SELECT ID, name, country, clantag, clanhp, leader, clanlogo, password, server, port, status FROM ".PREFIX."cup_all_clans ORDER BY name ASC");		
			$i = 1;
			while($ds=mysql_fetch_array($ergebnis2)){ 
			
  //get team limit (per page) for pagination from admin settings

			$ergebnis = safe_query("SELECT * FROM ".PREFIX."cup_settings WHERE cupteamlimit");	
	        while($db = mysql_fetch_array($ergebnis)) 
            $cupteamlimit = $db['cupteamlimit']; 

  //including team and username search forms
  
   echo '
    <form action="index.php" method="get">
      <input type="hidden" name="site" value="teamsearch">
      <input type="text" name="search" />
       Search By: 
        <select name="type">
          <option value="name" selected="selected">Team Name</option>
          <option value="short">Bracket Name</option>
          <option value="clantag">Clan Tag</option>
	  <option value="username">Username</option>
        </select>
      <input type="submit" value="Search" />
    </form><br /><br />';
    

/* PAGINATION*/

  // database connection info
include("_mysql.php");
$conn = mysql_connect($host, $user, $pwd) or system_error('ERROR: Can not connect to MySQL-Server');
$db = mysql_select_db($db, $conn) or trigger_error("SQL", E_USER_ERROR);

// find out how many rows are in the table 
$ergebnis2 = "SELECT COUNT(*) FROM ".PREFIX."cup_all_clans $alpha";
$result = mysql_query($ergebnis2, $conn) or trigger_error("SQL", E_USER_ERROR);
$r = mysql_fetch_row($result);
$numrows = $r[0];

// number of rows to show per page (limit from admin)
$rowsperpage = $cupteamlimit;
// find out total pages
$totalpages = ceil($numrows / $rowsperpage);

// get the current page or set a default
if (isset($_GET['currentpage']) && is_numeric($_GET['currentpage'])) {
   // cast var as int
   $currentpage = (int) $_GET['currentpage'];
} else {
   // default page num
   $currentpage = 1;
} // end if

// if current page is greater than total pages...
if ($currentpage > $totalpages) {
   // set current page to last page
   $currentpage = $totalpages;
} // end if
// if current page is less than first page...
if ($currentpage < 1) {
   // set current page to first page
   $currentpage = 1;
} // end if

// the offset of the list, based on current page 
$offset = ($currentpage - 1) * $rowsperpage;

// get the info from the db 
$ergebnis2 = "SELECT * FROM ".PREFIX."cup_all_clans $alpha ORDER BY name ASC LIMIT $offset, $rowsperpage";
$result = mysql_query($ergebnis2, $conn) or trigger_error("SQL", E_USER_ERROR);


/******  build the pagination links ******/
// range of num links to show
$range = 3;

// if not on page 1, don't show back links
if ($currentpage > 1) {
   // show << link to go back to page 1
   echo "<ul id='pagination-digg'><a class='previous-off' href='?site=clans&currentpage=1&v=$letter#alpha'>&laquo; First</a></ul>";
   // get previous page num
   $prevpage = $currentpage - 1;
   // show < link to go back to 1 page
   echo "<ul id='pagination-digg'><a class='previous-off' href='?site=clans&currentpage=$prevpage&v=$letter#alpha'>&lsaquo; Back</a></ul>";
} // end if 

// loop to show links to range of pages around current page
for ($x = ($currentpage - $range); $x < (($currentpage + $range) + 1); $x++) {
   // if it's a valid page number...
   if (($x > 0) && ($x <= $totalpages) && $numrows > $rowsperpage) {
      // if we're on current page...
      if ($x == $currentpage) {
         // 'highlight' it but don't make a link
         echo "<ul id='pagination-digg'><a class='active'><font color='white'><b>$x</b></font></a></ul>";
      // if not current page...
      } else {
         // make it a link
         echo "<ul id='pagination-digg'><a class='pagination-digg' href='?site=clans&currentpage=$x&v=$letter#alpha'> $x</a></ul>";
      } // end else
   } // end if 
} // end for
                 
// if not on last page, show forward and last page links        
if ($currentpage != $totalpages) {
   // get next page
   $nextpage = $currentpage + 1;
    // echo forward link for next page 
   echo "<ul id='pagination-digg'><a class='next' href='?site=clans&currentpage=$nextpage&v=$letter#alpha'>Next &rsaquo;</a></ul>";
   // echo forward link for lastpage
   echo "<ul id='pagination-digg'><a class='next' href='?site=clans&currentpage=$totalpages&v=$letter#alpha'>Last &raquo;</a></ul>";
} // end if
/****** end build pagination links ******/

if(!$team_list) {

echo '<a name="alpha"></a>
        <tr>
	  <td class="title" colspan="4" align="center"><strong>
	                                                <a href="?site=clans&v=1#alpha">#</a> -
	                                                <a href="?site=clans&v=a#alpha">A</a> - <a href="?site=clans&v=b#alpha">B</a> - <a href="?site=clans&v=c#alpha">C</a> - <a href="?site=clans&v=d#alpha">D</a> - <a href="?site=clans&v=e#alpha">E</a> - 
	                                                <a href="?site=clans&v=f#alpha">F</a> - <a href="?site=clans&v=g#alpha">G</a> - <a href="?site=clans&v=h#alpha">H</a> - <a href="?site=clans&v=i#alpha">I</a> - <a href="?site=clans&v=j#alpha">J</a> - 
							<a href="?site=clans&v=k#alpha">K</a> - <a href="?site=clans&v=l#alpha">L</a> - <a href="?site=clans&v=m#alpha">M</a> - <a href="?site=clans&v=n#alpha">N</a> - <a href="?site=clans&v=o#alpha">O</a> - 
							<a href="?site=clans&v=p#alpha">P</a> - <a href="?site=clans&v=q#alpha">Q</a> - <a href="?site=clans&v=r#alpha">R</a> - <a href="?site=clans&v=s#alpha">S</a> - <a href="?site=clans&v=t#alpha">T</a> - 
							<a href="?site=clans&v=u#alpha">U</a> - <a href="?site=clans&v=v#alpha">V</a> - <a href="?site=clans&v=w#alpha">W</a> - <a href="?site=clans&v=x#alpha">X</a> - <a href="?site=clans&v=y#alpha">Y</a> - 
							<a href="?site=clans&v=z#alpha">Z</a> - <a href="?site=clans&v=1#alpha">#</a></td>
						       </strong>
	</tr>
        <tr>
          <td class="title2">Team</td>
	  <td class="title2" align="center">Registered</td>
          <td class="title2" align="center">Awards</td>
          <td class="title2" align="center">Skill</td>
        </tr>';

}

// while there are rows to be fetched...

if(!mysql_num_rows($result)) echo '<tr><td bgcolor="'.$loosecolor.'" colspan="4">No teams found.</td></tr>';

while($ds=mysql_fetch_assoc($result)) {

/* END PAGINATION AND SHOWING TEAMS FROM CLANS PAGE (RESUME)*/


  //members in team

    $members=safe_query("SELECT userID FROM ".PREFIX."cup_clan_members WHERE clanID = '$clanID'");
    while($dv=mysql_fetch_array($members)) { }
    $sql_members = safe_query("SELECT * FROM ".PREFIX."cup_clan_members WHERE clanID = $ds[ID]");	
    $members = mysql_num_rows($sql_members);

  //status

    $status = $ds['status'];
	if($status) $status2 = '<img src="images/icons/online.gif" border="0" alt="" title="Accepted" />';
	else
	            $status2 = '<img src="images/icons/offline.gif" border="0" alt="" title="Not Accepted"  />';

    if(islocked($ds['ID'])) 
       $status1 = '<font color="red"><b>Team Locked</b></font> <a href="?site=clans&action=show&clanID='.$ds['ID'].'#points" name="locked" style="text-decoration:none; cursor:help" onmouseover="showWMTT(\'locked\')" onmouseout="hideWMTT()"><font color="black"><b><blink>(?)</blink></b></font></a>';
    elseif($status)         
       $status1 = $_language->module['activated1'];
    else                    
       $status1 = $_language->module['not_activated1'];

//V5.1 short team listing

$avatar = (empty($ds['clanlogo']) ? 'images/avatars/noavatar.gif' : $ds['clanlogo']);  

		getclanawards($ds['ID']);
		$award1 = '';
		$award2 = '';
		$award3 = '';
		if($ar_awards[1]){
			for($i=1; $i<=$ar_awards[1]; $i++)
				$award1.='<a href="?site=cups&action=tree&cupID='.$ar1_name[$i-1].'"><img src="images/cup/icons/award_gold.png" border="0" alt="Gold" title="'.getcupname($ar1_name[$i-1]).'" /></a>'; 
		}
		if($ar_awards[2]){
			for($i=1; $i<=$ar_awards[2]; $i++)
				$award2.='<a href="?site=cups&action=tree&cupID='.$ar2_name[$i-1].'"><img src="images/cup/icons/award_silver.png" border="0" alt="'.$_language->module['silver'].'" title="'.getcupname($ar2_name[$i-1]).'" /></a>';
		}
		if($ar_awards[3]){
			for($i=1; $i<=$ar_awards[3]; $i++)
				$award3.='<a href="?site=cups&action=tree&cupID='.$ar3_name[$i-1].'"><img src="images/cup/icons/award_bronze.png" border="0" alt="Bronze" title="'.getcupname($ar3_name[$i-1]).'" /></a>';
		}		
		$awards_cup=$award1.$award2.$award3;
		
		//
		
		getclanawards_lad($ds['ID']);
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
		$awards_lad=$award1.$award2.$award3;
		
		if(empty($awards_cup) && empty($awards_lad)) {
		         $awards_chk = "<img src='images/cup/icons/nok_32.png' width='16' height='16'>";
		}
		else{
		         $awards_chk = $awards_cup.$awards_lad;
		}
		
		if(user_cup_points($ds[ID],$_GET[cupID],$team=1,$won=0,$lost=0,$type='',$league)) {
		   $show_matches = '<a href="?site=matches&action=viewmatches&clanID='.$ds['ID'].'"><img border="0" src="images/cup/icons/add_result.gif" align="right"></a>';
		}
		else{
		   $show_matches = false;
		}

if(!$team_list) {


   echo '
         <tr>
           <td bgcolor="'.$bg1.'"><div style="margin-top: 5px; position: relative;"><img src="images/flags/'.getclancountry($ds[ID],$img=0).'.gif" align="right"></div> <div style="margin-top: 0px; margin-right: 25px; position: relative;">'.$show_matches.'</div>
                 <img src="'.$avatar.'" width="24" height="24">

				  	<span class="link">
					  <a href="javascript: void(0)" onclick="location.href=\'?site=clans&action=show&clanID='.$ds[ID].'\';"><strong>'.getclanname($ds[ID]).'</strong>
					    <span >  				  					
				  	       <center>	
						  <img src="'.$avatar.'" width="100" height="100"><br>
				  		  <img src="images/flags/'.getclancountry($ds[ID],$img=0).'.gif">&nbsp;<strong>'.getclanname2($ds[ID]).'</strong>'.'
                                                  <hr style="border: 1px solid '.$bghead.'">
						   <strong>
						    '.$members.' '.($members > 1 ? "Members" : "Member").' <br />
						    '.user_cup_points($ds[ID],$_GET[cupID],$team=1,$won=0,$lost=0,$type='',$league).' Matches <br />
						    Status: '.$status1.'
						   </strong>
				  	       </center>	
					    </span>
					  </a>
				  	</span>
           </td>
	   <td bgcolor="'.$bg1.'" align="center">'.date('M dS Y', $ds['reg']).'</td>
           <td bgcolor="'.$bg1.'" align="center">'.$awards_chk.'</td>
           <td bgcolor="'.$bg1.'" align="center">'.ratio_level($ds['ID']).'</td>
         </tr>';
}

//END SHORT TEAM LISTING


  //clan variables
  
  $getflag = safe_query("SELECT country FROM ".PREFIX."cup_all_clans WHERE ID='".$ds[ID]."'");
  $dp=mysql_fetch_array($getflag);
  
    $country = '<img src="images/flags/'.$dp[country].'.gif" width="20" height="13">'; 
    $clanname = '<a href="?site=clans&action=show&clanID='.$ds['ID'].'&cupID='.$cupID.'">'.$ds['name'].'</a>';
    $clantag = $ds[clantag];
    $clanhp = $ds[clanhp];
    
    
  //get founder
  
		$isfounder = mysql_num_rows(safe_query("SELECT ID FROM ".PREFIX."cup_all_clans WHERE leader = '".$userID."' AND ID = '".$clanID."'"));
		$leadmember = '';
		
  //if member only in team

    if(ismember($userID,$ds['ID'])) $details = '
      <a href="?site=clans&action=show&clanID='.$ds['ID'].'&cupID='.$cupID.'"><img border="0" src="images/icons/foldericons/folder.gif" border="0"></a>
      <a href="?site=messenger&action=touser&touser='.$ds['leader'].'"><img src="images/cup/icons/pm.png" border="0"></a>
      <a href="?site=matches&action=viewmatches&clanID='.$ds['ID'].'"><img src="images/cup/icons/add_result.gif" width="16" height="16" border="0"></a>
      <a href="?site=clans&action=clanleave&clanID='.$ds['ID'].'" onclick="return confirm(\'Are you sure you want to leave this team?\');"><img src="images/cup/error.png" width="16" height="16" border="0"></a>';

  //if leader/founder
  
    elseif(isleader($userID,$ds['ID'])) $details = '
      <a href="?site=clans&action=show&clanID='.$ds['ID'].'&cupID='.$cupID.'"><img border="0" src="images/icons/foldericons/folder.gif"></a> 
      <a href="?site=clans&action=clanedit&clanID='.$ds['ID'].'"><img border="0" src="images/cup/icons/manage.gif"></a> 
      <a href="?site=clans&action=editpwd&clanID='.$ds['ID'].'"><img border="0" src="images/cup/icons/key.png"></a>
      <a href="?site=clans&action=invite&clanID='.$ds['ID'].'"><img border="0" src="images/cup/icons/invite.gif"></a>
      <a href="?site=matches&action=viewmatches&clanID='.$ds['ID'].'"><img src="images/cup/icons/add_result.gif" width="16" height="16" border="0"></a>';

  //otherwise if not in team

    else $details = '
      <a href="?site=matches&action=viewmatches&clanID='.$ds['ID'].'"><img src="images/cup/icons/add_result.gif" width="16" height="16" border="0"></a>
      <a href="?site=clans&action=show&clanID='.$ds['ID'].'"><img border="0" src="images/icons/foldericons/folder.gif"></a> 
      <a href="?site=clans&action=clanjoin&clanID='.$ds['ID'].'"><img border="0" src="images/cup/icons/join.png" width="16" height="16"></a>';
      
  //continue getting team variables... (server, logo, status)
  
    $id=$ds[leader];
    include ("livecontact.php");
    $leader = '<a href="?site=profile&id='.$ds[leader].'">'.getnickname($ds[leader]).'</a><br><a href="?site=profile&id='.$ds[leader].'">'.$email.'</a> '.$pm .$buddy .$icq .$skype .$xfirec .$steam .$msn .$aim .$yahoo.' <a href="index.php?site=matches&action=viewmatches&memberID='.$id.'" target="_top"><img border="0" src="images/cup/icons/add_result.gif" width="18" height="18"></a>';
    
    if($ds['server'] && $ds['server'] != 'http://' && $ds['server'] != '0')
	  $serverinfo = '<b>Server Connect:</b><br><a href="xfire:join?game=cod4mp&server='.$ds['server'].':'.$ds['port'].'"><img src="images/cup/icons/xfire.png" border="0"></a> <a href="steam://connect:'.$ds['server'].':'.$ds['port'].'"><img src="images/cup/icons/steam.gif" border="0"></a> <a href="hlsw://'.$ds['server'].':'.$ds['port'].'?Connect"><img src="images/cup/icons/hlsw.jpg" border="0"></a> <a href="http://www.gametracker.com/server_info/'.$ds['server'].':'.$ds['port'].'/"><img border="0" src="images/cup/icons/gt.png"></a>';
	elseif(!isleader($userID,$ds['ID'])) 
	  $serverinfo = '';
    else
	  $serverinfo = '<b>Add Server:</b><br><a href="?site=clans&action=clanedit&clanID='.$ds['ID'].'#editserver"><img border="0" src="images/cup/icons/server_edit.gif"></a>';
				
	if($ds['clanlogo'] && $ds['clanlogo'] != 'http://' && $ds['clanlogo'] != 'http://google.com')
	  $clanlogo = '<img src="'.$ds['clanlogo'].'" alt="n/a" border="0" height="100" vspace="5" width="100">';
	else
	   $clanlogo = '<img src="images/avatars/noavatar.gif" alt="n/a" border="0" height="100" vspace="3" width="100">';
					
		
              if($team_list) {      
			
				eval ("\$clans = \"".gettemplate("clans")."\";");
				echo $clans;
              }


				if(!($i%4))
					echo '';

				$i++;
   echo $ds['id'] . "" . $ds['number'] . "";
} // end while
			}
			echo '</table>';
			
  //include the pagination links at the bottom
  
/******  build the pagination links ******/
// range of num links to show
$range = 3;

// if not on page 1, don't show back links
if ($currentpage > 1) {
   // show << link to go back to page 1
   echo "<ul id='pagination-digg'><a class='previous-off' href='?site=clans&currentpage=1&v=$letter#alpha'>&laquo; First</a></ul>";
   // get previous page num
   $prevpage = $currentpage - 1;
   // show < link to go back to 1 page
   echo "<ul id='pagination-digg'><a class='previous-off' href='?site=clans&currentpage=$prevpage&v=$letter#alpha'>&lsaquo; Back</a></ul>";
} // end if 

// loop to show links to range of pages around current page
for ($x = ($currentpage - $range); $x < (($currentpage + $range) + 1); $x++) {
   // if it's a valid page number...
   if (($x > 0) && ($x <= $totalpages) && $numrows > $rowsperpage) {
      // if we're on current page...
      if ($x == $currentpage) {
         // 'highlight' it but don't make a link
         echo "<ul id='pagination-digg'><a class='active'><font color='white'><b>$x</b></font></a></ul>";
      // if not current page...
      } else {
         // make it a link
         echo "<ul id='pagination-digg'><a class='pagination-digg' href='?site=clans&currentpage=$x&v=$letter#alpha'> $x</a></ul>";
      } // end else
   } // end if 
} // end for
                 
// if not on last page, show forward and last page links        
if ($currentpage != $totalpages) {
   // get next page
   $nextpage = $currentpage + 1;
    // echo forward link for next page 
   echo "<ul id='pagination-digg'><a class='next' href='?site=clans&currentpage=$nextpage&v=$letter#alpha'>Next &rsaquo;</a></ul>";
   // echo forward link for lastpage
   echo "<ul id='pagination-digg'><a class='next' href='?site=clans&currentpage=$totalpages&v=$letter#alpha'>Last &raquo;</a></ul>";
} // end if
/****** end build pagination links ******/					
			
			echo $inctemp; echo base64_decode('');
			}else
        echo'';
	}	
  }echo ($cpr ? ca_copyr() : die());
?>