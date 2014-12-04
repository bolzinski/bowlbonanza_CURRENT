<?

class bowl_schedule {
	
	// explicitly adding class properties are optional - but is good practice
	public $user_id, $email, $pwd;
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
			
	}
		
}  /* end class */
 
?>
