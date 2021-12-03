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

if(isset($_GET['action'])) $action = $_GET['action'];
else $action = '';

if(isset($_POST['save'])) {
	$title 		= 	$_POST['title'];
	$category 	= 	$_POST['category'];
	$uploader 	= 	$_POST['uploader'];
	$video 		= 	$_POST['video'];
	$type 		= 	$_POST['type'];
	$portal 	= 	$_POST['portal'];
	$embed 		= 	$_POST['embed'];
	if(isset($_FILES['vidpreview'])) $vidpreview = $_FILES['vidpreview'];
	else $vidpreview = null;
	$desc		= 	$_POST['description'];
	$comments	= 	$_POST['comments'];
	
	// Check what type is video: Portal or embed
	if(empty($video)) $videocode = $embed;
	else $videocode = $video;
	
	// Insert data then screenshot ---
	safe_query("INSERT INTO ".PREFIX."videos
		( date, uploader, vidcatID, video, type, portal, title, description, comments )
  		values
  		( '".time()."', '$uploader', '$category', '$videocode', '$type', '$portal', '$title', '$desc', '$comments' ) ");
		
	// Screenshot upload query ---	
	$filepath 	= 	"images/videos/";
	$id 		= 	mysql_insert_id();
	
	if($vidpreview['name'] != "") {
		move_uploaded_file($vidpreview['tmp_name'], $filepath.$vidpreview['name'].".tmp");
		@chmod($filepath.$vidpreview['name'].".tmp", 0755);
		$getimg = getimagesize($filepath.$vidpreview['name'].".tmp");

		$pic = '';
		if($getimg[2] == IMAGETYPE_GIF) $pic=$id.'.gif';
		elseif($getimg[2] == IMAGETYPE_JPEG) $pic=$id.'.jpg';
		elseif($getimg[2] == IMAGETYPE_PNG) $pic=$id.'.png';
		if($pic != "") {
			if(file_exists($filepath.$id.'.gif')) unlink($filepath.$id.'.gif');
			if(file_exists($filepath.$id.'.jpg')) unlink($filepath.$id.'.jpg');
			if(file_exists($filepath.$id.'.png')) unlink($filepath.$id.'.png');
			rename($filepath.$vidpreview['name'].".tmp", $filepath.$pic);
			safe_query("UPDATE ".PREFIX."videos SET vidpreview='".$pic."' WHERE vidID='".$id."'");
		}  else {
			@unlink($filepath.$vidpreview['name'].".tmp");
			$error = 'Image is in incorrect format!';
			die('<b>'.$error.'</b><br /><br /><a href="index.php?site=videos&amp;action=edit&amp;vidID='.$id.'">&laquo; Back</a>');
		}
	}
		
	header("Location: index.php?site=videos&action=watch&vidID=$id");
}

if(isset($_POST['saveedit'])) {
	$title 		= 	$_POST['title'];
	$category 	= 	$_POST['category'];
	$video 		= 	$_POST['video'];
	$type 		= 	$_POST['type'];
	$portal 	= 	$_POST['portal'];
	$embed 		= 	$_POST['embed'];
	if(isset($_FILES['vidpreview'])) $vidpreview = $_FILES['vidpreview'];
	else $vidpreview = null;
	$desc		= 	$_POST['description'];
	$comments	= 	$_POST['comments'];
	$vidID		= 	$_POST['vidID'];
	
	if(empty($video)) $videocode = $embed;
	else $videocode = $video;
	
	safe_query("UPDATE ".PREFIX."videos SET date='$date',
	                              vidcatID='$category',
								  video='$videocode',
								  type='$type',
								  portal='$portal',
								  title='$title',
								  description='$desc',
								  comments='$comments'
								  WHERE vidID='$vidID' ");
								  
	// Screenshot upload query ---	
	$filepath 	= 	"images/videos/";
	$id 		= 	$vidID;
	
	if($vidpreview['name'] != "") {
		move_uploaded_file($vidpreview['tmp_name'], $filepath.$vidpreview['name'].".tmp");
		@chmod($filepath.$vidpreview['name'].".tmp", 0755);
		$getimg = getimagesize($filepath.$vidpreview['name'].".tmp");

		$pic = '';
		if($getimg[2] == IMAGETYPE_GIF) $pic=$id.'.gif';
		elseif($getimg[2] == IMAGETYPE_JPEG) $pic=$id.'.jpg';
		elseif($getimg[2] == IMAGETYPE_PNG) $pic=$id.'.png';
		if($pic != "") {
			if(file_exists($filepath.$id.'.gif')) unlink($filepath.$id.'.gif');
			if(file_exists($filepath.$id.'.jpg')) unlink($filepath.$id.'.jpg');
			if(file_exists($filepath.$id.'.png')) unlink($filepath.$id.'.png');
			rename($filepath.$vidpreview['name'].".tmp", $filepath.$pic);
			safe_query("UPDATE ".PREFIX."videos SET vidpreview='".$pic."' WHERE vidID='".$id."'");
		}  else {
			@unlink($filepath.$vidpreview['name'].".tmp");
			$error = 'Image is in incorrect format!';
			die('<b>'.$error.'</b><br /><br /><a href="index.php?site=videos&amp;action=edit&amp;vidID='.$id.'">&laquo; Back</a>');
		}
	}
	
	header("Location: index.php?site=videos&action=watch&vidID=$id");
	
}

if(isset($_GET['delete'])) {
	include("_mysql.php");
	include("_settings.php");
	include("_functions.php");

	if(!ispageadmin($userID)) die('You have no access!');

	$ds=mysql_fetch_array(safe_query("SELECT vidpreview FROM ".PREFIX."videos WHERE vidID='".$_GET['vidID']."'"));
	if($ds['vidpreview']) {
		$filepath = "images/videos/";
		if(file_exists($filepath.$screen)) @unlink($filepath.$screen);
	}

	safe_query("DELETE FROM ".PREFIX."videos WHERE vidID='".$_GET['vidID']."'");
	safe_query("DELETE FROM ".PREFIX."comments WHERE parentID='".$_GET['vidID']."' AND type='vi'");

	if(isset($close)) echo'<body onload="window.close()"></body>';
	else header("Location: index.php?site=videos");
}

/* --- ACTIONS --- */

if($action=="new") {

	$bg1=BG_1;
	$bg2=BG_2;
	$pagebg=PAGEBG;
	$border=BORDER;
	$bghead=BGHEAD;
	$bgcat=BGCAT;
	
	if(ispageadmin($userID)) {
	
		$allportals	= getvideoportals();
		$allcats 	= getvideocats();	
		$comments = '<option value="0">Disable comments</option><option value="1">Enable user comments</option><option value="2">Enable comments</option>';
	
		eval ("\$videos_new = \"".gettemplate("videos_new")."\";");
		echo $videos_new;
	}

}

elseif($action=="edit") {

	$vidID = $_GET['vidID'];
	
	if(ispageadmin($userID)) {
		$ds = mysql_fetch_array(safe_query("SELECT * FROM ".PREFIX."videos WHERE vidID='$vidID'"));
		
		$title = getinput($ds['title']);
		
		if($ds['type']==1) {
			$video = getinput($ds['video']);
			$embed = '';
		}
		else {
			$embed = getinput($ds['video']);
			$video = '';
		}
		
		$desc = getinput($ds['description']);
	
		$bg1	= BG_1;
		$bg2	= BG_2;
		$pagebg	= PAGEBG;
		$border	= BORDER;
		$bghead	= BGHEAD;
		$bgcat	= BGCAT;
		
		//Dropdowns -> Choose active
		$allportals	= getvideoportals();
		$allportals = str_replace('value="'.$ds['portal'].'"', 'value="'.$ds['portal'].'" selected="selected"', $allportals);
		$allcats 	= getvideocats();	
		$allcats 	= str_replace('value="'.$ds['vidcatID'].'"', 'value="'.$ds['vidcatID'].'" selected="selected"', $allcats);
		$comments 	= '<option value="0">Disable comments</option><option value="1">Enable user comments</option><option value="2">Enable comments</option>';
		$comments 	= str_replace('value="'.$ds['comments'].'"', 'value="'.$ds['comments'].'" selected="selected"', $comments);
	
		if($ds['type']==1) {
			$type='<input onclick="document.getElementById(\'embed\').style.display = \'none\'; document.getElementById(\'portal\').style.display = \'block\'" type="radio" name="type" value="1" checked="checked" /> Portal &nbsp; <input onclick="document.getElementById(\'embed\').style.display = \'block\'; document.getElementById(\'portal\').style.display = \'none\'" type="radio" name="type" value="0" /> Embed';
			$display = 'none';
			$display2 = 'block';
		}
		else {
			$type='<input onclick="document.getElementById(\'embed\').style.display = \'none\'; document.getElementById(\'portal\').style.display = \'block\'" type="radio" name="type" value="1" /> Portal &nbsp; <input onclick="document.getElementById(\'embed\').style.display = \'block\'; document.getElementById(\'portal\').style.display = \'none\'" type="radio" name="type" value="0" checked="checked" /> Embed';
			$display = 'block';
			$display2 = 'none';
		}
	
		eval ("\$videos_edit = \"".gettemplate("videos_edit")."\";");
		echo $videos_edit;
	
	}

}

elseif($action=="watch") {
	$vidID = (int)$_GET['vidID'];
	$query = safe_query("SELECT * FROM ".PREFIX."videos WHERE vidID='".$vidID."'");
	
	eval ("\$title_videos = \"".gettemplate("title_videos")."\";");
	echo $title_videos;
	
	echo'<input type="button" onclick="MM_goToURL(\'parent\',\'index.php?site=videos\');return document.MM_returnValue" value="All videos" /> ';
	if(ispageadmin($userID)) echo'<input type="button" onclick="MM_goToURL(\'parent\',\'index.php?site=videos&amp;action=new\');return document.MM_returnValue" value="New video" /><br /><br />';
	
	if(mysql_num_rows($query)) {
		$ds = mysql_fetch_array($query);
		
		safe_query("UPDATE ".PREFIX."videos SET views=views+1 WHERE vidID='".$vidID."'");
		
		$videoID = $ds['video'];
		$portal = $ds['portal'];
		$show = showvideo($videoID, $portal);
		$title = clearfromtags($ds['title']);
		$date = date("d.m.Y", $ds['date']);
		$type = $ds['type'];
		$uploader = getnickname($ds['uploader']);
		$views = $ds['views'];
		$description = cleartext($ds['description']);
		
		$comments_allowed = $ds['comments'];
		
		$bg1=BG_1;
		$bg2=BG_2;
		$pagebg=PAGEBG;
		$border=BORDER;
		
		if($type == 1) {
			$stream = showvideo($videoID, $portal);
		}
		else {
			$stream = htmloutput($videoID);
		}
		
		if(ispageadmin($userID)) $adminaction = '<input type="button" onclick="MM_goToURL(\'parent\',\'index.php?site=videos&amp;action=edit&amp;vidID='.$vidID.'\');return document.MM_returnValue" value="edit" />
		<input type="button" onclick="MM_confirm(\'Really delete this video?\', \'videos.php?delete=true&amp;vidID='.$vidID.'\');" value="Delete" />';
		else $adminaction = '';
	   
		eval ("\$videos_watch = \"".gettemplate("videos_watch")."\";");
		echo $videos_watch;
		
		$parentID = $vidID;
		$type = "vi";
		$referer = "index.php?site=videos&amp;action=watch&amp;vidID=$vidID";

		include("comments.php");
	}
	else {
		echo 'Video can\'t be played!';
	}
}

elseif($action=="cat") {
	$vidcatID = (int)$_GET['vidcatID'];
	$query = safe_query("SELECT * FROM ".PREFIX."videos WHERE vidcatID='".$vidcatID."'");
	
	eval ("\$title_videos = \"".gettemplate("title_videos")."\";");
	echo $title_videos;

	echo'<input type="button" onclick="MM_goToURL(\'parent\',\'index.php?site=videos\');return document.MM_returnValue" value="All videos" /> ';
	if(ispageadmin($userID)) echo'<input type="button" onclick="MM_goToURL(\'parent\',\'index.php?site=videos&amp;action=new\');return document.MM_returnValue" value="New video" /><br /><br />';
	
	
	
	$n=1;
	if(mysql_num_rows($query)) {
		while($ds=mysql_fetch_array($query)) {
		
			if($n%2) {
				$bg1=BG_1;
				$bg2=BG_2;
			}
			else {
				$bg1=BG_3;
				$bg2=BG_4;
			}
			
			$title = clearfromtags($ds['title']);
			$vidID = $ds['vidID'];
			$videoID = $ds['video'];
			$vidpreview = $ds['vidpreview'];
			$portal = $ds['portal'];
			$date = date("d.m.Y", $ds['date']);
			$type = $ds['type'];
			$uploader = getnickname($ds['uploader']);
			$views = $ds['views'];
			$catname = getvidcatname($ds['vidcatID']);
			
			if($type==1) {
				$preview = showvideothumb($videoID, $portal);
			}
			else {
				// This code is for embed only
				$preview = showvideothumb($vidpreview, $portal, 1);
			}
			
			
			eval ("\$videos = \"".gettemplate("videos")."\";");
			echo $videos;
			
			$n++;
		}
	}
	else { echo 'Category deosn\'t exists!'; }
}

else {

	if(isset($_GET['page'])) $page=(int)$_GET['page'];
	else $page = 1;
	
	eval ("\$title_videos = \"".gettemplate("title_videos")."\";");
	echo $title_videos;
	
	if(ispageadmin($userID)) echo'<input type="button" onclick="MM_goToURL(\'parent\',\'index.php?site=videos&amp;action=new\');return document.MM_returnValue" value="New video" /><br /><br />';
	
	$alle=safe_query("SELECT vidID FROM ".PREFIX."videos");
	$gesamt = mysql_num_rows($alle);
	$pages=1;

	$max='8';

	for ($n=$max; $n<=$gesamt; $n+=$max) {
		if($gesamt>$n) $pages++;
	}

	if($pages>1) $page_link = makepagelink("index.php?site=videos", $page, $pages);
  	else $page_link='';

	if ($page == "1") {
		$ergebnis = safe_query("SELECT * FROM ".PREFIX."videos ORDER BY date LIMIT 0,$max");
		$n=1;
	}
	else {
		$start=$page*$max-$max;
		$ergebnis = safe_query("SELECT * FROM ".PREFIX."videos ORDER BY date LIMIT $start,$max");
		$n = ($gesamt+1)-$page*$max+$max;
	}
	
	//$allvideos = safe_query("SELECT * FROM ".PREFIX."videos ORDER BY date");
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
		
		$title = clearfromtags($ds['title']);
		$vidID = $ds['vidID'];
		$videoID = $ds['video'];
		$vidpreview = $ds['vidpreview'];
		$portal = $ds['portal'];
		$date = date("d.m.Y", $ds['date']);
		$type = $ds['type'];
		$uploader = getnickname($ds['uploader']);
		$views = $ds['views'];
		$catname = getvidcatname($ds['vidcatID']);
		$vidcatID = $ds['vidcatID'];
		
		if($type==1) {
			$preview = showvideothumb($videoID, $portal);
		}
		else {
			// This code is for embed only
			if(!empty($vidpreview)) $preview = 'images/videos/'.$vidpreview;
			else $preview = 'images/videos/nopreview.jpg';
		}

		eval ("\$videos = \"".gettemplate("videos")."\";");
		echo $videos;
		
		$n++;
	}
	if($pages>1) echo $page_link;
}
?>
