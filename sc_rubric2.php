<?php


$_language->read_module('news');

	$ergebnis=safe_query("SELECT * FROM ".PREFIX."news WHERE newsID='".$topnewsID."' AND rubric='0' AND published='1' LIMIT 0,1");
     while($dn=mysql_fetch_array($ergebnis)) {           

     $message_array = array();
     $query=safe_query("SELECT * FROM ".PREFIX."news_contents WHERE newsID='".$dn['newsID']."'");
     while($qs = mysql_fetch_array($query)) {
          $message_array[] = array('lang' => $qs['language'], 'headline' => $qs['headline'], 'message' => $qs['content']);
     }
     $showlang = select_language($message_array);

     $headline=clearfromtags($message_array[$showlang]['headline']);
	 if(strlen($headline)>25) 
	 {
                        $headline=substr($headline, 0, 25);
                        $headline.='..';
     }
	 
     $content=$message_array[$showlang]['message'];
     
     if(mb_strlen($content)> $maxtopnewschars) {
          $content=mb_substr($content, 0, $maxtopnewschars);
          $content.='...';
     }
      $anzcomments = getanzcomments($ds['newsID'], 'ne');
     $content = nl2br(strip_tags($content));
     $topnewsID = $dn['newsID'];
           $read_more = '<img src="slice/button.png" width="24" height="24" alt="button" style="float:right; margin-right:10px;" />';
     $rubrikname = getrubricname($dn['rubric']);
          $rubrikname_link = getinput($rubrikname);
          $rubricpic_path = "images/news-rubrics/".getrubricpic($dn['rubric']);
          $rubricpic='<img src="'.$rubricpic_path.'" border="0" alt="" />';
          if(!is_file($rubricpic_path)) $rubricpic='';
          $poster= getnickname($dn['poster']);
          $date = date("d.m.y", $dn['date']);
          $time = date("H", $dn['date']);


     eval ("\$sc_topnews = \"".gettemplate("sc_topnews")."\";");
     echo $sc_topnews;
}
?>