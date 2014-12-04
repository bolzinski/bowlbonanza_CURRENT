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
        <span class="form_msg">Enter Name and Email address, make your picks then click "Submit Picks".</span><br />
        <span class="form_msg">You can submit additional entries by changing either your name, email or both.</span>
    </div>
  </div> 
  </header>
  <div id="main">
  <div class="clear spacer10"></div>
  
  <form id="bowl_bonanza" action="process_picks.php" method="post">
  <fieldset>
     <p>
        <label for="fp_name">Name</label><br />
        <input type="text"  id="fp_name" name="fp_name" class="text" value="" required autofocus/>
     </p>
     <div class="clear spacer10"></div>
     <p>
        <label for="fp_email">Email</label><br />
        <input type="email" id="fp_email" name="fp_email"  class="text" value="" required  />
     </p>
     <div class="clear spacer10"></div>
     <p>
        <label for="fp_email">Confirm Email</label><br />
        <input type="email" id="fp_email2" name="fp_email2"  class="text" value="" required  />
     </p>
     <div class="clear spacer10"></div>
     <p>
     	<label for="fp_tie">Championship Point Spread (for tie breaker)</label><br />
     	<select id="fp_tie" name="fp_tie" class="list_box">
        	<option value="" selected></option>
        	
<?php
	for ( $i=1; $i<31; $i++) { 
?>
	<option value="<? echo ($i); ?>"><? echo ($i); ?></option>
<?php
	} /* end FOR */
?>
	</select>
	</p>
	<div class="clear spacer10"></div>
  	<ul class="row_header">
      <li class="wide_column col_header">Bowl</li>
      <li class="team col_header">Team 01</li>
      <li class="team col_header">Team 02</li>
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
            <li><input type="radio" id="<? echo ("fp_" . $r_bowl_id); ?>" name="<? echo ("fp_" . $r_bowl_id); ?>" value="<? echo ($r_team_01); ?>" <? if ($i<>5) echo "checked=checked"; ?> ><span class="input_value"><? echo ($r_team_01); ?></span></li>
            <li><input type="radio" id="<? echo ("fp_" . $r_bowl_id); ?>" name="<? echo ("fp_" . $r_bowl_id); ?>" value="<? echo ($r_team_02); ?>"><span class="input_value"><? echo ($r_team_02); ?></span></li>
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
<? 	} else {?>
	   <span class="radio_error_msg"></span>
<? 	}  ?>   
	<div class="spacer10 clear"></div>
	<button class="button" id=login type=submit>Submit Picks</button>
    <div class="link_button margin_left_button_med"><a href="view_entries.php">View Standings</a></div>
    <div class="link_button margin_left_button_med"><a href="view_picks.php">View picks I already made</a></div>

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
<script src="js/jquery.validate.min.js"></script>
<script id="demo" type="text/javascript">
    jQuery.validator.setDefaults({
  		debug: false,
  		success: "valid"
		});
	$(document).ready(function() {
      // validate signup form on keyup and submit
     $("#bowl_bonanza").validate({
          rules: {
              fp_name: "required",
              fp_email: {
                  required: true,
                  email: true
              },
              fp_email2: {
                  required: true,
                  email: true,
                  equalTo: "#fp_email"
              },
			  fp_tie: {
                  required: true
              },
			  fp_01: "required",
			  fp_02: "required",
			  fp_03: "required",
			  fp_04: "required",
			  fp_05: "required",
			  fp_06: "required",
			  fp_07: "required",
			  fp_08: "required",
			  fp_09: "required",
			  fp_10: "required",
			  fp_11: "required",
			  fp_12: "required",
			  fp_13: "required",
			  fp_14: "required",
			  fp_15: "required",
			  fp_16: "required",
			  fp_17: "required",
			  fp_18: "required",
			  fp_19: "required",
			  fp_20: "required",
			  fp_21: "required",
			  fp_22: "required",
			  fp_23: "required",
			  fp_24: "required",
			  fp_25: "required",
			  fp_26: "required",
			  fp_27: "required",
			  fp_28: "required",
			  fp_29: "required",
			  fp_30: "required",
			  fp_31: "required",
			  fp_32: "required",
			  fp_33: "required",
			  fp_34: "required",
			  fp_35: "required"
          },
		  groups: {
			fp_all: "fp_name fp_email fp_email2 fp_tie fp_01 fp_02 fp_03 fp_04 fp_05 fp_06 fp_07 fp_08 fp_09 fp_10 fp_11 fp_12 fp_13 fp_14 fp_15 fp_16 fp_17 fp_18 fp_19 fp_20 fp_21 fp_22 fp_23 fp_24 fp_25 fp_26 fp_27 fp_28 fp_29 fp_30 fp_31 fp_32 fp_33 fp_34 fp_35"
		  },
		  showErrors: function (errorMap, errorList) {
			if (this.numberOfInvalids() > 0 ) {
				$("span.radio_error_msg").css('color', 'red');
        		$("span.radio_error_msg").html("Please correct the highlighted errors.  There are " + this.numberOfInvalids() + " errors.");
				this.defaultShowErrors();
			}
			else {
				this.defaultShowErrors();
				$("span.radio_error_msg").css('color', 'green');
				$("span.radio_error_msg").html("Form correct, you can submit entry.  There are " + this.numberOfInvalids() + " errors.");
				this.defaultShowErrors();
			}
		  },
		  errorPlacement: function () {
		 	return false; // <- kill error messages
		  }
      });
  });
</script>
</body>
</html>