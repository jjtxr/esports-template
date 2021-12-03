<link href="cup.css" rel="stylesheet" type="text/css" />
<script language="javascript">wmtt = null; document.onmousemove = updateWMTT; function updateWMTT(e) { x = (document.all) ? window.event.x + document.body.scrollLeft : e.pageX; y = (document.all) ? window.event.y + document.body.scrollTop  : e.pageY; if (wmtt != null) { wmtt.style.left=(x + 20) + "px"; wmtt.style.top=(y + 20) + "px"; } } function showWMTT(id) {	wmtt = document.getElementById(id);	wmtt.style.display = "block" } function hideWMTT() { wmtt.style.display = "none"; }</script>

<div class="tooltip" id="unchecked" align="left"><font color="#FF6600"><b><center>Status...</center></b></font> <font color="white">"1" Team has active status<br>"0" Team has inactive status.</font> </div>
<?php 

include ("config.php");

$bg1=BG_1;
$bg2=BG_1;
$bg3=BG_1;
$bg4=BG_1;

$ct=mysql_fetch_array(safe_query("SELECT cupteamlimit FROM ".PREFIX."cup_settings"));
$cupteamlimit = $ct['cupteamlimit'];

$search = trim ($_GET['search']);
$type = trim ($_GET['type']);
$per_page=$cupteamlimit;
$start=$_GET['start'];

if($_GET['type']=='username') {
   redirect('?site=teams&login='.$_GET['search'], '', 0);
}

if (!$search) {
  $error="No search term inputted.<br /><br />";
}

if (!get_magic_quotes_gpc()){
$search = addslashes($search);
} 
include("_mysql.php");
@$db = new mysqli($host, $user, $pwd, $db);

if (mysqli_connect_errno()){
  echo 'Error: Connecting to the database.';
  exit;
}

if (!$start) {
  $start=0;
}

$query = "SELECT * FROM ".PREFIX."cup_all_clans WHERE ".$type." LIKE '%".$search."%' ORDER BY name ASC LIMIT $start, $per_page";
$query2 = "SELECT * FROM ".PREFIX."cup_all_clans WHERE ".$type." LIKE '%".$search."%' ORDER BY name ASC";
 
$result = $db->query($query);
$result2 = $db->query($query2);

$ergebnis2 = "SELECT ID, name, country, clantag, clanhp, leader, clanlogo, password, server, status FROM ".PREFIX."cup_all_clans ORDER BY name ASC";
$result3 = $db->query($ergebnis2);

$ergebnis = "SELECT ID, cupID, clanID, checkin FROM ".PREFIX."cup_clans";
$cup = $db->query($ergebnis);


$num_results = $result->num_rows;
$num_results2 = $result2->num_rows;
$max_pages = $result->num_rows/$per_page;

?>


<body>
<div id="maincontent">
    <form action="index.php" method="get">
      <input type="hidden" name="site" value="teamsearch">
      <input type="text" name="search" />
       Search By: 
        <select name="type">
          <option value="name" selected="selected">Team Name</option>
          <option value="short">Bracket Name</option>
          <option value="clantag">Clan Tag</option>
	  <option value="username">Username</option>
        </select>
      <input type="submit" value="Search" />
    </form>
    
<div id="maincontent">
<br />Found <font color="red"><strong><?php echo $num_results2;?></strong></font> team<?php echo $num_results2 >1 ? "s" : ""; ?> for your search query <strong><?php echo $search; ?></strong> in <strong><?php echo ucwords($type); ?></strong> category.<hr>
<br>
<?php if ($error){
  echo $error;
} ?>

<?php 
$prev = $start - $per_page;
$next = $start + $per_page;
if (!($start<=0)){
  echo "<ul id='pagination-digg'><a class='previous-off' href='?site=teamsearch&search=".$search."&type=".$type."&cupID=".$cupID."&start=".$prev."'><img border='0' src='images/cup/icons/goback.png' width='16' height='16'></a></ul>";
}
//pagination numbers....
$a=1;
 for ($x=0; $x<$num_results2; $x=$x+$per_page){

  if($num_results2 > $per_page)
  {  
   if ($start!=$x){
       echo "<ul id='pagination-digg'><a href='?site=teamsearch&search=".$search."&type=".$type."&cupID=".$cupID."&start=".$x."'> $a</a></ul>";
   }else{
       echo "<ul id='pagination-digg'><a class='active' href='?site=teamsearch&search=".$search."&type=".$type."&cupID=".$cupID."&start=".$x."'> <font color='white'>$a</font></a></strong></ul>";
   }
  }
   
$a++;
 }
//pagination numbers....
if ($start + $per_page<$num_results2){
echo "<a class='next' href='?site=teamsearch&search=".$search."&type=".$type."&cupID=".$cupID."&start=".$next."'> <img border='0' src='images/cup/icons/goforward.png' width='16' height='16'></a>";
}

for ($i=0; $i<$num_results; $i++) {
$row = $result->fetch_assoc();

?>

                    <tr> 
                      <td valign="top"><table width="100%" border="0" cellspacing="<?php echo $cellspacing; ?>" cellpadding="<?php echo $cellpadding; ?>" bgcolor="<?php echo $border; ?>">
                    <tr>
                      <td width="10%" bgcolor="<?php echo $bg1; ?>" rowspan="5" ><img src="<?php if ($row['clanlogo']=="http://" || $row['clanlogo']=="") { echo "images/avatars/noavatar.gif"; }else{ echo $row['clanlogo']; } ?>" width="100" vspace="5" height="100"></td>
                      <td width="10%" bgcolor="<?php echo $bg1; ?>" > <strong>Teamname</strong></td>
                      <td width="89%" bgcolor="<?php echo $bg1; ?>" ><img width="18" height="12" src="images/flags/<?php echo htmlspecialchars(stripslashes($row['country']));?>.gif"> <a href="?site=clans&action=show&clanID=<?php echo htmlspecialchars(stripslashes($row['ID']));?>"><?php echo htmlspecialchars(stripslashes($row['name']));?></a></td>
                    </tr>
                    <tr>
                      <td bgcolor="<?php echo $bg1; ?>" > <strong>Clan Tag</strong></td>
                      <td bgcolor="<?php echo $bg1; ?>" ><?php echo htmlspecialchars(stripslashes($row['clantag']));?></td>
                    </tr>
		    <?php if($row['clanhp']!='http://' && !empty($row['clanhp'])) { ?>
                    <tr>
                      <td bgcolor="<?php echo $bg1; ?>" > <strong>Website</strong></td>
                      <td bgcolor="<?php echo $bg1; ?>" ><a href="<?php echo htmlspecialchars(stripslashes($row['clanhp']));?>" target="_blank"><?php echo htmlspecialchars(stripslashes($row['clanhp']));?></a></td>
                    </tr>
		    <?php } ?>
                    <tr>
                      <td bgcolor="<?php echo $bg1; ?>" ><strong>Leader</strong></td>
                      <td bgcolor="<?php echo $bg1; ?>" ><?php echo livecontact($row['leader'],1); ?></td>
                    </tr>
                    <tr>
                      <td bgcolor="<?php echo $bg1; ?>" ><strong>Action</strong></td>
                      <td bgcolor="<?php echo $bg1; ?>" ><img border="0" src="images/icons/foldericons/folder.gif" width="16" height="16"> <a href="?site=clans&action=show&clanID=<?php echo htmlspecialchars(stripslashes($row['ID']));?>"><b>More Details</b></a> <img src="images/cup/icons/join.png" width="16" height="16"> <a href="?site=clans&action=clanjoin&clanID=<?php echo htmlspecialchars(stripslashes($row['ID']));?>"><b>Join Team <?php echo htmlspecialchars(stripslashes($row['tag']));?></b></a></td>
<?php
echo '                    </tr>
                   </td>
                  </table>
                  <br><br>';

}

$result->free();
$db->close();
?>

<?php 

$prev = $start - $per_page;
$next = $start + $per_page;
if (!($start<=0)){
  echo "<ul id='pagination-digg'><a class='previous-off' href='?site=teamsearch&search=".$search."&type=".$type."&cupID=".$cupID."&start=".$prev."'><img border='0' src='images/cup/icons/goback.png' width='16' height='16'></a></ul>";
}
//pagination numbers....
$a=1;
 for ($x=0; $x<$num_results2; $x=$x+$per_page){
  if($num_results2 > $per_page)
  {  
   if ($start!=$x){
       echo "<ul id='pagination-digg'><a href='?site=teamsearch&search=".$search."&type=".$type."&cupID=".$cupID."&start=".$x."'> $a</a></ul>";
   }else{
       echo "<ul id='pagination-digg'><a class='active' href='?site=teamsearch&search=".$search."&type=".$type."&cupID=".$cupID."&start=".$x."'> <font color='white'>$a</font></a></strong></ul>";
   }
  }
$a++;
 }
//pagination numbers....
if ($start + $per_page<$num_results2){
echo "<a class='next' href='?site=teamsearch&search=".$search."&type=".$type."&cupID=".$cupID."&start=".$next."'> <img border='0' src='images/cup/icons/goforward.png' width='16' height='16'></a>";
}
?>
<a href='#'>Top ^</a>
</div>
</div>
</body>
</html>