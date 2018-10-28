<?php
	/* 
	 * This page creates the functional
	 * interface for storing session data
	 * in a database.
	 * This page also start the session.
	 */
	 // Global variable used for the database
	 // connections in all session functions:
	 $sdbc = NULL;
	 
	// Define the open_session() function:
	// This function takes no arguments.
	// This function should open the database connect.
	// Otherwise this function  can expects two parameters:
	//where the first is the save path and the second is the session name. 
	 function open_session(){
		global $sdbc;
		$sdbc = mysqli_connect("localhost","root",'root',"ljf64") OR die('Cannot connect to the database.');
		return true;
	 }

	 //Define the clos_session() function:
	 // This function takes no arguments.
	 // This function closes the database connection.
	 function close_session(){
		global $sdbc;
		return mysqli_close($sdbc);
	 }

	//Define the read_session() function:
	// This function takes one arguments: the SESSION_ID.
	// This function retrieves the session data.
	function read_session($sid){
		global $sdbc;

		// Query the dadabase:
		$q = sprintf('SELECT data FROM Jackin_sessions WHERE id="%s"',mysqli_real_escape_string($sdbc,$sid));
		$r = mysqli_query($sdbc, $q);
		if(mysqli_num_rows($r) == 1){
			list($data) = mysqli_fetch_array($r,MYSQLI_NUM);
			return $data;
		}else{
			return '';
		}
	}

	//Define the write_session() function:
	// This function takes two arguments:
	//		the session ID and the session data.
	function write_session($sid, $sdata){
		global $sdbc;
		
		//Store in the database:
		$q = sprintf('REPLACE INTO Jackin_sessions(id,data) VALUES("%s","%s")',mysqli_real_escape_string($sdbc, $sid),mysqli_real_escape_string($sdbc, $sdata));

		$r = mysqli_query($sdbc, $q);
		return mysqli_affected_rows($sdbc);
	}

	// Define the destroy_session() function:
	// This function takes one argument: the session ID.
	function destroy_session($sid){
		global $sdbc;

		//Delete from the database:
		$q = sprintf('DELETE FROM Jackin_sessions WHERE id="%s"',mysqli_real_escape_string($sdbc, $sid));

		$r = mysqli_query($sdbc,$q);

		//Clear the $_SESSION array
		$_SESSION=array();

		return mysqli_affected_rows($sdbc);
	}

	// Define the clean_session() function:
	// This function takes one argument: a value in seconds.
	function clean_session($expire){
		global $sdbc;

		//Delete old sessions:
		$q = sprintf('DELETE FROM Jackin_sessions WHERE DATE_ADD(last_accessed,INTERVAL %d SECOND) < NOW()',(int) $expire);

		$r = mysqli_query($sdbc, $q);

		return mysqli_affected_rows($sdbc);
	}

	# ************************** #
	# **** END OF FUNCTIONS **** #
	# ************************** #

	// Declare the function to use:
	session_set_save_handler('open_session', 'close_session', 'read_session', 'write_session', 'destroy_session', 'clean_session');

	// Make whatever other changes to the session settings.

	//Start the session:
	session_start();
?>