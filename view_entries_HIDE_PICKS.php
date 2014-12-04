<?php
	require_once("lib/participant_class.php");
	//require_once("lib/bowl_schedule_class.php");

	define('MAX_DISPLAY_LENGTH_BOWL_NAME', 25);
	define('MAX_DISPLAY_LENGTH_TEAM_NAME', 2);
	define('MAX_DISPLAY_LENGTH_NAME', 30);
	define('NUMBER_OF_BOWL_GAMES', 35);
	define('SHOW_ENTRIES', 0);
	
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
  		<h2 class="margin_top">Bowl Bonanza Participants <span class="sub_header"><? echo "( Click a name to see their picks listing. )"; ?></span></h2>
    </div>
  </div> 
  </header>
  <div id="main">
  	<h3 class="margin_top">DATE:</h3>
    <div class="spacer10 clear clearfix"></div>
    <ul class="row_dd">
<?php
	
	$p_participant = new participant();
	$p_participant->result_list = NULL;
	$p_participant->get_bowls();
	//0=Success, this query prints out day (of date)
	if ($p_participant->sp_return_code == 0) {
	  $row_count = sizeof($p_participant->result_list);
	  for ( $i=0; $i<$row_count; $i++) { 
	  	  $r_team_01 = 	$p_participant->result_list[$i]['team_01'];
		  $r_team_02 = 	$p_participant->result_list[$i]['team_02'];
		  $r_bowl_date = 	$p_participant->result_list[$i]['bowl_date'];
		  $bowl_date_dd = substr($r_bowl_date, 8, 2);
		  $bowl_date_dd_padded = str_pad((int) $bowl_date_dd,2,"0",STR_PAD_LEFT);
		  $bowl_date_d_tens = substr($bowl_date_dd_padded, 0, 1);
		  $bowl_date_d_ones = substr($bowl_date_dd_padded, 1, 1);
		  if (($i % 2) == 1) {
			  $cell_date_class = "cell_date_odd";
		  }
	   	  else {
			  $cell_date_class = "cell_date_even";
		  }
?>
		<li class="cell <? echo ($cell_date_class); ?>" title="<? echo ($r_team_01 . " vs. " . $r_team_02); ?>"><? echo ($bowl_date_d_tens); ?></li>
<?php
	  } // end FOR
?>
		</ul>
        <div class="clear clearfix"></div>
        <ul class="row_dd">
<?php
		for ( $i=0; $i<$row_count; $i++) {
		  $r_team_01 = 	$p_participant->result_list[$i]['team_01'];
		  $r_team_02 = 	$p_participant->result_list[$i]['team_02'];	
		  $r_bowl_date = 	$p_participant->result_list[$i]['bowl_date'];
		  $bowl_date_dd = substr($r_bowl_date, 8, 2);
		  $bowl_date_dd_padded = str_pad((int) $bowl_date_dd,2,"0",STR_PAD_LEFT);
		  $bowl_date_d_tens = substr($bowl_date_dd_padded, 0, 1);
		  $bowl_date_d_ones = substr($bowl_date_dd_padded, 1, 1);
		  if (($i % 2) == 1) {
			  $cell_date_class = "cell_date_odd";
		  }
	   	  else {
			  $cell_date_class = "cell_date_even";
		  }
?>
        <li class="cell <? echo ($cell_date_class); ?>" title="<? echo ($r_team_01 . " vs. " . $r_team_02); ?>"><? echo ($bowl_date_d_ones); ?></li>
<?php
	  } // end FOR
?>
		</ul>
        <div class="spacer20 clear clearfix"></div>
		<ul class="row_winners">
<?php
		for ( $i=0; $i<$row_count; $i++) {  	
		  $r_bowl_winner = 	$p_participant->result_list[$i]['bowl_winner'];
		  $r_team_01 = 	$p_participant->result_list[$i]['team_01'];
		  $r_team_02 = 	$p_participant->result_list[$i]['team_02'];
		  $r_game_winner = "";
		  $cell_winner_style = "winner_row";
		  //set style for cell winners that have already been decided
		  if ($r_bowl_winner == $r_team_01) {
			  $r_game_winner_short = "1";
			  $cell_winner_style = "winner_row selected";
		  } 
		  else {
			  if ($r_bowl_winner == $r_team_02) {
				  $r_game_winner_short = "2";
				  $cell_winner_style = "winner_row selected";
			  }
			  else {
				  $r_game_winner_short = "*";
				  $r_game_winner = "TBD";
			  }
		  }
		  if (($i % 2) == 1) {
			  $cell_winner_style = "cell_date_odd pad_to_center " . $cell_winner_style;
		  }
	   	  else {
			  $cell_winner_style = "cell_date_even pad_to_center " . $cell_winner_style;
		  }
		  $game_winners_label = "   << GAME WINNERS";
?>
        <li class="cell <? echo ($cell_winner_style); ?>" title="<? echo ($r_bowl_winner); ?>"><? echo ($r_game_winner_short); ?></li>
<?php
	  } // end FOR
?>
		<li class="cell wins label winner_row" title="<? echo ($game_winners_label); ?>"><? echo ($game_winners_label); ?></li>
		</ul>
        <div class="spacer20 clear clearfix"></div>

<?php
	} /* end if - query success */
	else {
?>
	<p class=message><? echo ($p_participant->msg) ?></p>
<?php 
	exit; //output error then exit page if first query fails
	
	} /* end else - query success */
	
	//exit;
	//$bowl_schedule->__destruct();
	//$p_participant = new participant();
	//$p_participant->result_list = NULL;
	$p_participant->get_all_participants();

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
		  if ($i > 0) { //in order to calculate the participant RANK, need to save previous number correct for comparison, except for first time through.
		  	$r_bowls_correct_previous = 	$r_bowls_correct;
			$r_bowls_correct = 	$p_participant->result_list_participant[$i]['number_correct'];
			$participant_rank_previous = $participant_rank;
			if ($r_bowls_correct == $r_bowls_correct_previous ) { // all participants with same number of WINS will be tied for that rank.
			  $participant_rank = $participant_rank_previous;
			} 
			else {
				$participant_rank = $i + 1;
			}
		  } // end IF first time through
		  else { // default rank is "1" for first in list
		  	$r_bowls_correct = 	$p_participant->result_list_participant[$i]['number_correct'];
			$participant_rank = $i + 1;
		  }
			
		  //$r_bowls_correct = 	$p_participant->result_list_participant[$i]['number_correct'];
		 
		  //now get all bowl picks for participant
		  $p_participant->result_list = NULL;
		  $p_participant->get_picks_by_pid($r_participant_id );
	  
		  if ($p_participant->sp_return_code == 0) {
			$entry_row_count = sizeof($p_participant->result_list);
			//tie-breaker is the same for all entry items
			$r_entry_tiebreaker = 	$p_participant->result_list[0]['tie_breaker'];
			//print "ENTRY ROW COUNT: " . $entry_row_count . "<BR>";
			for ( $entry_idx=0; $entry_idx<$entry_row_count; $entry_idx++) {
				$r_team_selected = 	$p_participant->result_list[$entry_idx]['team_selected'];
				$r_bowl = 		$p_participant->result_list[$entry_idx]['bowl'];
				if (strlen($r_bowl) > MAX_DISPLAY_LENGTH_NAME) {
					$bowl_display = substr($r_bowl, 0, MAX_DISPLAY_LENGTH_NAME) . "...";
				}
				else {
					$bowl_display = $r_bowl;
				}
				$r_team_01 = 	$p_participant->result_list[$entry_idx]['team_01'];
				if (strlen($r_team_01) > MAX_DISPLAY_LENGTH_TEAM_NAME) {
					$team_01_display = substr($r_team_01, 0, MAX_DISPLAY_LENGTH_TEAM_NAME) . "...";
				}
				else {
					$team_01_display = $r_team_01;
				}
				$r_team_02 = 	$p_participant->result_list[$entry_idx]['team_02'];
				if (strlen($r_team_02) > MAX_DISPLAY_LENGTH_TEAM_NAME) {
					$team_02_display = substr($r_team_02, 0, MAX_DISPLAY_LENGTH_TEAM_NAME) . "...";
				}
				else {
					$team_02_display = $r_team_02;
				}
				$r_bowl_date = 	$p_participant->result_list[$entry_idx]['bowl_date'];
				$bowl_date_dd = substr($r_bowl_date, 4, 2);
				$r_bowl_time = 	$p_participant->result_list[$entry_idx]['bowl_time'];
				$time_hours = substr($r_bowl_time, 0, 2);
				//adjust hours from milatary time
				if ($time_hours > 12) {
					$time_hours = $time_hours - 12;
				}
				$time_rest = substr($r_bowl_time, 2);
				$bowl_time_adjusted = $time_hours . $time_rest;
				$r_tie_breaker = 	$p_participant->result_list[$entry_idx]['tie_breaker'];
				$r_pick_correct = 	$p_participant->result_list[$entry_idx]['team_selected_correct'];
				$r_game_winner = "";
				if ($r_team_selected == $r_team_01) {
					$r_team_selected_short = "*";
					$r_team_selected = $r_team_01;
				} 
				else {
					if ($r_team_selected == $r_team_02) {
						$r_team_selected_short = "*";
						$r_team_selected = $r_team_02;
					}
					else {
						$r_team_selected_short = "*";
						$r_team_selected = "TBD";
					}
				}
				//set some styles based on if cell is even or odd
				if (($entry_idx % 2) == 1) {
					$cell_date_class = "cell_date_odd";
				}
				else {
				  $cell_date_class = "cell_date_even";
				}
				//If PICK is correct, style green
				if ($r_pick_correct) {
					$cell_date_class = $cell_date_class . " selected";
				}
				  
?>
				<li class="cell <? echo ($cell_date_class); ?>" title="<? echo ($r_team_selected); ?>"><? echo ($r_team_selected_short); ?></li>
			
<?php
				} /* close for INNER loop */
				
				//$participant_name_display = $participant_name_display . " (" . $r_entry_tiebreaker . ") ";
				$participant_name_display = $participant_name_display . " (##) ";
				
				//STYLE WINNER & LOSER
				$winner_style = "";
				$loser_style = "";
				if ($r_bowl_bonanza_winner==1) {
					$participant_name_display = $participant_name_display . " * WINNER *";
					$winner_style = "winner";
				}
				else {
					if ($r_bowl_bonanza_winner== -1) {
						$participant_name_display = $participant_name_display . " * LAST PLACE *";
						$winner_style = "loser";
					}
				}
?>
				<li class="cell_name wins" title="<? echo ($r_bowls_correct); ?>"><? echo ("( " . str_pad((int) $r_bowls_correct,2,"0",STR_PAD_LEFT) . " WINs )"); ?></li>
				<li class="cell_name rank" ><? echo ($participant_rank); ?></li>
                <li class="cell_name name <? echo ($winner_style); ?>" title="<? echo ($r_participant_name); ?>"><? echo ($participant_name_display); ?></li>
				</ul>
				<div class="clear clearfix"></div>
<?php
			
			} /* end if - query success */
			else {
?>4
			<p class=message><? echo ($p_participant->msg) ?></p>
<?php
			} /* end else - query success */
		}	/* close for OUTER loop */
	} /* end if - query success */
	else {
?>
	<p class=message><? echo ($p_participant->msg) ?></p>
<?php
	} /* end else - query success */
?>

	<div class="spacer10 clear clearfix"></div>
    <div class="spacer10 clear clearfix"></div>
    <div class="spacer10 clear clearfix"></div>
	<ul class="row_header">
      <li class="bowl_column col_header">Bowl</li>
      <li class="col_header">Team 01</li>
      <li class="col_header">Team 02</li>
      <li class="date_column col_header">Date</li>
      <li class="date_column col_header">Time</li>
      <li class="date_column col_header">Network</li>
    </ul>
    <div class="clear clearfix"></div>
<?php
	//unset($p_participant);
	//$p_participant = new participant();
	//2012-12-11: DISCOVERED the result_list needs to be initialized before the next query, otherwise unexpected results.
	$p_participant->result_list = NULL;
	$p_participant->get_bowls();

	if ($p_participant->sp_return_code == 0) {
		$row_count = sizeof($p_participant->result_list);
		//print "ROW COUNT: " . $row_count . "<BR>";
		//exit;
		for ( $i=0; $i<$row_count; $i++) {  	
			//$r_team_selected = 	$p_participant->result_list[$i]['team_selected'];
			$r_bowl = 		$p_participant->result_list[$i]['bowl'];
			if (strlen($r_bowl) > MAX_DISPLAY_LENGTH_BOWL_NAME) {
				$bowl_display = substr($r_bowl, 0, MAX_DISPLAY_LENGTH_BOWL_NAME) . "...";
			}
			else {
				$bowl_display = $r_bowl;
			} 
			$r_bowl_name_short = 		$p_participant->result_list[$i]['bowl_name_short'];
			if (strlen($r_bowl_name_short) > MAX_DISPLAY_LENGTH_BOWL_NAME) {
				$bowl_name_short_display = substr($r_bowl, 0, MAX_DISPLAY_LENGTH_BOWL_NAME) . "...";
			}
			else {
				$bowl_name_short_display = $r_bowl_name_short;
			} 
			$r_team_01 = 	$p_participant->result_list[$i]['team_01'];
			$r_team_02 = 	$p_participant->result_list[$i]['team_02'];
			$r_bowl_winner =$p_participant->result_list[$i]['bowl_winner'];
			$r_bowl_date = 	$p_participant->result_list[$i]['bowl_date'];
			$r_tv_network = $p_participant->result_list[$i]['tv_network'];
			$r_bowl_time = 	$p_participant->result_list[$i]['bowl_time'];
			$cell_winner_style_01 = "";
			$cell_winner_style_02 = "";
			if ($r_bowl_winner == $r_team_01) {
				  $cell_winner_style_01 = "selected";
				  $cell_winner_style_02 = "";
			 } 
			 else {
				  if ($r_bowl_winner == $r_team_02) {
					  $cell_winner_style_01 = "";
					  $cell_winner_style_02 = "selected";
				  }
				  else {
					  $cell_winner_style_01 = "";
					  $cell_winner_style_02 = "";
				  }
		  	}
			
			$time_hours = substr($r_bowl_time, 0, 2);
			//adjust hours from milatary time
			if ($time_hours > 12) {
				$time_hours = str_pad((int) ($time_hours - 12),2,"0",STR_PAD_LEFT);
			}
			$time_rest = substr($r_bowl_time, 2);
			$bowl_time_adjusted = $time_hours . $time_rest;
			//$r_tie_breaker = 	$p_participant->result_list[$i]['tie_breaker'];
			if (($i % 2) == 0)	{
?>
				<ul class=row_even>
<?
			}
			else {
?>
				<ul class=row_odd>
<?
			}
?>
            <li class="bowl_column" title="<? echo ($r_bowl); ?>"><? echo ($bowl_name_short_display); ?></li>
            <li class="<? echo ($cell_winner_style_01); ?>"><? echo ($r_team_01); ?></li>
            <li class="<? echo ($cell_winner_style_02); ?>"><? echo ($r_team_02); ?></li>
            <li class="date_column"><? echo ($r_bowl_date); ?></li>
            <li class="date_column"><? echo ($bowl_time_adjusted); ?></li>   
            <li class="selected"><? echo ($r_tv_network); ?></li> 
                      
        </ul>
        <div class="clear clearfix"></div>
        
<?
		}		/* close for loop */
?>	

<? 
	} /* end if - query success */
	else {
?>
    <p class=message><? echo ($p_participant->msg) ?></p>
<?
	} /* end else - query success */
?>
    <div class="link_button"><a href="view_entries.php">View Standings</a></div>
    <div class="link_button margin_left_button"><a href="view_picks.php">View picks I already made</a></div>
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