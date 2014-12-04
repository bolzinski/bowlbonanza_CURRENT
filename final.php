<?php
	require_once("helper_error.php");
	require_once("lib/participant_class.php");
	require_once("lib/validate_class.php");
	require_once("lib/validator_helper_class.php");

	define('MAX_DISPLAY_LENGTH_BOWL_NAME', 25);
	define('MAX_DISPLAY_LENGTH_TEAM_NAME', 2);
	define('MAX_DISPLAY_LENGTH_NAME', 40);
	define('NUMBER_OF_BOWL_GAMES', 35);
	
/* NOTES:  For some unknown reason there have been issues with processing DB results that have not been encountered.
 1.  Instantiating a NEW class, for example "entry" in a page where participant was already instantiated caused an error.  As a work-around I decided to use
 ONE class to hold all DB methods/calls to SP.  THis is not great but time constraints forced the work-around scenario
 
 2. Found that after processing ONE query the "sizeof" command would return unexpected results OR results from a previous query even though the CALL was being made
 on  a NEW result set.
 
 3.  Found that we could not have concurrent result sets open, since opening the second one overwrote the first one, for example, processing ALL participants, then making a 2nd call to process ALL entries for a participant.  As a work-around a 2nd "result_list" class variable was created to handle just participant results.
 
NONE of these issues came up in past usage of same DB access model and Class usage, need to investigate how to fix these issues 

*/

?>
<!doctype html>
<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!-- Consider adding an manifest.appcache: h5bp.com/d/Offline -->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
  <meta charset="utf-8">

  <!-- Use the .htaccess and remove these lines to avoid edge case issues.
       More info: h5bp.com/b/378 -->
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title>Bowl Bonanza Participants</title>

  <!-- Place favicon.ico and apple-touch-icon.png in the root directory: mathiasbynens.be/notes/touch-icons -->
  <!-- CSS: implied media=all -->
  <link href='http://fonts.googleapis.com/css?family=Balthazar' rel='stylesheet' type='text/css'>
  <!-- CSS concatenated and minified via ant build script-->
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <!-- end CSS-->
</head>

<body>
 <!-- Prompt IE 6 users to install Chrome Frame. Remove this if you support IE 6.
       chromium.org/developers/how-tos/chrome-frame-getting-started -->
  <!--[if lt IE 7]><p class=chromeframe>Your browser is <em>ancient!</em> <a href="http://browsehappy.com/">Upgrade to a different browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to experience this site.</p><![endif]-->
<div id=container>
  <header>
  <div id=header>
  	<div class=banner_block_menu>
  		<h2 class="final align_text">Bowl Bonanza Final Standings!</h2>
    </div>
  </div> 
  </header>
  
  <div id="main">
  	<div class="clear clearfix"></div>
  	<div class="final_block">
<?php
	
	$p_participant = new participant();
	$p_participant->result_list = NULL;
	$p_participant->get_all_participants();

	if ($p_participant->sp_return_code == 0) {
		$participant_row_count = sizeof($p_participant->result_list_participant);
		//print "ROW COUNT: " . $participant_row_count . "<BR>";
		for ( $i=0; $i<$participant_row_count; $i++) {
		  $r_participant_id = $p_participant->result_list_participant[$i]['participant_id'];
		  $p_participant->update_participant_tie_breakers($r_participant_id);
		  switch ($p_participant->sp_return_code) {
			case 0:
			case 1:
				break ;
			case 106201:
			case 106202:
				write_to_error_log("final.php: error# 106202 - sp_update_participant_tie_breakers(" .  $r_participant_id . ")");
				print "final.php: error# 106202 - sp_update_participant_tie_breakers(" .  $r_participant_id . ")<br />";
				exit;
				break ;
			default:
				write_to_error_log("final.php: error# Unexpected Error: " . $p_participant->sp_return_code . " - sp_update_participant_tie_breakers(" .  $r_participant_id . ")");
				print "final.php: error# Unexpected Error: " . $p_participant->sp_return_code . " - sp_update_participant_tie_breakers(" .  $r_participant_id . ")<br />";
				exit;
				break;
		  }  //end switch		
		}	/* close for OUTER loop */
	} /* end if - query success */
	else {
?>
	<p class=message><? echo ($p_participant->msg) ?></p>
<?php
	} /* end else - query success */
?>

<?php
	
	//Get BCS Game info final standings
	$p_participant->result_list = NULL;
	$p_participant->get_bcs_champion();
	
	switch ($p_participant->sp_return_code) {
		case 0:
			break;
		case -99:
			write_to_error_log("final.php: error# -99 - get_bcs_champion() returned 0 rows!");
			print "final.php: error# -99 - get_bcs_champion() returned 0 rows!<br />";
			exit;
			break;
		default:
			write_to_error_log("final.php: error#" . $p_participant->sp_return_code . " - get_bcs_champion() failed!");
			print "final.php: error#" . $p_participant->sp_return_code . " - get_bcs_champion() failed! <br />";
			break;
	}  //end switch
	 
	$row_count = sizeof($p_participant->result_list);
	
	for ( $i=0; $i<$row_count; $i++) {  	
	  $r_team_01 = 	$p_participant->result_list[$i]['team_01'];
	  $r_team_01_score = $p_participant->result_list[$i]['team_01_score'];
	  $r_team_02 = 	$p_participant->result_list[$i]['team_02'];
	  $r_team_02_score = $p_participant->result_list[$i]['team_02_score'];
	  $r_bowl_winner =$p_participant->result_list[$i]['bowl_winner'];
	  $r_bowl_final_spread =$p_participant->result_list[$i]['final_score_spread'];
	} //END FOR
	
	if ($r_team_01 == $r_bowl_winner) {
		$team_winner = $r_team_01;
		$team_winner_score = $r_team_01_score;
		$team_loser = $r_team_02;
		$team_loser_score = $r_team_02_score;
	}
	else {
		$team_winner = $r_team_02;
		$team_winner_score = $r_team_02_score;
		$team_loser = $r_team_01;
		$team_loser_score = $r_team_01_score;
	}

?>

	<h4 class="final wins">BCS Championship Game Final Score: <? echo ($team_winner . ": " . $team_winner_score . "  " . $team_loser . ": " . $team_loser_score); ?> </h4>
    <div class="clear clearfix"></div>
    <ul class="row_header">
      <li class="col_header stat_column">Rank</li>
      <li class="col_header name_column">Name</li>
      <li class="col_header stat_column">Wins</li>
      <li class="col_header wins_column">BCS Winner</li>
      <li class="col_header stat_column margin_left_bump">Spread</li>
      <li class="col_header stat_column">Spread</li>
      <li class="col_header stat_column">Difference</li>
  	</ul>
	<div class="clear clearfix"></div>
    <ul class="row_header">
      <li class="col_header stat_column"></li>
      <li class="col_header name_column"></li>
      <li class="col_header stat_column"></li>
      <li class="col_header wins_column">Picked</li>
      <li class="col_header stat_column margin_left_bump">Actual</li>
      <li class="col_header stat_column">Picked</li>
      <li class="col_header stat_column"></li>
  	</ul>
	<div class="clear clearfix"></div>
    
<?PHP
	
	/* Print out final standings */
	
	$p_participant->result_list_participant = NULL;
	$p_participant->get_final_standings();

	if ($p_participant->sp_return_code == 0) {
		$participant_row_count = sizeof($p_participant->result_list_participant);
		//print "ROW COUNT: " . $participant_row_count . "<BR>";
		for ( $i=0; $i<$participant_row_count; $i++) {
		  //print "i: " . $i . "<BR>";
		  if (($i % 2) == 0)	{
?>
			  <ul class=row_even>
<?php
		  }
		  else {
?>
			  <ul class=row_odd>
<?php
		  }
		  //print "participant_counter: " . $participant_counter . "<br/>";
		  $r_participant_name = 	$p_participant->result_list_participant[($i)]['name'];
		  //print "participant_name: " . $p_participant->result_list_participant[$participant_counter]['name'] . "<br/>";
		  //get name
		  if (strlen($r_participant_name) > MAX_DISPLAY_LENGTH_NAME) {
			  $participant_name_display = substr($r_participant_name, 0, MAX_DISPLAY_LENGTH_NAME) . "...";
		  }
		  else {
			  $participant_name_display = $r_participant_name;
		  }
		  //get email
		  $r_participant_email = 	$p_participant->result_list_participant[$i]['email'];
		  if (strlen($r_participant_email) > MAX_DISPLAY_LENGTH_NAME) {
			  $participant_email_display = substr($r_participant_email, 0, MAX_DISPLAY_LENGTH_NAME) . "...";
		  }
		  else {
			  $participant_email_display = $r_participant_email;
		  }
		  //THIS WILL ALWAYS BE 0 UNTIL MANUALLY SET WHEN THE POOL IS OVER!
		  $r_bowl_bonanza_winner = 	$p_participant->result_list_participant[($i)]['bowl_bonanza_winner'];
		  //build link  
		  $query_data = array('fp_name'=>$r_participant_name,'fp_email'=>$r_participant_email);
		  $query_vars = http_build_query($query_data);
		  $entries_link = "/view_picks_process.php?$query_vars";
		  //get the number of wins for the entry
		  $r_participant_id = $p_participant->result_list_participant[$i]['participant_id'];
		  $r_bowls_correct = $p_participant->result_list_participant[$i]['number_correct'];
		  $participant_rank = $i + 1;
		  $r_selected_spread = $p_participant->result_list_participant[$i]['tie_breaker'];
		  $r_tie_breaker_01 = $p_participant->result_list_participant[$i]['tie_breaker_01'];
		  if ($r_tie_breaker_01) {
			  $r_tie_breaker_01_display=$team_winner;
			  $bcs_winner_style = "winner";
			  $r_selected_spread_display = " -" . str_pad((int) $r_selected_spread,2,"0",STR_PAD_LEFT);
			  //$r_selected_spread = " - " . $r_selected_spread;
		  }
		  else {
			  $r_tie_breaker_01_display=$team_loser;
			  $bcs_winner_style = "loser";
			  //$r_selected_spread = " + " . $r_selected_spread;
			  $r_selected_spread_display = " +" . str_pad((int) $r_selected_spread,2,"0",STR_PAD_LEFT);
		  }
		  $r_tie_breaker_02 = $p_participant->result_list_participant[$i]['tie_breaker_02'];
		  //$r_bowls_correct = 	$p_participant->result_list_participant[$i]['number_correct'];
  
		  //STYLE WINNER & LOSER
		  $winner_style = "";
		  $loser_style = "";
		  if ($r_bowl_bonanza_winner==1) {
			  //$participant_name_display = $participant_name_display . " * WINNER *";
			  $winner_style = "winner";
		  }
		  else {
			  if ($r_bowl_bonanza_winner== -1) {
				  //$participant_name_display = $participant_name_display . " * LAST PLACE *";
				  $winner_style = "loser";
			  }
		  }
?>
		<li class="final stat_column final_rank" ><? echo ($participant_rank); ?></li>
        <li class="final final_name name_column <? echo ($winner_style); ?>" title="<? echo ($r_participant_name); ?>"><a href="<? echo ($entries_link); ?>" class="name" title="<? echo ($entries_link); ?>"><? echo ($participant_name_display); ?></a></li>
        <li class="final stat_column final_wins" title="<? echo ($r_bowls_correct); ?>"><? echo (str_pad((int) $r_bowls_correct,2,"0",STR_PAD_LEFT)); ?></li>
        <li class="final wins_column final_winner <? echo ($bcs_winner_style); ?>" ><? echo ($r_tie_breaker_01_display); ?></li>
        <li class="final stat_column final_rank static"><? echo ($r_bowl_final_spread); ?></li>
        <li class="final stat_column final_rank <? echo ($bcs_winner_style); ?>" ><? echo ($r_selected_spread_display); ?></li>
        <li class="final stat_column final_rank <? echo ($bcs_winner_style); ?>" ><? echo ($r_tie_breaker_02); ?></li>
		</ul>
		<div class="clear clearfix"></div>
<?php
		}	/* close for OUTER loop */
	} /* end if - query success */
	else {
?>
	<p class=message><? echo ($p_participant->msg) ?></p>
<?php
	} /* end else - query success */
?>
    <div class="link_button"><a href="view_entries.php">View All Picks</a></div>
  </div>
  </div> <!-- END MAIN -->
  <div class="spacer5 clear clearfix"></div>
  <footer>
  	<div id=footer>
      <div class="footer_block page_width">
          <div class="spacer10 clear"></div>
          <p>&nbsp;</p>
      </div> 
    </div> 
  </footer>
  <div class=clear></div>
</div>
</body>
</html>