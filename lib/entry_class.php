<?

class entry {
	
	// explicitly adding class properties are optional - but is good practice
	public $participant_id, $participant_email, $bowl_id, $team_selected;
	public $msg;
	public $sp_return_code;
	public $result_list;
	public $user_count;
	private $db;
	
	//use this constructor to create a new artist class
	function __construct() {
		
		//make connection
		//require("link_mysqli2_class.php");
		require_once("/volumes/content/secure/link_BB_mysqli2_class.php");
	
		$this->msg="Success!";
		$this->result_list = array();
		$this->sp_return_code=0;
	}
	
	//use destructor to close DB connection if it hasn't been done yet
	function __destruct() {
			
		$this->db->close();
	}
	
	public function new_entry($participant_id, $participant_email, $bowl_id, $team_selected) {
	
		$sql = 'call sp_new_entry("' .$participant_id. '", "' .$participant_email.'", "' .$bowl_id.'", "' .$team_selected. '");';
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
							$this->msg = "Error - Duplicate Entry - entry for Participant ID / Bowl ID";
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
	
	
		
}  /* end class */
 
?>
