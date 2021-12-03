<?php

$release_date = "27/03/2012";

   if(isset($_GET['action']) && $_GET['action']=="install") { 

		safe_query("CREATE TABLE `".PREFIX."cups` (
		  `ID` int(11) NOT NULL auto_increment,
		  `gameaccID` int(11) NOT NULL,
		  `name` varchar(255) NOT NULL,
		  `desc` longtext NOT NULL,
		  `game` varchar(255) NOT NULL,
		  `typ` varchar(6) NOT NULL,
		  `maxclan` int(3) NOT NULL default '0',
		  `start` int(15) NOT NULL default '0',
		  `ende` int(15) NOT NULL default '0',		  
		  `gs_start` int(15) NOT NULL default '0',
		  `gs_end` int(15) NOT NULL default '0',		  
		  `gs_maxrounds` int(15) NOT NULL default '0',
		  `gs_staging` int(15) NOT NULL default '1',
		  `gs_regtype` int(15) NOT NULL default '0',
		  `gs_trans` int(15) NOT NULL default '1',	  
		  `gs_dxp` int(15) NOT NULL default '0',	  
		  `status` int(1) NOT NULL default '0',
		  `gewinn1` varchar(255) NOT NULL,
		  `gewinn2` varchar(255) NOT NULL,
		  `gewinn3` varchar(255) NOT NULL,
		  `1on1` int(1) NOT NULL default '0',
		  `checkin` int(11) NOT NULL default '0',
		  `clanmembers` int(11) NOT NULL default '0',
                  `cupgalimit` int(11) NOT NULL default '1', 
                  `cupaclimit` int(11) NOT NULL default '0', 
                  `gameacclimit` int(11) NOT NULL default '1', 
                  `cgameacclimit` int(11) NOT NULL default '0',
                  `ratio_low` int(11) NOT NULL default '0',
                  `ratio_high` int(11) NOT NULL default '0',
                  `timezone` varchar(255) NOT NULL,  
		  PRIMARY KEY  (`ID`)
		) AUTO_INCREMENT=1;") OR die(PREFIX.'cups table already exists? perhaps already installed?');

		safe_query("CREATE TABLE `".PREFIX."cup_admins` (
		  `adminID` int(11) NOT NULL auto_increment,
		  `cupID` int(11) NOT NULL default '0',
		  `ladID` int(11) NOT NULL default '0',
		  `userID` int(11) NOT NULL default '0',
		  PRIMARY KEY  (`adminID`)
		) AUTO_INCREMENT=1;") OR die(PREFIX.'cup_admins table already exists? perhaps already installed?');
		
		safe_query("CREATE TABLE IF NOT EXISTS `".PREFIX."cup_agents` (
		  `ID` int(11) NOT NULL auto_increment,
		  `userID` int(11) NOT NULL,
		  `cupID` int(11) NOT NULL,
		  `ladID` int(11) NOT NULL,
		  `avail` int(1) NOT NULL,
		  `name` varchar(255) NOT NULL,
		  `play` varchar(255) NOT NULL,
		  `info` longtext NOT NULL,
                  `time` int(15) NOT NULL,          
		  PRIMARY KEY  (`ID`)
		) AUTO_INCREMENT=1;") OR die(PREFIX.'cup_agents table already exists, perhaps v5.2 is already installed?');
		
		safe_query("DROP TABLE IF EXISTS `".PREFIX."cup_all_clans`");
		safe_query("CREATE TABLE `".PREFIX."cup_all_clans` (
		  `ID` int(11) NOT NULL auto_increment,
		  `name` varchar(255) NOT NULL,
		  `country` varchar(255) NOT NULL,
		  `short` varchar(255) NOT NULL,
		  `clantag` varchar(255) NOT NULL,
		  `clanhp` varchar(255) NOT NULL,
		  `clanlogo` varchar(255) NOT NULL,
		  `leader` int(11) NOT NULL default '0',
		  `password` varchar(255) NOT NULL,
		  `server` varchar(255) NOT NULL,
		  `port` varchar(255) NOT NULL,
		  `chat` INT(11) NOT NULL default '1',
		  `comment` INT(11) NOT NULL default '1',
		  `reg` int(15) NOT NULL,
		  `status` int(1) NOT NULL default '0',
		  PRIMARY KEY  (`ID`)
		) AUTO_INCREMENT=1;") OR die(PREFIX.'cup_all_clans table already exists? perhaps already installed?');
		
		
		safe_query("CREATE TABLE `".PREFIX."cup_baum` (
		  `ID` int(11) NOT NULL auto_increment,
		  `cupID` int(11) NOT NULL default '0',
		  `wb_winner` int(11) NOT NULL,
		  `lb_winner` int(11) NOT NULL,
		  `second_winner` int(11) NOT NULL,
		  `third_winner` int(11) NOT NULL,
		  `map1` varchar(255) NOT NULL,
		  `map2` varchar(255) NOT NULL,
		  `map3` varchar(255) NOT NULL,
		  `map4` varchar(255) NOT NULL,
		  `map5` varchar(255) NOT NULL,
		  `map6` varchar(255) NOT NULL,
		  `map7` varchar(255) NOT NULL,
		  `map8` varchar(255) NOT NULL,
		  `map9` varchar(255) NOT NULL,
		  `map10` varchar(255) NOT NULL,
		  `map11` varchar(255) NOT NULL,
		  `map12` varchar(255) NOT NULL,
		  `map13` varchar(255) NOT NULL,
		  `map14` varchar(255) NOT NULL,
		  `map15` varchar(255) NOT NULL,
                  `map16` varchar(255) NOT NULL,
                  `map17` varchar(255) NOT NULL,
		  `borderbg` varchar(255) NOT NULL,
		  `bg1` varchar(255) NOT NULL,
		  `bg2` varchar(255) NOT NULL,
		  PRIMARY KEY  (`ID`)
		) AUTO_INCREMENT=1;") OR die(PREFIX.'cup_baum table already exists? perhaps already installed?');

		safe_query("CREATE TABLE `".PREFIX."cup_challenges` (
		  `chalID` int(11) NOT NULL auto_increment,
		  `ladID` int(11) NOT NULL default '0',
		  `challenger` int(11) NOT NULL,
		  `challenged` int(11) NOT NULL,
		  `map1` varchar(255) NOT NULL,
		  `map2` varchar(255) NOT NULL,
		  `map3` varchar(255) NOT NULL,
		  `map4` varchar(255) NOT NULL,
		  `map5` varchar(255) NOT NULL,
		  `map1_final` varchar(255) NOT NULL,
		  `map2_final` varchar(255) NOT NULL,
		  `map3_final` varchar(255) NOT NULL,
		  `map4_final` varchar(255) NOT NULL,
		  `new_date` int(15) NOT NULL,
		  `reply_date` int(15) NOT NULL,
		  `finalized_date` int(15) NOT NULL,
		  `game_date` int(15) NOT NULL,
		  `date1` int(15) NOT NULL,
		  `date2` int(15) NOT NULL,
		  `date3` int(15) NOT NULL,
                  `date4` int(15) NOT NULL,
                  `date5` int(15) NOT NULL,
		  `server` varchar(255) NOT NULL,
		  `port` int(5) NOT NULL,
		  `serverc` varchar(255) NOT NULL,
		  `challenger_info` longtext NOT NULL,
		  `challenged_info` longtext NOT NULL,
		  `forfeit` int(11) NOT NULL,
		  `status` int(11) NOT NULL,	
                  `1on1` int(1) NOT NULL,	  
		  PRIMARY KEY (`chalID`)
		) AUTO_INCREMENT=1;") OR die(PREFIX.'cup_challenges table already exists? perhaps already installed?');

		safe_query("CREATE TABLE `".PREFIX."cup_clans` (
		  `ID` int(11) NOT NULL auto_increment,
		  `cupID` varchar(11) NOT NULL default '0',
		  `platID` int(11) NOT NULL,
		  `ladID` varchar(11) NOT NULL default '0',
		  `groupID` int(11) NOT NULL,
		  `credit` int(11) NOT NULL,
		  `registered` int(15) NOT NULL,
		  `clanID` int(11) NOT NULL,
		  `1on1` int(1) NOT NULL,
		  `checkin` int(1) NOT NULL,
		  `won` int(11) NOT NULL,
		  `draw` int(11) NOT NULL,
		  `lost` int(11) NOT NULL,
		  `streak` int(11) NOT NULL,
		  `xp` int(11) NOT NULL,
		  `tp` int(11) NOT NULL,
		  `rt` int(11) NOT NULL,
		  `wc` int(11) NOT NULL,
		  `ma` int(11) NOT NULL,
		  `lastpos` int(1) NOT NULL,
		  `lastact` int(15) NOT NULL,
                  `lastdeduct` int(15) NOT NULL,
                  `qual` int(1) NOT NULL,
                  `pm` int(1) NOT NULL,	
                  `rank_now` int(11) NOT NULL,
                  `rank_then` int(11) NOT NULL,
                  `type` varchar(255) NOT NULL, 		  
		  PRIMARY KEY  (`ID`)
		) AUTO_INCREMENT=1;") OR die(PREFIX.'cup_clans table already exists? perhaps already installed?');

		safe_query("CREATE TABLE `".PREFIX."cup_clan_lineup` (
		  `ID` int(11) NOT NULL auto_increment,
		  `cupID` int(11) NOT NULL default '0',
		  `clanID` int(11) NOT NULL default '0',
		  `userID` int(11) NOT NULL default '0',
		  PRIMARY KEY  (`ID`),
		  UNIQUE (`cupID`, `clanID`, `userID`)
		) AUTO_INCREMENT=1 ;") OR die(PREFIX.'cup_clan_lineup table already exists? perhaps already installed?');		

		safe_query("CREATE TABLE `".PREFIX."cup_clan_members` (
		  `ID` int(11) NOT NULL auto_increment,
		  `cupID` int(11) NOT NULL default '0',
		  `clanID` int(11) NOT NULL default '0',
		  `userID` int(11) NOT NULL default '0',
		  `function` varchar(255) NOT NULL,
		  `reg` int(15) NOT NULL,
		  `agent` int(1) NOT NULL,
		  PRIMARY KEY  (`ID`)
		) AUTO_INCREMENT=1;") OR die(PREFIX.'cup_clan_members table already exists? perhaps already installed?');

		safe_query("CREATE TABLE `".PREFIX."cup_deduction` (
		  `ID` int(11) NOT NULL auto_increment,
		  `ladID` int(11) NOT NULL default '0',
		  `clanID` int(11) NOT NULL default '0',
		  `deducted` int(11) NOT NULL default '0',
		  `terminated` int(1) NOT NULL,
		  `credit` int(11) NOT NULL,
		  `time` int(15) NOT NULL,
		  PRIMARY KEY  (`ID`)
		) AUTO_INCREMENT=1;") OR die(PREFIX.'cup_deduction table already exists? perhaps already installed?');

		safe_query("CREATE TABLE `".PREFIX."cup_departments` (
		  `ID` int(11) NOT NULL auto_increment,
		  `department` varchar(255) NOT NULL,
		  PRIMARY KEY  (`ID`)
		) AUTO_INCREMENT=1;") OR die(PREFIX.'cup_departments table already exists? perhaps already installed?');

		safe_query("CREATE TABLE `".PREFIX."cup_ladders` (
		  `ID` int(11) NOT NULL auto_increment,
		  `platID` int(11) NOT NULL default '0',
		  `mappack` varchar(255) NOT NULL,
		  `gameaccID` int(11) NOT NULL,
		  `name` varchar(255) NOT NULL,
		  `abbrev` varchar(255) NOT NULL,
		  `desc` longtext NOT NULL,
		  `game` varchar(255) NOT NULL,
		  `gametype` varchar(255) NOT NULL,
		  `maxclan` int(3) NOT NULL,
		  `start` int(15) NOT NULL,
		  `end` int(15) NOT NULL,
		  `gs_start` int(15) NOT NULL,
		  `gs_end` int(15) NOT NULL,
		  `gs_maxrounds` int(11) NOT NULL,
		  `gs_staging` int(1) NOT NULL,
		  `gs_regtype` int(1) NOT NULL,
		  `gs_trans` int(1) NOT NULL,
		  `ratio_low` int(11) NOT NULL,
		  `ratio_high` int(11) NOT NULL,
                  `type` varchar(6) NOT NULL,
                  `mode` int(1) NOT NULL,
		  `ranksys` int(1) NOT NULL,
		  `select_map` int(11) NOT NULL,
		  `selected_map` int(11) NOT NULL,
		  `select_date` int(11) NOT NULL,
		  `timestart` varchar(255) NOT NULL,
		  `timeintervals` int(15) NOT NULL,			  
		  `timeend` varchar(255) NOT NULL,
		  `timetorespond` int(15) NOT NULL,
		  `timetofinalize` int(15) NOT NULL,
		  `challallow` int(11) NOT NULL,
		  `challquant` int(11) NOT NULL,
		  `inactivity` int(15) NOT NULL,
		  `deduct_credits` int(11) NOT NULL,
		  `remove_inactive` int(15) NOT NULL,
		  `playdays` int(15) NOT NULL,
		  `ad_report` int(1) NOT NULL,
		  `challup` int(11) NOT NULL,
		  `challdown` int(11) NOT NULL,
		  `status` int(11) NOT NULL,
		  `gewinn1` varchar(255) NOT NULL,
		  `gewinn2` varchar(255) NOT NULL,
		  `gewinn3` varchar(255) NOT NULL,
		  `1on1` int(1) NOT NULL,
		  `clanmembers` int(1) NOT NULL,
		  `gameacclimit` int(1) NOT NULL,
		  `cgameacclimit` int(1) NOT NULL,
		  `d_xp` int(1) NOT NULL,
                  `timezone` varchar(255) NOT NULL, 
                  `1st` int(11) NOT NULL,
                  `2nd` int(11) NOT NULL,	
                  `3rd` int(11) NOT NULL,  		  
		  PRIMARY KEY  (`ID`)
		) AUTO_INCREMENT=1;") OR die(PREFIX.'cup_ladders table already exists? perhaps already installed?');

		safe_query("CREATE TABLE `".PREFIX."cup_maps` (
		  `mapID` int(11) NOT NULL auto_increment,
		  `mappack` varchar(255) NOT NULL,
		  `map` varchar(255) NOT NULL,
		  `pic` varchar(255) NOT NULL,
		  PRIMARY KEY  (`mapID`)
		) AUTO_INCREMENT=1;") OR die(PREFIX.'cup_maps table already exists? perhaps already installed?');

		safe_query("CREATE TABLE `".PREFIX."cup_matches` (
		  `matchID` int(11) NOT NULL auto_increment,
		  `cupID` varchar(11) NOT NULL,
		  `ladID` varchar(11) NOT NULL,
		  `date` int(15) NOT NULL,
		  `date2` varchar(255) NOT NULL,
		  `added_date` int(15) NOT NULL,
		  `inscribed_date` int(15) NOT NULL,
		  `confirmed_date` int(15) NOT NULL,
		  `matchno` int(11) NOT NULL,
		  `clan1` int(11) NOT NULL,
		  `clan2` int(11) NOT NULL,
		  `score1` int(11) NOT NULL,
		  `score2` int(11) NOT NULL,		  
		  `map1_score1` int(11) NOT NULL,
		  `map1_score2` int(11) NOT NULL,
		  `map2_score1` int(11) NOT NULL,
		  `map2_score2` int(11) NOT NULL,
		  `map3_score1` int(11) NOT NULL,
		  `map3_score2` int(11) NOT NULL,
		  `map4_score1` int(11) NOT NULL,
		  `map4_score2` int(11) NOT NULL,		  
		  `server` varchar(255) NOT NULL,
		  `hltv` varchar(255) NOT NULL,
		  `screens` varchar(255) NOT NULL,
		  `screen_name` text NOT NULL,
		  `screen_upper` varchar(255) NOT NULL,
		  `report` varchar(255) NOT NULL,
		  `report_team1` text NOT NULL,
		  `report_team2` text NOT NULL,
		  `inscribed` int(1) NOT NULL,
		  `confirmscore` int(1) NOT NULL default '0',
		  `einspruch` int(1) NOT NULL default '0',
		  `comment` int(1) NOT NULL default '2',
		  `1on1` int(1) NOT NULL,
		  `si` int(1) NOT NULL,
                  `type` varchar(255) NOT NULL,
		  PRIMARY KEY  (`matchID`)
		) AUTO_INCREMENT=1;") OR die(PREFIX.'cup_matches table already exists? perhaps already installed?');


		safe_query("CREATE TABLE `".PREFIX."cup_platforms` (
		  `ID` int(11) NOT NULL auto_increment,
		  `platform` varchar(255) NOT NULL,
		  `name` varchar(255) NOT NULL,
		  `abbrev` varchar(255) NOT NULL,
		  `descrip` longtext NOT NULL,
                  `logo` varchar(255) NOT NULL,
                  `status` int(11) NOT NULL default '1',
		  PRIMARY KEY  (`ID`)
		) AUTO_INCREMENT=1;") OR die(PREFIX.'cup_platforms table already exists? perhaps already installed?');

		safe_query("CREATE TABLE `".PREFIX."cup_requests` (
		  `ID` int(11) NOT NULL auto_increment,
		  `matchID` int(11) NOT NULL,
		  `userID` int(11) NOT NULL,
		  `reason` varchar(255) NOT NULL,
		  `time` int(15) NOT NULL,
		  PRIMARY KEY  (`ID`)
		) AUTO_INCREMENT=1;") OR die(PREFIX.'cup_requests table already exists? perhaps already installed?');

		safe_query("CREATE TABLE `".PREFIX."cup_rules` (
		  `rulesID` int(11) NOT NULL auto_increment,
		  `cupID` int(11) NOT NULL,
		  `ladID` int(11) NOT NULL,
		  `value` longtext NOT NULL,
		  `lastedit` int(15) NOT NULL,
		  PRIMARY KEY  (`rulesID`)
		) AUTO_INCREMENT=1;") OR die(PREFIX.'cup_rules table already exists? perhaps already installed?');

		safe_query("CREATE TABLE `".PREFIX."cup_settings` (
		  `settingID` int(11) NOT NULL auto_increment,
		  `cupaclimit` INT(11) NOT NULL default '0',
	          `cupblockage` INT(11) NOT NULL default '10',
	          `cupteamlimit` INT(11) NOT NULL default '15',
	          `cupteamadd` INT(11) NOT NULL default '1',
                  `cupteamjoin` INT(11) NOT NULL default '4',
                  `cupsclimit` INT(11) NOT NULL default '3',
                  `cupgalimit` INT(11) NOT NULL default '1',
                  `cupgamelimit` INT(11) NOT NULL default '1',
                  `ccupgamelimit` INT(11) NOT NULL default '0', 
                  `gameacclimit` INT(11) NOT NULL default '0',
                  `cgameacclimit` INT(11) NOT NULL default '1',
                  `cupchathost` varchar(255) NOT NULL default 'irc.evolu.net',
                  `cupinfo` INT(11) NOT NULL default '1',
                  `maintenance` INT(11) NOT NULL default '0',
                  `timezone` varchar(255) NOT NULL default 'Europe/London',
		  PRIMARY KEY  (`settingID`)
		) AUTO_INCREMENT=1;") OR die(PREFIX.'cup_settings table already exists? perhaps already installed?');

		safe_query("CREATE TABLE `".PREFIX."cup_tickets` (
		  `ticketID` int(11) NOT NULL auto_increment,
		  `department` int(11) NOT NULL,
	          `userID` int(11) NOT NULL,
	          `adminID` int(11) NOT NULL,
	          `cupID` int(11) NOT NULL,
                  `ladID` int(11) NOT NULL,
                  `matchID` int(11) NOT NULL,
                  `subject` varchar(255) NOT NULL,
                  `desc` longtext NOT NULL,
                  `time` int(15) NOT NULL, 
                  `updated` int(15) NOT NULL,
                  `comment` int(1) NOT NULL,
                  `status` int(11) NOT NULL default '1',
		  PRIMARY KEY (`ticketID`)
		) AUTO_INCREMENT=1;") OR die(PREFIX.'cup_tickets table already exists? perhaps already installed?');

		safe_query("CREATE TABLE `".PREFIX."cup_warnings` (
		  `warnID` int(11) NOT NULL auto_increment,
		  `clanID` int(11) NOT NULL,
		  `adminID` int(11) NOT NULL,
		  `points` int(11) NOT NULL,
		  `title` varchar(255) NOT NULL,
		  `desc` varchar(255) NOT NULL,
		  `matchlink` varchar(255) NOT NULL,
		  `time` int(15) NOT NULL,
		  `deltime` int(15) NOT NULL,
		  `1on1` int(11) NOT NULL,
		  `expired` int(11) NOT NULL,
		  PRIMARY KEY  (`warnID`)
		) AUTO_INCREMENT=1;") OR die(PREFIX.'cup_warnings table already exists? perhaps already installed?');
		
		safe_query("CREATE TABLE IF NOT EXISTS `".PREFIX."tchat` (
		  `ID` int(11) NOT NULL AUTO_INCREMENT,
		  `channelID` int(11) NOT NULL,
		  `pseudo` varchar(255) NOT NULL,
		  `message` varchar(255) NOT NULL,
		  `heure` varchar(255) NOT NULL,
		  `type` varchar(255) NOT NULL,
		  PRIMARY KEY (`ID`)
		) AUTO_INCREMENT=1;") OR die(PREFIX.'tchat table already exists? perhaps already installed?');

		safe_query("CREATE TABLE IF NOT EXISTS `".PREFIX."tchat_private` (
		  `ID` int(11) NOT NULL AUTO_INCREMENT,
		  `userID` varchar(255) NOT NULL,
		  `friend` varchar(255) NOT NULL,
		  `message` varchar(255) NOT NULL,
		  `heure` varchar(255) NOT NULL,
		  PRIMARY KEY (`ID`)
		) AUTO_INCREMENT=1;") OR die(PREFIX.'tchat_private table already exists? perhaps already installed?');
		
		safe_query("ALTER TABLE `".PREFIX."whoisonline` 
                  ADD `channelID` int(11) NOT NULL AFTER `time`,
                  ADD `url` varchar(255) NOT NULL, 
                  ADD `type` varchar(255) NOT NULL, 		  
		  ADD `afk` int(1) NOT NULL,	  
                  ADD `call` int(1) NOT NULL,
                  ADD `calltimer` int(15) NOT NULL;")			  
		OR die(PREFIX.'failed to add columns, perhaps v5.2 is already installed?');
		
		safe_query("ALTER TABLE `".PREFIX."whowasonline` 	  	  
		  ADD `url` varchar(255) NOT NULL;")	           
		OR die(PREFIX.'failed to add column, perhaps v5.2 is already installed?');
		
	safe_query("ALTER TABLE ".PREFIX."user 
	    ADD `msn` varchar(255) NOT NULL default 'n/a',
	    ADD `skype` varchar(255) NOT NULL default 'na',
	    ADD `yahoo` varchar(255) NOT NULL default 'n/a',
	    ADD `aim` varchar(255) NOT NULL default 'n/a',
	    ADD `xfirec` varchar(255) NOT NULL default 'n/a',
	    ADD `steam` varchar(255) NOT NULL default 'n/a',
	    ADD `sc_id` varchar(255) NOT NULL,
	    ADD `xfire` varchar(60) NOT NULL,
	    ADD `xfirestyle` varchar(255) NOT NULL,
	    ADD `xfiregroesse` int(10) NOT NULL,
	    ADD `clanesl` varchar(50) NOT NULL default 'n/a',
	    ADD `storage` varchar(50) NOT NULL default 'n/a',
	    ADD `headset` varchar(50) NOT NULL default 'n/a',
	    ADD `fgame` varchar(50) NOT NULL default 'n/a',
	    ADD `fclan` varchar(50) NOT NULL default 'n/a',
	    ADD `fmap` varchar(50) NOT NULL default 'n/a',
	    ADD `fweapon` varchar(50) NOT NULL default 'n/a',
	    ADD `ffood` varchar(50) NOT NULL default 'n/a',
	    ADD `fdrink` varchar(50) NOT NULL default 'n/a',
	    ADD `fmovie` varchar(50) NOT NULL default 'n/a',
	    ADD `fmusic` varchar(50) NOT NULL default 'n/a',
	    ADD `fsong` varchar(50) NOT NULL default 'n/a',
	    ADD `fbook` varchar(50) NOT NULL default 'n/a',
	    ADD `factor` varchar(50) NOT NULL default 'n/a',
	    ADD `fcar` varchar(50) NOT NULL default 'n/a',
	    ADD `fsport` varchar(50) NOT NULL default 'n/a';") 
OR die(PREFIX.'user table failed to alter, profile mod or similar addon is installed. try re-installing.');	

		safe_query("CREATE TABLE `".PREFIX."gameacc` (
		  `gameaccID` int(11) NOT NULL auto_increment,
		  `type` varchar(255) NOT NULL,
		  PRIMARY KEY  (`gameaccID`)
		) AUTO_INCREMENT=1;") OR die(PREFIX.'gameacc table already exists? gameaccount addon or smilar is installed. try re-installing.');

		safe_query("ALTER TABLE `".PREFIX."user_groups` ADD `cup` int(1) NOT NULL default '0'") OR die(PREFIX.'user_groups table - column cup already exists? perhaps already installed?');
		
		safe_query("INSERT INTO `".PREFIX."gameacc` VALUES (10, 'BF1942 CD-Hashkey');");
		safe_query("INSERT INTO `".PREFIX."gameacc` VALUES (9, 'AoE3 ESO Nick');");
		safe_query("INSERT INTO `".PREFIX."gameacc` VALUES (8, 'AAO PB GUID');");
		safe_query("INSERT INTO `".PREFIX."gameacc` VALUES (11, 'BF2 PB_GUID');");
		safe_query("INSERT INTO `".PREFIX."gameacc` VALUES (12, 'BF:V PB_GUID');");
		safe_query("INSERT INTO `".PREFIX."gameacc` VALUES (13, 'BFME EA Login');");
		safe_query("INSERT INTO `".PREFIX."gameacc` VALUES (14, 'BFME2 EA Login');");
		safe_query("INSERT INTO `".PREFIX."gameacc` VALUES (15, 'Blitzkrieg 2');");
		safe_query("INSERT INTO `".PREFIX."gameacc` VALUES (16, 'COD PB GUID');");
		safe_query("INSERT INTO `".PREFIX."gameacc` VALUES (17, 'COD UO PB GUID');");
		safe_query("INSERT INTO `".PREFIX."gameacc` VALUES (18, 'COD2 PB GUID');");
		safe_query("INSERT INTO `".PREFIX."gameacc` VALUES (19, 'Carom3d Account Number');");
		safe_query("INSERT INTO `".PREFIX."gameacc` VALUES (20, 'CoD GUID');");
		safe_query("INSERT INTO `".PREFIX."gameacc` VALUES (21, 'CoD 2 GUID');");
		safe_query("INSERT INTO `".PREFIX."gameacc` VALUES (22, 'Code Alienware');");
		safe_query("INSERT INTO `".PREFIX."gameacc` VALUES (23, 'Counter-Strike Manager');");
		safe_query("INSERT INTO `".PREFIX."gameacc` VALUES (24, 'Cyanide Chaosleague');");
		safe_query("INSERT INTO `".PREFIX."gameacc` VALUES (25, 'Diablo 2 Battlenet(EU)');");
		safe_query("INSERT INTO `".PREFIX."gameacc` VALUES (26, 'EA-Login');");
		safe_query("INSERT INTO `".PREFIX."gameacc` VALUES (27, 'ET PB GUID');");
		safe_query("INSERT INTO `".PREFIX."gameacc` VALUES (28, 'ETPro GUID');");
		safe_query("INSERT INTO `".PREFIX."gameacc` VALUES (29, 'Fear PB GUID');");
		safe_query("INSERT INTO `".PREFIX."gameacc` VALUES (30, 'FarCry PB GUID');");
		safe_query("INSERT INTO `".PREFIX."gameacc` VALUES (31, 'FIFA 04 Matchmaker');");
		safe_query("INSERT INTO `".PREFIX."gameacc` VALUES (32, 'FIFA 05 Matchmaker');");
		safe_query("INSERT INTO `".PREFIX."gameacc` VALUES (33, 'FIFA 06 Matchmaker');");
		safe_query("INSERT INTO `".PREFIX."gameacc` VALUES (34, 'FIFA WM 06 Matchmaker');");
		safe_query("INSERT INTO `".PREFIX."gameacc` VALUES (35, 'GameSpy ID');");
		safe_query("INSERT INTO `".PREFIX."gameacc` VALUES (36, 'Gunbound_ID');");
		safe_query("INSERT INTO `".PREFIX."gameacc` VALUES (37, 'Gunz Nick');");
		safe_query("INSERT INTO `".PREFIX."gameacc` VALUES (38, 'ICQ');");
		safe_query("INSERT INTO `".PREFIX."gameacc` VALUES (39, 'Joint Operations GUID');");
		safe_query("INSERT INTO `".PREFIX."gameacc` VALUES (40, 'LFS Ingame Nick');");
		safe_query("INSERT INTO `".PREFIX."gameacc` VALUES (41, 'LFS-World Nick');");
		safe_query("INSERT INTO `".PREFIX."gameacc` VALUES (42, 'Lanfield ID');");
		safe_query("INSERT INTO `".PREFIX."gameacc` VALUES (43, 'MOH:AA DMW ID');");
		safe_query("INSERT INTO `".PREFIX."gameacc` VALUES (44, 'MOH:PA PG GUID');");
		safe_query("INSERT INTO `".PREFIX."gameacc` VALUES (45, 'Matchmaker FIFA UEFA');");
		safe_query("INSERT INTO `".PREFIX."gameacc` VALUES (46, 'NBA 2005 Matchmaker');");
		safe_query("INSERT INTO `".PREFIX."gameacc` VALUES (47, 'NBA 2006 Matchmaker');");
		safe_query("INSERT INTO `".PREFIX."gameacc` VALUES (48, 'NFS Online User Name');");
		safe_query("INSERT INTO `".PREFIX."gameacc` VALUES (49, 'NHL 2005 Matchmaker');");
		safe_query("INSERT INTO `".PREFIX."gameacc` VALUES (50, 'NHL 2006 Matchmaker');");
		safe_query("INSERT INTO `".PREFIX."gameacc` VALUES (51, 'OFP PlayerID');");
		safe_query("INSERT INTO `".PREFIX."gameacc` VALUES (52, 'PES5 Lobbynick');");
		safe_query("INSERT INTO `".PREFIX."gameacc` VALUES (53, 'Q3 PB GUID');");
		safe_query("INSERT INTO `".PREFIX."gameacc` VALUES (54, 'Quake4 PB GUID');");
		safe_query("INSERT INTO `".PREFIX."gameacc` VALUES (55, 'R6: Raven Shield GUID');");
		safe_query("INSERT INTO `".PREFIX."gameacc` VALUES (56, 'RS: Lockdown PB GUID');");
		safe_query("INSERT INTO `".PREFIX."gameacc` VALUES (57, 'SCBW Battlenet(EU)');");
		safe_query("INSERT INTO `".PREFIX."gameacc` VALUES (58, 'SCBW Battlenet(ntc)');");
		safe_query("INSERT INTO `".PREFIX."gameacc` VALUES (59, 'Schach.de Login');");
		safe_query("INSERT INTO `".PREFIX."gameacc` VALUES (60, 'SoF2 PB GUID');");
		safe_query("INSERT INTO `".PREFIX."gameacc` VALUES (61, 'Spellforce 2 Login');");
		safe_query("INSERT INTO `".PREFIX."gameacc` VALUES (62, 'SteamID');");
		safe_query("INSERT INTO `".PREFIX."gameacc` VALUES (63, 'SteamID CS:CZ');");
		safe_query("INSERT INTO `".PREFIX."gameacc` VALUES (64, 'SteamID DoD:S');");
		safe_query("INSERT INTO `".PREFIX."gameacc` VALUES (65, 'SteamID HL2/CSS');");
		safe_query("INSERT INTO `".PREFIX."gameacc` VALUES (66, 'carom3d.com Login Name');");
		safe_query("INSERT INTO `".PREFIX."gameacc` VALUES (67, 'SteamID RO');");
		safe_query("INSERT INTO `".PREFIX."gameacc` VALUES (68, 'Trackmania Nations');");
		safe_query("INSERT INTO `".PREFIX."gameacc` VALUES (69, 'UT2003 GUID');");
		safe_query("INSERT INTO `".PREFIX."gameacc` VALUES (70, 'UT2004 GUID');");
		safe_query("INSERT INTO `".PREFIX."gameacc` VALUES (71, 'Ubi Login');");
		safe_query("INSERT INTO `".PREFIX."gameacc` VALUES (72, 'Vietcong ID');");
		safe_query("INSERT INTO `".PREFIX."gameacc` VALUES (73, 'WC3 Battlenet(EU)');");
		safe_query("INSERT INTO `".PREFIX."gameacc` VALUES (74, 'WC3 Battlenet for ENC');");
		safe_query("INSERT INTO `".PREFIX."gameacc` VALUES (75, 'WonID (HL)');");
		safe_query("INSERT INTO `".PREFIX."gameacc` VALUES (76, 'carom3d.com Login Name');");
		safe_query("INSERT INTO `".PREFIX."cup_settings` VALUES (1, '0', '10', '15', '1', '4', '3', '1', '1', '0', '0', '1', 'irc.evolu.net', '1', '0', 'Europe/London');"); 

		safe_query("CREATE TABLE `".PREFIX."user_gameacc` (
		  `gameaccID` int(11) NOT NULL auto_increment,
		  `userID` int(11) NOT NULL default '0',
		  `type` int(11) NOT NULL default '0',
		  `value` varchar(255) NOT NULL,
		  `log` int(11) NOT NULL default '0',
		  PRIMARY KEY  (`gameaccID`)
		) AUTO_INCREMENT=1 ;") OR die(PREFIX.'user_gameacc table already exists? if you already have the gameaccount addon installed try re-installing.');
		
	  $db_status = '<font color="red"><strong>The cup has been successfully installed! Proceed to uploading part 2.</strong></font>';
   
   }elseif(isset($_GET['action']) && $_GET['action']=="uninstall") {
   
    safe_query("ALTER TABLE `".PREFIX."user` 
    DROP `msn`,
    DROP `skype`,
    DROP `yahoo`,
    DROP `aim`,
    DROP `xfirec`,
    DROP `steam`,
    DROP `sc_id`,
    DROP `xfire`,
    DROP `xfirestyle`,
    DROP `xfiregroesse`,
    DROP `clanesl`,
    DROP `storage`,
    DROP `headset`,
    DROP `fgame`,
    DROP `fclan`,
    DROP `fmap`,
    DROP `fweapon`,
    DROP `ffood`,
    DROP `fdrink`,
    DROP `fmovie`,
    DROP `fmusic`,
    DROP `fsong`,
    DROP `fbook`,
    DROP `factor`,
    DROP `fcar`,
    DROP `fsport`");
    
    safe_query("ALTER TABLE `".PREFIX."whoisonline` 
    DROP `channelID`,
    DROP `url`,
    DROP `type`,
    DROP `afk`,
    DROP `call`,
    DROP `calltimer`");
    
    safe_query("ALTER TABLE `".PREFIX."whowasonline` 
    DROP `url`");
    
    safe_query("DROP TABLE IF EXISTS `".PREFIX."cups`");
    safe_query("DROP TABLE IF EXISTS `".PREFIX."cup_clan_lineup`");
    safe_query("DROP TABLE IF EXISTS `".PREFIX."cup_settings`");
    safe_query("DROP TABLE IF EXISTS `".PREFIX."cup_tickets`");
    safe_query("DROP TABLE IF EXISTS `".PREFIX."cup_admins`");
    safe_query("DROP TABLE IF EXISTS `".PREFIX."cup_agents`");
    safe_query("DROP TABLE IF EXISTS `".PREFIX."cup_requests`");
    safe_query("DROP TABLE IF EXISTS `".PREFIX."cup_baum`");
    safe_query("DROP TABLE IF EXISTS `".PREFIX."cup_challenges`");
    safe_query("DROP TABLE IF EXISTS `".PREFIX."cup_clan_members`");
    safe_query("DROP TABLE IF EXISTS `".PREFIX."cup_deduction`");
    safe_query("DROP TABLE IF EXISTS `".PREFIX."cup_departments`");
    safe_query("DROP TABLE IF EXISTS `".PREFIX."cup_ladders`");
    safe_query("DROP TABLE IF EXISTS `".PREFIX."cup_maps`");
    safe_query("DROP TABLE IF EXISTS `".PREFIX."cup_clans`");
    safe_query("DROP TABLE IF EXISTS `".PREFIX."cup_all_clans`");
    safe_query("DROP TABLE IF EXISTS `".PREFIX."cup_matches`");
    safe_query("DROP TABLE IF EXISTS `".PREFIX."cup_platforms`");
    safe_query("DROP TABLE IF EXISTS `".PREFIX."cup_rules`");
    safe_query("DROP TABLE IF EXISTS `".PREFIX."cup_warnings`");
    safe_query("DROP TABLE IF EXISTS `".PREFIX."gameacc`");
    safe_query("DROP TABLE IF EXISTS `".PREFIX."user_gameacc`");
    safe_query("DROP TABLE IF EXISTS `".PREFIX."tchat`");
    safe_query("DROP TABLE IF EXISTS `".PREFIX."tchat_private`");
    safe_query("ALTER TABLE `".PREFIX."user_groups` DROP `cup`");
    
  if(isset($_GET['reinstall'])=="true") {
  
    $db_status =  '<font color="red"><strong>Successfully uninstalled, now reinstalling...</strong></font>';  
    redirect('?site=db_v5_2&action=install', '', 2);   	   
  
  }else{
    
    $db_status =  '<font color="red"><strong>The cup has been successfully uninstalled!</strong></font>';  
    
  }
	
 }elseif(isset($_GET['action']) && $_GET['action']=="update") {
 

/* THE UPDATE */

		safe_query("CREATE TABLE IF NOT EXISTS `".PREFIX."cup_agents` (
		  `ID` int(11) NOT NULL auto_increment,
		  `userID` int(11) NOT NULL,
		  `cupID` int(11) NOT NULL,
		  `ladID` int(11) NOT NULL,
		  `avail` int(1) NOT NULL,
		  `name` varchar(255) NOT NULL,
		  `play` varchar(255) NOT NULL,
		  `info` longtext NOT NULL,
                  `time` int(15) NOT NULL,          
		  PRIMARY KEY  (`ID`)
		) AUTO_INCREMENT=1;") OR die(PREFIX.'cup_agents table already exists, perhaps you are already updated?');
		
		safe_query("ALTER TABLE `".PREFIX."cup_baum` 	  	  
		  ADD `second_winner` int(11) NOT NULL AFTER `lb_winner`;")	           
		OR die(PREFIX.'failed to add column, perhaps you are already updated?');
		
		safe_query("ALTER TABLE `".PREFIX."cups` 	  	  
		  ADD `timezone` varchar(255) NOT NULL;")	           
		OR die(PREFIX.'failed to add column, perhaps you are already updated?');
		
		safe_query("ALTER TABLE `".PREFIX."cup_ladders` 
                  ADD `1st` int(11) NOT NULL,
                  ADD `2nd` int(11) NOT NULL,	
                  ADD `3rd` int(11) NOT NULL,		  
		  ADD `timezone` varchar(255) NOT NULL;")	           
		OR die(PREFIX.'failed to add column, perhaps you are already updated?');	
		
		safe_query("ALTER TABLE `".PREFIX."cup_clans` 
                  ADD `rt` int(11) NOT NULL,
		  ADD `type` varchar(255) NOT NULL;")	           
		OR die(PREFIX.'failed to add columns, perhaps you are already updated?');
		
		safe_query("ALTER TABLE `".PREFIX."cup_clan_members` 
                  ADD `agent` int(1) NOT NULL, 		
		  ADD `reg` int(15) NOT NULL;")	           
		OR die(PREFIX.'failed to add column, perhaps you are already updated?');
		
		safe_query("ALTER TABLE `".PREFIX."cup_matches` 	  	  
		  ADD `date2` varchar(255) NOT NULL AFTER `date`;")	           
		OR die(PREFIX.'failed to add column, perhaps you are already updated?');
		
		safe_query("CREATE TABLE IF NOT EXISTS `".PREFIX."tchat` (
		  `ID` int(11) NOT NULL AUTO_INCREMENT,
		  `channelID` int(11) NOT NULL,
		  `pseudo` varchar(255) NOT NULL,
		  `message` varchar(255) NOT NULL,
		  `heure` varchar(255) NOT NULL,
		  `type` varchar(255) NOT NULL,
		  PRIMARY KEY (`ID`)
		) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;");

		safe_query("CREATE TABLE IF NOT EXISTS `".PREFIX."tchat_private` (
		  `ID` int(11) NOT NULL AUTO_INCREMENT,
		  `userID` varchar(255) NOT NULL,
		  `friend` varchar(255) NOT NULL,
		  `message` varchar(255) NOT NULL,
		  `heure` varchar(255) NOT NULL,
		  PRIMARY KEY (`ID`)
		) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;");
		
		safe_query("ALTER TABLE `".PREFIX."whoisonline` 
                  ADD `channelID` int(11) NOT NULL AFTER `time`,
                  ADD `url` varchar(255) NOT NULL, 
                  ADD `type` varchar(255) NOT NULL, 		  
		  ADD `afk` int(1) NOT NULL,
                  ADD `call` int(1) NOT NULL,
                  ADD `calltimer` int(15) NOT NULL;")	           
		OR die(PREFIX.'failed to add columns, perhaps you are already updated?');
		
		safe_query("ALTER TABLE `".PREFIX."whowasonline` 	  	  
		  ADD `url` varchar(255) NOT NULL;")	           
		OR die(PREFIX.'failed to add column, perhaps you are already updated?');
		
	  $db_status = '<font color="red"><strong>The cup has been successfully updated! Proceed to overwriting part 2.</strong></font>';
	  
/* END UPDATE */         
 
 
 }
 elseif(isset($_GET['action']) && $_GET['action']=="5204update") {
 
		safe_query("ALTER TABLE `".PREFIX."cup_ladders` 
                  ADD `1st` int(11) NOT NULL,
                  ADD `2nd` int(11) NOT NULL,	
                  ADD `3rd` int(11) NOT NULL;")		  	           
		OR die(PREFIX.'failed to add column, perhaps you are already updated to built 5204?');
		
		safe_query("ALTER TABLE `".PREFIX."whoisonline` 
                  ADD `call` int(1) NOT NULL,
                  ADD `calltimer` int(15) NOT NULL;")		  	           
		OR die(PREFIX.'failed to add column, perhaps you are already updated to built 5204?');
		
		$db_status = '<font color="red"><strong>The cup has been successfully updated to 5204!</strong></font>';
 
 }
 
   if($db_status) 
   {
      $db_show = $db_status; 
   }
   else                
   {
      $db_show = '<img src="images/cup/new_message.gif"> <a href="?site=db_v5_2&action=install" onclick="return confirm(\'To prevent installation problems you are strongly advised to read the readme file. You cannot have the following addons installed (profile, gameaccount, tchat and whoisonline monitor). After success do not forget to upload part 2!\');">New Install (Built 5204)</a><br>
   			   <img src="images/cup/new_message.gif"> <a href="?site=db_v5_2&action=uninstall" onclick="return confirm(\'Important: This will remove all associated cup tables including removal of the following addons (profile, gameaccount, tchat and whoisonline monitor) \');">Uninstall</a><br>
			   <img src="images/cup/new_message.gif"> <a href="?site=db_v5_2&action=uninstall&reinstall=true" onclick="return confirm(\'Important: This will remove all associated cup tables including removal of the following addons (profile, gameaccount, tchat and whoisonline monitor) then reinstate them as empty.\');">Reinstall</a><br>
			   <img src="images/cup/new_message.gif"> <a href="?site=db_v5_2&action=update" onclick="return confirm(\'Please backup your database and files to avoid any data loss. You are advised also to read the readme file.\');">Update from V5.1 to V5.2 (Built 5204)</a><br>
			   <img src="images/cup/new_message.gif"> <a href="?site=db_v5_2&action=5204update">Update from V5.2.x to Built 5204</a>';
   }
   

		echo '<br />
			<fieldset style="border: 1px solid '.$border.'"><legend style="font-size:13px;"><b>Cup Addon V5.2 BETA - Installation & Updater</b></legend>
				<br><center><img src="http://team-x1.co.uk/images/wave.gif"><br /> To help improve the addon you can download free updates and report bugs at <a href="http://teamx1.com">teamx1.com</a>. 
				<br /> If you wish to update from previous versions you should update to V5.1 and vice-versa. You should make a full backup before clicking anything below!                               				
				<br /><br />'.$db_show.'<br /><br />
				[ Developed by <a href="http://teamx1.com/" target="_blank">Team -X1-</a> & Creak | Release: '.$release_date.' | Website: <a href="http://teamx1.com/" target="_blank">Cupaddon.com</a> ] </center>
			</fieldset>';
?>