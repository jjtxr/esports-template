<?php

include ("config.php");

$bg1=BG_1;
$bg2=BG_1;
$bg3=BG_1;
$bg4=BG_1;

if(isset($id) and getnickname($id) != '') {

		$livecontact = safe_query("SELECT * FROM ".PREFIX."user WHERE userID='".$id."'");
		$lc = mysql_fetch_array($livecontact);

		if(!$lc['email_hide']) $email = '<a href="mailto:'.mail_protect(cleartext($lc['email'])).'"><img src="images/icons/email.gif" border="0" alt="'.$_language->module['email'].'" /></a>';
		
		$sem = '[0-9]{4,11}';
		if(eregi($sem, $lc['icq'])) $icq = '<a href="http://www.icq.com/people/about_me.php?uin='.sprintf('%d', $lc['icq']).'" target="_blank"><img src="http://online.mirabilis.com/scripts/online.dll?icq='.sprintf('%d', $lc['icq']).'&amp;img=5" border="0" alt="icq" /></a>';
		
		if($loggedin && $lc['userID'] != $userID) {
			$pm = '<a href="?site=messenger&amp;action=touser&amp;touser='.$lc['userID'].'" target="_top"><img src="images/icons/pm.gif" border="0" width="12" height="13" alt="messenger" /></a>';
			if(isignored($userID, $lc['userID'])) $buddy = '<a href="?site=buddys.php?action=readd&amp;id='.$lc['userID'].'&amp;userID='.$userID.'"target="_top"><img src="images/icons/buddy_readd.gif" border="0" alt="'.$_language->module['back_buddylist'].'" /></a>';
			elseif(isbuddy($userID, $lc['userID'])) $buddy = '<a href="?site=buddys.php?action=ignore&amp;id='.$lc['userID'].'&amp;userID='.$userID.'" target="_blank"><img src="images/icons/buddy_ignore.gif" border="0" alt="'.$_language->module['ignore_user'].'" /></a>';
			elseif($userID == $lc['userID']) $buddy = '';
			else $buddy = '<a href="?site=buddys.php?action=add&amp;id='.$lc['userID'].'&amp;userID='.$userID.'" target="_blank"><img src="images/icons/buddy_add.gif" border="0" alt="'.$_language->module['add_buddylist'].'" /></a>';
		}

        if($lc[skype]) $skype='<a href="skype:'.$lc[skype].'?userinfo"><img src="http://mystatus.skype.com/smallicon/'.$lc[skype].'" style="border: none;" width="16" height="16" alt="Mein Status" align="default" /></a>'; else $skype = '';

if($lc['msn']) $msn='<a href="http://members.msn.com/'.$lc[msn].'">
<img src="http://www.funnyweb.dk:8080/msn/'.$lc[msn].'/
onurl=www.funnyweb.dk/osi/iconset3/msnonline.gif/
offurl=www.funnyweb.dk/osi/iconset3/msnoffline.gif/
unknownurl=www.funnyweb.dk/osi/iconset3/msnunknown.gif"
align="default" border="0" width="18" height="18"
alt="" /></a>';
else $msn='';

if($lc[aim]) $aim='<a href="aim:goim?screenname='.$lc[aim].'">
<img src="http://www.funnyweb.dk:8080/aim/'.$lc[aim].'/
onurl=www.funnyweb.dk/osi/iconset3/aimonline.gif/
offurl=www.funnyweb.dk/osi/iconset3/aimoffline.gif/
unknownurl=www.funnyweb.dk/osi/iconset3/aimunknown.gif"
align="default" border="0" width="18" height="18"
alt="" /></a>';
else $aim='';

if($lc[yahoo]) $yahoo='<a href="ymsgr:sendIM?'.$lc[yahoo].'">
<img src="http://www.funnyweb.dk:8080/yahoo/'.$lc[yahoo].'/
onurl=www.funnyweb.dk/osi/iconset3/yahooonline.gif/
offurl=www.funnyweb.dk/osi/iconset3/yahoooffline.gif/
unknownurl=www.funnyweb.dk/osi/iconset3/yahoounknown.gif"
align="default" border="0" width="18" height="18"
alt="" /></a>';
else $yahoo='';

		
		if($lc[xfirec]) $xfirec='<a href="http://profile.xfire.com/'.$lc[xfire].'" target="_blank"><img src="http://miniprofile.xfire.com/bg/bg/type/4/'.$lc[xfire].'.gif" border="0" width="16" height="16" alt="Xfire" target="_blank"></a>'; else $xfirec = '';
		if($lc[steam]) $steam='<a href="http://steamcommunity.com/id/'.$lc[steam].'" target="_blank"><img src="images/cup/icons/steam.png" border="0" width="16" height="16" alt="Steam" /></a>'; else $steam = '';
	

   }
?>