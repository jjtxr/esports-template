<?php
/*
##########################################################################
#                                                                        #
#           Version 4       /                        /   /               #
#          -----------__---/__---__------__----__---/---/-               #
#           | /| /  /___) /   ) (_ `   /   ) /___) /   /                 #
#          _|/_|/__(___ _(___/_(__)___/___/_(___ _/___/___               #
#                       Free Content / Management System                 #
#                                   /                                    #
#                                                                        #
#                                                                        #
#   Copyright 2005-2010 by webspell.org                                  #
#                                                                        #
#   visit webSPELL.org, webspell.info to get webSPELL for free           #
#   - Script runs under the GNU GENERAL PUBLIC LICENSE                   #
#   - It's NOT allowed to remove this copyright-tag                      #
#   -- http://www.fsf.org/licensing/licenses/gpl.html                    #
#                                                                        #
#   Code based on WebSPELL Clanpackage (Michael Gruber - webspell.at),   #
#   Far Development by Development Team - webspell.org                   #
#                                                                        #
#   visit webspell.org                                                   #
#                                                                        #
##########################################################################
*/

// WHO IS ONLINE

$_language->read_module('whoisonline');

eval ("\$title_whoisonline = \"".gettemplate("title_whoisonline")."\";");
echo $title_whoisonline;

$ip_head=(issuperadmin($userID) ? '<td class="title_new" align="center">IP Address:</td>' : ""); 
$result_guests = safe_query("SELECT * FROM ".PREFIX."whoisonline WHERE userID='0'");
$guests = mysql_num_rows($result_guests);
$result_user = safe_query("SELECT * FROM ".PREFIX."whoisonline WHERE userID!='0'");
$user = mysql_num_rows($result_user);
$useronline = $guests + $user;
if($user==1) $user_on='<b>1</b> '.$_language->module['registered_user'];
else $user_on='<b>'.$user.'</b> '.$_language->module['registered_users'];

if($guests==1) $guests_on='<b>1</b> '.$_language->module['guest'];
else $guests_on= '<b>'.$guests.'</b> '.$_language->module['guests'];

$online=$_language->module['now_online'].' '.$user_on.' '.$_language->module['and'].' '.$guests_on;
$sort = 'time';
if(isset($_GET['sort'])){
  if($_GET['sort']=='nickname'){
  	$sort = 'nickname';
  }
}
$type = 'DESC';
if(isset($_GET['type'])){
  if($_GET['type']=='ASC'){
  	$type = 'ASC';
  }
}

if($type=="ASC"){
	$sorter='<a href="index.php?site=whoisonline&amp;sort='.$sort.'&amp;type=DESC">'.$_language->module['sort'].'</a> <img src="images/icons/asc.gif" width="9" height="7" border="0" alt="" />';
}
else{
	$sorter='<a href="index.php?site=whoisonline&amp;sort='.$sort.'&amp;type=ASC">'.$_language->module['sort'].'</a> <img src="images/icons/desc.gif" width="9" height="7" border="0" alt="" />';
}

$ergebnis = safe_query("SELECT w.*, u.nickname FROM ".PREFIX."whoisonline w LEFT JOIN ".PREFIX."user u ON u.userID = w.userID ORDER BY $sort $type");



eval ("\$whoisonline_head = \"".gettemplate("whoisonline_head")."\";");
echo $whoisonline_head;

$n=1;
while($ds=mysql_fetch_array($ergebnis)) {
	if($n%2) {
		$bg1=BG_1;
		$bg2=BG_2;
	}
	else {
		$bg1=BG_3;
		$bg2=BG_4;
	}
	
	$ip_content=(issuperadmin($userID) ? '<td bgcolor="'.$bg1.'" align="center">'.(!$ds['ip'] ? "n/a" : $ds['ip']).'</td>' : ""); 
    
		$nickname=($ds['userID'] ? '<a href="index.php?site=profile&amp;id='.$ds['userID'].'"><b>'.$ds['nickname'].'</b></a>' : $_language->module['guest']);
		if(isclanmember($ds['userID'])) $member=' <img src="images/icons/member.gif" width="6" height="11" alt="Clanmember" />';
		else $member='';
		if(getemailhide($ds['userID'])) $email = '';
		else $email='<a href="mailto:'.mail_protect(getemail($ds['userID'])).'"><img src="images/icons/email.gif" border="0" width="15" height="11" alt="e-mail" /></a>';

		$country='[flag]'.getcountry($ds['userID']).'[/flag]';
		$country=flags($country);

		if(!validate_url(gethomepage($ds['userID']))) $homepage='';
		else $homepage='<a href="'.gethomepage($ds['userID']).'" target="_blank"><img src="images/icons/hp.gif" border="0" width="14" height="14" alt="homepage" /></a>';

		$pm='';
		$buddy='';
		if($loggedin && $ds['userID'] != $userID) {
			$pm='<a href="index.php?site=messenger&amp;action=touser&amp;touser='.$ds['userID'].'"><img src="images/icons/pm.gif" border="0" width="12" height="13" alt="messenger" /></a>';
			if(isignored($userID, $ds['userID'])) $buddy='<a href="buddys.php?action=readd&amp;id='.$ds['userID'].'&amp;userID='.$userID.'"><img src="images/icons/buddy_readd.gif" width="16" height="16" border="0" alt="back to buddy-list" /></a>';
			elseif(isbuddy($userID, $ds['userID'])) $buddy='<a href="buddys.php?action=ignore&amp;id='.$ds['userID'].'&amp;userID='.$userID.'"><img src="images/icons/buddy_ignore.gif" width="16" height="16" border="0" alt="ignore user" /></a>';
			elseif($userID==$ds['userID']) $buddy='';
			else $buddy='<a href="buddys.php?action=add&amp;id='.$ds['userID'].'&amp;userID='.$userID.'"><img src="images/icons/buddy_add.gif" width="16" height="16" border="0" alt="add to buddylist" /></a>';
		}


    $url_site=(empty($ds['url']) ? "?site=".$ds['site'] : $ds['url']);
    $status = "viewing ". "<a href='$url_site'><strong>$ds[site]</strong></a>";

	eval ("\$whoisonline_content = \"".gettemplate("whoisonline_content")."\";");
	echo $whoisonline_content;
	$n++;
}

eval ("\$whoisonline_foot = \"".gettemplate("whoisonline_foot")."\";");
echo $whoisonline_foot;

// WHO WAS ONLINE

if($type=="ASC")
$sorter='<a href="index.php?site=whoisonline&amp;sort='.$sort.'&amp;type=DESC">'.$_language->module['sort'].'</a> <img src="images/icons/asc.gif" width="9" height="7" border="0" alt="" />';
else
$sorter='<a href="index.php?site=whoisonline&amp;sort='.$sort.'&amp;type=ASC">'.$_language->module['sort'].'</a> <img src="images/icons/desc.gif" width="9" height="7" border="0" alt="" />';

$ergebnis = safe_query("SELECT w.*, u.nickname FROM ".PREFIX."whowasonline w LEFT JOIN ".PREFIX."user u ON u.userID = w.userID ORDER BY $sort $type");

eval ("\$whowasonline_head = \"".gettemplate("whowasonline_head")."\";");
echo $whowasonline_head;

$n=1;
while($ds=mysql_fetch_array($ergebnis)) {
	if($n%2) {
		$bg1=BG_1;
		$bg2=BG_2;
	}
	else {
		$bg1=BG_3;
		$bg2=BG_4;
	}
	
    $ip_content=(issuperadmin($userID) ? '<td bgcolor="'.$bg1.'" align="center">'.(!$ds['ip'] ? "n/a" : $ds['ip']).'</td>' : ""); 

	$date=date("d.m.Y - H:i", $ds['time']);
	$nickname=($ds['userID'] ? '<a href="index.php?site=profile&amp;id='.$ds['userID'].'"><b>'.$ds['nickname'].'</b></a>' : $_language->module['guest']);
	if(isclanmember($ds['userID'])) $member=' <img src="images/icons/member.gif" width="6" height="11" alt="Clanmember" />';
	else $member='';
	if(getemailhide($ds['userID'])) $email = '';
	else $email='<a href="mailto:'.mail_protect(getemail($ds['userID'])).'"><img src="images/icons/email.gif" border="0" width="15" height="11" alt="e-mail" /></a>';

	$country='[flag]'.getcountry($ds['userID']).'[/flag]';
	$country=flags($country);

	if(!validate_url($ds['userID'])) $homepage='';
	else $homepage='<a href="'.gethomepage($ds['userID']).'" target="_blank"><img src="images/icons/hp.gif" border="0" width="14" height="14" alt="homepage" /></a>';

	$pm='';
	$buddy='';
	if($loggedin && $ds['userID'] != $userID) {
		$pm='<a href="index.php?site=messenger&amp;action=touser&amp;touser='.$ds['userID'].'"><img src="images/icons/pm.gif" border="0" width="12" height="13" alt="messenger" /></a>';
		if(isignored($userID, $ds['userID'])) $buddy='<a href="buddys.php?action=readd&amp;id='.$ds['userID'].'&amp;userID='.$userID.'"><img src="images/icons/buddy_readd.gif" width="16" height="16" border="0" alt="back to buddy-list" /></a>';
		elseif(isbuddy($userID, $ds['userID'])) $buddy='<a href="buddys.php?action=ignore&amp;id='.$ds['userID'].'&amp;userID='.$userID.'"><img src="images/icons/buddy_ignore.gif" width="16" height="16" border="0" alt="ignore user" /></a>';
		elseif($userID==$ds['userID']) $buddy='';
		else $buddy='<a href="buddys.php?action=add&amp;id='.$ds['userID'].'&amp;userID='.$userID.'"><img src="images/icons/buddy_add.gif" width="16" height="16" border="0" alt="add to buddylist" /></a>';
	}

    $url_site=(empty($ds['url']) ? "?site=".$ds['site'] : $ds['url']);
    $status = "viewed ". "<a href='$url_site'><strong>$ds[site]</strong></a>";

	eval ("\$whowasonline_content = \"".gettemplate("whowasonline_content")."\";");
	echo $whowasonline_content;
	$n++;
}

eval ("\$whowasonline_foot = \"".gettemplate("whowasonline_foot")."\";");
echo $whowasonline_foot;
?>