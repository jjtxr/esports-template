<?php
/*
    #########################################################
  ###                                                       #
#####            FEATURED CONTENT SLIDER ADDON FOR          #
#####                      WEBSPELL 4.X                     #
#####               VERSION 2.0.0 - 15.03.2011              #
#####              LICENSE: GNU GPL www.gnu.org             #
#####              ORIGINAL CODE BY HENNINGK.DE             #
#####                                                       #
#####             CONTACT: admin@henningk.de                #
#####                                                       #
#### ########################################################
## ########################################################
 ########################################################
*/

class fc_slider {

	static function get_include_head() {
		$set = self::get_setting('*');
		if($set['include_jquery']) $jquery = '<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/'.$set['jquery_v'].'/jquery.min.js"></script>';
		else $jquery = '';
		$params = $set['js_parameter'] ? ','.PHP_EOL.$set['js_parameter'] : '';
		echo PHP_EOL.'<!-- FEATURED CONTENT SLIDER v2.0 by http://henningk.de -->'.PHP_EOL;
		eval ("\$head = \"".gettemplate("sc_fc_slider_head_includes")."\";");
		return $head;
	}

	static function get_setting($name = '') {
		if($name) {
			$res = mysql_fetch_array(safe_query("SELECT $name FROM ".PREFIX."featuredcont_set LIMIT 0,1"));
			return count($res) == 1 ? $res[$name] : $res;
		}
	}

	static function get_include_body() {
		$max_entrys = 8; // change limit for more or less entrys

		$fullimgs = "";
		$fcnavs = "";
			
		$query = safe_query("SELECT * FROM ".PREFIX."featuredcont WHERE activated=1 ORDER BY sortid ASC LIMIT 0,".$max_entrys);
		$blankID = 1;
		while($result = mysql_fetch_array($query)) {
			$fullimgurl = $result['fullimg'];
			$smallimgurl = $result['smallimg'];
			$fcid = $result['id'];
			$curclass = ($blankID == 1) ? $curclass = " cur" : $curclass="";
			$fcurl = $result['url'];
			$fctext = $result['text'];
			$fcname = $result['name'];
			$fctease = $result['tease'];
			$target = $result['new_window'] ? '_blank' : '_self';

			eval ("\$fcnavs .= \"".gettemplate("sc_fc_slider_nav")."\";");
			eval ("\$fullimgs .= \"".gettemplate("sc_fc_slider_img")."\";");
			$blankID++;
		}
			
		eval ("\$sc_featuredcont = \"".gettemplate("sc_fc_slider_wrap")."\";");
		return $sc_featuredcont;
	}

	// ADMINCENTER

	static function move_image_home($imgsrc,$pre) {
		if(is_uploaded_file($imgsrc['tmp_name'])) {
			$imgname = $pre.time()."_".$imgsrc["name"];
			if($move = move_uploaded_file($imgsrc['tmp_name'], '../images/featuredcont/'.$imgname)) {
				return $imgname;
			}
			else return false;
		}
		else {
			return false;
		}
	}
}
?>