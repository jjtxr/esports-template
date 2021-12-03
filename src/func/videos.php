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
#   @author: eGGzy - www.kode-designs.com                                #
#                                                                        #
##########################################################################
*/

// List all video portals for <select> element
function getvideoportals() {
	// To add a new portal just add a new array entry
	$portals = array( 'Youtube', 'Wipido', 'Xfire' );
					  
	$n=0;
	$videoportals = '';		  
	foreach($portals as $portal) {
		$videoportals .= '<option value="'.$n.'">'.$portal.'</option>';
		$n++;
	}
	
	return $videoportals;
}

// Return all available video rubrics for <select> element
function getvideocats() {
	$cats = safe_query("SELECT * FROM ".PREFIX."video_categories ORDER BY title");
	$videocats = '';
	while($ds = mysql_fetch_array($cats)) {
		$videocats .= '<option value="'.$ds['vidcatID'].'">'.htmlspecialchars($ds['title']).'</option>';
	}
	return $videocats;
}

// Show video by portal, if you add portal array you should also add embed code
function showvideo($videoID, $portal) {
	global $picsize_l;
	
	//YouTube Portal
	if($portal == 0) {
		$videoreturn = '<object width="'.$picsize_l.'" height="385"><param name="movie" value="http://www.youtube.com/v/'.$videoID.'&amp;hl=en_US&amp;fs=1"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/'.$videoID.'&amp;hl=en_US&amp;fs=1" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="'.$picsize_l.'" height="385"></embed></object>';
	}
	//Wipido Portal
	elseif($portal == 1) {
		//$videoreturn = '<iframe src="http://www.wipido.com/main/video/external/'.$videoID.'" style="border:none; width:480px;height:320px;overflow:hidden;" scrolling="no" border="0" frameborder="0"></iframe>';
		$videoreturn = '<object width="'.$picsize_l.'" height="320"><param name="movie" value="http://embed.minoto-video.com/90/'.$videoID.'"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://embed.minoto-video.com/90/'.$videoID.'" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="'.$picsize_l.'" height="320"></embed></object>';
	}
	//Xfire portal
	elseif($portal == 2) {
		$videoreturn = '<object width="'.$picsize_l.'" height="305"><embed src="http://media.xfire.com/swf/embedplayer.swf" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="'.$picsize_l.'" height="305" flashvars="videoid='.$videoID.'"></embed></object>';
	}
	//Unknow portal
	else {
		$videoreturn = 'Unknown portal choosed!';
	}
	
	return $videoreturn;
}

// Show portal video thumbnail
function showvideothumb($videoID, $portal, $embed='') {
	if($embed == 1) {
		//If embed preview then $videoid = vidpreview row, portal = 0, embed = 1
		$ds = mysql_fetch_array(safe_query("SELECT vidpreview FROM ".PREFIX."videos WHERE vidID='".$videoID."'"));
		$pic = $ds['vidpreview'];
		if(empty($pic)) $videoreturn = 'images/videos/nopreview.jpg';
		else $videoreturn = 'images/videos/'.$pic;
	}
	elseif($embed == '') {
		//YouTube Portal
		if($portal == 0) {
			$videoreturn = 'http://img.youtube.com/vi/'.$videoID.'/default.jpg';
		}
		//Wipido Portal
		elseif($portal == 1) {
			//Bugged a little -> wipido sux
			$videoreturn = 'http://imgs.minoto-video.com/'.$videoID.'_thumbnail-3.jpg';
		}
		//Xfire portal
		elseif($portal == 2) {
			$videoreturn = 'http://video.xfire.com/'.$videoID.'-3.jpg';
		}
		//Unknow portal
		else {
			$videoreturn = 'images/videos/nopreview.jpg';
		}
	}
	else {
		$videoreturn = 'images/videos/nopreview.jpg';
	}
	
	return $videoreturn;
}

// Return video category name by vidcatID
function getvidcatname($vidcatID) {
	$ds=mysql_fetch_array(safe_query("SELECT title FROM ".PREFIX."video_categories WHERE vidcatID='".$vidcatID."'"));
	return getinput($ds['title']);
}

?>