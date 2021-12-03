<script language="javascript">wmtt = null; document.onmousemove = updateWMTT; function updateWMTT(e) { x = (document.all) ? window.event.x + document.body.scrollLeft : e.pageX; y = (document.all) ? window.event.y + document.body.scrollTop  : e.pageY; if (wmtt != null) { wmtt.style.left=(x + 20) + "px"; wmtt.style.top=(y + 20) + "px"; } } function showWMTT(id) {	wmtt = document.getElementById(id);	wmtt.style.display = "block" } function hideWMTT() { wmtt.style.display = "none"; }</script>
<div class="tooltip" id="cross_signup" align="left"><font color="#DD0000"><b>This cup is not on sign-up phase <img src="images/cup/error.png" width="16" height="16"></b></font><br><font color="white">Please try again later...</font></div>
<div class="tooltip" id="already_started" align="left"><font color="#DD0000"><b>This cup has already started <img src="images/cup/error.png" width="16" height="16"></b></font><br><font color="white">You were unfortunately too late!</font></div>
<div class="tooltip" id="irc_notice" align="left"><img src="images/cup/icons/info.gif" width="16" height="16"> <font color="#FF6600"><b>IRC will be available when cup starts</b></font><br><center><font color="white">Click now to view details on start times.</font></center></div>

<?php

  if($_GET['cupID'])
  {
     $cupID = $_GET['cupID'];
  }
  else
  {
     $cupID = ($_GET['laddID'] ? $_GET['laddID'] : $_GET['ladderID']);
  }

  if($_GET['cupID']) 
  {
     $table_sel = "cups";
     $type = "is1on1";
     $league = "cupID";
     $entered = iscupparticipant($userID,$cupID,$checkin=0);
     $checked = iscupparticipant($userID,$cupID,$checkin=1);
     $unchecked = iscupparticipant($userID,$cupID,$checkin=2); 
     $alphacupname = getalphacupname($cupID);
  }
  elseif($_GET['laddID'] || $_GET['site']=='ladders') 
  {
     $table_sel = "cup_ladders";
     $type = "ladderis1on1";
     $league = "ladID";
     $entered = isladparticipant($userID,$cupID,$checkin=0);
     $checked = isladparticipant($userID,$cupID,$checkin=1);
     $unchecked = isladparticipant($userID,$cupID,$checkin=2);  
     $alphacupname = getalphaladname($cupID);
  }
  
  $type_link = ($_GET['cupID'] ? "cupID" : "laddID");
  $type_link2 =($_GET['cupID'] ?"": "&type=ladders");
  
  $st = mysql_fetch_array(safe_query("SELECT * FROM ".PREFIX."$table_sel WHERE ID='$cupID'"));
   
     $one = ($type($cupID) ? "1on1='1'" : "1on1='0'");   
  
        $registered = safe_query("SELECT * FROM ".PREFIX."cup_clans WHERE $one && $league='$cupID'");
        $tds = mysql_fetch_array($registered); 
        
           if($st['gs_start'] || $st['gs_end'])
           {
		      $groups_tit ='<td class="title"><img border="0" src="images/cup/icons/groups.png"> <a class="titlelink" href="?site=groups&'.$type_link.'='.$cupID.'">Groups</a></td>';
           }       
           if(!$loggedin) 
           {
	      $show_su = 1;
              $csignup = '<a href="?site=login">Login</a>';
           }
           elseif($st['gs_start'] <= time() && $st['gs_end'] >= time())
           {
	      $show_su = 1;
              $csignup = '<a href="?site=groups&'.$type_link.'='.$cupID.'">Sign-Up</a>';
           }
           elseif($loggedin && !$entered && $st['status']==1)
           {
	      $show_su = 1;
              $csignup = '<a href="?site=quicknavi&cup='.$alphacupname.$type_link2.'">Sign-Up</a>';
           }
           elseif($loggedin && !$entered && $st['status']!=1)
           {
	      $show_su = 0;
              $csignup = '<a name="cross_signup" style="text-decoration:none; cursor:help" onmouseover="showWMTT(\'cross_signup\')" onmouseout="hideWMTT()"><s>Sign-Up</s></a>';
           }
           elseif($loggedin && $checked && $st['status']==2)
           {
	      $show_su = 1;
              $csignup = '<a href="javascript:void(0)" onclick="MM_openBrWindow(\'popup.php?site=shout&id='.$cupID.'&type=cupID\',\'Cup Chat\',\'toolbar=no,status=no,scrollbars=no,width=550,height=340\');"><strong>Chat</strong>';
           }
           elseif($checked)
           {
	      $show_su = 0;
              $csignup = '<a href="?site=cups&action=details&cupID='.$cupID.'#start" name="irc_notice" style="text-decoration:none; cursor:help" onmouseover="showWMTT(\'irc_notice\')" onmouseout="hideWMTT()">Checked</a>';
           }
           elseif($unchecked && $st['status']==2) 
           {
	      $show_su = 0;
              $csignup = '<a name="already_started" style="text-decoration:none; cursor:help" onmouseover="showWMTT(\'already_started\')" onmouseout="hideWMTT()">Unchecked</a>';
           }
           elseif($unchecked)
           {
	      $show_su = 0;
              $csignup = '<a href="?site=quicknavi&cup='.$alphacupname.'">Unchecked</a>';
           }
           else
           {
	      $show_su = 0;
              $csignup = '<s>Sign-Up</s>';
           } 
        
           if(!$type($cupID)) 
           {
              
           $teams = safe_query("SELECT * FROM ".PREFIX."cup_clan_members WHERE userID='$userID'");
               while($te=mysql_fetch_array($teams))
                {             
                    if(mysql_num_rows(safe_query("SELECT * FROM ".PREFIX."cup_clans WHERE clanID='".$te['clanID']."' && 1on1='0' && cupID='$cupID'")))
                    {
                       $mylineup = '<td class="title"><img border="0" src="images/cup/icons/random.png" width="16" height="16"> <a class="titlelink" href="?site=clans&action=lineup&cupID='.$cupID.'">Lineup</a></td>';  
                 }
              }             
           }
           if($show_su)
           $main_signup = "<td class='title'>&nbsp;<img border='0' src='images/cup/icons/checkin.png' width='16' height='16'>&nbsp;$csignup</td>";		   
?>