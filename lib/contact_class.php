<?

class contact {
	
	// explicitly adding class properties are optional - but is good practice
	public $contact_id, $last_name,	$first_name,$address1,$address2,$city,$state,$postal_code,$phone_mobile,$phone_work,$country,$phone_home,$email,$position;
	public $msg;
	public $sp_return_code;
	public $result_list;
	public $contact_count;
	private $db;
	
	//use this constructor to create a new artist class
	function __construct() {
		
		//make connection
		//require("link_mysqli2_class.php");
		require_once("/volumes/content/secure/link_mysqli2_class.php");
	
		$this->msg="Success!";
		$this->result_list = array();
		$this->sp_return_code=0;
	}
	
	//use destructor to close DB connection if it hasn't been done yet
	function __destruct() {
			
		$this->db->close();
	}
	
	public function get_contact_all() {
	
		$sql = 'call sp_get_contact_all();';
				
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
						$this->sp_return_code = -99;
					}
					else {
						$this->sp_return_code = 0;
					}
				}
			} while ($db->next_result());
		}
		else {
			$this->msg="UNEXPECTED SQL ERROR: " .$db->errno. " - " .$db->error.  " please contact site administrator!<br>";
			//use -100 to designate a unhandled MYSQL error
			$this->sp_return_code = -100;
		}
			
	} /* end function */
	public function get_contact_by_id($contact_id) {
	
		$sql = 'call sp_get_contact_by_id("' .$contact_id. '");';
				
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
						$this->sp_return_code = -99;
					}
					else {
						$this->sp_return_code = 0;
					}
				}
			} while ($db->next_result());
		}
		else {
			$this->msg="UNEXPECTED SQL ERROR: " .$db->errno. " - " .$db->error.  " please contact site administrator!<br>";
			//use -100 to designate a unhandled MYSQL error
			$this->sp_return_code = -100;
		}
			
	} /* end function */
	
	/****** not complete ********/
	public function new_contact($email, $pwd) {
	
		$sql = 'call sp_new_contact("' .$email. '", "' .$pwd. '");';
		//echo " ------------------------------------- New contact ------------------------------------------------------<br>";
//		echo "SQL COMMAND: " .$sql. "<br>";
//		echo " ------------------------------------- New contact ------------------------------------------------------<br>";
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
						case 106201:
							$this->msg = "Error - contact cannot be added, a contact with this contact_id already exists!";
							$this->sp_return_code = $p_result_code;
							break ;
						case 106202:
							$this->msg = "Error - contact cannot be added, a contact with this email already exists!";
							$this->sp_return_code = $p_result_code;
							break ;
						case 1: /* expect "ONE" = number of rows added */
							$this->msg = "Complete - contact added successfully!";
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
	
	/****** not complete ********/
	public function update_password($email, $pwd, $new_pwd) {
	
		$sql = 'call sp_update_contact("' .$email. '", "' .$pwd. '", "' .$new_pwd. '");';
		//echo " ------------------------------------- update contact ------------------------------------------------------<br>";
//		echo "SQL COMMAND: " .$sql. "<br>";
//		echo " ------------------------------------- update contact ------------------------------------------------------<br>";
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
						case -99:
							$this->msg = "Error - contact cannot be added, a contact with this contact_id already exists!";
							$this->sp_return_code = $p_result_code;
							break ;
						case 0: // current password GOOD but NEW password is the same, 0 rows updated
							$this->msg = "New password same as old password.";
							$this->sp_return_code = $p_result_code;
							break ;
						case 1: // password changed successfully!
							$this->msg = "Complete - contact added successfully!";
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
	
	public function check_contact($email, $pwd) {
	
		$sql = 'call sp_check_contact("' .$email. '", "' .$pwd. '");';
/*		echo " ------------------------------------- check contact ------------------------------------------------------<br>";
		echo "SQL COMMAND: " .$sql. "<br>";
		echo " ------------------------------------- check contact ------------------------------------------------------<br>";
*/		$this->msg="";
		$p_result_code = 0;
		$db=$this->db;
		if ($db->multi_query($sql)) {
			do {
				if ($result = $db->store_result()) {
					while ($row = $result->fetch_assoc()) {
						$p_result_code = $row['p_return_code'];
						switch ($p_result_code)
						{
						case -99:
							$this->msg = "Login failed.  Email or password is incorrect";
							$this->sp_return_code = $p_result_code;
							break ;
						case 0:
						case 1: // password changed successfully!
							$this->msg = "Login Successful";
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
