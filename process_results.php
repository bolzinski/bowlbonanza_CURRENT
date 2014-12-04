<?php

require_once("helper_error.php");
require_once("helper_mail.php");
require_once("lib/participant_class.php");
require_once("lib/entry_class.php");
require_once("lib/validate_class.php");
require_once("lib/validator_helper_class.php");

define('BB_RESULTS_PAGE', 'results.php');
define('BB_STANDINGS_PAGE', 'view_entries.php');
define('NUMBER_OF_BOWL_GAMES', 35);

$p_participant = new participant();
foreach ($_POST as $key => $entry) { 
  //print $key . ": " . $entry . "<br>";
  $rb_index = str_pad((int) $key,2,"0",STR_PAD_LEFT); //pad 0 left single digit bowl id to access corresponding radio button selection post var
  //print $rb_index . " : " . $_POST[$rb_index] . "<br>";
  $bowl_id = $rb_index; 
  $bowl_winner = $entry;
  $p_participant->update_bowl_winner($bowl_id, $bowl_winner);
  switch ($p_participant->sp_return_code) {
	case 0:
	case 1:
		//return_to_form("", 222230, BB_RESULTS_PAGE);
		break ;
	case 106202:
		return_to_form("", 222231, BB_RESULTS_PAGE);
		break ;
	default:
		return_to_form("", 444431, BB_RESULTS_PAGE);
		//print "message: " . $p_participant->msg . "<br>";
		break;
  }  //end switch 
} /* end FOREACH bowl_list */		

$p_participant->result_list_participant = NULL;
$p_participant->get_all_participants();
	
switch ($p_participant->sp_return_code) {
	case 0:
		break;
	default:
		return_to_form($p_participant->msg, 444432, BB_RESULTS_PAGE);
		//print "message: " . $p_participant->msg . "<br>";
		break;
}  //end switch
  
$participant_row_count = sizeof($p_participant->result_list_participant);
//print "ROW COUNT: " . $participant_row_count . "<BR>";
for ( $i=0; $i<$participant_row_count; $i++) {
	$r_participant_id = $p_participant->result_list_participant[$i]['participant_id'];
	//first update all bowls for and mark pick as correct for completed games
	//print "PID: " . $r_participant_id . " : ";
	$p_participant->sp_return_code = 0;
	$p_participant->msg = "";
	$p_participant->update_entry_team_correct_by_pid($r_participant_id);
	switch ($p_participant->sp_return_code) {
		case 0:
		case 1:
			//return_to_form($p_participant->msg, 222230, BB_RESULTS_PAGE);
			//print "message1: " . $p_participant->msg . "<br>";
			break;
		case 106202:
			return_to_form($p_participant->msg, 222231, BB_RESULTS_PAGE);
			//print "message2: " . $p_participant->msg . "<br>";
			break ;
		default:
			return_to_form($p_participant->msg, 444432, BB_RESULTS_PAGE);
			//print "message3: " . $p_participant->msg . "<br>";
			break;
	}  //end switch
	
} /* end FOR particpant_list */

return_to_form($p_participant->msg, 222230, BB_STANDINGS_PAGE);

?>