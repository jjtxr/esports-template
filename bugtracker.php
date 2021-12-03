<?php

$settings=safe_query("SELECT * FROM ".PREFIX."settings");
$do=mysql_fetch_array($settings);

echo '<link href="http://'.getinput($do['hpurl']).'/_stylesheet.css" rel="stylesheet" type="text/css" />
      <iframe src="http://teamx1.com/popup.php?site=bugtracker&catID=9" width="100%" height="1050" frameborder="0"></iframe>';

?>