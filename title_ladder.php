<?php

if($_GET['ladderID'] || $_GET['ID'] || $_GET['laddID'])
{

  $title_cuptype = (ladderis1on1($id) ? '&type=1' : '');

  if($_GET['ladderID']){
     $s_active = 'active" style="margin-left:0px;"';
     $id = $_GET['ladderID'];
  }
  elseif($_GET['action']=='show'){
     $r_active = 'active" style="margin-left:0px;"';
     $id = $_GET['laddID'];
  }
  elseif($_GET['action']=='rules'){
     $r_active = 'active" style="margin-left:0px;"';
     $id = $_GET['ID'];
  }
  elseif($_GET['ID']){
     $d_active = 'active" style="margin-left:0px;"';
     $id = $_GET['ID'];
  }
  elseif($_GET['action']=='newchallenge' || $_GET['action']=='viewchallenge'){
     $c_active = 'active" style="margin-left:0px;"';
     $id = $_GET['laddID'];
  }
  elseif(($_GET['action']=='viewmatches' && $_GET['clanID'] && $_GET['laddID']) || ($_GET['action']=='report' || $_GET['action']=='confirmscore')){
     $mr_active = 'active" style="margin-left:0px;"';
     $id = $_GET['laddID'];    
  }
  elseif($_GET['action']=='viewmatches' || $_GET['site']=='cup_matches'){
     $ma_active = 'active" style="margin-left:0px;"';
     $id = $_GET['laddID'];
  }
  elseif($_GET['site']=='groups'){
     $g_active = 'active" style="margin-left:0px;"';
     $id = $_GET['laddID']; 
  }
  
  $stl=mysql_fetch_array(safe_query("SELECT * FROM ".PREFIX."cup_ladders WHERE ID='$id'"));
  
  if($stl['gs_start'] || $stl['gs_end'])  {
     $groups_tit = '<td class="title"><img border="0" src="images/cup/icons/groups.png"> <a class="titlelink" href="?site=groups&laddID='.$id.'">Groups</a></td>';
  } 
  
  if(isladparticipant($userID,$id,$checkin=0)){  
     $alt = '<a href="index.php?site=matches&action=viewmatches&clanID='.participantID($userID,$id).'&laddID='.$id.$title_cuptype.'">Reporting</a>';
  }
  else
  {
     //Ladder registration
     
     if(!$loggedin)
     {
         $signup = 'Login';
         $link = '?site=login';
     }
     else
     {
         $signup = 'Sign-Up';
         $link = '?site=quicknavi&type=ladders&cup='.getalphaladname($id);
     }
     
     $alt = '<a href="'.$link.'">'.$signup.'</a>';
  }
}
     if($stl['challallow']!=1 && isladparticipant($userID,$id)) {
        $chall_titl = '<td class="title">&nbsp;<img border="0" src="images/cup/icons/challenge.gif" width="16" height="16">&nbsp;<a class="titlelink" href="?site=standings&action=newchallenge&laddID='.$id.'">Challenge</a></td>';
     }
          
     eval ("\$title_ladder = \"".gettemplate("title_ladder")."\";");
     echo $title_ladder;
?>