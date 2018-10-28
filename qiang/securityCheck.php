<?php
	session_start();
	function securityCheck(){
		if (isset($_SESSION['admin']) && isset($_SESSION['online']) && $_SESSION['online']) {
			return true;
		}else{
			return false;
		}
	}