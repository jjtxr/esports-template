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
#   Copyright 2005-2009 by webspell.org                                  #
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
#   Scene addon by www.kode-designs.com                                  #
#   Contact: xf: eggzy email: karlo.mikus@goowy.com                      #
#   Support @ www.kode-desings.com/index.php?site=forums                 #
#                                                                        #
##########################################################################
*/

if(isset($_GET['action'])) $action = $_GET['action'];
else $action = '';

if($action=="save") {
	include("_mysql.php");
	include("_settings.php");
	include("_functions.php");
	$_language->read_module('scene');

	if(!isnewsadmin($userID)) die($_language->module['no_access']);
	$title = $_POST['title'];
	$message = $_POST['message'];
	$link1 = $_POST['link1'];
	$url1 = $_POST['url1'];
	$window1 = $_POST['window1'];
	$link2 = $_POST['link2'];
	$url2 = $_POST['url2'];
	$window2 = $_POST['window2'];
	$link3 = $_POST['link3'];
	$url3 = $_POST['url3'];
	$window3 = $_POST['window3'];
	$link4 = $_POST['link4'];
	$url4 = $_POST['url4'];
	$window4 = $_POST['window4'];
	$comments = $_POST['comments'];
	$game = $_POST['game'];
	$scenecatID = $_POST['scenecatID'];
	$sceneID = $_POST['sceneID'];

	safe_query("UPDATE ".PREFIX."scene SET
								 title='".$title."',
								 link1='".$link1."',
								 url1='".$url1."',
								 window1='".$window1."',
								 link2='".$link2."',
								 url2='".$url2."',
								 window2='".$window2."',
								 link3='".$link3."',
								 url3='".$url3."',
								 window3='".$window3."',
								 link4='".$link4."',
								 url4='".$url4."',
								 window4='".$window4."',
								 saved='1',
								 game='$game',
								 scenecatID='".$scenecatID."',
								 comments='".$comments."' WHERE sceneID='".$sceneID."'");

	$anzpages = mysql_num_rows(safe_query("SELECT * FROM ".PREFIX."scene_contents WHERE sceneID='".$sceneID."'"));
	if($anzpages > count($message)) {
		safe_query("DELETE FROM `".PREFIX."scene_contents` WHERE `sceneID` = '".$sceneID."' and `page` > ".count($message));
	}
	
	for($i = 0; $i <= count($message); $i++) {
	 	if(isset($message[$i])){
			if($i >= $anzpages) {
				safe_query("INSERT INTO ".PREFIX."scene_contents (sceneID, content, page) VALUES ('".$sceneID."', '".$message[$i]."', '".$i."')");
			}
			else {
				safe_query("UPDATE ".PREFIX."scene_contents SET content = '".$message[$i]."' WHERE sceneID = '".$sceneID."' and page = '".$i."'");
			}
		}
	}
	for($x=$_POST['language_count'];$x<100;$x++){
		safe_query("DELETE FROM ".PREFIX."scene_contents WHERE sceneID = '".$sceneID."' and page = '".$x."'");
	}

	// delete the entries that are older than 2 hour and contain no text
	safe_query("DELETE FROM `".PREFIX."scene` WHERE `saved` = '0' and ".time()." - `date` > ".(2 * 60 * 60));

	die('<body onload="window.close()"></body>');
}
elseif(isset($_GET['delete'])) {
	include("_mysql.php");
	include("_settings.php");
	include("_functions.php");
	$_language->read_module('scene');

	if(!isnewsadmin($userID)) die($_language->module['no_access']);

	$ds=mysql_fetch_array(safe_query("SELECT screens FROM ".PREFIX."scene WHERE sceneID='".$_GET['sceneID']."'"));
	if($ds['screens']) {
		$screens=explode("|", $ds['screens']);
		if(is_array($screens)) {
			$filepath = "./images/scene-pics/";
			foreach($screens as $screen) {
				if(file_exists($filepath.$screen)) @unlink($filepath.$screen);
			}
		}
	}

	safe_query("DELETE FROM ".PREFIX."scene WHERE sceneID='".$_GET['sceneID']."'");
	safe_query("DELETE FROM ".PREFIX."scene_contents WHERE sceneID='".$_GET['sceneID']."'");
	safe_query("DELETE FROM ".PREFIX."comments WHERE parentID='".$_GET['sceneID']."' AND type='sc'");

	if(isset($close)) echo'<body onload="window.close()"></body>';
	else header("Location: index.php?site=scene");
}

function top5() {
	$pagebg=PAGEBG;
	$border=BORDER;
	$bghead=BGHEAD;
	$bgcat=BGCAT;

	global $_language;

	$_language->read_module('scene');

	echo'<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td>';
	echo'<table><td><tr><table></table></td></tr></table>';
}

if($action=="new") {
	include("_mysql.php");
	include("_settings.php");
	include("_functions.php");

	$_language->read_module('scene');
	$_language->read_module('bbcode', true);

	$pagebg=PAGEBG;
	$border=BORDER;
	$bghead=BGHEAD;
	$bgcat=BGCAT;
	
	//game add
	if(file_exists('images/games/'.$ds['game'].'.jpg')) $pic = $ds['game'].'.jpg'; else $pic = $ds['game'].'.gif';
	$game='<img src="images/games/'.$pic.'" border="0" />';
	
	//game add
		$gamesa=safe_query("SELECT * FROM ".PREFIX."games ORDER BY name");
 		while($ds=mysql_fetch_array($gamesa)) {
		 $games.='<option value="'.$ds['tag'].'">'.$ds['name'].'</option>';
		}
					
	if(isnewsadmin($userID)) {
		safe_query("INSERT INTO ".PREFIX."scene ( date, poster, saved, game ) VALUES( '".time()."', '$userID', '0', '".$game."' ) ");
		$sceneID=mysql_insert_id();

		$selects='';
		for($i=1;$i<100;$i++) {
			$selects .= '<option value="'.$i.'">'.$i.'</option>';
		}

		$pages = 1;

		$bg1=BG_1;
		eval ("\$addbbcode = \"".gettemplate("addbbcode")."\";");
		eval ("\$addflags = \"".gettemplate("flags")."\";");

		eval ("\$scene_post = \"".gettemplate("scene_post")."\";");
		echo $scene_post;
	}
	else redirect('index.php?site=scene', $_language->module['no_access']);
}
elseif($action=="edit") {

	include("_mysql.php");
	include("_settings.php");
	include("_functions.php");

	$_language->read_module('scene');
	$_language->read_module('bbcode', true);

	$sceneID = $_GET['sceneID'];

	$pagebg=PAGEBG;
	$border=BORDER;
	$bghead=BGHEAD;
	$bgcat=BGCAT;
	
	//game add
	if(file_exists('images/games/'.$ds['game'].'.jpg')) $pic = $ds['game'].'.jpg'; else $pic = $ds['game'].'.gif';
	$game='<img src="images/games/'.$pic.'" border="0" />';
	
	//game add
		$gamesa=safe_query("SELECT * FROM ".PREFIX."games ORDER BY name");
 		while($ds=mysql_fetch_array($gamesa)) {
		 $games.='<option value="'.$ds['tag'].'">'.$ds['name'].'</option>';
		}

	if(isnewsadmin($userID)) {
		$ds=mysql_fetch_array(safe_query("SELECT * FROM ".PREFIX."scene WHERE sceneID = '".$sceneID."'"));

		$title=getinput($ds['title']);

		$message = array();
		$query = mysql_query("SELECT content FROM ".PREFIX."scene_contents WHERE sceneID = '".$sceneID."' ORDER BY page ASC");
		while($qs = mysql_fetch_array($query)) {
			$message[] = $qs['content'];
		}

		$message_vars='';
		$i=0;
		foreach($message as $val) {
			$message_vars .= "message[".$i."] = '".js_replace($val)."';\n";
			$i++;
		}
		$pages = count($message);

		$selects='';
		for($i=1;$i<100;$i++) {
		 	if($i==$pages) $selected = "selected='selected'";
		 	else $selected = NULL;
			$selects .= '<option value="'.$i.'" '.$selected.'>'.$i.'</option>';
		}

		$link1=getinput($ds['link1']);
		$link2=getinput($ds['link2']);
		$link3=getinput($ds['link3']);
		$link4=getinput($ds['link4']);
		$url1=getinput($ds['url1']);
		$url2=getinput($ds['url2']);
		$url3=getinput($ds['url3']);
		$url4=getinput($ds['url4']);

		$comments='<option value="0">'.$_language->module['no_comments'].'</option><option value="1">'.$_language->module['user_comments'].'</option><option value="2">'.$_language->module['visitor_comments'].'</option>';
		$comments=str_replace('value="'.$ds['comments'].'"', 'value="'.$ds['comments'].'" selected="selected"', $comments);

		$bg1=BG_1;
		eval ("\$addbbcode = \"".gettemplate("addbbcode")."\";");
		eval ("\$addflags = \"".gettemplate("flags")."\";");

		eval ("\$scene_edit = \"".gettemplate("scene_edit")."\";");
		echo $scene_edit;
	}
	else redirect('index.php?site=scene', $_language->module['no_access']);
}
elseif($action=="show") {

	$_language->read_module('scene');

	eval ("\$title_scene = \"".gettemplate("title_scene")."\";");
	echo $title_scene;

	$sceneID = (int)$_GET['sceneID'];
	if(isset($_GET['page'])) $page = (int)$_GET['page'];
	else $page = 1;

	if(isnewsadmin($userID)) echo'<input type="button" onclick="MM_openBrWindow(\'scene.php?action=new\',\'scene\',\'toolbar=no,status=no,scrollbars=yes,width=800,height=600\');" value="'.$_language->module['new_scene'].'" /> ';
	echo'<input type="button" onclick="MM_goToURL(\'parent\',\'index.php?site=scene\');return document.MM_returnValue;" value="'.$_language->module['all_scene'].'" /><br /><br />';

	if($page==1) safe_query("UPDATE ".PREFIX."scene SET viewed=viewed+1 WHERE sceneID='".$sceneID."'");
	$result=safe_query("SELECT * FROM ".PREFIX."scene WHERE sceneID='".$sceneID."'");

	if(mysql_num_rows($result)) {

		$ds=mysql_fetch_array($result);
		$date = date("d.m.Y", $ds['date']);
		$time = date("H:i", $ds['date']);
		$title = clearfromtags($ds['title']);

		$content = array();
		$query = mysql_query("SELECT * FROM ".PREFIX."scene_contents WHERE sceneID = '".$sceneID."' ORDER BY page ASC");
		while($qs = mysql_fetch_array($query)) {
			$content[] = $qs['content'];
		}

		$pages = count($content);
		$content = htmloutput($content[$page-1]);
		$content = toggle($content, $ds['sceneID']);
		if($pages>1) $page_link = makepagelink("index.php?site=scene&amp;action=show&amp;sceneID=$sceneID", $page, $pages);
    else $page_link='';

		$poster='<a href="index.php?site=profile&amp;id='.$ds['poster'].'"><b>'.getnickname($ds['poster']).'</b></a>';
		$related="";
    if($ds['link1'] && $ds['url1']!="http://" && $ds['window1']) $related.='&#8226; <a href="'.$ds['url1'].'" target="_blank">'.$ds['link1'].'</a> ';
		if($ds['link1'] && $ds['url1']!="http://" && !$ds['window1']) $related.='&#8226; <a href="'.$ds['url1'].'">'.$ds['link1'].'</a> ';

		if($ds['link2'] && $ds['url2']!="http://" && $ds['window2']) $related.='&#8226; <a href="'.$ds['url2'].'" target="_blank">'.$ds['link2'].'</a> ';
		if($ds['link2'] && $ds['url2']!="http://" && !$ds['window2']) $related.='&#8226; <a href="'.$ds['url2'].'">'.$ds['link2'].'</a> ';

		if($ds['link3'] && $ds['url3']!="http://" && $ds['window3']) $related.='&#8226; <a href="'.$ds['url3'].'" target="_blank">'.$ds['link3'].'</a> ';
		if($ds['link3'] && $ds['url3']!="http://" && !$ds['window3']) $related.='&#8226; <a href="'.$ds['url3'].'">'.$ds['link3'].'</a> ';

		if($ds['link4'] && $ds['url4']!="http://" && $ds['window4']) $related.='&#8226; <a href="'.$ds['url4'].'" target="_blank">'.$ds['link4'].'</a> ';
		if($ds['link4'] && $ds['url4']!="http://" && !$ds['window4']) $related.='&#8226; <a href="'.$ds['url4'].'">'.$ds['link4'].'</a> ';
		if(empty($related)) $related="n/a";

		$comments_allowed = $ds['comments'];
		//game add
	$pic = $ds['game'].'.gif';
	$game='<img src="images/games/'.$pic.'" border="0" />';

		$ratings=array(0,0,0,0,0,0,0,0,0,0);
		for($i=0; $i<$ds['rating']; $i++) {
			$ratings[$i]=1;
		}
		$ratingpic='<img src="images/icons/rating_'.$ratings[0].'_start.gif" width="1" height="5" alt="" />';
		foreach($ratings as $pic) {
			$ratingpic.='<img src="images/icons/rating_'.$pic.'.gif" width="4" height="5" alt="" />';
		}

		if(isnewsadmin($userID)) $adminaction='<br /><br /><input type="button" onclick="MM_openBrWindow(\'scene.php?action=edit&amp;sceneID='.$ds['sceneID'].'\',\'News\',\'toolbar=no,status=no,scrollbars=yes,width=800,height=600\');" value="'.$_language->module['edit'].'" />
    <input type="button" onclick="MM_confirm(\''.$_language->module['really_delete'].'\', \'scene.php?delete=true&amp;sceneID='.$ds['sceneID'].'\');" value="'.$_language->module['delete'].'" />';
		else $adminaction='';

		if($loggedin) {
			$getscene=safe_query("SELECT scene FROM ".PREFIX."user WHERE userID='$userID'");
			$found=false;
			if(mysql_num_rows($getscene)) {
				$ga=mysql_fetch_array($getscene);
				if($ga['scene']!="") {
					$string=$ga['scene'];
					$array=explode(":", $string);
					$anzarray=count($array);
					for($i=0; $i<$anzarray; $i++) {
						if($array[$i]==$sceneID) $found=true;
					}
				}
			}
			if($found) $rateform=$_language->module['already_rated'];
			else $rateform='<form method="post" action="rating.php">
      <table cellspacing="0" cellpadding="2" align="right">
        <tr>
          <td>'.$_language->module['rate_with'].'
          <select name="rating">
            <option>0 - '.$_language->module['poor'].'</option>
            <option>1</option>
            <option>2</option>
            <option>3</option>
            <option>4</option>
            <option>5</option>
            <option>6</option>
            <option>7</option>
            <option>8</option>
            <option>9</option>
            <option>10 - '.$_language->module['perfect'].'</option>
          </select>
          <input type="hidden" name="userID" value="'.$userID.'" />
          <input type="hidden" name="type" value="sc" />
          <input type="hidden" name="id" value="'.$ds['sceneID'].'" />
          <input type="submit" name="Submit" value="'.$_language->module['rate'].'" /></td>
        </tr>
      </table>
      </form>';
		}
		else $rateform=$_language->module['login_for_rate'];

		$bg1=BG_1;
		eval ("\$scene = \"".gettemplate("scene")."\";");
		echo $scene;

		unset($related);
		unset($comments);
		unset($lang);
		unset($ds);
		unset($ratingpic);
		unset($page);
		unset($pages);

		$parentID = $sceneID;
		$type = "sc";
		$referer = "index.php?site=scene&amp;action=show&amp;sceneID=$sceneID";

		include("comments.php");
	}
	else echo $_language->module['no_entries'];
}
else {

	$_language->read_module('scene');

	if(isset($_GET['page'])) $page=(int)$_GET['page'];
	else $page = 1;
	$sort="date";
	if(isset($_GET['sort'])){
	  if(($_GET['sort']=='date') || ($_GET['sort']=='poster') || ($_GET['sort']=='rating') || ($_GET['sort']=='viewed')) $sort=$_GET['sort'];
	}
	$type="DESC";
	if(isset($_GET['type'])){
	  if(($_GET['type']=='ASC') || ($_GET['type']=='DESC')) $type=$_GET['type'];
	}
	
	eval ("\$title_scene = \"".gettemplate("title_scene")."\";");
	echo $title_scene;
	
  if(isnewsadmin($userID)) echo'<input type="button" onclick="MM_openBrWindow(\'scene.php?action=new\',\'scene\',\'toolbar=no,status=no,scrollbars=yes,width=800,height=600\');" value="'.$_language->module['new_scene'].'" /><br /><br />';

	$alle=safe_query("SELECT sceneID FROM ".PREFIX."scene WHERE saved='1'");
	$gesamt = mysql_num_rows($alle);
	$pages=1;
	
	//game add
		$gamesa=safe_query("SELECT * FROM ".PREFIX."games ORDER BY name");
 		while($ds=mysql_fetch_array($gamesa)) {
		 $games.='<option value="'.$ds['tag'].'">'.$ds['name'].'</option>';
		}

	$max=$maxscene;

	for ($n=$max; $n<=$gesamt; $n+=$max) {
		if($gesamt>$n) $pages++;
	}

	if($pages>1) $page_link = makepagelink("index.php?site=scene&amp;sort=".$sort."&amp;type=".$type, $page, $pages);
  else $page_link='';

	if ($page == "1") {
		$ergebnis = safe_query("SELECT * FROM ".PREFIX."scene WHERE saved='1' ORDER BY $sort $type LIMIT 0,$max");
		if($type=="DESC") $n=$gesamt;
		else $n=1;
	}
	else {
		$start=$page*$max-$max;
		$ergebnis = safe_query("SELECT * FROM ".PREFIX."scene WHERE saved='1' ORDER BY $sort $type LIMIT $start,$max");
		if($type=="DESC") $n = ($gesamt)-$page*$max+$max;
		else $n = ($gesamt+1)-$page*$max+$max;
	}
	if($gesamt) {
		top5();
		if($type=="ASC")
		echo'<a href="index.php?site=scene&amp;page='.$page.'&amp;sort='.$sort.'&amp;type=DESC">'.$_language->module['sort'].'</a> <img src="images/icons/asc.gif" width="9" height="7" border="0" alt="" />&nbsp;&nbsp;&nbsp;';
		else
		echo'<a href="index.php?site=scene&amp;page='.$page.'&amp;sort='.$sort.'&amp;type=ASC">'.$_language->module['sort'].'</a> <img src="images/icons/desc.gif" width="9" height="7" border="0" alt="" />&nbsp;&nbsp;&nbsp;';


		if($pages>1) echo $page_link;
		
    eval ("\$scene_head = \"".gettemplate("scene_head")."\";");
		echo $scene_head;
    
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
			$date=date("d.m.Y", $ds['date']);

			$title='<a href="index.php?site=scene&amp;action=show&amp;sceneID='.$ds['sceneID'].'">'.clearfromtags($ds['title']).'</a>';
			$poster='<a href="index.php?site=profile&amp;id='.$ds['poster'].'"><b>'.getnickname($ds['poster']).'</b></a>';
			$viewed=$ds['viewed'];
			//game add
			$pic = $ds['game'].'.gif';
			$game='<img src="images/games/'.$pic.'" border="0" />';
	

			$ratings=array(0,0,0,0,0,0,0,0,0,0);
			for($i=0; $i<$ds['rating']; $i++) {
				$ratings[$i]=1;
			}
			$ratingpic='<img src="images/icons/rating_'.$ratings[0].'_start.gif" width="1" height="5" alt="" />';
			foreach($ratings as $pic) {
				$ratingpic.='<img src="images/icons/rating_'.$pic.'.gif" width="4" height="5" alt="" />';
			}

			eval ("\$scene_content = \"".gettemplate("scene_content")."\";");
			echo $scene_content;
			unset($ratingpic);
			$n++;
		}
		eval ("\$scene_foot = \"".gettemplate("scene_foot")."\";");
		echo $scene_foot;
		unset($ds);
	}
	else echo $_language->module['no_entries'];
}

?>