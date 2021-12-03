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

$_language->read_module('staff');

eval ("\$title_staff = \"".gettemplate("title_staff")."\";");
echo $title_staff;


	$all=safe_query("SELECT staffID FROM ".PREFIX."staff ");
	$all=mysql_num_rows($all);
	$pages=1;
	if(!isset($page)) $page = 1;
	if(!isset($type)) $type = "ASC";
	$max =5;
	for ($n=$max; $n<=$all; $n+=$max) {

	    if($all>$n) $pages++;

	}
	if($pages>1) $page_link = makepagelink("index.php?site=staff", $page, $pages);
	if ($page == "1") {
        $ergebnis = safe_query("SELECT * FROM ".PREFIX."staff  ORDER BY staffID $type LIMIT 0,$max");
	    if($type=="DESC") $n=$gesamt;
		else $n=1;
	}
	else {
	    $start=$page*$max-$max;
	    $ergebnis = safe_query("SELECT * FROM ".PREFIX."staff ORDER BY staffID $type LIMIT $start,$max");
	    if($type=="DESC") $n = ($gesamt)-$page*$max+$max;
		else $n = ($gesamt+1)-$page*$max+$max;
	}
		$i=1;
		while($ds=mysql_fetch_array($ergebnis)) {
			if($i%2) {

				$bg1=BG_1;
		     	$bg2=BG_2;
			}
			else {
				$bg1=BG_3;
				$bg2=BG_4;

			}
            $name=$ds[name];
            $slogan=$ds[slogan];
            $position=$ds[position];
            $country=$ds[country];

            
            eval ("\$staff = \"".gettemplate("staff")."\";");
            echo $staff;

			$i++;

		}
      unset($ds);
	if($all) {
	}

else echo $_language->module['no_staff'];

$end='</table>';
echo $end;



?>