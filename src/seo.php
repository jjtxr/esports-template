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

$_language = $GLOBALS['_language'];
$_language->read_module('seo');

function settitle($string){
	return $GLOBALS['hp_title'].' - '.$string;
}

function breadcrumb($content){
	$breadcrumb = '<a href="index.php">'.$GLOBALS['hp_title'].'</a>';
	foreach($content AS $entry){
		$breadcrumb .= '&nbsp; &raquo; &nbsp;<a href="'.$entry['link'].'">'.$entry['text'].'</a>';
	}
	define('BREADCRUMB', $breadcrumb);
}

if(isset($_GET['action'])) $action = $_GET['action'];
else $action='';

switch ($GLOBALS['site']) {

	case 'about':
		define('PAGETITLE', settitle($_language->module['about']));
		$breadcrumb = array(array('link' => 'index.php?site=about', 'text' => $_language->module['about']));
		breadcrumb($breadcrumb);
		break;
	
	case 'articles':
		if(isset($_GET['articlesID'])) $articlesID = (int)$_GET['articlesID'];
		else $articlesID = '';
		if($action=="show") {
			$get=mysql_fetch_array(safe_query("SELECT title FROM `".PREFIX."articles` WHERE articlesID='$articlesID'"));
			define('PAGETITLE', settitle($_language->module['articles'].'&nbsp; &raquo; &nbsp;'.$get['title']));
			$breadcrumb = array(array('link' => 'index.php?site=articles', 'text' => $_language->module['articles']),
								array('link' => 'index.php?site=articles&action=show&articlesID='.$articlesID, 'text' => $get['title']));
			breadcrumb($breadcrumb);
		}
		else {
			define('PAGETITLE', settitle($_language->module['articles']));
			$breadcrumb = array(array('link' => 'index.php?site=articles', 'text' => $_language->module['articles']));
			breadcrumb($breadcrumb);
		}
		break;
	
	case 'awards':
		if(isset($_GET['awardID'])) $awardID = (int)$_GET['awardID'];
		else $awardID = '';		
		if($action=="details") {
			$get=mysql_fetch_array(safe_query("SELECT award FROM `".PREFIX."awards` WHERE awardID='$awardID'"));
			define('PAGETITLE', settitle($_language->module['awards'].'&nbsp; &raquo; &nbsp;'.$get['award']));
			$breadcrumb = array(array('link' => 'index.php?site=awards', 'text' => $_language->module['awards']),
								array('link' => 'index.php?site=awards&action=details&awardID='.$awardID, 'text' => $get['award']));
			breadcrumb($breadcrumb);
		}
		else {
			define('PAGETITLE', settitle($_language->module['awards']));
			$breadcrumb = array(array('link' => 'index.php?site=awards', 'text' => $_language->module['awards']));
			breadcrumb($breadcrumb);
		}
		break;
	
	case 'buddys':
		define('PAGETITLE', settitle($_language->module['buddys']));
		$breadcrumb = array(array('link' => 'index.php?site=buddys', 'text' => $_language->module['buddys']));
		breadcrumb($breadcrumb);
		break;
	
	case 'calendar':
		define('PAGETITLE', settitle($_language->module['calendar']));
		$breadcrumb = array(array('link' => 'index.php?site=calendar', 'text' => $_language->module['calendar']));
		breadcrumb($breadcrumb);
		break;
	
	case 'cash_box':
		define('PAGETITLE', settitle($_language->module['cash_box']));
		$breadcrumb = array(array('link' => 'index.php?site=cash_box', 'text' => $_language->module['cash_box']));
		breadcrumb($breadcrumb);
		break;
	
	case 'challenge':
		define('PAGETITLE', settitle($_language->module['challenge']));
		$breadcrumb = array(array('link' => 'index.php?site=challenge', 'text' => $_language->module['challenge']));
		breadcrumb($breadcrumb);
		break;
	
	case 'clanwars':
		if($action=="stats") {
			define('PAGETITLE', settitle($_language->module['clanwars'].'&nbsp; &raquo; &nbsp;'.$_language->module['stats']));
			$breadcrumb = array(array('link' => 'index.php?site=clanwars', 'text' => $_language->module['clanwars']),
								array('link' => 'index.php?site=clanwars&action=stats', 'text' => $_language->module['stats']));
			breadcrumb($breadcrumb);
		}
		else {
			define('PAGETITLE', settitle($_language->module['clanwars']));
			$breadcrumb = array(array('link' => 'index.php?site=clanwars', 'text' => $_language->module['clanwars']));
			breadcrumb($breadcrumb);
		}
		break;
	
	case 'clanwars_details':
		if(isset($_GET['cwID'])) $cwID = (int)$_GET['cwID'];
		else $cwID = '';
		$get=mysql_fetch_array(safe_query("SELECT opponent FROM `".PREFIX."clanwars` WHERE cwID='$cwID'"));
		define('PAGETITLE', settitle($_language->module['clanwars'].'&nbsp; &raquo; &nbsp;'.$_language->module['clanwars_details'].'&nbsp;'.$get['opponent']));
		$breadcrumb = array(array('link' => 'index.php?site=clanwars', 'text' => $_language->module['clanwars']),
							array('link' => 'index.php?site=clanwars_details&cwID='.$cwID, 'text' => $get['opponent']));
		breadcrumb($breadcrumb);
		break;
	
	case 'contact':
		define('PAGETITLE', settitle($_language->module['contact']));
		$breadcrumb = array(array('link' => 'index.php?site=contact', 'text' => $_language->module['contact']));
		breadcrumb($breadcrumb);
		break;
	
	case 'counter_stats':
		define('PAGETITLE', settitle($_language->module['stats']));
		$breadcrumb = array(array('link' => 'index.php?site=counter_stats', 'text' => $_language->module['stats']));
		breadcrumb($breadcrumb);
		break;
	
	case 'demos':
		if(isset($_GET['demoID'])) $demoID = (int)$_GET['demoID'];
		else $demoID = '';
		if($action=="showdemo") {
			$get=mysql_fetch_array(safe_query("SELECT game, clan1, clan2 FROM `".PREFIX."demos` WHERE demoID='$demoID'"));
			define('PAGETITLE', settitle($_language->module['demos'].'&nbsp; &raquo; &nbsp;'.$get['game'].' '.$_language->module['demo'].': '.$get['clan1'].' '.$_language->module['versus'].' '.$get['clan2']));
			$breadcrumb = array(array('link' => 'index.php?site=demos', 'text' => $_language->module['demos']),
								array('link' => 'index.php?site=demos&action=showdemo&demoID='.$demoID, 'text' => $get['game'].' '.$_language->module['demo'].': '.$get['clan1'].' '.$_language->module['versus'].' '.$get['clan2']));
			breadcrumb($breadcrumb);
		}
		else {
			define('PAGETITLE', settitle($_language->module['demos']));
			$breadcrumb = array(array('link' => 'index.php?site=demos', 'text' => $_language->module['demos']));
			breadcrumb($breadcrumb);
		}
		break;
	
	case 'faq':
		if(isset($_GET['faqcatID'])) $faqcatID = (int)$_GET['faqcatID'];
		else $faqcatID = '';
		if(isset($_GET['faqID'])) $faqID = (int)$_GET['faqID'];
		else $faqID = '';
		$get=mysql_fetch_array(safe_query("SELECT faqcatname FROM `".PREFIX."faq_categories` WHERE faqcatID='$faqcatID'"));
		$get2=mysql_fetch_array(safe_query("SELECT question FROM `".PREFIX."faq` WHERE faqID='$faqID'"));
		if($action=="faqcat") {
			define('PAGETITLE', settitle($_language->module['faq'].'&nbsp; &raquo; &nbsp;'.$get['faqcatname']));
			$breadcrumb = array(array('link' => 'index.php?site=faq', 'text' => $_language->module['faq']),
								array('link' => 'index.php?site=faq&action=faqcat&faqcatID='.$faqcatID, 'text' => $get['faqcatname']));
			breadcrumb($breadcrumb);
		}
		elseif($action=="faq") {
			define('PAGETITLE', settitle($_language->module['faq'].'&nbsp; &raquo; &nbsp;'.$get['faqcatname'].'&nbsp; &raquo; &nbsp;'.$get2['question']));
			$breadcrumb = array(array('link' => 'index.php?site=faq', 'text' => $_language->module['faq']),
								array('link' => 'index.php?site=faq&action=faqcat&faqcatID='.$faqcatID, 'text' => $get['faqcatname']),
								array('link' => 'index.php?site=faq&action=faq&faqID='.$faqID.'&faqcatID='.$faqcatID, 'text' => $get2['question']));
			breadcrumb($breadcrumb);
		}
		else {
			define('PAGETITLE', settitle($_language->module['faq']));
			$breadcrumb = array(array('link' => 'index.php?site=faq', 'text' => $_language->module['faq']));
			breadcrumb($breadcrumb);
		}
		break;
	
	case 'files':
		if(isset($_GET['cat'])) $cat = (int)$_GET['cat'];
		else $cat = '';
		if(isset($_GET['file'])) $file = (int)$_GET['file'];
		else $file = '';
		if(isset($_GET['cat'])) {
			$cat = mysql_fetch_array(safe_query("SELECT filecatID, name FROM ".PREFIX."files_categorys WHERE filecatID='".$cat."'"));
			define('PAGETITLE', settitle($_language->module['files'].'&nbsp; &raquo; &nbsp;'.$cat['name']));
			$breadcrumb = array(array('link' => 'index.php?site=files', 'text' => $_language->module['files']),
								array('link' => 'index.php?site=files&cat='.$cat, 'text' => $cat['name']));
			breadcrumb($breadcrumb);
		}
		elseif(isset($_GET['file'])) {
			$file = mysql_fetch_array(safe_query("SELECT fileID, filecatID, filename FROM ".PREFIX."files WHERE fileID='".$file."'"));
			$catname = mysql_fetch_array(safe_query("SELECT name FROM ".PREFIX."files_categorys WHERE filecatID='".$file['filecatID']."'"));
			define('PAGETITLE', settitle($_language->module['files'].'&nbsp; &raquo; &nbsp;'.$catname['name'].'&nbsp; &raquo; &nbsp;'.$file['filename']));
			$breadcrumb = array(array('link' => 'index.php?site=files', 'text' => $_language->module['files']),
								array('link' => 'index.php?site=files&cat='.$cat, 'text' => $catname['name']),
								array('link' => 'index.php?site=files&file='.$file, 'text' => $file['filename']));
			breadcrumb($breadcrumb);
		}
		else {
			define('PAGETITLE', settitle($_language->module['files']));
			$breadcrumb = array(array('link' => 'index.php?site=files', 'text' => $_language->module['files']));
			breadcrumb($breadcrumb);
		}
		break;
	
	case 'forum':
		if(isset($_GET['board'])) $board = (int)$_GET['board'];
		else $board = '';		
		if(isset($_GET['board'])) {
			$board = mysql_fetch_array(safe_query("SELECT boardID, name FROM ".PREFIX."forum_boards WHERE boardID='".$board."'"));
			define('PAGETITLE', settitle($_language->module['forum'].'&nbsp; &raquo; &nbsp;'.$board['name']));
			$breadcrumb = array(array('link' => 'index.php?site=forum', 'text' => $_language->module['forum']),
								array('link' => 'index.php?site=forum&board='.(int)$_GET['board'], 'text' => $board['name']));
			breadcrumb($breadcrumb);
		}
		else {
			define('PAGETITLE', settitle($_language->module['forum']));
			$breadcrumb = array(array('link' => 'index.php?site=forum', 'text' => $_language->module['forum']));
			breadcrumb($breadcrumb);
		}
		break;
	
	case 'forum_topic':
		if(isset($_GET['topic'])) $topic = (int)$_GET['topic'];
		else $topic = '';
		if(isset($_GET['topic'])) {
			$topic = mysql_fetch_array(safe_query("SELECT topicID, boardID, topic FROM ".PREFIX."forum_topics WHERE topicID='".$topic."'"));
			$boardname = mysql_fetch_array(safe_query("SELECT name FROM ".PREFIX."forum_boards WHERE boardID='".$topic['boardID']."'"));
			define('PAGETITLE', settitle($_language->module['forum'].'&nbsp; &raquo; &nbsp;'.$boardname['name'].'&nbsp; &raquo; &nbsp;'.$topic['topic']));
			$breadcrumb = array(array('link' => 'index.php?site=forum', 'text' => $_language->module['forum']),
								array('link' => 'index.php?site=forum&board='.$topic['boardID'], 'text' => $boardname['name']),
								array('link' => 'index.php?site=forum_topic&topic='.(int)$_GET['topic'], 'text' => $topic['topic']));
			breadcrumb($breadcrumb);
		}
		else {
			define('PAGETITLE', settitle($_language->module['forum']));
			$breadcrumb = array(array('link' => 'index.php?site=forum', 'text' => $_language->module['forum']));
			breadcrumb($breadcrumb);
		}
		break;
	
	case 'gallery':
		if(isset($_GET['groupID'])) $groupID = (int)$_GET['groupID'];
		else $groupID = '';
		if(isset($_GET['galleryID'])) $galleryID = (int)$_GET['galleryID'];
		else $galleryID = '';
		if(isset($_GET['picID'])) $picID = (int)$_GET['picID'];
		else $picID = '';
		if(isset($_GET['groupID'])) {
			$groupID = mysql_fetch_array(safe_query("SELECT groupID, name FROM ".PREFIX."gallery_groups WHERE groupID='".$groupID."'"));
			define('PAGETITLE', settitle($_language->module['gallery'].'&nbsp; &raquo; &nbsp;'.$groupID['name']));
			$breadcrumb = array(array('link' => 'index.php?site=gallery', 'text' => $_language->module['gallery']),
								array('link' => 'index.php?site=gallery&groupID='.$groupID['groupID'], 'text' => $groupID['name']));
			breadcrumb($breadcrumb);
		}
		elseif(isset($_GET['galleryID'])) {
			$galleryID = mysql_fetch_array(safe_query("SELECT galleryID, name, groupID FROM ".PREFIX."gallery WHERE galleryID='".$galleryID."'"));
			$groupname = mysql_fetch_array(safe_query("SELECT groupID, name FROM ".PREFIX."gallery_groups WHERE groupID='".$galleryID['groupID']."'"));
			if($groupname['name'] == "") $groupname['name'] = $_language->module['usergallery'];
			define('PAGETITLE', settitle($_language->module['gallery'].'&nbsp; &raquo; &nbsp;'.$groupname['name'].'&nbsp; &raquo; &nbsp;'.$galleryID['name']));
			$breadcrumb = array(array('link' => 'index.php?site=gallery', 'text' => $_language->module['gallery']),
								array('link' => 'index.php?site=gallery&groupID='.$groupname['groupID'], 'text' => $groupname['name']),
								array('link' => 'index.php?site=gallery&galleryID='.$galleryID['galleryID'], 'text' => $galleryID['name']));
			breadcrumb($breadcrumb);
		}
		elseif(isset($_GET['picID'])) {
			$getgalleryname = mysql_fetch_array(safe_query("SELECT gal.groupID, gal.galleryID, gal.name FROM ".PREFIX."gallery_pictures as pic, ".PREFIX."gallery as gal WHERE pic.picID='".$_GET['picID']."' AND gal.galleryID=pic.galleryID"));
			$getgroupname = mysql_fetch_array(safe_query("SELECT name FROM ".PREFIX."gallery_groups WHERE groupID='".$getgalleryname['groupID']."'"));
			if($getgroupname['name'] == "") $getgroupname['name'] = $_language->module['usergallery'];
			$picID = mysql_fetch_array(safe_query("SELECT picID, galleryID, name FROM ".PREFIX."gallery_pictures WHERE picID='".$picID."'"));
			define('PAGETITLE', settitle($_language->module['gallery'].'&nbsp; &raquo; &nbsp;'.$getgroupname['name'].'&nbsp; &raquo; &nbsp;'.$getgalleryname['name'].'&nbsp; &raquo; &nbsp;'.$picID['name']));
			$breadcrumb = array(array('link' => 'index.php?site=gallery', 'text' => $_language->module['gallery']),
								array('link' => 'index.php?site=gallery&groupID='.$getgalleryname['groupID'], 'text' => $getgroupname['name']),
								array('link' => 'index.php?site=gallery&galleryID='.$getgalleryname['galleryID'], 'text' => $getgalleryname['name']),
								array('link' => 'index.php?site=gallery&picID='.$picID['picID'], 'text' => $picID['name']));
			breadcrumb($breadcrumb);
		}
		else {
			define('PAGETITLE', settitle($_language->module['gallery']));
			$breadcrumb = array(array('link' => 'index.php?site=gallery', 'text' => $_language->module['gallery']));
			breadcrumb($breadcrumb);
		}
		break;
	
	case 'guestbook':
		define('PAGETITLE', settitle($_language->module['guestbook']));
		$breadcrumb = array(array('link' => 'index.php?site=guestbook', 'text' => $_language->module['guestbook']));
		breadcrumb($breadcrumb);
		break;
	
	case 'history':
		define('PAGETITLE', settitle($_language->module['history']));
		$breadcrumb = array(array('link' => 'index.php?site=history', 'text' => $_language->module['history']));
		breadcrumb($breadcrumb);
		break;
	
	case 'imprint':
		define('PAGETITLE', settitle($_language->module['imprint']));
		$breadcrumb = array(array('link' => 'index.php?site=imprint', 'text' => $_language->module['imprint']));
		breadcrumb($breadcrumb);
		break;
	
	case 'joinus':
		define('PAGETITLE', settitle($_language->module['joinus']));
		$breadcrumb = array(array('link' => 'index.php?site=joinus', 'text' => $_language->module['joinus']));
		breadcrumb($breadcrumb);
		break;
	
	case 'links':
		if(isset($_GET['linkcatID'])) $linkcatID = (int)$_GET['linkcatID'];
		else $linkcatID = '';
		if($action=="show") {
			$get=mysql_fetch_array(safe_query("SELECT name FROM `".PREFIX."links_categorys` WHERE linkcatID='$linkcatID'"));
			define('PAGETITLE', settitle($_language->module['links'].'&nbsp; &raquo; &nbsp;'.$get['name']));
			$breadcrumb = array(array('link' => 'index.php?site=links', 'text' => $_language->module['links']),
								array('link' => 'index.php?site=links&action=show&linkcatID='.$linkcatID, 'text' => $get['name']));
			breadcrumb($breadcrumb);
		}
		else {
			define('PAGETITLE', settitle($_language->module['links']));
			$breadcrumb = array(array('link' => 'index.php?site=links', 'text' => $_language->module['links']));
			breadcrumb($breadcrumb);
		}
		break;
	
	case 'linkus':
		define('PAGETITLE', settitle($_language->module['linkus']));
		$breadcrumb = array(array('link' => 'index.php?site=linkus', 'text' => $_language->module['linkus']));
		breadcrumb($breadcrumb);
		break;
	
	case 'login':
		define('PAGETITLE', settitle($_language->module['login']));
		$breadcrumb = array(array('link' => 'index.php?site=login', 'text' => $_language->module['login']));
		breadcrumb($breadcrumb);
		break;
	
	case 'loginoverview':
		define('PAGETITLE', settitle($_language->module['loginoverview']));
		$breadcrumb = array(array('link' => 'index.php?site=loginoverview', 'text' => $_language->module['loginoverview']));
		breadcrumb($breadcrumb);
		break;
	
	case 'lostpassword':
		define('PAGETITLE', settitle($_language->module['lostpassword']));
		$breadcrumb = array(array('link' => 'index.php?site=lostpassword', 'text' => $_language->module['lostpassword']));
		breadcrumb($breadcrumb);
		break;
	
	case 'members':
		if(isset($_GET['squadID'])) $squadID = (int)$_GET['squadID'];
		else $squadID = '';
		if($action=="show") {
			$get=mysql_fetch_array(safe_query("SELECT name FROM `".PREFIX."squads` WHERE squadID='$squadID'"));
			define('PAGETITLE', settitle($_language->module['members'].'&nbsp; &raquo; &nbsp;'.$get['name']));
			$breadcrumb = array(array('link' => 'index.php?site=members', 'text' => $_language->module['members']),
								array('link' => 'index.php?site=members&action=show&squadID='.$squadID, 'text' => $get['name']));
			breadcrumb($breadcrumb);
		}
		else {
			define('PAGETITLE', settitle($_language->module['members']));
			$breadcrumb = array(array('link' => 'index.php?site=members', 'text' => $_language->module['members']));
			breadcrumb($breadcrumb);
		}
		break;
	
	case 'messenger':
		define('PAGETITLE', settitle($_language->module['messenger']));
		$breadcrumb = array(array('link' => 'index.php?site=messenger', 'text' => $_language->module['messenger']));
		breadcrumb($breadcrumb);
		break;
	
	case 'myprofile':
		define('PAGETITLE', settitle($_language->module['myprofile']));
		$breadcrumb = array(array('link' => 'index.php?site=myprofile', 'text' => $_language->module['myprofile']));
		breadcrumb($breadcrumb);
		break;
	
	case 'news':
		if($action=="archive") {
			define('PAGETITLE', settitle($_language->module['news'].'&nbsp; &raquo; &nbsp;'.$_language->module['archive']));
			$breadcrumb = array(array('link' => 'index.php?site=news', 'text' => $_language->module['news']),
								array('link' => 'index.php?site=news&action=archive', 'text' => $_language->module['archive']));
			breadcrumb($breadcrumb);
		}
		else {
			define('PAGETITLE', settitle($_language->module['news']));
			$breadcrumb = array(array('link' => 'index.php?site=news', 'text' => $_language->module['news']));
			breadcrumb($breadcrumb);
		}
		break;
	
	case 'news_comments':
		if(isset($_GET['newsID'])) $newsID = (int)$_GET['newsID'];
		else $newsID = '';
		$get=mysql_fetch_array(safe_query("SELECT headline FROM `".PREFIX."news_contents` WHERE newsID='$newsID'"));
		define('PAGETITLE', settitle($_language->module['news'].'&nbsp; &raquo; &nbsp;'.$get['headline']));
		$breadcrumb = array(array('link' => 'index.php?site=news', 'text' => $_language->module['news']),
							array('link' => 'index.php?site=news_comments&newsID='.$newsID, 'text' => $get['headline']));
		breadcrumb($breadcrumb);
		break;
	
	case 'newsletter':
		define('PAGETITLE', settitle($_language->module['newsletter']));
		$breadcrumb = array(array('link' => 'index.php?site=newsletter', 'text' => $_language->module['newsletter']));
		breadcrumb($breadcrumb);
		break;
	
	case 'partners':
		define('PAGETITLE', settitle($_language->module['partners']));
		$breadcrumb = array(array('link' => 'index.php?site=partners', 'text' => $_language->module['partners']));
		breadcrumb($breadcrumb);
		break;
	
	case 'polls':
		if(isset($_GET['vote'])) $vote = (int)$_GET['vote'];
		else $vote = '';
		if(isset($_GET['pollID'])) $pollID = (int)$_GET['pollID'];
		else $pollID = '';
		if(isset($_GET['vote'])) {
			$vote = mysql_fetch_array(safe_query("SELECT pollID, titel FROM ".PREFIX."poll WHERE pollID='".$vote."'"));
			define('PAGETITLE', settitle($_language->module['polls'].'&nbsp; &raquo; &nbsp;'.$vote['titel']));
			$breadcrumb = array(array('link' => 'index.php?site=polls', 'text' => $_language->module['polls']),
								array('link' => 'index.php?site=polls&vote='.$vote['pollID'], 'text' => $vote['titel']));
			breadcrumb($breadcrumb);
		}
		elseif(isset($_GET['pollID'])) {
			$pollID = mysql_fetch_array(safe_query("SELECT pollID, titel FROM ".PREFIX."poll WHERE pollID='".$pollID."'"));
			define('PAGETITLE', settitle($_language->module['polls'].'&nbsp; &raquo; &nbsp;'.$pollID['titel']));
			$breadcrumb = array(array('link' => 'index.php?site=polls', 'text' => $_language->module['polls']),
								array('link' => 'index.php?site=polls&pollID='.$pollID['pollID'], 'text' => $pollID['titel']));
			breadcrumb($breadcrumb);
		}
		else {
			define('PAGETITLE', settitle($_language->module['polls']));
			$breadcrumb = array(array('link' => 'index.php?site=polls', 'text' => $_language->module['polls']));
			breadcrumb($breadcrumb);
		}
		break;
	
	case 'profile':
		if(isset($_GET['id'])) $id = (int)$_GET['id'];
		else $id='';
		define('PAGETITLE', settitle($_language->module['profile'].' '.getnickname($id)));
		$breadcrumb = array(array('link' => 'index.php?site=profile&id='.$id, 'text' => $_language->module['profile'].' '.getnickname($id)));
		breadcrumb($breadcrumb);
		break;
	
	case 'register':
		define('PAGETITLE', settitle($_language->module['register']));
		$breadcrumb = array(array('link' => 'index.php?site=register', 'text' => $_language->module['register']));
		breadcrumb($breadcrumb);
		break;
	
	case 'registered_users':
		define('PAGETITLE', settitle($_language->module['registered_users']));
		$breadcrumb = array(array('link' => 'index.php?site=registered_users', 'text' => $_language->module['registered_users']));
		breadcrumb($breadcrumb);
		break;
	
	case 'search':
		define('PAGETITLE', settitle($_language->module['search']));
		$breadcrumb = array(array('link' => 'index.php?site=search', 'text' => $_language->module['search']));
		breadcrumb($breadcrumb);
		break;
		
	case 'server':
		define('PAGETITLE', settitle($_language->module['server']));
		$breadcrumb = array(array('link' => 'index.php?site=server', 'text' => $_language->module['server']));
		breadcrumb($breadcrumb);
		break;
		
	case 'shoutbox':
		define('PAGETITLE', settitle($_language->module['shoutbox']));
		$breadcrumb = array(array('link' => 'index.php?site=shoutbox', 'text' => $_language->module['shoutbox']));
		breadcrumb($breadcrumb);
		break;
	
	case 'sponsors':
		define('PAGETITLE', settitle($_language->module['sponsors']));
		$breadcrumb = array(array('link' => 'index.php?site=sponsors', 'text' => $_language->module['sponsors']));
		breadcrumb($breadcrumb);
		break;
	
	case 'squads':
		if(isset($_GET['squadID'])) $squadID = (int)$_GET['squadID'];
		else $squadID = '';
		if($action=="show") {
			$get=mysql_fetch_array(safe_query("SELECT name FROM `".PREFIX."squads` WHERE squadID='$squadID'"));
			define('PAGETITLE', settitle($_language->module['squads'].'&nbsp; &raquo; &nbsp;'.$get['name']));
			$breadcrumb = array(array('link' => 'index.php?site=squads', 'text' => $_language->module['squads']),
								array('link' => 'index.php?site=squads&action=show&squadID='.$squadID, 'text' => $get['name']));
			breadcrumb($breadcrumb);
		}
		else {
			define('PAGETITLE', settitle($_language->module['squads']));
			$breadcrumb = array(array('link' => 'index.php?site=squads', 'text' => $_language->module['squads']));
			breadcrumb($breadcrumb);
		}
		break;
	
	case 'static':
		if(isset($_GET['staticID'])) $staticID = (int)$_GET['staticID'];
		else $staticID = '';
		$get=mysql_fetch_array(safe_query("SELECT name FROM `".PREFIX."static` WHERE staticID='$staticID'"));
		define('PAGETITLE', settitle($get['name']));
		$breadcrumb = array(array('link' => 'index.php?site=static&staticID='.$staticID, 'text' => $get['name']));
		breadcrumb($breadcrumb);
		break;
	
	case 'usergallery':
		define('PAGETITLE', settitle($_language->module['usergallery']));
		$breadcrumb = array(array('link' => 'index.php?site=usergallery', 'text' => $_language->module['usergallery']));
		breadcrumb($breadcrumb);
		break;
	
	case 'whoisonline':
		define('PAGETITLE', settitle($_language->module['whoisonline']));
		$breadcrumb = array(array('link' => 'index.php?site=whoisonline', 'text' => $_language->module['whoisonline']));
		breadcrumb($breadcrumb);
		break;
	
	default:
		define('PAGETITLE', settitle($_language->module['news']));
		break;
}
?>