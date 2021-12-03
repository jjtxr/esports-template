<link href="cup.css" rel="stylesheet" type="text/css">

<?php 

include("config.php");

$bg1=BG_1;
$bg2=BG_1;
$bg3=BG_1;
$bg4=BG_1;
$cpr=ca_copyr();

!$cpr || !ca_copyr() ? die() : '';

$user = mysql_real_escape_string(getuserid2($_GET['login']));
$id = (!$user ? $userID : $user);
$nick = '<a href="?site=profile&id='.$id.'">'.getnickname($id).'</a>';

  if($loggedin) {

    echo "<img src='images/cup/icons/join.png'> <a href='?site=clans&action=clanjoin'><b>Join Team</b></a> | <a href='?site=clans&action=clanadd'><b>Create Team</b></a> <img src='images/cup/icons/addresult.gif' width='16' height='16'> <br>
          <table width='100%' border='0' cellspacing='$cellspacing' cellpadding='$cellpadding'>
            <tr>
	      <td class='title' colspan='3'><img src='images/cup/icons/support.png'> Teams by $nick</td>
	    </tr>";
  
      $query = safe_query("SELECT * FROM ".PREFIX."cup_clan_members WHERE userID='$id'");
        if(mysql_num_rows($query)) {
	
	    while($ds = mysql_fetch_assoc($query)) {
	    
	        $team=mysql_fetch_array(safe_query("SELECT * FROM ".PREFIX."cup_all_clans WHERE ID='".$ds['clanID']."'"));
		$count=mysql_fetch_array(safe_query("SELECT count(*) AS TOTAL_MEMBERS FROM ".PREFIX."cup_clan_members WHERE clanID='".$ds['clanID']."'"));
	    
	        $clanID = $ds['clanID'];
	        $clanhp = $team['clanhp'];
	        $clanlogo = clanlogo($clanID);
		$country = getclancountry3($clanID);
		$members = $count['TOTAL_MEMBERS'];
		$rank_title = ($id==$userID ? 'Your Rank' : 'Rank');
		$clanname = '<a href="?site=clans&action=show&clanID='.$clanID.'"><strong>'.getclanname2($clanID).'</strong></a>';		
		
		if($id==$team['leader']) {
		       $rank = "Owner";
		}
		elseif($ds['function']=='Leader') {
		       $rank = "Leader";
		}
		else{
		       $rank = "Member";
		}
		
		if($clanhp && $clanhp!='http://') {
		
		        $rowspan = 5;
		        $show_hp = '<tr>
                                      <td bgcolor="'.$bg1.'"><strong>Website</strong></td>
                                      <td bgcolor="'.$bg1.'"><a href="'.$clanhp.'" target="_blank">'.$clanhp.'</a></td>
                                    </tr>';
		
		}
		else{
		        $rowspan = 4;
                        $show_hp = '';
		}
	
		eval ("\$lteams = \"".gettemplate("cup_teams")."\";");
		echo $lteams; 
		
	    }
	    
	}
        else{
                echo "<tr><td bgcolor='$loosecolor'>No teams found</td></tr>";
        }	
         echo "</table>";
    }
    else{
            echo '-please login-';   
    }
    
!$cpr || !ca_copyr() ? die() : '';
?>