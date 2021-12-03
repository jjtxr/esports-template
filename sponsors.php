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
#   Copyright 2005-2011 by webspell.org                                  #
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

eval("\$title_sponsors = \"".gettemplate("title_sponsors")."\";");
echo $title_sponsors;

$ergebnis = safe_query("SELECT * FROM ".PREFIX."sponsors WHERE displayed = '1' ORDER BY sort");
if(mysql_num_rows($ergebnis)) {
	$i = 1;
	while($ds=mysql_fetch_array($ergebnis)) {
		if($i % 2) $bg1 = BG_1;
		else $bg1 = BG_2;
		
		$url=str_replace('http://', '', $ds['url']);
		$sponsor = $ds['name'];
		$link = '<center><a href="out.php?sponsorID='.$ds['sponsorID'].'" target="_blank"><img src="slice/visit-website-button.gif" /></a></center>';
		$info = cleartext($ds['info']);
		$banner = '<br /><img src="images/sponsors/'.$ds['banner'].'" alt="'.htmlspecialchars($ds['name']).'" border="0" width="630" height="90" />';
		
		eval ("\$sponsors = \"".gettemplate("sponsors")."\";");
		echo $sponsors;
		$i++;
	}
}
else echo $_language->module['no_sponsors'];
?>