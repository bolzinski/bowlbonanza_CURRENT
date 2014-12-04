<?
class validator_helper
{
	
	// constructor not implemented
	public function __construct(){}
	 
	// validate integer
	public static function validate_int($value)
	{
		return filter_var($value, FILTER_VALIDATE_INT);
	}
	
	// validate integer with range
	public static function validate_int_range($value, $min, $max)
	{
		return filter_var($value, FILTER_VALIDATE_INT, array('options' => array('min_range' => $min, 'max_range' => $max)));
	}
	 
	// validate float number, value must be greater than 0
	public static function validate_float($value)
	{
		if (filter_var($value, FILTER_VALIDATE_FLOAT) > 0)   {
			return $value;
		}
		else {
			return FALSE;
		}
	}
	 
	// validate alphabetic value
	public static function validate_alpha($value)
	{
		return filter_var($value, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => "/^[a-zA-Z \/\#\&\.\,\'\-\!\?]+$/")));
	}
	// validate alphabetic value allow null
	public static function validate_alpha_allow_null($value)
	{
		if (!(empty($value))) {
			return filter_var($value, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => "/^[a-zA-Z \/\#\&\.\,\'\-\!\?]+$/")));
		}
		else { /* NULL is valid value for this field */
			return "";
		}
	}	 
	// validate alphanumeric value
	public static function validate_alphanum($value)
	{
		return filter_var($value, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => "/^[a-zA-Z0-9  \/\#\&\.\,\'\-\!\?]+$/")));
	} 
	
	// validate alphanumeric value
	public static function validate_alphanum_strlen($value, $string_length)
	{
		if (strlen(trim($value)) == $string_length) {
			return filter_var($value, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => "/^[a-zA-Z0-9  \&\.\,\'\-\!\?]+$/")));
		}
		else {
			return 0;
		}
	} 
	
	// validate alphanumeric value
	public static function validate_alphanum_allow_slash($value)
	{
		return filter_var($value, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => "/^[a-zA-Z0-9  \/\.\'\-\!\?]+$/")));
	} 
	
	// validate alphanumeric value
	public static function validate_alphanum_textarea($value, $min_length, $max_length)
	{
		$value = trim($value);
		//echo "value" . $value . "<br>";
		// empty user name is not valid
		if ((strlen($value) >= $min_length) && (strlen($value) <= $max_length) ) {
			//return filter_var($value, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => "/^[a-zA-Z0-9  \/\.\'\-\!\?\@\#\$\%\&\*\(\)\+\=\,\;\:]+$/")));
			return 1; /* valid  */
		}
		else {
			return 0; /* invalid characters */
		}
	} 
		
	// validate alphanumeric value, some special chars allowed, apostrophe, comma, period
	public static function validate_alphanum_allow_null($value)
	{
		if (!(empty($value))) {
			//echo "Biography 1: " . $value . "<BR>";
			//return filter_var($value, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => "/^[a-zA-Z0-9 \.\,\'\!]+$/" )));
			return filter_var($value, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => "/^[\S ]+$/" )));
		}
		else { /* NULL is valid value for this field */
			//echo "Biography 2: " . $value . "<BR>";
			return "";
		}
	}
	
	// validate URL
	public static function validate_url($url)
	{
		return filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED);
	}
	 
	// validate IP address
	public static function validate_ip($ip)
	{
		return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
	}
	 
	// validate email address
	public static function validate_email($email)
	{
		return filter_var($email, FILTER_VALIDATE_EMAIL);
	}
	
	// validate using regexp
	public static function validate_order_number($value, $pattern="")
	{
		//order_number=mmv + 5 character alphanum + 5 digits
		return filter_var($value, FILTER_VALIDATE_REGEXP, array('options' =>array('regexp' => "/^mmv[0-9a-zA-Z]{5}[1-9][0-9]{4}$/")));
		
	}
	
	// validate using regexp
	public static function validate_custom_regexp($value, $pattern="")
	{
		return filter_var($value, FILTER_VALIDATE_REGEXP, array('options' =>array('regexp' => $pattern)));
		//return filter_var($value, FILTER_VALIDATE_REGEXP, array('options' =>array('regexp' => "/^[0-9][0-9][0-9]?[0-9]$/")));
		
	}
	
	public static function validate_custom($value, $pattern="")
	{
		//return filter_var($value, FILTER_VALIDATE_REGEXP, array('options' =>array('regexp' => $pattern)));
		return filter_var($value, FILTER_VALIDATE_REGEXP, array('options' =>array('regexp' => "/^[a-z,A-Z][0-9]$/")));
		//  "/^[a-z,A-Z][0-9]/$"
		//return filter_var($value, FILTER_VALIDATE_REGEXP, array('options' =>array('regexp' => "/^[a-zA-Z0-9]+$/")));
		
	}
	
	// validate using regexp
	public static function validate_custom2($value, $pattern)
	{
		//return filter_var($value, FILTER_VALIDATE_REGEXP, array('options' =>array('regexp' => $pattern)));
		return filter_var($value, FILTER_VALIDATE_REGEXP, array('options' =>array('regexp' => "/^[0-9]?[0-9]?:?[0-9]?[0-9]:[0-9][0-9]$/")));
		//  "/^[a-z,A-Z][0-9]/$"
		//return filter_var($value, FILTER_VALIDATE_REGEXP, array('options' =>array('regexp' => "/^[a-zA-Z0-9]+$/")));
		
	}
	
	//sanitize functions
	public static function sanitize_string($value)
	{	
		return filter_var($value, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	}
	
	public static function sanitize_string_with_options($value, $options)
	{
		return filter_var($value, FILTER_SANITIZE_STRING, $options);
	}
	
	public static function validate_array_entry($array_target, $array_value)
	{
		return array_search($array_value, $array_target);
	}
}
?>