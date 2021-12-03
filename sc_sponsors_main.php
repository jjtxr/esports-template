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

$_language->read_module('sponsors');
$mainsponsors=safe_query("SELECT * FROM ".PREFIX."sponsors WHERE (displayed = '1' AND mainsponsor = '1') ORDER BY sort");
if(mysql_num_rows($mainsponsors)) {
	
	if(mysql_num_rows($mainsponsors) == 1) $main_title = $_language->module['mainsponsor'];
	else $main_title = $_language->module['mainsponsors'];
	
	while($da=mysql_fetch_array($mainsponsors)) {
		if(!empty($da['banner'])) $sponsor='<center><img src="images/sponsors/'.$da['banner'].'" style="width:580px;height:76px;margin:2px 0;border-radius: 10px;" alt="'.htmlspecialchars($da['name']).'" title="'.htmlspecialchars($da['name']).'" /></center>';
		else $sponsor=$da['name'];
		$sponsorID = $da['sponsorID'];
		
		eval ("\$sc_sponsors_main = \"".gettemplate("sc_sponsors_main")."\";");
		echo $sc_sponsors_main;
	}
}
?>