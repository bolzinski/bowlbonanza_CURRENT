<?
require_once("helper_error.php");
require_once("lib/bowl_schedule_class.php");
require_once("lib/validate_class.php");
require_once("lib/validator_helper_class.php");

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
  		<h1>Bowl Bonanza</h1>
        <span class="form_msg">Enter Name and Email, then click "View My Picks" to view picks you previously made.</span><br />
        <span class="form_msg">If you enter an incorrect name or email, you will get an error message.</span>
    </div>
  </div> 
  </header>
  <div class="clear spacer10"></div>
  <div id="main">
  <form id="bowl_bonanza" action="view_picks_process.php" method="post">
  <fieldset>
  	<p>
        <label for="fp_name">Name</label><br />
        <input type="text"  id="fp_name" name="fp_name" class="text" value="" required autofocus/>
     </p>
     <p>
        <label for="fp_email">Email</label><br />
        <input type="email" id="fp_email" name="fp_email"  class="text" value="" required  />
     </p>
  <div class="spacer10 clear"></div>
  <? if ( $p_error_number > 0) { ?>
	   <span class="form_error_msg">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Sorry, there was an error processing this form, please try again.</span>
  <? } ?>
  <div class="spacer10 clear"></div>
  <button class="button" id=login type=submit>View My Picks</button><div class="link_button margin_left_button margin_top_button"><a href="view_entries.php">View Standings</a></div>
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
    $(document).ready(function() {
      // validate signup form on keyup and submit
      var validator = $("#bowl_bonanza").validate({
          rules: {
              fp_name: "required",
              fp_email: {
                  required: true,
                  email: true
              }
          },
          messages: {
              fp_name: "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Enter your name",
              fp_email: {
                  required: "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Invalid email address",
                  email: "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Invalid email address",
                  minlength: "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Invalid email address"
              }
          },
          // the errorPlacement has to take the table layout into account
          errorPlacement: function(error, element) {
              if ( element.is(":radio") )
                  error.appendTo( element.parent().next().next() );
              else if ( element.is(":checkbox") )
                  error.appendTo ( element.next() );
              else
                  error.appendTo( element.parent() );
          }
      });
  });
</script>
</body>
</html>