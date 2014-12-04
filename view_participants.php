<?php
	require_once("lib/participant_class.php");

	define('MAX_DISPLAY_LENGTH_NAME', 40);
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
  		<h2 class="margin_top">Bowl Bonanza Participants</h2>
    </div>
  </div> 
  </header>
  <div id="main">
  <ul class="row_header">
    <li class="x_wide_column col_header">Name</li>
    <li class="x_wide_column col_header">EMail</li>
    <li class="date_time_column col_header">Date Created</li>
  </ul>
  <div class="clear clearfix"></div>
<?php
	
	$p_participant = new participant();
	$p_participant->get_all_participants();

	if ($p_participant->sp_return_code == 0) {
		$row_count = sizeof($p_participant->result_list_participant);
		//echo "ROW COUNT: " . $row_count . "<BR>";
		for ( $i=0; $i<$row_count; $i++) {  	
			$r_participant_name = 	$p_participant->result_list_participant[$i]['name'];
			if (strlen($r_participant_name) > MAX_DISPLAY_LENGTH_NAME) {
				$participant_name_display = substr($r_participant_name, 0, MAX_DISPLAY_LENGTH_NAME) . "...";
			}
			else {
				$participant_name_display = $r_participant_name;
			}
			$r_participant_email = 	$p_participant->result_list_participant[$i]['email'];
			if (strlen($r_participant_email) > MAX_DISPLAY_LENGTH_NAME) {
				$participant_email_display = substr($r_participant_email, 0, MAX_DISPLAY_LENGTH_NAME) . "...";
			}
			else {
				$participant_email_display = $r_participant_email;
			}
			$r_date_created = 	$p_participant->result_list_participant[$i]['date_created'];
			$query_data = array('fp_name'=>$r_participant_name,'fp_email'=>$r_participant_email);
			$query_vars = http_build_query($query_data);
			$entries_link = "/view_picks_process.php?$query_vars";
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
            <li class="x_wide_column" title="<? echo ($participant_name_display); ?>"><a href="<? echo ($entries_link); ?>" title="<? echo ($entries_link); ?>"><? echo ($participant_name_display); ?></a></li>
            <li class="x_wide_column" title="<? echo ($participant_email_display); ?>"><? echo ($participant_email_display); ?></li>
            <li class="date_time_column"><? echo ($r_date_created); ?></li>   
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
	<div class="spacer10 clear clearfix"></div>
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