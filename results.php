<?
require_once("helper_error.php");
require_once("lib/bowl_schedule_class.php");
require_once("lib/validate_class.php");
require_once("lib/validator_helper_class.php");
define('MAX_DISPLAY_LENGTH_NAME', 30);
define('MAX_DISPLAY_LENGTH_EMAIL', 25);
define('MAX_DISPLAY_LENGTH_POSITION', 20);

if (filter_has_var(INPUT_GET, 'en')) {
  $p_error_number = $_GET['en'];
}
else {
  $p_error_number = 0;
}

unset($_GET['en']);

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
  		<h1 class="margin_top">Bowl Bonanza</h1>
        <span class="form_msg">Use this page to set the winner of the bowl game.</span><br />
        <span class="form_msg">Participant standings are updated each time Submit is pressed.</span>
    </div>
  </div> 
  </header>
  <div id="main">
  <div class="clear spacer10"></div>
  
  <form id="bowl_bonanza" action="process_results.php" method="post">
  <fieldset>
     
	<div class="clear spacer10"></div>
  	<ul class="row_header">
      <li class="wide_column col_header">Bowl</li>
      <li class="team col_header">Team 01</li>
      <li class="team col_header">Team 02</li>
      <li class="team col_header">TBD</li>
      <li class="date_column col_header">Date</li>
    </ul>
  <div class="clear clearfix"></div>
<? 	
	
	$bowl_schedule = new bowl_schedule();
	//get order detail rows
	$bowl_schedule->get_bowls();
	//0=Success
	if ($bowl_schedule->sp_return_code == 0) {
		$row_count = sizeof($bowl_schedule->result_list);
		//echo "ROW COUNT: " . $row_count . "<BR>";
		for ( $i=0; $i<$row_count; $i++) {  	
			$r_bowl_id = 	$bowl_schedule->result_list[$i]['bowl_id'];
			$r_bowl = 		$bowl_schedule->result_list[$i]['bowl'];
			if (strlen($r_bowl) > MAX_DISPLAY_LENGTH_NAME) {
				$bowl_display = substr($r_bowl, 0, MAX_DISPLAY_LENGTH_NAME) . "...";
			}
			else {
				$bowl_display = $r_bowl;
			}
			$r_team_01 = 	$bowl_schedule->result_list[$i]['team_01'];
			$r_team_02 = 	$bowl_schedule->result_list[$i]['team_02'];
			$r_bowl_date = 	$bowl_schedule->result_list[$i]['bowl_date'];
			$r_bowl_winner = 	$bowl_schedule->result_list[$i]['bowl_winner'];
			$team_01_checked = "";
			$team_02_checked = "";
			$none_checked = " checked=checked";
			
			//print "bowl_winner: " . $r_bowl_winner . " team_01: " . $r_team_01 . " team_02: " . $r_team_02 . "<BR>";
			
			if ($r_bowl_winner == $r_team_01) {
			  	$team_01_checked = " checked=checked";
				$team_02_checked = "";
				$none_checked = "";
			} 
			else {
				if ($r_bowl_winner == $r_team_02) {
					$team_02_checked = " checked=checked";
					$team_01_checked = "";
					$none_checked = "";
				}
				else {
					$team_01_checked = "";
					$team_02_checked = "";
					$none_checked = " checked=checked";
				}
			}
				
			/*switch (true) {
			  case (strcmp($r_bowl_winner, $r_team_01)) :
			  	$team_01_checked = "checked";
				$team_02_checked = "";
				break;
			  case (strcmp($r_bowl_winner, $r_team_02)) :
			  	$team_02_checked = "checked";
				$team_01_checked = "";
				break;
			  default:
				$team_01_checked = "";
				$team_02_checked = "";
				break;
			} /* end switch */
			
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
            <li><input type="radio" name="<? echo ($r_bowl_id); ?>" value="<? echo ($r_team_01); ?>" <? echo ($team_01_checked); ?>><span class="input_value"><? echo ($r_team_01); ?></span></li>
            <li><input type="radio" name="<? echo ($r_bowl_id); ?>" value="<? echo ($r_team_02); ?>" <? echo ($team_02_checked); ?>><span class="input_value"><? echo ($r_team_02); ?></span></li>
            <li><input type="radio" name="<? echo ($r_bowl_id); ?>" value="TBD" <? echo ($none_checked); ?>><span class="input_value">Reset</span></li>
            <li class="date_col"><? echo ($r_bowl_date); ?></li>            
        </ul>
        <div class="clear clearfix"></div>
        
<?
		}		/* close for loop */
?>	

<? 
	} /* end if - query success */
	else {
?>
    <p class=message><? echo ($bowl_schedule->msg) ?></p>
<?
	} /* end else - query success */
?>
	<div class="spacer10 clear"></div>
<? 	if ( $p_error_number > 0) { ?>
       <span class="form_error_msg"><? echo $error_array[$p_error_number]; ?></span>
<? 	} ?>
	<div class="spacer10 clear"></div>
	<button class="button" id=login type=submit>Set Winners</button>
    <div class="link_button margin_left_button margin_top_button"><a href="view_entries.php">View Standings</a></div>

    </fieldset>
	</form>
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
<!-- Grab Google CDN's jQuery, with a protocol relative URL; fall back to local if offline -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="js/vendor/jquery-1.7.2.min.js"><\/script>')</script>

</body>
</html>