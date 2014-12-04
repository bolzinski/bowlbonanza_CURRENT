<?php

	print "server name: " . $_SERVER["SERVER_NAME"] . "<br />";
	
	foreach ($_POST as $key => $entry)
	{
		 
		 
			print $key . ": " . $entry . "<br>";
	}
	
?>