<?php
include("_mysql.php");
include("_settings.php");
include("_functions.php");

$bg1=BG_1;
$bg2=BG_1;
$bg3=BG_1;
$bg4=BG_1;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta name="description" content="Clanpage using webSPELL 4 CMS">
    <meta name="author" content="webspell.org">
    <meta name="keywords" content="webspell, webspell4, clan, cms">
    <meta name="copyright" content="Copyright © 2005 by webspell.org" />
    <meta name="generator" content="webSPELL" />
  </head>

<!-- Head & Title include -->
<title><? echo PAGETITLE; ?></title>
<link href="_stylesheet.css" rel="stylesheet" type="text/css">
<script src="js/bbcode.js" language="jscript" type="text/javascript"></script>
<script src="js/drop.js" language="jscript" type="text/javascript"></script>
<script src="js/roll.js" language="jscript" type="text/javascript"></script>

<style>
tr, td, table
{
padding:4px;
border-collapse:separate;
border-spacing:0px 0px;
border-color:#ffffff;
}
fieldset, legend {
margin:10px;
padding:px;
}

.title
{
border-spacing:0px 0px;
margin-top: 25px;
}
</style>

  <body>
    <?
      if(!isset($site)) $site="news";
      $invalide = array('/','/\/',':','.');
      $site = str_replace($invalide,' ',$site);
      if(!file_exists($site.".php")) $site = "news";
      include($site.".php");
    ?>
  </body>    
</html>
