<?

class participant {
	
	// explicitly adding class properties are optional - but is good practice
	public $user_id, $email, $pwd;
	public $msg;
	public $sp_return_code;
	public $result_list;
	public $result_list_participant;
	public $user_count;
	private $db;
	
	//use this constructor to create a new artist class
	function __construct() {
		
		//make connection
		//require("../../lib/link_mysqli2_class.php");
		require_once("/volumes/content/secure/link_BB_mysqli2_class.php");
	
		$this->msg="Success!";
		$this->result_list = array();
		$this->result_list_participant = array();
		$this->sp_return_code=0;
	}
	
	//use destructor to close DB connection if it hasn't been done yet
	function __destruct() {
		
		$this->result_list = NULL;
		$this->db->close();
	}
	
	public function new_participant($name, $email) {
	
		$sql = 'call sp_new_participant("' .$name. '", "' .$email. '");';
		/*echo " ------------------------------------- New challenger_log ------------------------------------------------------<br>";
		echo "SQL COMMAND: " .$sql. "<br>";
		echo " ------------------------------------- New challenger_log ------------------------------------------------------<br>";*/
		$this->msg="";
		$p_result_code = 0;
		$db=$this->db;
		if ($db->multi_query($sql)) {
			do {
				if ($result = $db->store_result()) {
					while ($row = $result->fetch_assoc()) {
						$p_result_code = $row['p_return_code'];
						switch ($p_result_code)
						{
						case 106202:
							$this->msg = "Error - participant with same Name AND Email already exists";
							$this->sp_return_code = $p_result_code;
							break ;
						case 106201:
							$this->msg = "Error - participant ID already exists";
							$this->sp_return_code = $p_result_code;
							break ;
						case 0:
						case 1:
							$this->msg = "participant added successfully!";
							$this->sp_return_code = $p_result_code;
							break ;
						default:
							$this->msg = "Trouble!";
							$this->sp_return_code = $p_result_code;
							break;
						}
					}
					$result->close();
				}
			} while ($db->next_result());
			//$result->free_result();
		}
		else {
			$this->msg="UNEXPECTED SQL ERROR: " .$db->errno. " - " .$db->error.  " please contact site administrator!<br>";
			//use -100 to designate a unhandled MYSQL error
			$this->sp_return_code = -100;
		}
		
	} /* END FUNCTION */
	
	
	public function get_participant($name, $email) {
	
		$sql = 'call sp_get_participant("' .$name. '", "' .$email. '");';
		//echo " ------------------------------------- Get show List ------------------------------------------------------<br>";
//		echo "SQL COMMAND: " .$sql. "<br>";
//		echo " ------------------------------------- Get show List ------------------------------------------------------<br>";
				
		$db=$this->db;
		if ($db->multi_query($sql)) {
			do {
				if ($result = $db->store_result()) {
					$i=0;
					while ($row = $result->fetch_array()) {
						$this->result_list[$i] = $row;
						$i++;
					}
					$result->close();
					if ($i==0) {
						//echo "HERE1<br>";
						$this->sp_return_code = -99;
					}
					else {
						//echo "HERE2<br>";
						$this->sp_return_code = 0;
					}
				}
			} while ($db->next_result());
		}
		else {
			$this->msg="UNEXPECTED SQL ERROR: " .$db->errno. " - " .$db->error.  " please contact site administrator!<br>";
			//use -100 to designate a unhandled MYSQL error
			$this->sp_return_code = -100;
			//echo "SQL ERROR: " .$db->errno. " - " .$db->error.  "<br>";
		}
			
	}
	
	public function get_all_participants() {
	
		$sql = 'call sp_get_all_participants();';
		//echo " ------------------------------------- Get show List ------------------------------------------------------<br>";
//		echo "SQL COMMAND: " .$sql. "<br>";
//		echo " ------------------------------------- Get show List ------------------------------------------------------<br>";
				
		$db=$this->db;
		if ($db->multi_query($sql)) {
			do {
				if ($result = $db->store_result()) {
					$i=0;
					while ($row = $result->fetch_array()) {
						$this->result_list_participant[$i] = $row;
						$i++;
					}
					$result->close();
					if ($i==0) {
						//echo "HERE1<br>";
						$this->sp_return_code = -99;
					}
					else {
						//echo "HERE2<br>";
						$this->sp_return_code = 0;
					}
				}
			} while ($db->next_result());
		}
		else {
			$this->msg="UNEXPECTED SQL ERROR: " .$db->errno. " - " .$db->error.  " please contact site administrator!<br>";
			//use -100 to designate a unhandled MYSQL error
			$this->sp_return_code = -100;
			//echo "SQL ERROR: " .$db->errno. " - " .$db->error.  "<br>";
		}
			
	} /* END FUNCTION */
	
	public function get_final_standings() {
	
		$sql = 'call sp_get_final_standings();';
		//echo " ------------------------------------- Get show List ------------------------------------------------------<br>";
//		echo "SQL COMMAND: " .$sql. "<br>";
//		echo " ------------------------------------- Get show List ------------------------------------------------------<br>";
				
		$db=$this->db;
		if ($db->multi_query($sql)) {
			do {
				if ($result = $db->store_result()) {
					$i=0;
					while ($row = $result->fetch_array()) {
						$this->result_list_participant[$i] = $row;
						$i++;
					}
					$result->close();
					if ($i==0) {
						//echo "HERE1<br>";
						$this->sp_return_code = -99;
					}
					else {
						//echo "HERE2<br>";
						$this->sp_return_code = 0;
					}
				}
			} while ($db->next_result());
		}
		else {
			$this->msg="UNEXPECTED SQL ERROR: " .$db->errno. " - " .$db->error.  " please contact site administrator!<br>";
			//use -100 to designate a unhandled MYSQL error
			$this->sp_return_code = -100;
			//echo "SQL ERROR: " .$db->errno. " - " .$db->error.  "<br>";
		}
			
	} /* END FUNCTION */
	
	public function get_participant_count() {
	
		$sql = 'call sp_get_participant_count();';
		//echo " ------------------------------------- Get show List ------------------------------------------------------<br>";
//		echo "SQL COMMAND: " .$sql. "<br>";
//		echo " ------------------------------------- Get show List ------------------------------------------------------<br>";
				
		$db=$this->db;
		if ($db->multi_query($sql)) {
			do {
				if ($result = $db->store_result()) {
					$i=0;
					while ($row = $result->fetch_array()) {
						$this->result_list[$i] = $row;
						$i++;
					}
					$result->close();
					if ($i==0) {
						//echo "HERE1<br>";
						$this->sp_return_code = -99;
					}
					else {
						//echo "HERE2<br>";
						$this->sp_return_code = 0;
					}
				}
			} while ($db->next_result());
		}
		else {
			$this->msg="UNEXPECTED SQL ERROR: " .$db->errno. " - " .$db->error.  " please contact site administrator!<br>";
			//use -100 to designate a unhandled MYSQL error
			$this->sp_return_code = -100;
			//echo "SQL ERROR: " .$db->errno. " - " .$db->error.  "<br>";
		}
			
	} /* END FUNCTION */
	
	public function update_participant_total_correct_by_pid($participant_id) {
	
		$sql = 'call sp_update_participant_total_correct_by_pid(' .$participant_id. ');';
		/*echo " ------------------------------------- update user ------------------------------------------------------<br>";
		echo "SQL COMMAND: " .$sql. "<br>";
		echo " ------------------------------------- update user ------------------------------------------------------<br>";*/
		$this->msg="";
		$p_result_code = 0;
		$db=$this->db;
		if ($db->multi_query($sql)) {
			do {
				if ($result = $db->store_result()) {
					while ($row = $result->fetch_assoc()) {
						$p_result_code = $row['p_return_code'];
						switch ($p_result_code)
						{
						case 106202:
							$this->msg = "Error - No participant found for participant id.";
							$this->sp_return_code = $p_result_code;
							break ;
						case 106201:
							$this->msg = "Error - Unknown update error, updating participant.";
							$this->sp_return_code = $p_result_code;
							break ;
						case 0: // current password GOOD but NEW password is the same, 0 rows updated
							$this->msg = "Update successful, no rows affected.";
							$this->sp_return_code = $p_result_code;
							break ;
						case 1: // password changed successfully!
							$this->msg = "Update successful!";
							$this->sp_return_code = $p_result_code;
							break ;
						default:
							$this->msg = "Error - Unknown Update Error in SP: sp_update_participant_total_correct_by_pid.";
							$this->sp_return_code = $p_result_code;
							break ;
						}
					}
					$result->close();
				}
			} while ($db->next_result());
			//$result->free_result();
		}
		else {
			$this->msg="UNEXPECTED SQL ERROR: " .$db->errno. " - " .$db->error.  " please contact site administrator!<br>";
			//use -100 to designate a unhandled MYSQL error
			$this->sp_return_code = -100;
			//echo "SQL ERROR: " .$db->errno. " - " .$db->error.  "<br>";
		}
		
	} /* end function */
	
	public function update_participant_tie_breakers($participant_id) {
	
		$sql = 'call sp_update_participant_tie_breakers(' .$participant_id. ');';
		/*echo " ------------------------------------- update user ------------------------------------------------------<br>";
		echo "SQL COMMAND: " .$sql. "<br>";
		echo " ------------------------------------- update user ------------------------------------------------------<br>";*/
		$this->msg="";
		$p_result_code = 0;
		$db=$this->db;
		if ($db->multi_query($sql)) {
			do {
				if ($result = $db->store_result()) {
					while ($row = $result->fetch_assoc()) {
						$p_result_code = $row['p_return_code'];
						switch ($p_result_code)
						{
						case 106202:
							$this->msg = "Error - No participant found for participant id.";
							$this->sp_return_code = $p_result_code;
							break ;
						case 106201:
							$this->msg = "Error - Unknown update error, updating participant.";
							$this->sp_return_code = $p_result_code;
							break ;
						case 0: // current password GOOD but NEW password is the same, 0 rows updated
							$this->msg = "Update successful, no rows affected.";
							$this->sp_return_code = $p_result_code;
							break ;
						case 1: // password changed successfully!
							$this->msg = "Update successful!";
							$this->sp_return_code = $p_result_code;
							break ;
						default:
							$this->msg = "Error - Unknown Update Error in SP: sp_update_participant_total_correct_by_pid.";
							$this->sp_return_code = $p_result_code;
							break ;
						}
					}
					$result->close();
				}
			} while ($db->next_result());
			//$result->free_result();
		}
		else {
			$this->msg="UNEXPECTED SQL ERROR: " .$db->errno. " - " .$db->error.  " please contact site administrator!<br>";
			//use -100 to designate a unhandled MYSQL error
			$this->sp_return_code = -100;
			//echo "SQL ERROR: " .$db->errno. " - " .$db->error.  "<br>";
		}
		
	} /* end function */
	
	public function new_entry($participant_id, $participant_email, $bowl_id, $team_selected, $entry_tiebreaker) {
	
		$sql = 'call sp_new_entry("' .$participant_id. '", "' .$participant_email.'", "' .$bowl_id.'", "' .$team_selected. '", ' .$entry_tiebreaker. ');';
		//$sql = 'call sp_new_entry(26, "bolzinski@test.com", 01, "Texas");';
		/*echo " ------------------------------------- New challenger_log ------------------------------------------------------<br>";
		echo "SQL COMMAND: " .$sql. "<br>";
		echo " ------------------------------------- New challenger_log ------------------------------------------------------<br>";*/
		$this->msg="";
		$p_result_code = 0;
		$db=$this->db;
		if ($db->multi_query($sql)) {
			do {
				if ($result = $db->store_result()) {
					while ($row = $result->fetch_assoc()) {
						$p_result_code = $row['p_return_code'];
						switch ($p_result_code)
						{
						case 106203:
							$this->msg = "Error - Duplicate Entry - entry for Participant ID / Bowl ID - PID: " . $participant_id . " /  BID: " .$bowl_id ;
							$this->sp_return_code = $p_result_code;
							break ;
						case 106202:
							$this->msg = "Error - Entry not added - participant not found";
							$this->sp_return_code = $p_result_code;
							break ;
						case 106201:
							$this->msg = "Error - Entry ID already exists";
							$this->sp_return_code = $p_result_code;
							break ;
						case 0:
						case 1:
							$this->msg = "Entry added successfully!";
							$this->sp_return_code = $p_result_code;
							break ;
						default:
							$this->msg = "UNEXPECTED SQL ERROR: " .$db->errno. " - " .$db->error.  " - resultcode: " . $p_result_code. " please contact site administrator!<br>";
							$this->sp_return_code = $p_result_code;
							break;
						}
					}
					$result->close();
				}
			} while ($db->next_result());
			//$result->free_result();
		}
		else {
			$this->msg="UNEXPECTED SQL ERROR: " .$db->errno. " - " .$db->error.  " please contact site administrator!<br>";
			//use -100 to designate a unhandled MYSQL error
			$this->sp_return_code = -100;
		}
		
	} /* END FUNCTION */
	
	public function get_picks_by_pid($participant_id) {
	
		$sql = 'call sp_get_bowl_picks_by_pid(' .$participant_id. ');';
		//$sql = 'call sp_new_entry(26, "bolzinski@test.com", 01, "Texas");';
		/*echo " ------------------------------------- New challenger_log ------------------------------------------------------<br>";
		echo "SQL COMMAND: " .$sql. "<br>";
		echo " ------------------------------------- New challenger_log ------------------------------------------------------<br>";*/
		$db=$this->db;
		if ($db->multi_query($sql)) {
			do {
				if ($result = $db->store_result()) {
					$i=0;
					while ($row = $result->fetch_array()) {
						$this->result_list[$i] = $row;
						$i++;
					}
					$result->close();
					if ($i==0) {
						//echo "HERE1<br>";
						$this->sp_return_code = -99;
					}
					else {
						//echo "HERE2<br>";
						$this->sp_return_code = 0;
					}
				}
			} while ($db->next_result());
		}
		else {
			$this->msg="UNEXPECTED SQL ERROR: " .$db->errno. " - " .$db->error.  " please contact site administrator!<br>";
			//use -100 to designate a unhandled MYSQL error
			$this->sp_return_code = -100;
			//echo "SQL ERROR: " .$db->errno. " - " .$db->error.  "<br>";
		}
	} /* END FUNCTION */
	
	public function get_all_entries() {
	
		$sql = 'call sp_get_all_entries();';
		//echo " ------------------------------------- Get show List ------------------------------------------------------<br>";
//		echo "SQL COMMAND: " .$sql. "<br>";
//		echo " ------------------------------------- Get show List ------------------------------------------------------<br>";
				
		$db=$this->db;
		if ($db->multi_query($sql)) {
			do {
				if ($result = $db->store_result()) {
					$i=0;
					while ($row = $result->fetch_array()) {
						$this->result_list[$i] = $row;
						$i++;
					}
					$result->close();
					if ($i==0) {
						//echo "HERE1<br>";
						$this->sp_return_code = -99;
					}
					else {
						//echo "HERE2<br>";
						$this->sp_return_code = 0;
					}
				}
			} while ($db->next_result());
		}
		else {
			$this->msg="UNEXPECTED SQL ERROR: " .$db->errno. " - " .$db->error.  " please contact site administrator!<br>";
			//use -100 to designate a unhandled MYSQL error
			$this->sp_return_code = -100;
			//echo "SQL ERROR: " .$db->errno. " - " .$db->error.  "<br>";
		}
			
	} /* END FUNCTION */
	
	public function update_entry_team_correct_by_pid($participant_id) {
	
		$sql = 'call sp_update_entry_team_correct_by_pid(' .$participant_id. ');';
		//echo " ------------------------------------- update user ------------------------------------------------------<br>";
//		echo "SQL COMMAND: " .$sql. "<br>";
//		echo " ------------------------------------- update user ------------------------------------------------------<br>";
		$this->msg="";
		$p_result_code = 0;
		$db=$this->db;
		if ($db->multi_query($sql)) {
			do {
				if ($result = $db->store_result()) {
					while ($row = $result->fetch_assoc()) {
						$p_result_code = $row['p_return_code'];
						switch (true)
						{
						case ($p_result_code==106202):
							$this->msg = "Error - No entries found for participant_id: " . $participant_id;
							$this->sp_return_code = $p_result_code;
							break ;
						case ($p_result_code==106201):
							$this->msg = "Error - Unknown update error on Entry.";
							$this->sp_return_code = $p_result_code;
							break ;
						case ($p_result_code==0): // current password GOOD but NEW password is the same, 0 rows updated
							$this->msg = "Update successful, no rows affected.";
							$this->sp_return_code = $p_result_code;
							break ;
						case (($p_result_code>1) || ($p_result_code<35)): // update returns the number of rows affected, i.e. number picks correct, 35=# of bowl games
							$this->msg = "Update successful, entries updated as correct!";
							$this->sp_return_code = $p_result_code;
							break ;
						default:
							$this->msg = "Error - Unknown Update Error in SP: sp_update_entry_team_correct_by_pid.";
							$this->sp_return_code = $p_result_code;
							break ;
						}
					}
					$result->close();
				}
			} while ($db->next_result());
			//$result->free_result();
		}
		else {
			$this->msg="UNEXPECTED SQL ERROR: " .$db->errno. " - " .$db->error.  " please contact site administrator!<br>";
			//use -100 to designate a unhandled MYSQL error
			$this->sp_return_code = -100;
			//echo "SQL ERROR: " .$db->errno. " - " .$db->error.  "<br>";
		}
		
	} /* end function */
	
	public function get_bowls_correct_count_by_pid($participant_id) {
	
		$sql = 'call sp_get_bowls_correct_count_by_pid(' .$participant_id. ');';
		//echo " ------------------------------------- Get show List ------------------------------------------------------<br>";
//		echo "SQL COMMAND: " .$sql. "<br>";
//		echo " ------------------------------------- Get show List ------------------------------------------------------<br>";
				
		$db=$this->db;
		if ($db->multi_query($sql)) {
			do {
				if ($result = $db->store_result()) {
					$i=0;
					while ($row = $result->fetch_array()) {
						$this->result_list[$i] = $row;
						$i++;
					}
					$result->close();
					if ($i==0) {
						//echo "HERE1<br>";
						$this->sp_return_code = -99;
					}
					else {
						//echo "HERE2<br>";
						$this->sp_return_code = 0;
					}
				}
			} while ($db->next_result());
		}
		else {
			$this->msg="UNEXPECTED SQL ERROR: " .$db->errno. " - " .$db->error.  " please contact site administrator!<br>";
			//use -100 to designate a unhandled MYSQL error
			$this->sp_return_code = -100;
			//echo "SQL ERROR: " .$db->errno. " - " .$db->error.  "<br>";
		}
			
	} /* END FUNCTION */
	
	public function get_bowls() {
	
		//$sql = 'call sp_get_shows_limit("' . $where_value . '", "' . $where_field .  '", "' . $sort_field . '", "' . $limit_start .  '", "' . $limit_max_rows . '");';
		$sql = 'call sp_get_bowls();';
		//echo " ------------------------------------- Get show List ------------------------------------------------------<br>";
//		echo "SQL COMMAND: " .$sql. "<br>";
//		echo " ------------------------------------- Get show List ------------------------------------------------------<br>";
				
		$db=$this->db;
		if ($db->multi_query($sql)) {
			do {
				if ($result = $db->store_result()) {
					$i=0;
					while ($row = $result->fetch_array()) {
						$this->result_list[$i] = $row;
						$i++;
					}
					$result->close();
					if ($i==0) {
						//echo "HERE1<br>";
						$this->sp_return_code = -99;
					}
					else {
						//echo "HERE2<br>";
						$this->sp_return_code = 0;
					}
				}
			} while ($db->next_result());
		}
		else {
			$this->msg="UNEXPECTED SQL ERROR: " .$db->errno. " - " .$db->error.  " please contact site administrator!<br>";
			//use -100 to designate a unhandled MYSQL error
			$this->sp_return_code = -100;
			//echo "SQL ERROR: " .$db->errno. " - " .$db->error.  "<br>";
		}
			
	} /* END FUNCTION */
	
	public function get_bowl_by_teams($team_01, $team_02) {
	
		//$sql = 'call sp_get_shows_limit("' . $where_value . '", "' . $where_field .  '", "' . $sort_field . '", "' . $limit_start .  '", "' . $limit_max_rows . '");';
		$sql = 'call sp_get_bowl_by_teams("' .$team_01. '", "' .$team_02. '");';
		//echo " ------------------------------------- Get show List ------------------------------------------------------<br>";
//		echo "SQL COMMAND: " .$sql. "<br>";
//		echo " ------------------------------------- Get show List ------------------------------------------------------<br>";
				
		$db=$this->db;
		if ($db->multi_query($sql)) {
			do {
				if ($result = $db->store_result()) {
					$i=0;
					while ($row = $result->fetch_array()) {
						$this->result_list[$i] = $row;
						$i++;
					}
					$result->close();
					if ($i==0) {
						//echo "HERE1<br>";
						$this->sp_return_code = -99;
					}
					else {
						//echo "HERE2<br>";
						$this->sp_return_code = 0;
					}
				}
			} while ($db->next_result());
		}
		else {
			$this->msg="UNEXPECTED SQL ERROR: " .$db->errno. " - " .$db->error.  " please contact site administrator!<br>";
			//use -100 to designate a unhandled MYSQL error
			$this->sp_return_code = -100;
			//echo "SQL ERROR: " .$db->errno. " - " .$db->error.  "<br>";
		}
			
	} /* END FUNCTION */
	
	public function get_bcs_champion() {
	
		//$sql = 'call sp_get_shows_limit("' . $where_value . '", "' . $where_field .  '", "' . $sort_field . '", "' . $limit_start .  '", "' . $limit_max_rows . '");';
		$sql = 'call sp_get_bcs_champion();';
		//echo " ------------------------------------- Get show List ------------------------------------------------------<br>";
//		echo "SQL COMMAND: " .$sql. "<br>";
//		echo " ------------------------------------- Get show List ------------------------------------------------------<br>";
				
		$db=$this->db;
		if ($db->multi_query($sql)) {
			do {
				if ($result = $db->store_result()) {
					$i=0;
					while ($row = $result->fetch_array()) {
						$this->result_list[$i] = $row;
						$i++;
					}
					$result->close();
					if ($i==0) {
						//echo "HERE1<br>";
						$this->sp_return_code = -99;
					}
					else {
						//echo "HERE2<br>";
						$this->sp_return_code = 0;
					}
				}
			} while ($db->next_result());
		}
		else {
			$this->msg="UNEXPECTED SQL ERROR: " .$db->errno. " - " .$db->error.  " please contact site administrator!<br>";
			//use -100 to designate a unhandled MYSQL error
			$this->sp_return_code = -100;
			//echo "SQL ERROR: " .$db->errno. " - " .$db->error.  "<br>";
		}
			
	} /* END FUNCTION */
	
	public function update_bowl_winner($bowl_id, $bowl_winner) {
	
		$sql = 'call sp_update_bowl_winner(' .$bowl_id. ', "' .$bowl_winner. '");';
		//echo " ------------------------------------- update user ------------------------------------------------------<br>";
//		echo "SQL COMMAND: " .$sql. "<br>";
//		echo " ------------------------------------- update user ------------------------------------------------------<br>";
		$this->msg="";
		$p_result_code = 0;
		$db=$this->db;
		if ($db->multi_query($sql)) {
			do {
				if ($result = $db->store_result()) {
					while ($row = $result->fetch_assoc()) {
						$p_result_code = $row['p_return_code'];
						switch ($p_result_code)
						{
						case 106202:
							$this->msg = "Error - Winning team entered does not play in this bowl game (bowl_id).";
							$this->sp_return_code = $p_result_code;
							break ;
						case 106201:
							$this->msg = "Error - Unknown Update Error.";
							$this->sp_return_code = $p_result_code;
							break ;
						case 0: // current password GOOD but NEW password is the same, 0 rows updated
							$this->msg = "Update successful, no rows affected.";
							$this->sp_return_code = $p_result_code;
							break ;
						case 1: // password changed successfully!
							$this->msg = "Complete - user added successfully!";
							$this->sp_return_code = $p_result_code;
							break ;
						default:
							$this->msg = "Error - Unknown Update Error.";
							$this->sp_return_code = $p_result_code;
							break ;
						}
					}
					$result->close();
				}
			} while ($db->next_result());
			//$result->free_result();
		}
		else {
			$this->msg="UNEXPECTED SQL ERROR: " .$db->errno. " - " .$db->error.  " please contact site administrator!<br>";
			//use -100 to designate a unhandled MYSQL error
			$this->sp_return_code = -100;
			//echo "SQL ERROR: " .$db->errno. " - " .$db->error.  "<br>";
		}
		
	} /* end function */
		
}  /* end class */
 
?>
