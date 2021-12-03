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
##########################################################################
*/

$ergebnis = safe_query("SELECT * FROM ".PREFIX."scene WHERE saved='1' ORDER BY date DESC LIMIT 0, ".$latestscene);
if(mysql_num_rows($ergebnis)){
	echo'<table width="100%" cellspacing="0" cellpadding="0">';
  $n=1;
	while($ds = mysql_fetch_array($ergebnis)) {
		$date = date("d.m.Y", $ds['date']);
		$time = date("H:i", $ds['date']);
		$title = $ds['title'];
		$pic = $ds['game'].'.gif';
	$game='<img src="images/games/'.$pic.'" border="0" />';
		$sceneID = $ds['sceneID'];
    
    if($n%2) {
			$bg1=BG_1;
			$bg2=BG_2;
		}
		else {
			$bg1=BG_3;
			$bg2=BG_4;
		}
    
    if(mb_strlen($title) > $scenechars) {
			$title = mb_substr($title, 0, $scenechars);
			$title .= '..';
		}
	
		eval("\$sc_scene = \"".gettemplate("sc_scene")."\";");
		echo $sc_scene;
    $n++;
	}
	echo'</table>';
}	
?>
