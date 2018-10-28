<?php
	if(isset("userid"))
	{
		session_start();
		echo $_SESSION['userid'];
	}
	else
	{
		echo "รปำะตวยฝฃก"
	}
?>