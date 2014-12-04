<?
$this->db = new mysqli('localhost', 'mmv_web', '4qtr=$','mudbrickmedia'); 
if (mysqli_connect_errno()) { 
	$this->msg="Connect failed: " . mysqli_connect_error() . "<br>";
	$this->sp_return_code = -100; 
	exit; 
} 
?>