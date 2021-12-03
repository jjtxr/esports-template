<link href="cup.css" rel="stylesheet" type="text/css">

<?php
/*
##########################################################################
#               CUP ADDON LADDER EXTENSION BY CUPADDON.COM               #                                                                                                                    #
##########################################################################
*/

$_language->read_module('platforms');

include ("config.php");

$bg1=BG_1;
$bg2=BG_1;
$bg3=BG_1;
$bg4=BG_1;
$cpr=ca_copyr();
if(!$cpr || !ca_copyr()) die();

//date and timezone

$timezone = safe_query("SELECT timezone FROM ".PREFIX."cup_settings");
$tz = mysql_fetch_array($timezone); $gmt = $tz['timezone'];
date_default_timezone_set($tz['timezone']);

//get platforms

$platforms = safe_query("SELECT platform FROM ".PREFIX."cup_platforms GROUP BY platform");

  if(!mysql_num_rows($platforms)) {
      echo '(no platforms created)<br />';
  }

  if(iscupadmin($userID)) {
      echo '<input type="button" onclick="MM_openBrWindow(\'admin/admincenter.php?site=platforms\',\'Manage Platforms\',\'toolbar=no,status=no,scrollbars=yes,width=800,height=550\');" value="Manage Platforms" />';
  }

    while($pt=mysql_fetch_array($platforms)) { $platform = $pt['platform'];

	eval ("\$plat_head = \"".gettemplate("platforms_head")."\";");
	echo $plat_head;

if(!$show_inactive_platforms) $activity = "WHERE platform='$platform'";
else $activity = "WHERE platform='$platform' AND status='1'"; 

  $getplatforms = safe_query("SELECT * FROM ".PREFIX."cup_platforms $activity"); 
  while($pl=mysql_fetch_array($getplatforms)) { $platID = $pl['ID'];

   $getladders = safe_query("SELECT * FROM ".PREFIX."cup_ladders WHERE platID='$platID' AND 1on1='1'");
   $single_ladder_rows = mysql_num_rows($getladders);

   $getladders1 = safe_query("SELECT * FROM ".PREFIX."cup_ladders WHERE platID='$platID' AND 1on1='0'");
   $team_ladder_rows = mysql_num_rows($getladders1);

   $getladders2 = safe_query("SELECT * FROM ".PREFIX."cup_ladders WHERE platID='$platID'");
   $total_ladder_rows = mysql_num_rows($getladders2);

   $getteams = safe_query("SELECT * FROM ".PREFIX."cup_clans WHERE platID='$platID' AND checkin='1'");
   $active_teams = mysql_num_rows($getteams);

   $getteams2 = safe_query("SELECT * FROM ".PREFIX."cup_clans WHERE platID='$platID'");
   $all_teams = mysql_num_rows($getteams2);
   
   $getIDs = safe_query("SELECT ID FROM ".PREFIX."cup_ladders WHERE platID='$platID'");
   if(!mysql_num_rows($getIDs)) { $played_matches = "0"; }

   while($dp=mysql_fetch_array($getIDs)) {

   $getmatches = safe_query("SELECT * FROM ".PREFIX."cup_matches WHERE ladID='".$dp['ID']."'");
   $played_matches = mysql_num_rows($getmatches); }

   $logo = getplatlogo($platID);

	eval ("\$plat_content = \"".gettemplate("platforms_content")."\";");
	echo $plat_content;

   }echo '</table>';
}echo ($cpr ? ca_copyr() : die());
?>