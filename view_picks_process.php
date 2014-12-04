<?php
	require_once("helper_error.php");
	require_once("lib/participant_class.php");
	require_once("lib/entry_class.php");
	
	define('BB_VIEW_PAGE', 'view_picks.php');
	//print "name: " . trim($_POST['fp_name']);
//	print "email: " . trim($_POST['fp_email']);
//	exit;
	$participant_email = trim($_REQUEST['fp_email']);
	$participant_name = trim($_REQUEST['fp_name']);
	$p_participant = new participant();
	$p_participant->get_participant($participant_name, $participant_email);
	if ($p_participant->sp_return_code == 0) {
	  $row_count = sizeof($p_participant->result_list);
	  //echo "ROW COUNT: " . $row_count . "<BR>";
	  for ( $i=0; $i<$row_count; $i++) {  	
		  $participant_id = $p_participant->result_list[$i]['participant_id'];
	  } /* end FOR */
	} /* end IF */
	else {
		return_to_form("", 222201, BB_VIEW_PAGE);
	}
?>

<?php
	require_once("view_picks_sub.php");
?>