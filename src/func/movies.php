<?php

//************************************
//* Movie-Addon v1.1 by FIRSTBORN.de *
//************************************

function getmovcat($movcatID) {

		$ds=mysql_fetch_array(safe_query("SELECT movcatname FROM ".PREFIX."movie_categories WHERE movcatID='".$movcatID."'"));
		return htmlspecialchars($ds[movcatname]);

	}
	
	
?>