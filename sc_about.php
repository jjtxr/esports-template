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

$_language->read_module('about');

$ergebnis=safe_query("SELECT * FROM ".PREFIX."about");
if(mysql_num_rows($ergebnis)) {
	$ds=mysql_fetch_array($ergebnis);

	$about=htmloutput($ds['about']);
	$about=toggle($about, 1);
	if(strlen($about)>500) {
                        $about=substr($about, 0, 500);
                        $about.='...';
                }
	echo $about;
	echo "<br /><br /><a href='http://nd-esports.ga/index.php?site=about'>Read More</a>";
}
else echo $_language->module['no_about'];


?>
