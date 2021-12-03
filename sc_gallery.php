<?php
$max = 4; # Wie viel bilder max. angezeigt wreden
$witdh = 100/$max;
$picwitdh = $witdh-1;
$_language->read_module('gallery');

$ergebnis=safe_query("SELECT * FROM ".PREFIX."gallery_pictures ORDER BY RAND() LIMIT  0, ".$max);
	if(mysql_num_rows($ergebnis)){
			while($ds=mysql_fetch_array($ergebnis)) {
				$id = $ds['picID'];
				$title = $ds['name'];
				$views = $ds['views'];
				
				if(mb_strlen($title)> 15) {
				$title=mb_substr($title, 0, 15);
				$title.='...';
				}
				
				if(file_exists('images/gallery/large/'.$id.'.jpg')) $file='images/gallery/large/'.$id.'.jpg';
				elseif(file_exists('images/gallery/large/'.$id.'.gif')) $file='images/gallery/large/'.$id.'.gif';
				elseif(file_exists('images/gallery/large/'.$id.'.png')) $file='images/gallery/large/'.$id.'.png';
				else $file='images/nopic.gif';

				
				echo '<div style="width:'.$witdh.'%; float:left; text-align:center;"><a href="index.php?site=gallery&amp;picID='.$id.'"><img src="'.$file.'" alt="'.$title.'" style="max-width:40px" border="0" /></a><br />
<b>'.$title.'</b><br />
'.$_language->module['views'].' ('.$views.')</div>';
			}

	}
?>

