<?php
// load error handler and database configuration
 
require_once("validator_helper_class.php");

// Class supports AJAX and PHP web form validation 
class Validate
{
  // stored database connection
  private $mMysqli;
  
  public $month_array = array("0" => "[Select]",
                      "1" => "01",
                      "2" => "02",
                      "3" => "03",
                      "4" => "04",
                      "5" => "05",
                      "6" => "06",
                      "7" => "07",
                      "8" => "08",
                      "9" => "09",
                      "10" => "10",
                      "11" => "11",
                      "12" => "12");
					  
	// initialize year array
	public $year_array = array("0" => "[Select]",
                      "1" => "2010",
                      "2" => "2011",
                      "3" => "2012",
                      "4" => "2013",
                      "5" => "2014",
                      "6" => "2015",
                      "7" => "2016",
                      "8" => "2017",
                      "9" => "2018",
                      "10" => "2019",
                      "11" => "2020",
                      "12" => "2021");
  
  // constructor opens database connection
  function __construct()
  {
    
  }

  // destructor closes database connection
  function __destruct()
  {
    
  }
    

  
  /* contact_us form validation */
  
 
  // validates all form fields on form submit
  public function ValidatePHP_contact()
  {
    // error flag, becomes 1 when errors are found.
    $errorsExist = 0;
    
        
    if (!$this->validate_name($_POST['ce_name']))
    {
		//print "error name: " .$_POST['ce_name']. "<br>";
     	$errorsExist = 1;
    }
    if (!$this->validate_email($_POST['ce_email']))
    {
		//print "error email: " .$_POST['ce_email']. "<br>";
		$errorsExist = 1;
    }
	else { /* email addresses must be valid and the same! */
		if ($_POST['ce_email'] != $_POST['ce_email2']) {
			//print "error email2: " .$_POST['ce_email2']. "<br>";
			$errorsExist = 1;
		}
	}			
	if (!$this->validate_name($_POST['ce_subject']))
    {
		//print "error subject: " .$_POST['ce_subject']. "<br>";
		$errorsExist = 1;
    }    
	if (!$this->validate_comments($_POST['ce_details'], 1, 1000))
    {
		//print "error details: " .$_POST['ce_details']. "<br>";
		$errorsExist = 1;
    }    
	/*//save current input to Session vars
	foreach ($_POST as $key => $value)
	{
		$_SESSION['values_cf'][$key] = $_POST[$key];
	}*/
    
	return $errorsExist;
	
  } /* end function */

 public function ValidatePHP_login()
  {
    // error flag, becomes 1 when errors are found.
    $errorsExist = 0;
    
/*    if (!$this->validate_email($_POST['lf_email']))
    {
		//print "error email: " .$_POST['lf_email']. "<br>";
		$errorsExist = 1;
    }		*/
	if (!$this->validate_password($_POST['lf_password'], 4, 20))
    {
		//print "error password: " .$_POST['lf_password']. "<br>";
		$errorsExist = 1;
    }       
	//save current input to Session vars
	foreach ($_POST as $key => $value)
	{
		$_SESSION['values_lf'][$key] = $_POST[$key];
	}
	$_SESSION['values_lf']['lf_password'] = '';
    
	return $errorsExist;
	
  } /* end function */
  
   // validates all form fields on form submit
  public function ValidatePHP_cc_detail()
  {
    // error flag, becomes 1 when errors are found.
    $errorsExist = 0;
    
        
    if (!$this->validate_contact_id($_GET['cid']))
    {
		//print "error name: " .$_POST['ce_name']. "<br>";
     	$errorsExist = 1;
    }
        
	return $errorsExist;
	
  } /* end function */

/* start validation functions */
	// validate password - not restricting any characters just min/max length
	private function validate_password($value, $min_length, $max_length)
	{
		/* this field is not saved to the DB only echo'd back in an email */
		/* therefore decided to limit validation to length and sanitize value */
		/* before inserting in email */
		// trim and escape input value    
		$value = trim($value);
		//echo "value" . $value . "<br>";
		// empty user name is not valid
		if ($value) {
			$p_field_valid = validator_helper::validate_alphanum_textarea($value, $min_length, $max_length);
			//since the validator function returns FALSE if invalid or the value if valid,
			//it's safer to check explicitly for not FALSE)
			if (!$p_field_valid === FALSE) {
				//echo "p_field_valid: " . $p_field_valid . "<br>";
				return 1; // valid
			}
			else {
				return 0; /* invalid characters */
			}
		}
		else {
		  return 0; /* field empty */
		}
	}
  // validate name
	private function validate_name($value)
	{
		// trim and escape input value    
		$value = trim($value);
		// empty user name is not valid
		if ($value) {
			//CHANGED FROM validate_alpha to allow periods, such as A. K. Oliver
			$p_field_valid = validator_helper::validate_alphanum_allow_slash($value);
			//since the validator function returns FALSE if invalid or the value if valid,
			//it's safer to check explicitly for not FALSE)
			if (!$p_field_valid === FALSE) {
				//echo "p_field_valid: " . $p_field_valid . "<br>";
				return 1; // valid
			}
			else {
				return 0; /* invalid characters */
			}
		}
		else {
		  return 0; /* field empty */
		}
	
	} /* end function */

	private function validate_address($value)
	{
		// trim and escape input value    
		$value = trim($value);
		// empty user name is not valid
		if ($value) {
			$p_field_valid = validator_helper::validate_alphanum($value);
			//since the validator function returns FALSE if invalid or the value if valid,
			//it's safer to check explicitly for not FALSE)
			if (!$p_field_valid === FALSE) {
				//echo "p_field_valid: " . $p_field_valid . "<br>";
				return 1; // valid
			}
			else {
				return 0; /* invalid characters */
			}
		}
		else {
		  return 0; /* field empty */
		}
	}
	
	private function validate_address_2($value)
	{
		// trim and escape input value    
		$value = trim($value);
		// empty user name is not valid
		if ($value) {
			$p_field_valid = validator_helper::validate_alphanum($value);
			//since the validator function returns FALSE if invalid or the value if valid,
			//it's safer to check explicitly for not FALSE)
			if (!$p_field_valid === FALSE) {
				//echo "p_field_valid: " . $p_field_valid . "<br>";
				return 1; // valid
			}
			else {
				return 0; /* invalid characters */
			}
		}
		else {
		  return 1; /* field empty */
		}
	}	

	private function validate_city($value)
 	{
    	$value = trim($value);
		// empty user name is not valid
		if ($value) {
			$p_field_valid = validator_helper::validate_alpha($value);
			//since the validator function returns FALSE if invalid or the value if valid,
			//it's safer to check explicitly for not FALSE)
			if (!$p_field_valid === FALSE) {
				//echo "p_field_valid: " . $p_field_valid . "<br>";
				return 1; // valid
			}
			else {
				return 0; /* invalid characters */
			}
		}
		else {
		  return 0; /* field empty */
		}
  	} 
	
	/* don't trust listbox value since POST value can be changed intransit */
	/* 0=no selection, and is INVALID */
	private function validate_state($value)
 	{
    	$value = trim($value);
		// empty user name is not valid
		if ($value) {
			$p_field_valid = validator_helper::validate_int_range($value, 1, 51);
			//echo "p_field_valid: " . $p_field_valid . "<br>";
			//since the validator function returns FALSE if invalid or the value if valid,
			//it's safer to check explicitly for not FALSE)
			if (!$p_field_valid === FALSE) {
				//echo "p_field_valid: " . $p_field_valid . "<br>";
				return 1; // valid
			}
			else {
				return 0; /* invalid characters */
			}
		}
		else {
		  return 0; /* field empty */
		}
  	} 
  	
	/* don't trust listbox value since POST value can be changed intransit */
	/* 0=no selection, and is INVALID */
	private function validate_country($value)
 	{
    	$value = trim($value);
		// empty user name is not valid
		if ($value) {
			$p_field_valid = validator_helper::validate_int_range($value, 1, 197);
			//echo "p_field_valid: " . $p_field_valid . "<br>";
			//since the validator function returns FALSE if invalid or the value if valid,
			//it's safer to check explicitly for not FALSE)
			if (!$p_field_valid === FALSE) {
				//echo "p_field_valid: " . $p_field_valid . "<br>";
				return 1; // valid
			}
			else {
				return 0; /* invalid characters */
			}
		}
		else {
		  return 0; /* field empty */
		}
  	}   	
 	
	private function validate_email($value)
	{
		// trim and escape input value    
		$value = trim($value);
		// empty user name is not valid
		if ($value) {
			$p_field_valid = validator_helper::validate_email($value);
			//since the validator function returns FALSE if invalid or the value if valid,
			//it's safer to check explicitly for not FALSE)
			if (!$p_field_valid === FALSE) {
				//echo "p_field_valid: " . $p_field_valid . "<br>";
				return 1; // valid
			}
			else {
				return 0; /* invalid characters */
			}
		}
		else {
		  return 0; /* field empty */
		}
	
	} /* end function */
	
	
	private function validate_email2($value, $value2)
 	{
    	return 1;
  	}  /* end function */
	
	private function validate_phone($value)
 	{
    	// valid phone format: ###-###-#### 
    	return (!eregi('^[0-9]{3}-*[0-9]{3}-*[0-9]{4}$', $value)) ? 0 : 1;
  	}
	
	private function validate_contact_id($value)
 	{
    	$value = trim($value);
		if ($value) {
			$p_field_valid = validator_helper::validate_int_range($value, 1, 1000);
			if (!$p_field_valid === FALSE) {
				return 1; // valid
			}
			else {
				return 0; /* invalid characters */
			}
		}
		else {
		  return 0; /* field empty */
		}
  	}  /* end function */
	
	private function validate_creditcard_type($value)
 	{
    	$value = trim($value);
		if ($value) {
			$p_field_valid = validator_helper::validate_int_range($value, 1, 3);
			if (!$p_field_valid === FALSE) {
				return 1; // valid
			}
			else {
				return 0; /* invalid characters */
			}
		}
		else {
		  return 0; /* field empty */
		}
  	} 
	
	private function validate_creditcard($value)
 	{
    	$value = trim($value);
		// empty user name is not valid
		if ($value) {
			$p_field_valid = validator_helper::validate_int($value);
			//echo "p_field_valid: " . $p_field_valid . "<br>";
			//since the validator function returns FALSE if invalid or the value if valid,
			//it's safer to check explicitly for not FALSE)
			if (!$p_field_valid === FALSE) {
				//echo "p_field_valid: " . $p_field_valid . "<br>";
				return 1; // valid
			}
			else {
				return 0; /* invalid characters */
			}
		}
		else {
		  return 0; /* field empty */
		}
  	}  
 	
	private function validate_month($value)
	{
	// month must be non-null, and between 1 and 12  
		return ($value == '' || $value > 12 || $value < 1) ? 0 : 1;
	} 
	
	private function validate_year($value)
	{
	// year must be non-null, and between 2010 and 2021  
		return ($value == '' || $value > 12 || $value < 1) ? 0 : 1;
	}
	
	//only called if month and year are valid
	private function validate_expire_date($value)
 	{
    	$current_month=date("m");
		$current_year=date("Y"); 
		$current_date=$current_year.$current_month;
		$expire_date=$value;
			
		if ($expire_date >= $current_date) {
			return 1; // valid expire date
		}
		else {
			return 0; /* card expired */
		}
		
  	}  
	
	private function validate_cvn($value)
 	{
    	$value = trim($value);
		// empty user name is not valid
		if ($value) {
			$p_field_valid = validator_helper::validate_custom_regexp($value, "/^[0-9][0-9][0-9]?[0-9]$/");
			//since the validator function returns FALSE if invalid or the value if valid,
			//it's safer to check explicitly for not FALSE)
			if (!$p_field_valid === FALSE) {
				//echo "p_field_valid: " . $p_field_valid . "<br>";
				return 1; // valid
			}
			else {
				return 0; /* invalid characters */
			}
		}
		else {
		  return 0; /* field empty */
		}
  	}  
 	
	// check the user has read the terms of use
	private function validate_read_terms($value)
	{
		// valid value is 'true'
		return ($value == 'true' || $value == 'on') ? 1 : 0;
	}
	
	private function validate_comments($value, $min_length, $max_length)
	{
		/* this field is not saved to the DB only echo'd back in an email */
		/* therefore decided to limit validation to length and sanitize value */
		/* before inserting in email */
		// trim and escape input value    
		$value = trim($value);
		//echo "value" . $value . "<br>";
		// empty user name is not valid
		if ($value) {
			$p_field_valid = validator_helper::validate_alphanum_textarea($value, $min_length, $max_length);
			//since the validator function returns FALSE if invalid or the value if valid,
			//it's safer to check explicitly for not FALSE)
			if (!$p_field_valid === FALSE) {
				//echo "p_field_valid: " . $p_field_valid . "<br>";
				return 1; // valid
			}
			else {
				return 0; /* invalid characters */
			}
		}
		else {
		  return 0; /* field empty */
		}
	}
	
	private function validate_isrc($value)
	{
		// empty user name is not valid
		if (strlen(trim($value)) == 12) {
			return 1; /* valid */
		}
		else {
			return 0; /* invalid */
		}
	} /* end function */

} /* end class */
?>
