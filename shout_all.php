<script src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
<script language="javascript" type="text/javascript" src="js/tchat.js"></script>
<link href="css/tchat.css" rel="stylesheet" type="text/css" />
<div id="shoutAll" style="width:50%; position:absolute; top:0; left:0; z-index:100; background-color:#FFF; padding:20px; border-radius:10px; border:5px solid #333; display:none; box-shadow:0 0 10px #FFF;">
<div style="min-height:50px; max-height:400px;overflow:auto; width:100%; float:left;">
<table width="100%" cellpadding="0" cellspacing="0">
<?php

if($channelID != "" AND $type != "") {

       $channel = ($channelID==$userID ? "channelID='$channelID' && type='$type'" : "channelID='$channelID' && type='$type' && (pseudo='$userID' || pseudo='$channelID')");
}
else{
       $channel = "channelID=''";
} 

$requete = safe_query("SELECT * FROM ".PREFIX."tchat WHERE $channel ORDER BY ID DESC");
while($ds=mysql_fetch_assoc($requete)){
$date = date("d.m.Y", $ds['heure']);
$time = date("H:i", $ds['heure']);
?>
<tr id="<?php echo $ds['ID'] ?>" onmouseover="mouseOver(<?php echo $ds['ID'] ?>);" onmouseout="mouseOut(<?php echo $ds['ID'] ?>);">
<td  style="border-bottom:1px solid #000; margin-bottom:5px;"><?php echo '<b>'.getnickname($ds['pseudo']).'</b> '.$_language->module['the']." ".$date.' '.$_language->module['at'].' '.$time ?><br /><?php echo cleartext($ds['message']) ?></td>
</tr>

<?php
}
eval ("\$addbbcode = \"".gettemplate("addbbcode")."\";");
?>
</table>
</div>
</div>
<div id="black" onclick="allMsgClose();" style="background:#000000; opacity:0.5; width:100%; height:100%; position:absolute; top:0; left:0; z-index:50; display:none;"></div>
