<?
define('ERROR_RANGE_MIN', 222200);
define('ERROR_RANGE_MAX', 444400);
	
$error_array = array("0"=>"<BR>",
				"222201"=>"There was a problem creating a new participant. (201)",
				"222202"=>"A participant with the same name and email address already exists. (202)",
				"222203"=>"You must select a winner for each game. (203)",
				"222210"=>"System error creating new entry, please start over. (210)",
				"222211"=>"A participant with this name and email could not be found in the system. (211)",
				"222212"=>"Attempt to add a duplicate entry for a particpant/bowl game. (212)",
				"222230"=>"Bowl Winner Update Successful!",
				"222231"=>"Team slected as WINNER does not play in specified Bowl Game (bowl_id). (231)",
				"222232"=>"No participant found for particpant ID. (232)",
				"444431"=>"Unexpected Error updating bowl winner. (431)",
				"444432"=>"Unexpected Error retrieving Participant list. (432)",
				"444401"=>"Unexpected Error.  Please try again or email for assistance. (400)");
				
function return_to_form($error_message="", $error_number=0, $next_page, $query_string="") {
	
	/* $error_text = native error message written to log */
	/* $error_number is the used for application error messages displayed to the user */
	error_log($error_message,0);
	
	if ($error_number > 0) { /* error number indicates error occured processing order */
		header("Location:" .$next_page."?en=" . $error_number."&".$query_string);
		exit;
		//echo "returning to form: " .ORDER_FORM_PAGE."?en=" . $error_number. "<BR><BR>";
	} 
	else { /* no error number indicates form field validation errors */
		header("Location:" .$next_page);
		exit;
		//echo "returning to form: " .ORDER_FORM_PAGE;
	}

} /* end function */

function write_to_error_log($error_message) {
	
	error_log($error_message,0);
	
} /* end function */

/* END FUNCTIONS */
 
?>