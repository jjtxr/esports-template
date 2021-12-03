<?php

/* CONFIGURATION - LADDER SCRIPT */

//sc_'s for sideblock at your index.php (other settings at admin - cupsettings)

$sc_ladders = 3; //how many ladders to display at sc_ladders.php ?

//styling

$cellspacing = 2; 
$cellpadding = 2;
$error_box = 'style="margin: 5px; padding: 6px; border: 4px solid #D6B4B4; text-align: center;"';
$selected_cup_quicknavi = "#e4f3ff"; // when cup is selected at quicknavi, make bgcolor ?

//instant (V5.1)

$mi_name = "MatchII"; //name MatchII to? 
$mi_name_long = "Match Instant-Initiation"; //MatchII long name?
$mi_enable = 1; // 1 = enable //0 = disable

//platform page

$show_inactive_platforms = 0; // 0 = show all platforms 1 = hide closed platforms
$logo_width = '100'; // width of platform logo at platform page?
$logo_height = '100'; // height of platform logo at platform page?
$logo_width2 = '20'; // width of platform logo at matches page?
$logo_height2 = '20'; // height of platform logo at matches page?
$map_width = '100'; // width of map pic when submitting a result
$map_height = '100'; // height of map pic when submitting a result

/* GROUP-STAGES */

$per_group_8 = 8;   // max = 8  then 8 is needed in group A and B (4 teams in each group go through)
$per_group_16 = 8; // max = 16 then 8 in groups A, B, C, D (4 teams in each group go through)
$per_group_32 = 8;  // max = 32 then 8 is needed in groups A, B, C, D, E, F, G and H (4 teams in each group go through)
$per_group_64 = 16;  // max = 64 then 16 is needed in groups A, B, C, D, E, F, G and H (8 teams in each group go through)
$pass = 0; // 1 = teams go through if they have most wins 0 = teams go through if they have most XP
$staging = 1; // 0 = teams go through if they won their first match // 1 = teams go through by matching all other teams
$selected_match = '#000000'; // clicking on match report at match details will show you background color of selected match.
$show_matchstats = 1; // 1 = only show matchstats after all matches finish // 0 = show anytime
$league_begin = 1; // If "Open cup registration after group stages finish" Then set end time for group stages after last match and start time to plus "1" day. (prior to 1 day is registration)
$league_end = 3; // If "Open cup registration after group stages finish" Then set end time for league "3" days ahead of league start time.
$gs_start = 1; // 1 = set status to "Start" as soon as last participant registers to group stages //0 = leave times pre-set.
$sm_qualified  = "Congratulations! You are a qualified participant and your registration is reserved for the league.";
$sm_unqualified = "Sorry! You did not have enough points to pass through to the qualifying league. We hope you better luck next time!";
$sm_fcfs = "You are a FCFS participant which means it will be first-come-first-serve after all qualifiers are registered.";
$sm_elig_subject = "League Eligibility Notification";
$auto_checkin = 1; // 1= auto-check qualifiers only //0 = only auto check at checkin phase.
$insert_qualifiers = 1; // 1 = qualifiers automatically registered to next league // 0 = qualifiers must register manually to next league

/* LADDER */

$legend = 1; //1 = show legend at ladder details //0 = hide legend at ladder details
$instant_play = 0; // 0 = can challenge/report even if fewer participants than max signups // 1 = must wait for all participants
$differential = 0; // 1 = score A minus score B to calculate XP // 0 = score A and score B to calculate XP

/* STANDINGS */

$startupcredit = 10; //if credit reaches 0, participant is unranked
$woncredit = 3; //plus creditibility
$lostcredit = 1; // minus creditibility 
$drawcredit  = 1; //plus creditibility
$forfeitloss = 2; //minus creditibility
$forfeitaward = 2; //plus creditibility
$show_challenges = "8"; //X = show same amount as participants // (number) - amount of challenges shown at standings
$show_matches = "8"; // (number) recent amount of matches shown at standings
$unranked_in = "3"; //X = show all the time // (number) - e.g. if 3 days show users to be unranked in 3 days or less
$warning_remove_in = "1"; //(number) = e.g. if user will be removed in 1 day, show warning
$warning_unranked_in = "1"; //(number) = e.g. if user will be unranked in 1 day, show warning
$recent_activity_no = "X"; // (number) = e.g. 5 will show 5 recent matches for participant in ladder. // X = all
$recent_challenges_no = "X"; // (number) = e.g. 5 will show 5 recent challenges for participant in ladder. // X = all
$recent_deductions_no = "X"; // (number) e.g. 5 will show 5 recent deductions for participant in ladder. // X = all
$unranking = 1; //1 = unranked participants with 0 credits //0 = do not unrank
$days_inactive = 3; // if (number) of days or greater of inactivity, show snooze image at standings
$get_image_size = 0; // 1 = checks avatar's size for existence but slows page // 0 = may not show all avatars but speeds page

 //under-works OR BUGGED

  $ratio_determination = 1; //1 = ratio according to matches //0 = ratio according to xp
  $removed_in = "X"; //X = show all the time // (number) - e.g. if 3 days show users to be removed in 3 days or less

/* TICKETING SYSTEM */

//status 1 = unreviewed
//status 2 = awaiting admin-reply
//status 3 = onhold
//status 4 = awaiting user-reply
//status 5 = closed
//status 6 = custom1
//status 7 = custom2

$status_resolved = '<font color="#DD0000"><b>Closed</b></font>';
$status_unreviewed = '<font color="#0066CC"><b>Not Reviewed</b></font>';
$status_pending = '<font color="#FF6600"><b>Awaiting Admin-Reply</b>';
$status_onhold = '<font color="#FF6600"><b>On-Hold</b>';
$status_waiting = '<font color="#FF6600"><b>Awaiting User-Reply</b>';
$status_custom1 = '<b>(custom-config.php)</b>';
$status_custom2 = '<b>(custom-config.php)</b>';

$status_resolved_nc = "<b>Closed</b>";
$status_unreviewed_nc = "<b>Not Reviewed</b>";
$status_pending_nc = "<b>Awaiting Admin-Reply</b>";
$status_onhold_nc = "<b>On-Hold</b>";
$status_waiting_nc = "<b>Awaiting User-Reply</b>";
$status_custom1_nc = "<b>(custom-config.php)</b>";
$status_custom2_nc = "<b>(custom-config.php)</b>";

$notification_status_admin = array(1,2); // instant notification for admin if status is? (separated by commas, no comma if 1 value)
$notification_status_user = array(1,4); // instant notification for user if status is? (separated by commas, no comma if 1 value)
$unresolved_ticket_u = "Unresolved Ticket"; // if instant notification for ticket for user, call header?
$unresolved_ticket_a = "Unresolved Ticket"; // if instant notification for ticket for admin, call header?
$unconfirmed_result = "Unconfirmed Result"; // if instant notification for match, call header?

$delete_confirmed_protests = 0; //0 = opened protests always remain as a ticket even if confirmed unless deleted //1 = confirmed protests automatically delete
$order_by = 1; //0 = order tickets by "added date" by user //1 = order tickets by "updated date" by user or admin
$user_reply_status = 2; //when user replies after admin, automatically set status to ? 
$admin_reply_status = 4; //when admin replies, automatically set status to ?? 
$match_confirmed_status = 5; //when admin confirms a match protest, set status to ?? 
$match_protest_status = 3; // when admin sets the match status to protest, set status to ?? 
$ticket_autoclose_time = 86400; //if no reply, autoclose ticket by how many seconds? // 86400 = 1 day
$ticket_autoclose_status = 5; //set auto-closed tickets to status ? (see above)
$only_autoclose_ticket = array(4); //what status(es) can the autoclose ticket apply to? seperated by commas or no comma if 1 value
$hide_closed_tickets = 1; //1 = hide closed tickets at admin //0 = show closed

//v5.1
$user_close_ticket = 1; //1 = users can close tickets //0 = users can't close tickets.
$user_delete_ticket = 1; //1 = users can delete tickets //0 = users can't delete tickets.

$period_dot = ""; // 1 = white animated dots // (BLANK) = black animated dots
$period_dot_tp = ""; // 1 = white animated dots for tooltip // (BLANK) = black animated dots for tooltip

/* CONFIGURATION - V4.1.6* - SCORE RATIO */

$ratio = '1'; // 1 = show skill level 0 = show ratio range
$none = 'n/a'; // specifiy text that has no ratio

//low skill level:

$low = 'Low'; //name it?
$h1 = '0'; // equal to or greater than
$l1 = '33'; // equal to or lesser than

//med skill level:

$med = 'Med'; //name it?
$h2 = '34'; // equal to or greater than
$l2 = '66'; // equal to or lesser than

//low-med skill level:

$lowmed = 'Low-Med'; //name it?
$h3 = '0'; // equal to or greater than
$l3 = '66'; // equal to or lesser than

//high skill level:

$high = 'High'; // name it?
$h4 = '67'; // equal to or greater than
$l4 = '89'; // equal to or lesser than

//med-high skill level:

$medhigh = 'Med-High'; // name it?
$h5 = '33'; // equal to or greater than
$l5 = '89'; // equal to or lesser than

//high + skill level:

$high1 = 'High +'; // name it?
$h6 = '90'; // equal to or greater than
$l6 = '97'; // equal to or lesser than

//high ++ skill level:

$high2 = 'High ++'; // name it?
$h7 = '98'; // equal to or greater than
$l7 = '100'; // equal to or lesser than

/* CUP CONFIGURATION */

$limit_upcoming_matches = 4; //how many upcoming matches to display at sc_ ?
$limit_recent_matches = 4; //how many recent matches to display at sc_ ?
$limit_team_matches = 5; //how many recent matches to be displayed at teams page?
$borderbg1='#e4f3ff'; // brackets border color
$background1='#e4f3ff'; // brackets background 1 color
$background2='#D9D9D9'; // brackets background 2 color
$sc_cupmatches_order = 1; //1 = order by confirmed date, (only matches from V5 takes this affect) // 0 = order by match created date
$shrink_tree = 0; // 1 = automatically reduce the tree size if max participants does not register when cup starts // 0 = do not shrink
$team_list = 0; // 0 = short team listing // 1 = detail team listing

$auto_close_cup = 1; //if there is a 1st winner, close the cup //0 = leave times the same.

$winner = "Winner";
$third_winner = "3rd-Place";
$lower_winner = "LB Winner";

$round1 = "Round 1"; //for upper-bracket
$round2 = "Round 2"; //for upper-bracket
$round3 = "Round 3"; //for upper-bracket
$round4 = "Round 4"; //for upper-bracket

$round1_lb = "LB Round 1"; //for lower-bracket
$round2_lb = "LB Round 2"; //for lower-bracket
$round3_lb = "LB Round 3"; //for lower-bracket
$round4_lb = "LB Round 4"; //for lower-bracket
$round5_lb = "LB Round 5"; //for lower-bracket
$round6_lb = "LB Round 6"; //for lower-bracket
$round7_lb = "LB Round 7"; //for lower-bracket

$round_qf_ub = "Quarterfinal";
$round_qf_lb = "LB Quarterfinal";
$round_sf_ub = "Semi-Final";
$round_sf_lb = "LB Semi Final";
$round_gf_ub = "Grand Final";
$round_gf_lb = "LB Final";

$auto_randomize_brackets = 2; //1 = cup starttime and is full, auto randomize //0 = randomize yourself from admin //2 = when cup is full, auto set start time to now and randomize

/* SYSTEM CONFIGURATION */

$debugging = 1;

/* DO NOT EDIT - FOR DEVELOPERS ONLY - FUTURE UPDATES - CHANGES WILL RESULT IN INACCURACY AND ERRORS */

$maxclan_array = array(8,16,32,64);
$groups_array = array('a','b','c','d','e','f','g','h');

if(mb_substr(basename($_SERVER['REQUEST_URI']),0,15)!="admincenter.php") { ?>

<!-- CUP STYLING - MODIFY/REMOVE BELOW IF NEEDED -->

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

<!-- CUP STYLING - MODIFY/REMOVE ABOVE IF NEEDED -->

<?php } ?>