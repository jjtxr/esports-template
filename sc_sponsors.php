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
$sponsors=safe_query("SELECT * FROM ".PREFIX."sponsors WHERE (displayed = '1' AND mainsponsor = '0') ORDER BY sort");
if(mysql_num_rows($sponsors)) {
	
	if(mysql_num_rows($sponsors) == 1) $title = $_language->module['sponsor'];
	else $title = $_language->module['sponsors'];
	
	while($db=mysql_fetch_array($sponsors)) {
		if(!empty($db['banner_small'])) $sponsor='<img src="images/sponsors/'.$db['banner_small'].'" style="height:70px;margin:2px 0;" border="0" alt="'.htmlspecialchars($db['name']).'" title="'.htmlspecialchars($db['name']).'" />';
		else $sponsor=$db['name'];
		$sponsorID = $db['sponsorID'];
		
		eval ("\$sc_sponsors = \"".gettemplate("sc_sponsors")."\";");
		echo $sc_sponsors;
	}
}

?>