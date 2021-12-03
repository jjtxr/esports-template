<?php

//**************************************
//* Movie-Addon v2.0 by FIRSTBORN e.V. *
//**************************************

$_language->read_module('movies');
$movies_per_row=1;
$filepath = "./images/movies/";

if(!$userID) echo '<h2>'.$_language->module['myvideos'].'</h2>
				'.$_language->module['unlogged_vote'].'<br><br>

			  &#8226; <a href="index.php?site=register">register now</a><br>
			  &#8226; <a href="index.php?site=login">log in</a>';
			  
elseif($allow_usermovies==0) 
		echo '<h2>'.$_language->module['myvideos'].'</h2>
				No user videos allowed!<br><br>';
			  
else {	

if($_GET["action"]=="add") {

	$movcatselect=safe_query("SELECT * FROM ".PREFIX."movie_categories ORDER BY movcatname");
 		while($ds=mysql_fetch_array($movcatselect)) {
		 $movcat.='<option value="'.$ds[movcatID].'">'.$ds[movcatname].'</option>';
		}

	echo '<h2>'.$_language->module['myvideos'].'</h2>
		<input type="button" class="button" onClick="MM_goToURL(\'parent\',\'index.php?site=myvideos\');return document.MM_returnValue" value="'.$_language->module['myvideos'].'"> 
		<h3>new video</h3>';
	echo'
		<form method="post" action="index.php?site=myvideos" enctype="multipart/form-data">
		<table cellpadding="4" cellspacing="1" bgcolor="#cccccc">
			<tr bgcolor="#ffffff">
				<td>'.$_language->module['catselect'].':</td>
				<td><select name="movcatID">'.$movcat.'</select></td>
			</tr>
			<tr bgcolor="#e7e7e7">
				<td>'.$_language->module['movhead'].':</td>
				<td><input type="text" name="movheadline" size="60" maxlength="255" class="form_off" onFocus="this.className=\'form_on\'" onBlur="this.className=\'form_off\'"></td>
			</tr>
			<tr bgcolor="#ffffff">
				<td>'.$_language->module['screen'].':</td>
				<td><input name="movscreenshot" type="file"></td>
			</tr>
			<tr bgcolor="#e7e7e7">
				<td>'.$_language->module['description'].'</td>
				<td><input type="text" name="movdescription" size="60" maxlength="255" class="form_off" onFocus="this.className=\'form_on\'" onBlur="this.className=\'form_off\'"></td>
			</tr>
			<tr bgcolor="#ffffff">
				<td>Youtube vID <i>(ex: 5LZB4o_qmZ8)</i>:</td>
				<td><input type="text" name="movfile" size="60" class="form_off" onFocus="this.className=\'form_on\'" onBlur="this.className=\'form_off\'"></td>
			</tr>
			<tr bgcolor="#ffffff">
				<td><input type="hidden" name="uploader" value="'.$userID.'"></td>
				<td><input type="submit" name="save" value="'.$_language->module['add_video'].'"></td>
			</tr>
		</table>
		</form>';
}
elseif($_GET["action"]=="edit") {
	$ds=mysql_fetch_array(safe_query("SELECT * FROM ".PREFIX."movies WHERE movID='".$_GET["movID"]."'"));
	if(file_exists($filepath.$ds['movID'].'.gif'))	$pic='<img src="../images/movies/'.$ds['movID'].'.gif" width="200" height="115" border="0" alt="'.$ds['movheadline'].'">';
	elseif(file_exists($filepath.$ds['movID'].'.jpg'))	$pic='<img src="../images/movies/'.$ds['movID'].'.jpg" width="200" height="115" border="0" alt="'.$ds['movheadline'].'">';
	elseif(file_exists($filepath.$ds['movID'].'.png'))	$pic='<img src="../images/movies/'.$ds['movID'].'.png" width="200" height="115" border="0" alt="'.$ds['movheadline'].'">';
	else $pic='no image uploaded';
	
	$movcatselect=safe_query("SELECT * FROM ".PREFIX."movie_categories ORDER BY movcatname");
 		while($dv=mysql_fetch_array($movcatselect)) {
		 $movcat.='<option value="'.$dv[movcatID].'">'.$dv[movcatname].'</option>';
		}
	
	$movcat=str_replace('value="'.$ds[movcatID].'"', 'value="'.$ds[movcatID].'" selected', $movcat);
	
	
	echo'
		<h2>'.$_language->module['myvideos'].'</h2>
		<input type="button" class="button" onClick="MM_goToURL(\'parent\',\'index.php?site=myvideos\');return document.MM_returnValue" value="'.$_language->module['myvideos'].'">
		<h3>'.$_language->module['edit_video'].'</h3>';
	echo'
		<form method="post" action="index.php?site=myvideos" enctype="multipart/form-data">
		<input type="hidden" name="movID" value="'.$ds['movID'].'">
		<table cellpadding="4" cellspacing="1" bgcolor="#cccccc">
			<tr bgcolor="#ffffff">
				<td>'.$_language->module['catselect'].':</td>
				<td><select name="movcatID">'.$movcat.'</select></td>
			</tr>
		    <tr bgcolor="#e7e7e7">
				<td>'.$_language->module['movhead'].':</td>
				<td><input type="text" name="movheadline" size="60" maxlength="255" class="form_off" onFocus="this.className=\'form_on\'" onBlur="this.className=\'form_off\'" value="'.$ds['movheadline'].'"></td>
			</tr>
			<tr bgcolor="#ffffff">
				<td>'.$_language->module['pscreen'].':</td>
				<td>'.$pic.'</td>
			</tr>		
			<tr bgcolor="#e7e7e7">
				<td>'.$_language->module['img_up'].':</td>
				<td><input name="movscreenshot" type="file"></td>
			</tr>
			<tr bgcolor="#ffffff">
				<td>'.$_language->module['description'].':</td>
				<td><input type="text" name="movdescription" size="60" class="form_off" onFocus="this.className=\'form_on\'" onBlur="this.className=\'form_off\'" value="'.$ds['movdescription'].'"></td>
			</tr>
			<tr bgcolor="#ffffff">
				<td>Youtube Link:</i></td>
				<td><input type="text" name="movfile" size="60" class="form_off" onFocus="this.className=\'form_on\'" onBlur="this.className=\'form_off\'"></td>
			</tr>
			<tr bgcolor="#e7e7e7">
				<td><input type="hidden" name="uploader" value="'.$ds['uploader'].'"></td>
				<td><input type="submit" name="saveedit" value="edit video"></td>
			</tr>
		</table>
		</form>';
}
elseif($_POST["save"]) {
	$movscreenshot=$_FILES["movscreenshot"];
	$movheadline=$_POST["movheadline"];
	$movfile=$_POST["movfile"];
	$movdescription=$_POST["movdescription"];
	$movcatID=$_POST["movcatID"];
	$uploader=$_POST["uploader"];
	$embed=$_POST["embed"];
	
	echo'
		<h2>'.$_language->module['movies'].'</h2>';
	
	if($movheadline AND ($movfile OR $embed)) {
		
		safe_query("INSERT INTO ".PREFIX."movies (movID, activated, embed, uploader, movheadline, movfile, movdescription, date, movcatID) values('', '".$admin_activation."', '".$embed."', '".$uploader."', '".$movheadline."', '".$movfile."', '".$movdescription."', '".time()."', '".$movcatID."')");
		$id=mysql_insert_id();
		
		if($movscreenshot[name]!="") {
		$file_ext=strtolower(substr($movscreenshot[name], strrpos($movscreenshot[name], ".")));
		if($file_ext==".gif" OR $file_ext==".jpg" OR $file_ext==".png") {
			if($movscreenshot[name] != "") {
				move_uploaded_file($movscreenshot[tmp_name], $filepath.$movscreenshot[name]);
				@chmod($filepath.$movscreenshot[name], 0755);
				$file=$id.$file_ext;
				rename($filepath.$movscreenshot[name], $filepath.$file);
				if(safe_query("UPDATE ".PREFIX."movies SET movscreenshot='".$file."' WHERE movID='".$id."'")) {
					redirect("index.php?site=myvideos", "".$_language->module['vid_created'].".", "3");
				} else {
					redirect("index.php?site=myvideos", "".$_language->module['screen_error'].".", "3");
				}
			}
		} else echo'<b>'.$_language->module['screen_error1'].'</b><br><br><a href="javascript:history.back()">&laquo; '.$_language->module['back'].'</a>';
		}
		redirect("index.php?site=myvideos", "".$_language->module['mov_added']."!", "3");
	} else echo'<b>'.$_language->module['form_error'].'</b><br><br><a href="javascript:history.back()">&laquo; '.$_language->module['back'].'</a>';
}
elseif($_POST["saveedit"]) {
	$movscreenshot=$_FILES["movscreenshot"];
	$movheadline=$_POST["movheadline"];
	$movfile=$_POST["movfile"];
	$movdescription=$_POST["movdescription"];
	$movcatID=$_POST["movcatID"];
	$uploader=$_POST["uploader"];
	$embed=$_POST["embed"];

	echo'<h2>'.$_language->module['myvideos'].'</h2>';	
	
	if($movheadline) {
		if(eregi('http://', $movfile)) $movfile=$movfile;
		else $movfile='http://'.$movfile;
		
		if($movscreenshot[name]=="") {
			if(safe_query("UPDATE ".PREFIX."movies SET embed='".$embed."', uploader='".$uploader."', movheadline='".$movheadline."', movfile='".$movfile."', movdescription='".$movdescription."', movcatID='".$movcatID."' WHERE movID='".$_POST["movID"]."'"))
				redirect("index.php?site=myvideos", "".$_language->module['mov_updated'].".", "3");
		} else {
			$file_ext=strtolower(substr($movscreenshot[name], strrpos($movscreenshot[name], ".")));
			if($file_ext==".gif" OR $file_ext==".jpg" OR $file_ext==".png") {
				move_uploaded_file($movscreenshot[tmp_name], $filepath.$movscreenshot[name]);
				@chmod($filepath.$movscreenshot[name], 0755);
				$file=$_POST['movID'].$file_ext;
				rename($filepath.$movscreenshot[name], $filepath.$file);

				if(safe_query("UPDATE ".PREFIX."movies SET embed='".$embed."', uploader='".$uploader."', movscreenshot='".$file."', movheadline='".$movheadline."', movfile='".$movfile."', movdescription='".$movdescription."' WHERE movID='".$_POST["movID"]."'")) {
					redirect("index.php?site=myvideos", "Video edited.", "3");
				}
			} else echo'<b>'.$_language->module['screen_error1'].'</b><br><br><a href="javascript:history.back()">&laquo; '.$_language->module['back'].'</a>';
		}
	} else echo'<b>'.$_language->module['form_error'].'.</b><br><br><a href="javascript:history.back()">&laquo; '.$_language->module['back'].'</a>';
}
elseif($_GET["delete"]) {
	
	$select=safe_query("SELECT uploader FROM ".PREFIX."movies WHERE movID='".$_GET["movID"]."'");
	$selection=mysql_fetch_array($select);
	
	if($selection['uploader']==$userID){

	if(safe_query("DELETE FROM ".PREFIX."movies WHERE movID='".$_GET["movID"]."'")) {
		if(file_exists($filepath.$_GET["movID"].'.jpg')) unlink($filepath.$_GET["movID"].'.jpg');
		if(file_exists($filepath.$_GET["movID"].'.gif')) unlink($filepath.$_GET["movID"].'.gif');
		if(file_exists($filepath.$_GET["movID"].'.png')) unlink($filepath.$_GET["movID"].'.png');
		redirect("index.php?site=myvideos", "".$_language->module['mov_del'].".", "3");
	} else {
		redirect("index.php?site=myvideos", "".$_language->module['no_mov_del']."!", "3");
	}
	}else redirect("index.php?site=myvideos", "".$_language->module['no_perm']."!", "3");
}
elseif($_GET["action"]=="unactivated") {

	echo'<h2>my videos</h2>
		<input type="button" class="button" onClick="MM_goToURL(\'parent\',\'index.php?site=myvideos&action=add\');return document.MM_returnValue" value="'.$_language->module['new_vid'].'">
		<input type="button" class="button" onClick="MM_goToURL(\'parent\',\'index.php?site=myvideos\');return document.MM_returnValue" value="'.$_language->module['myvideos'].'">

		<table width="100%" cellpadding="4" cellspacing="1"  bgcolor="#cccccc">
			<tr bgcolor="#CCCCCC">
				<td class="title">'.$_language->module['headline'].':</td>
				<td class="title">'.$_language->module['category'].':</td>
				<td class="title">'.$_language->module['uploader'].':</td>
				<td class="title">'.$_language->module['screen'].':</td>
				<td class="title">'.$_language->module['description'].':</td>
				<td class="title">'.$_language->module['actions'].':</td>
			</tr>
			<tr bgcolor="#EEEEEE">
				<td colspan="6"></td>
			</tr>
			<tr>
				<td colspan="6"><b>'.$_language->module['discliamer'].'.</b></td>
			</tr>';
	$qry=safe_query("SELECT * FROM ".PREFIX."movies WHERE activated='1' ORDER BY movheadline");
	$anz=mysql_num_rows($qry);
	if($anz) {
		while($ds = mysql_fetch_array($qry)) {
		
			if($ds[movscreenshot])	$pic='<img src="../images/movies/'.$ds[movscreenshot].'" width="200" height="115" border="1" alt="'.$ds[movheadline].'">';
			else $pic='<img src="../images/movies/nopic.png" width="200" height="115" border="1" alt="'.$ds[movheadline].'">';
			

			echo'
				<tr bgcolor="#FFFFFF">
					<td>'.$ds[movheadline].'</td>
					<td>'.getmovcat($ds[movcatID]).'</td>
					<td>'.getnickname($ds[uploader]).'</td>
					<td>'.$pic.'</td>
					<td>'.$ds[movdescription].'</td>
					<td>
				   <input type="button" class="button" onClick="MM_goToURL(\'parent\',\'index.php?site=myvideos&action=edit&movID='.$ds[movID].'\');return document.MM_returnValue" value="'.$_language->module['edit'].'">
				   <input type="button" class="button" onClick="MM_confirm(\'really delete this video?\', \'index.php?site=myvideos&delete=true&movID='.$ds[movID].'\')" value="'.$_language->module['del'].'"></td>
					</td>
				</tr>
			';
		}
	} else echo'<tr><td colspan="4">'.$_language->module['no_entries'].'</td></tr>';
	echo '</table>';
		
		

}
else {
	
	echo'<h2>'.$_language->module['myvideos'].'</h2>
		<input type="button" class="button" onClick="MM_goToURL(\'parent\',\'index.php?site=myvideos&action=add\');return document.MM_returnValue" value="'.$_language->module['new_vid'].'">';
	
	$qry1=safe_query("SELECT * FROM ".PREFIX."movies WHERE activated='1' ORDER BY movheadline");
	$anz1=mysql_num_rows($qry1);
	if($anz1){
		echo ' <input type="button" class="button" onClick="MM_goToURL(\'parent\',\'index.php?site=myvideos&action=unactivated\');return document.MM_returnValue" value="'.$anz1.' '.$_language->module['unact_vids'].'">';
	}


	$page = $_GET['page'];
	$sort = $_GET['sort'];
	$type = $_GET['type'];
	
	
	$gesamt = mysql_num_rows(safe_query("SELECT movID FROM ".PREFIX."movies WHERE uploader='".$userID."' AND activated='2'"));
	$pages=1;
	if(!isset($page)) $page = 1;
	if(!isset($sort)) $sort = "date";
	if(!isset($type)) $type = "DESC";
	
	$max=10;
		
    for($n=$max; $n<=$gesamt; $n+=$max) {
		if($gesamt>$n) $pages++;
	}
		 	
	if($pages>1) $page_k = makepagelink("index.php?site=myvideos&sort=$sort&type=$type", $page, $pages);
		
	if ($page == "1") {
        $ergebnis = safe_query("SELECT * FROM ".PREFIX."movies WHERE uploader='".$userID."' AND activated='2' ORDER BY $sort $type LIMIT 0,$max");
	    if($type=="DESC") $n=$gesamt;
		else $n=1;
	}
	else {
	    $start=$page*$max-$max;
	    $ergebnis = safe_query("SELECT * FROM ".PREFIX."movies WHERE uploader='".$userID."' AND activated='2' ORDER BY $sort $type LIMIT $start,$max");
	    if($type=="DESC") $n = ($gesamt)-$page*$max+$max;
		else $n = ($gesamt+1)-$page*$max+$max;
	}
	if($gesamt) {
	if($type=="ASC") $seiten='<a href="index.php?site=myvideos&page='.$page.'&sort='.$sort.'&type=DESC">'.$_language->module['sort'].':</a> <img src="images/icons/asc.gif" width="9" height="7" border="0"> '.$page_link.'<br><br>';
	else $seiten='<a href="index.php?site=myvideos&page='.$page.'&sort='.$sort.'&type=ASC">'.$_language->module['sort'].':</a> <img src="images/icons/desc.gif" width="9" height="7" border="0"> '.$page_link.'<br><br>';
				
	
    echo '<table width="100%" border="0" cellspacing="0" cellpadding="2"><tr><td>'.$seiten.'</td></tr></table>';
	
	
	eval ("\$movies_head = \"".gettemplate("movies_head")."\";");
	echo $movies_head;
				
	$i=1;
	while($ar=mysql_fetch_array($ergebnis)) {
				if($i%2) {
				$bg1=BG_1;
				$bg2=BG_2;
			}
			else {
				$bg1=BG_3;
				$bg2=BG_4;
			}
			
			if($i%$movies_per_row) echo '</tr><tr>';
			
			$com=getanzcomments($ar[movID],"mo");
			$hits=$ar[hits];
			$movheadline=$ar[movheadline];
			$movID=$ar[movID];
			$uploader=getnickname($ar[uploader]);
			if($ar[rating])	$ratingpic='<img src="images/rating'.$ar[rating].'.png" width="103" height="31" title="'.$ar[rating].' of 10; '.$ar[votes].' votes" />';
			else $ratingpic='<img src="images/rating0.png" width="103" height="31" title="no votes yet" />';
			
			if($admin_activation==2) $useredit=' <input type="button" class="button" onClick="MM_goToURL(\'parent\',\'index.php?site=myvideos&action=edit&movID='.$ds[movID].'\');return document.MM_returnValue" value="'.$_language->module['edit'].'">';
			$useradminaction=''.$useredit.' <input type="button" class="button" onClick="MM_confirm(\'really delete this video?\', \'index.php?site=myvideos&delete=true&movID='.$ar[movID].'\')" value="'.$_language->module['del'].'">';
			
			if($ar[movscreenshot])	$pic='<img src="../images/movies/'.$ar[movscreenshot].'" width="200" height="115" border="1" alt="'.$movheadline.'">';
			else $pic='<img src="../images/movies/nopic.png" width="200" height="115" border="1" alt="'.$movheadline.'">';
			
			
	eval ("\$movies_content = \"".gettemplate("movies_content")."\";");
	echo $movies_content;
	$i++;
	}
	eval ("\$movies_foot = \"".gettemplate("movies_foot")."\";");
	echo $movies_foot;
	}
	else{
	echo''.$_language->module['no_entries'].'!';
	}
}

}

?>