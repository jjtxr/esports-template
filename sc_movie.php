<?php

//****************************************
//* sc_Movie-Addon 2.0 by FIRSTBORN e.V. *
//* this Addon works only with the	     *
//* installed FIRSTBORN Movie-Addon      *
//****************************************

$selection = "0";    // 1 -> most viewed;  2 -> latest video;  3 -> preselected video;	0 -> random video

$playerwidth = "200"; // the width of the player
$playerheight = "150"; // the height of the player

// only edit below this Line if chosen preselected video

$movID = "1";  // enter here the ID of the Movie you want to be displayed


// no need to edit below this line

if($selection==2) { $sort = "WHERE activated='2' ORDER BY date DESC LIMIT 0,1"; }
elseif($selection==3) { $sort = "WHERE movID='".$movID."' AND activated='2' LIMIT 0,1"; }
elseif($selection==1) { $sort = "WHERE activated='2' ORDER BY hits DESC LIMIT 0,1"; }
else {
	$result = safe_query("SELECT * FROM ".PREFIX."movies ORDER BY movID"); 
	$anzahl = mysql_num_rows($result); 

	for($i=0; $i<1; $i++){
	srand((double)microtime()*1000000);
	${"output".$i} = rand(1,$anzahl);
	}
	$output=$output0-1;
	$sort = "WHERE activated='2' ORDER BY movID ASC LIMIT ".$output.",1";

}

$ergebnis = safe_query("SELECT * FROM ".PREFIX."movies $sort");
echo '<table width="'.$playerwidth.'" cellpadding="0" cellspacing="0">
  	   <tr>
	    <td>';
while($dr=mysql_fetch_array($ergebnis)) {

echo '<div id="container1"><a href="http://www.macromedia.com/go/getflashplayer"><b>Get the Flash Player</b></a> to see this player.</div>
	<script type="text/javascript" src="js/swfobject.js"></script>
	<script type="text/javascript">
		var s1 = new SWFObject("mediaplayer.swf","mediaplayer","'.$playerwidth.'","'.$playerheight.'","8");
		s1.addParam("allowfullscreen","true");
		s1.addVariable("width","'.$playerwidth.'");
		s1.addVariable("height","'.$playerheight.'");
		s1.addVariable("file","'.$dr[movfile].'");
		s1.write("container1");
	</script>';

}

echo '</td></tr></table>';

?>