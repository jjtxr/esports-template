<?php

// Addon by WEBST4RS.DE | Bulletproof
// Addon Copyright by www.bulletproof-media.net

$anz_images = "4"; // Anzahl der Bilder die angezeigt werden sollen!
$sort = "2"; // 1 = Zufallsbilder, 2 = Die neuesten 3 Bilder

switch($sort)
{
	case 1: $sort='RAND()'; break;	
	case 2: $sort='picID DESC'; break;
	default: $sort='RAND()'; break;
}
$query = safe_query("SELECT * FROM `".PREFIX."gallery_pictures` ORDER BY ".$sort." LIMIT 0,".$anz_images);
$n=1;
while($ds = mysql_fetch_array($query))
{
	$picID = $ds['picID'];
	$name = $ds['name'];
	
	// Margin vom letzten Bild aufheben
	if($n == "4") $margin = 'style="margin-right:0px;"';			
	else $margin = '';	
	
	eval ("\$sc_randomgallery = \"".gettemplate("sc_randomgallery")."\";");
	echo $sc_randomgallery;
	$n++;
}
// Um die Floats aufzuheben!
echo '<div style="clear:both;"></div>';
?>