<link href="cup.css" rel="stylesheet" type="text/css" />

<?php
/*
##########################################################################
#              CUP ADDON V5.1 MATCHES MODULE RE-WRITTEN                  #
##########################################################################
*/

//-- GLOBALS --//

  (!ca_copyr() ? system_error(die()) : true);
  match_query_type();

  safe_query("UPDATE ".PREFIX."cups SET status='2' WHERE start<='".time()."'");
  safe_query("UPDATE ".PREFIX."cups SET status='3' WHERE ende<='".time()."'");
  safe_query("UPDATE ".PREFIX."cup_warnings SET expired='1' WHERE deltime<='".time()."'");

  include ("config.php");
  $_language->read_module('matches');

  $bg1=BG_1;
  $bg2=BG_1;
  $bg3=BG_1;
  $bg4=BG_1;
  $cpr=ca_copyr();
  !$cpr || !ca_copyr() ? die() : '';
  
  gettimezone();

  $set = mysql_fetch_array(safe_query("SELECT * FROM ".PREFIX."cup_settings"));
  
  if($set['ccupgamelimit']==0 && !$loggedin) {
       echo $_language->module['logged_in'];
  }
  else{
        
    $clanID = ($_GET['memberID'] ? $_GET['memberID'] : $_GET['clanID']);
    $team = ($_GET['type']==1 ? 0 : 1);
    
    if($_GET['cupID']) { 
       $cupID = $_GET['cupID'];
       $typename = 'cupID';
       $cuptype = 'cupID';  
       $cuptitle = 'Cup';
       $cuplink = '<a href="?site=cups&action=details&cupID='.$cupID.'">'.getcupname($cupID).'</a>';
       $table = 'cups';
       $one_check = 'is1on1';
    }
    else{
       $cupID = $_GET['laddID'];
       $typename = 'ladID';
       $cuptype = 'laddID';
       $cuptitle = 'Ladder';
       $cuplink = '<a href="?site=ladders&ID='.$cupID.'">'.getladname($cupID).'</a>';
       $table = 'cup_ladders'; 
       $one_check = 'ladderis1on1';   
    }
    
    if($_GET['type']=="gs") {        
       $query_grouping = "matchno";
       $query_where = "gs";
    }
    elseif($_GET['type']=="ladder" || $_GET['laddID']) { 
       $query_grouping = "ladID";
       $query_where = "ladder";
    }
    else{
       $query_grouping = "cupID";
       $query_where = "cup";
    }      
         
//-- MATCH MODULE --//

    if(isset($_GET['action']) && $_GET['action']=='viewmatches') {
        
//-- MATCH REPORTING --//

        if($cupID && $clanID) {
        
          if($_GET['laddID'])
            include("title_ladder.php");
            
            if($one_check($cupID) && $_GET['type']!=1)
            redirect('?site=matches&action=viewmatches&clanID='.$clanID.'&'.$cuptype.'='.$cupID.'&type=1', '...', 0);
        
    	    $count = safe_query("SELECT count(*) as allmatches FROM ".PREFIX."cup_matches WHERE $typename='$cupID'");
	    $dv=mysql_fetch_array($count); 
            
		          
            if(!$one_check($cupID)){	
                $link = "?site=clans&action=show&clanID=$clanID"; 
	            $id = 'ID'; 
	            $short = 'clantag';                            
	            $cc=mysql_fetch_array(safe_query("SELECT $id, $short FROM ".PREFIX."cup_all_clans WHERE ID='$clanID'"));
            }
            else{   
                $link = "?site=profile&id=$clanID";
	            $id = 'userID';   
	            $short = 'firstname';
	            $cc=mysql_fetch_array(safe_query("SELECT $id, $short FROM ".PREFIX."user WHERE userID='$clanID'"));
            }
            		          
            $cupname = ($cuptitle=="Cup" ? getcupname($cupID) : getladname($cupID));           
            $clanname = (empty($cc[$id]) ? "(DELETED)" : getname1($clanID,$cupID,$ac=0,strtolower($cuptitle)));            
            $clantag = (empty($cc[$short]) ? 'Their' : '<a href="'.$link.'">'.$cc[$short].'\'s</a>');
            $cupn = '<a href="?site=matches&action=viewmatches&'.$cuptype.'='.$cupID.'"><img src="images/cup/icons/add_result.gif" align="right" border="0"></a> <a href="?site=cups&action=details&'.$cuptype.'='.$cupID.'">'.$cupname.'</a>';
	        $one_query=($team ? "1on1='0'" : "1on1='1'");
	        $game_query=($cuptitle=="Cup" ? "cups" : "cup_ladders");
	        
	        if($cuptitle=="Cup") {
	        
	           include ("title_cup.php");
	           $participants = ($_GET['type']==1 ? "Players" : "Teams");
		   	    
	           $sta_ext = ($_GET['cupID'] ? "&cupID=$_GET[cupID]" : "");	
	        
	           eval ("\$title_cup = \"".gettemplate("title_cup")."\";");
	           echo $title_cup;		   
	        } 
		
	        if($_GET['display']=='team') { 
		
		    $dis_team_ext = '&display=team';
		
		    eval ("\$clans_details_title = \"".gettemplate("clans_details_title")."\";");
	            echo $clans_details_title;
	        }		
			
			   $scr_head  = (($_GET['cupID'] && is1on1($cupID)) || ($_GET['laddID'] && ladderis1on1($_GET['ladderID'])) ? 'Player' : 'Team');
			   $shw_cp_mt = '<a href="?site=matches&action=viewmatches&'.$cuptype.'='.$cupID.'"><img src="images/cup/icons/add_result.gif" align="right" border="0" alt="All matches for this cup">';
			
	                   $matchstatus2 = match_status2($userID,$cupID,$clanID);	

			   if($matchstatus2['report']) {
			          $date_width = 25;
                                  $report_head = '<td bgcolor="'.$bg1.'" align="center"><b>Report</b></td>';
			   }
			   else{
			          $date_width = 30;
                                  $report_head = '';
			   }
			
		      
	             $getmatches = safe_query("SELECT * FROM ".PREFIX."cup_matches WHERE $typename='$cupID' && (clan1='$clanID' || clan2='$clanID') && (clan1 != '0' AND clan2 != '0') && (clan1 !='2147483647' AND clan2 !='2147483647') && $one_query ORDER BY date DESC");
	               
		       if(mysql_num_rows($getmatches)) {
		       
	                  eval ("\$one_head = \"".gettemplate("report_matches_head")."\";");
	                  echo $one_head;
		       }
		   
	                 while($dm = mysql_fetch_array($getmatches)) 
	                  { 
	                     
                         $ed=mysql_fetch_array(safe_query("SELECT game FROM ".PREFIX."$game_query WHERE ID='$cupID'"));
	                 
	                     $image = $ed['game'];
	                     $matchid = $dm['matchID'];
	                     $matchno = (empty($dm['matchno']) ? "OP" : $dm['matchno']);
	                     $game = '<a name="'.$matchno.'"></a><img src="images/games/'.$image.'.gif" width="20" height="20" border="0">';             
	                     $match = '<img src="images/cup/icons/go.png"> <a href='.matchlink($matchid,$ac=0,$tg=0,$redirect=0).'><b>'.$matchid.'</b></a>';
	                     $date = date('l M dS Y', $dm['date']);  
	                     
	                     if(!$one_check($cupID)){	 
	                        $id = 'ID';                             
    	                    $cc1=mysql_fetch_array(safe_query("SELECT $id FROM ".PREFIX."cup_all_clans WHERE ID='".$dm['clan1']."'"));
                            $cc2=mysql_fetch_array(safe_query("SELECT $id FROM ".PREFIX."cup_all_clans WHERE ID='".$dm['clan2']."'"));
    	                 }
    	                 else{
	                        $id = 'userID';   
    	                    $cc1=mysql_fetch_array(safe_query("SELECT $id FROM ".PREFIX."user WHERE userID='".$dm['clan1']."'"));
    	                    $cc2=mysql_fetch_array(safe_query("SELECT $id FROM ".PREFIX."user WHERE userID='".$dm['clan2']."'"));
    	                 }
    	                                
	                     if($clanID == $dm['clan1']){
	                                 $score1 = $dm['score1'];
	                                 $score2 = $dm['score2'];		                                                                
	                                 $clanname = (empty($cc1[$id]) ? "(DELETED)" : getname1($dm['clan1'],$cupID,$ac=0,strtolower($cuptitle)));
    	                             $opponent = (empty($cc2[$id]) ? "(DELETED)" : getname1($dm['clan2'],$cupID,$ac=0,strtolower($cuptitle)));	                                 
	                                 $teammatches = '<a href="?site=matches&action=viewmatches&clanID='.$dm['clan1'].'&'.$cuptype.'='.$cupID.($one_check($cupID) ? "&type=1" : "").'"><img border="0" src="images/cup/icons/add_result.gif" align="right" width="16" height="16"></a>';
	                                 $oppmatches = '<a href="?site=matches&action=viewmatches&clanID='.$dm['clan2'].'&'.$cuptype.'='.$cupID.($one_check($cupID) ? "&type=1" : "").'"><img border="0" src="images/cup/icons/add_result.gif" align="left" width="16" height="16"></a>';
	                     }
	                     else{
	                                 $score1 = $dm['score2'];
	                                 $score2 = $dm['score1'];	
	                                 $clanname = (empty($cc2[$id]) ? "(DELETED)" : getname1($dm['clan2'],$cupID,$ac=0,strtolower($cuptitle)));
    	                             $opponent = (empty($cc1[$id]) ? "(DELETED)" : getname1($dm['clan1'],$cupID,$ac=0,strtolower($cuptitle)));	                                 
	                                 $teammatches = '<a href="?site=matches&action=viewmatches&clanID='.$dm['clan2'].'&'.$cuptype.'='.$cupID.($one_check($cupID) ? "&type=1" : "").'"><img border="0" src="images/cup/icons/add_result.gif" align="right" width="16" height="16"></a>';
	                                 $oppmatches = '<a href="?site=matches&action=viewmatches&clanID='.$dm['clan1'].'&'.$cuptype.'='.$cupID.($one_check($cupID) ? "&type=1" : "").'"><img border="0" src="images/cup/icons/add_result.gif" align="left" width="16" height="16"></a>';
	                     }
	                            
	                     $score1 = ((!$score1 && !$score2) ? '-' : $score1);
	                     $score2 = ((!$score2 && $score1=='-') ? '-' : $score2);
						 
	                     $matchstatus = match_status($userID,$matchid);	                     
	                     $action = ($matchstatus['report'] ? $matchstatus['report'] : $matchstatus['status']);
			  
			     $report_content  = ($matchstatus['report'] ? '<td bgcolor="'.$bg1.'" align="center">'.$matchstatus['report'].'</td>' : '<td bgcolor="'.$bg1.'" align="center">'.$matchstatus['status'].'</td>');
	                     
	                     if($score1>$score2) {
	                        $allscore = '<div style="width: 30px; background-color: green; color: white; padding: 1px;"><b>'.$score1.'</b></div>';
	                     }
						 elseif($score2>$score1) {
	                        $allscore = '<div style="width: 30px; background-color: red; color: white; padding: 1px;"><b>'.$score1.'</b></div>';
	                     }
						 elseif($score1 == $score2) {						
	                        $allscore = '<div style="width: 30px; background-color: #FF6600; color: white; padding: 1px;"><b>'.$score1.'</b></div>'; 
                         }
							 
	                     eval ("\$team_content = \"".gettemplate("report_matches")."\";");
	                     echo $team_content;
	                
	                     } 
	                
	                     eval ("\$one_foot = \"".gettemplate("1on1_foot")."\";");
	                     echo $one_foot;     
	                         
//-- View matches by user or team --//
	                                
            }elseif($clanID && $clanID!=2147483647){  

	        if($_GET['display']=='team') { 
		
		    $dis_team_ext = '&display=team';
		
		    eval ("\$clans_details_title = \"".gettemplate("clans_details_title")."\";");
	            echo $clans_details_title;
	        }
            
                 $one = ($_GET['memberID'] ? 1 : 0); 
                 $one_query = ($one ? "1on1='1'" : "1on1='0'");
                 $one_type = ($_GET['memberID'] ? "memberID" : "clanID");
                 
                 if($_GET['type']=="gs") {
                    echo 'Showing matches for: <b>Group Stages</b> ('.num_matches($clanID,0,$one,'gs').') <br> <img src="images/cup/icons/go.png"> <a href="?site=matches&action=viewmatches&'.$one_type.'='.$clanID.$dis_team_ext.'"><b>Show Tournament Matches</b></a> ('.num_matches($clanID,$cupID_none=0,$one,$league="cup").') <br> <img src="images/cup/icons/go.png"> <a href="?site=matches&action=viewmatches&'.$one_type.'='.$clanID.'&type=ladder'.$dis_team_ext.'"><b>Show Ladder Matches</b></a> ('.num_matches($clanID,$cupID_none=0,$one,$league="ladder").')';
                    $no_match_return = "group stage";
		            $cuptype = '';
		            $lk_details = '';
                 }
                 elseif($_GET['type']=="ladder") {
                    echo 'Showing matches for: <b>Ladders</b> ('.num_matches($clanID,0,$one,'ladder').') <br> <img src="images/cup/icons/go.png"> <a href="?site=matches&action=viewmatches&'.$one_type.'='.$clanID.$dis_team_ext.'"><b>Show Tournament Matches</b></a> ('.num_matches($clanID,$cupID_none=0,$one,$league="cup").') <br> <img src="images/cup/icons/go.png"> <a href="?site=matches&action=viewmatches&'.$one_type.'='.$clanID.'&type=gs'.$dis_team_ext.'"><b>Show Group Matches</b></a> ('.num_matches($clanID,$cupID_none=0,$one,$league="gs").')';
                    $no_match_return = "ladder";
		            $cuptype = 'laddID';
		            $lk_details = '?site=ladders&ID=';
                 }
                 else{
                    echo 'Showing matches for: <b>Tournaments</b> ('.num_matches($clanID,0,$one,'cup').') <br> <img src="images/cup/icons/go.png"> <a href="?site=matches&action=viewmatches&'.$one_type.'='.$clanID.'&type=ladder'.$dis_team_ext.'"><b>Show Ladder Matches</b></a> ('.num_matches($clanID,$cupID_none=0,$one,$league="ladder").') <br> <img src="images/cup/icons/go.png"> <a href="?site=matches&action=viewmatches&'.$one_type.'='.$clanID.'&type=gs'.$dis_team_ext.'"><b>Show Group Matches</b></a> ('.num_matches($clanID,$cupID_none=0,$one,$league="gs").')';
                    $no_match_return = "tournament";
		            $cuptype = 'cupID';
		            $lk_details = '?site=cups&action=details&cupID=';
                 }
		 
                 $getcups = safe_query("SELECT * FROM ".PREFIX."cup_matches WHERE $one_query && type='$query_where' && (clan1='$clanID' || clan2='$clanID') GROUP BY $query_grouping ORDER BY date DESC");
                   if(!mysql_num_rows($getcups)) echo "<br /><br /> <strong>No $no_match_return matches</strong>";
                     while($da=mysql_fetch_array($getcups)) {
                     
                     $cupID = getleagueID($da['matchID']);
                     $cuptitle = league($da['matchID']);
                     $cupname = ($cuptitle=="cup" ? getcupname($cupID) : getladname($cupID));
                     $leaguetype = getleagueType($da['matchID']);                 
                     $clanname = getname1($clanID,$cupID,$ac=0,$cuptitle); 
                     $clantag = getname1($clanID,$cupID,$ac=0,$cuptitle);
                     $cupn = '<a href="?site=matches&action=viewmatches&'.$cuptype.'='.$cupID.'"><img src="images/cup/icons/add_result.gif" align="right" border="0" alt="All matches for this cup"></a> <a href="'.$lk_details.$cupID.'">'.$cupname.'</a>';
                     $game_query=($cuptitle=="cup" ? "cups" : "cup_ladders");                  
					 $scr_head  = ($one ? 'Player' : 'Team');					 
					 
                     if(($one && $userID==$clanID) || (!$one && isleader($userID,$clanID))) {
                         $matchreporting = '<td height="20" class="title" align="center"><img src="images/cup/icons/go.png"> <a href="'.match_report_link($da['matchID'],$clanID).'">Reporting</a></td>'; 
                     }
                     else{
                         $matchreporting = '<td height="20" class="title" align="center"></td>';
                     }                     
  	 
                     
                          $getmatches = safe_query("SELECT * FROM ".PREFIX."cup_matches WHERE $leaguetype='$cupID' && (clan1='$clanID' || clan2='$clanID') && (clan1 != '0' AND clan2 != '0') && (clan1 !='2147483647' AND clan2 !='2147483647') && $one_query ORDER BY date DESC");
	                        if(mysql_num_rows($getmatches)) {
				
	                          eval ("\$one_head = \"".gettemplate("clans_matches_head")."\";");	
                                  echo $one_head;
			  }		  
				  
    	                      while($dm = mysql_fetch_array($getmatches)) 
    	                       { 
    	                       
	                              $dd=mysql_fetch_array(safe_query("SELECT $leaguetype FROM ".PREFIX."cup_matches WHERE matchID='".$dm['matchID']."'"));
	                              $ed=mysql_fetch_array(safe_query("SELECT game FROM ".PREFIX."$game_query WHERE ID='$cupID'"));
	                              
	                              $image = $ed['game'];
	                              $clan1 = $dm['clan1'];
	                              $clan2 = $dm['clan2'];
	                              $matchid = $dm['matchID'];
	                              $matchno = (empty($dm['matchno']) ? "OP" : $dm['matchno']);
	                              $match = '<img src="images/cup/icons/go.png"> <a href='.matchlink($matchid,$ac=0,$tg=0,$redirect=0).'><b>'.$matchid.'</b></a>';
	                              $game = '<img src="images/games/'.$image.'.gif" width="20" height="20" border="0">';
                                  $date = date('l M dS Y', $dm['date']);								  
									 
	                                 $matchstatus = match_status($userID,$matchid);	                     
	                                 $details = ($matchstatus['report'] ? $matchstatus['report'] : $matchstatus['status']);                              
	                                 $details.= '&nbsp;<a href='.matchlink($matchid,$ac=0,$tg=0,$redirect=0).'><img border="0" src="images/icons/foldericons/folder.gif"></a>';
	                              
	                                     if($clanID == $dm['clan1'])
	                                     {
	                                             $score1 = $dm['score1'];
	                                             $score2 = $dm['score2'];		                                 
	                                             $clanname = getname1($dm['clan1'],$cupID,$ac=0,strtolower($cuptitle));
	                                             $opponent = getname1($dm['clan2'],$cupID,$ac=0,strtolower($cuptitle));
	                                             $teammatches = '<a href="?site=matches&action=viewmatches&clanID='.$dm['clan1'].'&'.$cuptype.'='.$cupID.($_GET['memberID'] ? "&type=1" : "").'"><img border="0" src="images/cup/icons/add_result.gif" align="right" width="16" height="16"></a>';
	                                             $oppmatches =  '<a href="?site=matches&action=viewmatches&clanID='.$dm['clan2'].'&'.$cuptype.'='.$cupID.($_GET['memberID'] ? "&type=1" : "").'"><img border="0" src="images/cup/icons/add_result.gif" align="left" width="16" height="16"></a>';
	                                     }
	                                     else
	                                         {
         	                                     $score1 = $dm['score2'];
	                                             $score2 = $dm['score1'];	
	                                             $clanname = getname1($dm['clan2'],$cupID,$ac=0,strtolower($cuptitle));
	                                             $opponent = getname1($dm['clan1'],$cupID,$ac=0,strtolower($cuptitle));
	                                             $teammatches = '<a href="?site=matches&action=viewmatches&clanID='.$dm['clan2'].'&'.$cuptype.'='.$cupID.($_GET['memberID'] ? "&type=1" : "").'"><img border="0" src="images/cup/icons/add_result.gif" align="right" width="16" height="16"></a>';
	                                             $oppmatches =  '<a href="?site=matches&action=viewmatches&clanID='.$dm['clan1'].'&'.$cuptype.'='.$cupID.($_GET['memberID'] ? "&type=1" : "").'"><img border="0" src="images/cup/icons/add_result.gif" align="left" width="16" height="16"></a>';
	                                     }
	                                     
	                              $matchstatus = match_status($userID,$matchid);	                     
	                              $action = ($matchstatus['report'] ? $matchstatus['report'] : $matchstatus['status']);
	                              
	                              $score1 = ((!$score1 && !$score2) ? '-' : $score1);
	                              $score2 = ((!$score2 && $score1=='-') ? '-' : $score2);
	                     
	                              if($score1>$score2) 
	                       	         $allscore = '<div style="width: 30px; background-color: green; color: white; padding: 1px;"><b>'.$score1.'</b></div>';
	                              elseif($score2>$score1)
	                                 $allscore = '<div style="width: 30px; background-color: red; color: white; padding: 1px;"><b>'.$score1.'</b></div>';
	                              elseif($score1 == $score2)						
	                                 $allscore = '<div style="width: 30px; background-color: #FF6600; color: white; padding: 1px;"><b>'.$score1.'</b></div>'; 

	                              eval ("\$team_content = \"".gettemplate("clans_matches")."\";");
	                              echo $team_content;


         	                     }
	                             
	                         unset($cupID);
    	                     unset($cuptitle);
    	                        
	                     eval ("\$one_foot = \"".gettemplate("1on1_foot")."\";");
	                     echo $one_foot;	
                   }  

                unset($cuptype);		   
                   
//-- View cup, group stage or ladder matches --//
                 
        }elseif($cupID && $cupID!=0) {      
        		          
    	    $count = safe_query("SELECT count(*) as allmatches FROM ".PREFIX."cup_matches WHERE $typename='$cupID' && (clan1 != '0' AND clan2 != '0') && (clan1 !='2147483647' AND clan2 !='2147483647')");
		    $dv=mysql_fetch_array($count);  
		    
		      if($_GET['laddID']) {
		         include("title_ladder.php");
		      }
		      elseif($_GET['cupID']) {
		      
		         $participants = ($one_check($cupID) ? 'Players' : 'Teams');
		      
		         include("title_cup.php");
		         eval ("\$title_cup = \"".gettemplate("title_cup")."\";");
		         echo $title_cup;
		      }
		      		    			                 
                             $getmatches = safe_query("SELECT * FROM ".PREFIX."cup_matches WHERE $typename='$cupID' AND (clan1 != '0' AND clan2 != '0') AND (clan1 != '2147483647' AND clan2 != '2147483647') ORDER BY date DESC");
	                        
				if(mysql_num_rows($getmatches)) {
		             
			            eval ("\$one_head = \"".gettemplate("cup_matches_head")."\";");
		                    echo $one_head;
				}			      
			        else{
			            echo '<strong>No matches found.</strong>';
			      }
				
    	                      while($dm = mysql_fetch_array($getmatches)) 
    	                       { 
    	                          
	                              $dd=mysql_fetch_array(safe_query("SELECT $typename FROM ".PREFIX."cup_matches WHERE matchID='".$dm['matchID']."'"));
	                              $ed=mysql_fetch_array(safe_query("SELECT game FROM ".PREFIX."$table WHERE ID='$cupID'"));
	                              
	                              if(!$one_check($cupID))
	                              {	 
	                                 $id = 'ID';                             
    	                             $cc1=mysql_fetch_array(safe_query("SELECT $id FROM ".PREFIX."cup_all_clans WHERE ID='".$dm['clan1']."'"));
    	                             $cc2=mysql_fetch_array(safe_query("SELECT $id FROM ".PREFIX."cup_all_clans WHERE ID='".$dm['clan2']."'"));
    	                          }
    	                          else
    	                          {
	                                 $id = 'userID';   
    	                             $cc1=mysql_fetch_array(safe_query("SELECT $id FROM ".PREFIX."user WHERE userID='".$dm['clan1']."'"));
    	                             $cc2=mysql_fetch_array(safe_query("SELECT $id FROM ".PREFIX."user WHERE userID='".$dm['clan2']."'"));
    	                          }
    	                          	                              
	                              $image = $ed['game'];
	                              $clan1 = $dm['clan1'];
	                              $clan2 = $dm['clan2'];
	                              $matchid = $dm['matchID'];
	                              $matchno = (empty($dm['matchno']) ? "OP" : $dm['matchno']);
	                              $clanname = (empty($cc1[$id]) ? "(DELETED)" : getname1($dm['clan1'],$cupID,$ac=0,strtolower($cuptitle)));
    	                          $opponent = (empty($cc2[$id]) ? "(DELETED)" : getname1($dm['clan2'],$cupID,$ac=0,strtolower($cuptitle)));
	                              $match = '<img src="images/cup/icons/go.png"> <a href='.matchlink($matchid,$ac=0,$tg=0,$redirect=0).'><b>'.$matchid.'</b></a>';
	                              $game = '<img src="images/games/'.$image.'.gif" width="20" height="20" border="0">';
	                              $date = date('l M dS Y', $dm['date']);
	                              $vs = '<a href='.matchlink($matchid,$ac=0,$tg=0,$redirect=0).'><strong>vs.</strong></a>'; 
                               
	                              $score1 = $dm['score1'];	
	                              $score2 = $dm['score2'];		                                 
	                              $teammatches = '<a href="?site=matches&action=viewmatches&clanID='.$dm['clan1'].'&'.$cuptype.'='.$cupID.($one_check($cupID) ? "&type=1" : "").'"><img border="0" src="images/cup/icons/add_result.gif" align="left" width="16" height="16"></a>';
	                              $oppmatches =  '<a href="?site=matches&action=viewmatches&clanID='.$dm['clan2'].'&'.$cuptype.'='.$cupID.($one_check($cupID) ? "&type=1" : "").'"><img border="0" src="images/cup/icons/add_result.gif" align="right" width="16" height="16"></a>';
	                            
	                              $details = '<a href='.matchlink($matchid,$ac=0,$tg=0,$redirect=0).'><img src="images/icons/foldericons/folder.gif"></a>';
	                              $score1 = (!$score1 ? '&nbsp;(-)' : '&nbsp;('.$score1.')');
	                              $score2 = (!$score2 ? '&nbsp;(-)' : '&nbsp;('.$score2.')');
	                     	                              
	                              eval ("\$inctemp = \"".gettemplate("all_cup_matches")."\";");
	                              echo $inctemp;
    	                       
    	                      }

	                     eval ("\$footer = \"".gettemplate("1on1_foot")."\";");
	                     echo $footer;	


      } 
    
//-- SEARCH MATCH --//    
    }
	elseif(isset($_GET['action']) && $_GET['action']=='search') {
	
        $per_page = 15;
        $search = trim ($_GET['search']);
        $type = trim ($_GET['type']);
        $start = $_GET['start'];

        if(!$search AND $_POST['submit'] != '') {
            $error="No search term inputted.<br /><br />";
        }
		
	if(!get_magic_quotes_gpc()){
            $search = addslashes($search);
        }
		
        include("_mysql.php");
        @$db = new mysqli($host, $user, $pwd, $db);
		
        if (mysqli_connect_errno()){
            echo 'Error: Connecting to the database (mysqli)';
            exit;
        }

	if(!$start) {
            $start = 0;
        } 
	
	if($type=='date' && !empty($search)) {
	        echo 'The search input must be empty to search recent matches.';
	}
	
	if($type=='username' || $type=='nickname' || $type=='userID') {	
	        $ds=mysql_fetch_array(safe_query("SELECT userID FROM ".PREFIX."user WHERE $type='$search'"));
		
		if(is_array($ds)) {
		           redirect('?site=matches&action=viewmatches&memberID='.$ds['userID'], '...', 0);
		}
		else{
	                   echo "";    
		}
	      
	}
	elseif($type=='name' || $type=='short' || $type=='clantag' || $type=='ID') {	
	        $ds=mysql_fetch_array(safe_query("SELECT ID FROM ".PREFIX."cup_all_clans WHERE $type='$search'"));
		
		
		if(is_array($ds)) {
		           redirect('?site=matches&action=viewmatches&clanID='.$ds['ID'], '...', 0);
		}
		else{
	                   echo "";
		}

	}
	
	if($type=='matchID' || $type=='matchno' || $type=='matchid') {
	        $search_query = "$type='$search'";
	}
	else{
	        $search_query = "$type LIKE '%".$search."%'";
	}

	$query = "SELECT * FROM ".PREFIX."cup_matches WHERE $search_query && (clan1 != '0' AND clan2 != '0') && (clan1 !='2147483647' AND clan2 !='2147483647') ORDER BY matchID DESC LIMIT $start, $per_page";
        $query2 = "SELECT * FROM ".PREFIX."cup_matches WHERE $search_query && (clan1 != '0' AND clan2 != '0') && (clan1 !='2147483647' AND clan2 !='2147483647') ORDER BY matchID DESC";
    
        $result = $db->query($query);
        $result2 = $db->query($query2);
	
        $num_results = $result->num_rows;
        $num_results2 = $result2->num_rows;
        $max_pages = $result->num_rows/$per_page;
	
	$dd_options='<option value="date">Recent</option><option value="matchID">Match ID</option><option value="matchno">Match No#</option><option value="username">Username</option><option value="nickname">Nickname</option><option value="name">Team Name</option><option value="short">Bracket Name</option><option value="clantag">Clan Tag</option><option value="ID">Team ID</option><option value="userID">User ID</option>';
	$dd_options=str_replace(' selected', '', $dd_options);
	$dd_options=str_replace('value="'.$_GET['type'].'"', 'value="'.$_GET['type'].'" selected', $dd_options);
	
           $form ='<form action="index.php" method="get">
                     <input type="hidden" name="site" value="matches">
		     <input type="hidden" name="action" value="search">
                     <input type="text" name="search" value="'.$_GET['search'].'"/>
                     Search By: 
                      <select name="type">
		        '.$dd_options.'
                      </select>
                    <input type="submit" value="Search" />
                  </form>
				  				  
                <div id="maincontent">
                  <br />Found <font color="red"><strong>'.(!$num_results2 ? "0" : $num_results2).'</strong></font> match'.($num_results2!=1 ? "es" : "").' for your search query <strong>'.$search.'</strong> in <strong>'.ucwords($type).'</strong> category.<hr>
                  <br>';
		  
           echo $form;
			
	if($error){
           echo $error;
        } 
		
        $prev = $start - $per_page;
        $next = $start + $per_page;
	
	// top pagination
		
        if (!($start<=0)){
          echo "<ul id='pagination-digg'><a class='previous-off' href='?site=matches&action=search&search=".$search."&type=".$type."&start=".$prev."#matchestable'><img border='0' src='images/cup/icons/goback.png' width='16' height='16'></a></ul>";
        }

        $a=1;
         for ($x=0; $x<$num_results2; $x=$x+$per_page){

          if($num_results2 > $per_page)
          {  
             if ($start!=$x){
                 echo "<ul id='pagination-digg'><a href='?site=matches&action=search&search=".$search."&type=".$type."&start=".$x."#matchestable'> $a</a></ul>";
             }
		     else{
                 echo "<ul id='pagination-digg'><a class='active' href='?site=matches&action=search&search=".$search."&type=".$type."&start=".$x."#matchestable'> <font color='white'>$a</font></a></strong></ul>";
             }
          }
        $a++;
        }

        if ($start + $per_page<$num_results2){
           echo "<a class='next' href='?site=matches&action=search&search=".$search."&type=".$type."&start=".$next."#matchestable'> <img border='0' src='images/cup/icons/goforward.png' width='16' height='16'></a>";
        }
	
	//!top pagination
	
	$cuptitle = '';
	
	eval ("\$one_head = \"".gettemplate("cup_matches_head")."\";");
	echo $one_head;

        for ($i=0; $i<$num_results; $i++) {
        $row = $result->fetch_assoc();
	
	                $matchid = $row['matchID'];
	
	                $typename = (league($matchid)=='cup' ? 'cupID' : 'ladID');
			$table = (league($matchid)=='cup' ? 'cups' : 'cup_ladders');
			$one_check = (league($matchid)=='cup' ? 'is1on1' : 'ladderis1on1');
			$cupID = getleagueID($matchid);
	
	                        $dd=mysql_fetch_array(safe_query("SELECT $typename FROM ".PREFIX."cup_matches WHERE matchID='$matchid'"));
	                        $ed=mysql_fetch_array(safe_query("SELECT game FROM ".PREFIX."$table WHERE ID='$cupID'"));
	
	                        $image = $ed['game'];
	                        $game = '<img src="images/games/'.$image.'.gif" width="20" height="20" border="0">';
			

	                              
	                              if(!$one_check($cupID)){	 
				      
	                                     $id = 'ID';                             
    	                                     $cc1=mysql_fetch_array(safe_query("SELECT $id FROM ".PREFIX."cup_all_clans WHERE ID='".$row['clan1']."'"));
    	                                     $cc2=mysql_fetch_array(safe_query("SELECT $id FROM ".PREFIX."cup_all_clans WHERE ID='".$row['clan2']."'"));
    	                              }
    	                              else{
				      
	                                     $id = 'userID';   
    	                                     $cc1=mysql_fetch_array(safe_query("SELECT $id FROM ".PREFIX."user WHERE userID='".$row['clan1']."'"));
    	                                     $cc2=mysql_fetch_array(safe_query("SELECT $id FROM ".PREFIX."user WHERE userID='".$row['clan2']."'"));
    	                              }
    	                          	                              
	                              $clan1 = $row['clan1'];
	                              $clan2 = $row['clan2'];
	                              $matchid = $row['matchID'];
	                              $matchno = (empty($row['matchno']) ? "OP" : $row['matchno']);
	                              $clanname = (empty($cc1[$id]) ? "(DELETED)" : getname1($row['clan1'],$cupID,$ac=0,strtolower($cuptitle)));
    	                              $opponent = (empty($cc2[$id]) ? "(DELETED)" : getname1($row['clan2'],$cupID,$ac=0,strtolower($cuptitle)));
	                              $match = '<img src="images/cup/icons/go.png"> <a href='.matchlink($matchid,$ac=0,$tg=0,$redirect=0).'><b>'.$matchid.'</b></a>';
	                              $date = date('l M dS Y', $row['date']);
	                              $vs = '<a href='.matchlink($matchid,$ac=0,$tg=0,$redirect=0).'><strong>vs.</strong></a>'; 
                               
	                              $score1 = $row['score1'];	
	                              $score2 = $row['score2'];		                                 
	                              $teammatches = '<a href="?site=matches&action=viewmatches&clanID='.$row['clan1'].'&'.$cuptype.'='.$cupID.($one_check($cupID) ? "&type=1" : "").'"><img border="0" src="images/cup/icons/add_result.gif" align="left" width="16" height="16"></a>';
	                              $oppmatches =  '<a href="?site=matches&action=viewmatches&clanID='.$row['clan2'].'&'.$cuptype.'='.$cupID.($one_check($cupID) ? "&type=1" : "").'"><img border="0" src="images/cup/icons/add_result.gif" align="right" width="16" height="16"></a>';
	                            
	                        $details = '<a href='.matchlink($matchid,$ac=0,$tg=0,$redirect=0).'><img src="images/icons/foldericons/folder.gif"></a>';
	                        $score1 = (!$score1 ? '&nbsp;(-)' : '&nbsp;('.$score1.')');
	                        $score2 = (!$score2 ? '&nbsp;(-)' : '&nbsp;('.$score2.')');  

                        unset($typename);
                        unset($table);
                        unset($one_check);
                        unset($cupID);	
                        unset($cuptitle);				
		
	        eval ("\$inctemp = \"".gettemplate("all_cup_matches")."\";");
	        echo $inctemp;	
	}
	
	eval ("\$footer = \"".gettemplate("1on1_foot")."\";");
	echo $footer;
		
	$result->free();
		
	// bottom pagination
        
        if (!($start<=0)){
          echo "<ul id='pagination-digg'><a class='previous-off' href='?site=matches&action=search&search=".$search."&type=".$type."&start=".$prev."#matchestable'><img border='0' src='images/cup/icons/goback.png' width='16' height='16'></a></ul>";
        }

        $a=1;
         for ($x=0; $x<$num_results2; $x=$x+$per_page){

          if($num_results2 > $per_page)
          {  
             if ($start!=$x){
                 echo "<ul id='pagination-digg'><a href='?site=matches&action=search&search=".$search."&type=".$type."&start=".$x."#matchestable'> $a</a></ul>";
             }
		     else{
                 echo "<ul id='pagination-digg'><a class='active' href='?site=matches&action=search&search=".$search."&type=".$type."&start=".$x."#matchestable'> <font color='white'>$a</font></a></strong></ul>";
             }
          }
        $a++;
        }

        if ($start + $per_page<$num_results2){
           echo "<a class='next' href='?site=matches&action=search&search=".$search."&type=".$type."&start=".$next."#matchestable'> <img border='0' src='images/cup/icons/goforward.png' width='16' height='16'></a>";
        }
		
	//!bottom pagination
		
		echo '</div>';
    
        }
	else{    
	
	   $dd_options='<option value="date">Recent</option><option value="matchID">Match ID</option><option value="matchno">Match No#</option><option value="username">Username</option><option value="nickname">Nickname</option><option value="name">Team Name</option><option value="short">Bracket Name</option><option value="clantag">Clan Tag</option><option value="ID">Team ID</option><option value="userID">User ID</option>';
	   $dd_options=str_replace(' selected', '', $dd_options);
	   $dd_options=str_replace('value="'.$_GET['type'].'"', 'value="'.$_GET['type'].'" selected', $dd_options);
	
           $form ='<form action="index.php" method="get">
                     <input type="hidden" name="site" value="matches">
		     <input type="hidden" name="action" value="search">
                     <input type="text" name="search" value="'.$_GET['search'].'"/>
                     Search By: 
                      <select name="type">
		        '.$dd_options.'
                      </select>
                    <input type="submit" value="Search" />
                  </form>';
		  
           echo $form;
    }       

  }        
echo ($cpr ? ca_copyr() : die());
?>