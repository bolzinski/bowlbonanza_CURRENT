<?php
	require_once("lib/participant_class.php");
	
	define('MAX_DISPLAY_LENGTH_NAME', 30);
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
  <title>Bowl Bonanza</title>

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
  		<h2 class="margin_top">Bowl Bonanza Picks for: <span class="sub_header"><? echo $participant_name . " - " . $participant_email; ?></span></h2>
    </div>
  </div> 
  </header>
  <div id="view">
  <ul class="row_header">
    <li class="wide_column col_header">Bowl</li>
    <li class="date_column col_header">Date</li>
    <li class="date_column col_header">Time</li>
    <li class="col_header">Team 01</li>
    <li class="col_header">Team 02</li>
    <li class="col_header">Your Pick</li>
  </ul>
  <div class="clear clearfix"></div>
<?php

	$p_participant->get_picks_by_pid($participant_id);

	if ($p_participant->sp_return_code == 0) {
		$row_count = sizeof($p_participant->result_list);
		//echo "ROW COUNT: " . $row_count . "<BR>";
		for ( $i=0; $i<$row_count; $i++) {
			$r_participant_name = 	$p_participant->result_list[$i]['name'];
			if (strlen($r_participant_name) > MAX_DISPLAY_LENGTH_NAME) {
				$participant_name_display = substr($r_participant_name, 0, MAX_DISPLAY_LENGTH_NAME) . "...";
			}
			else {
				$participant_name_display = $r_participant_name;
			}
			$r_participant_email = 	$p_participant->result_list[$i]['email'];
			if (strlen($r_participant_email) > MAX_DISPLAY_LENGTH_NAME) {
				$participant_email_display = substr($r_participant_email, 0, MAX_DISPLAY_LENGTH_NAME) . "...";
			}
			else {
				$participant_email_display = $r_participant_email;
			}  	
			$r_team_selected = 	$p_participant->result_list[$i]['team_selected'];
			$r_bowl = 		$p_participant->result_list[$i]['bowl'];
			if (strlen($r_bowl) > MAX_DISPLAY_LENGTH_NAME) {
				$bowl_display = substr($r_bowl, 0, MAX_DISPLAY_LENGTH_NAME) . "...";
			}
			else {
				$bowl_display = $r_bowl;
			}
			$r_team_01 = 	$p_participant->result_list[$i]['team_01'];
			$r_team_02 = 	$p_participant->result_list[$i]['team_02'];
			$r_bowl_date = 	$p_participant->result_list[$i]['bowl_date'];
			$r_bowl_time = 	$p_participant->result_list[$i]['bowl_time'];
			$time_hours = substr($r_bowl_time, 0, 2);
			//adjust hours from milatary time
			if ($time_hours > 12) {
				$time_hours = $time_hours - 12;
			}
			$time_rest = substr($r_bowl_time, 2);
			$bowl_time_adjusted = $time_hours . $time_rest;
			$r_tie_breaker = 	$p_participant->result_list[$i]['tie_breaker'];
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
            <li class="wide_column" title="<? echo ($r_bowl); ?>"><? echo ($bowl_display); ?></li>
            <li class="date_column"><? echo ($r_bowl_date); ?></li>
            <li class="date_column"><? echo ($bowl_time_adjusted); ?></li>   
            <li><? echo ($r_team_01); ?></li>
            <li><? echo ($r_team_02); ?></li>
            <li class="selected"><? echo ($r_team_selected); ?></li> 
                      
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
    <p class=form_msg>BCS Championship game point spread: <? echo ($r_tie_breaker) ?></p>
    <div class="link_button"><a href="home.php">Make NEW picks</a></div><div class="link_button margin_left"><a href="view_picks.php">View picks I already made</a></div>
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