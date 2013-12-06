<?php
	//declare our assets 
	$name = stripcslashes($_POST['name']);
	$emailAddr = stripcslashes($_POST['email']);
	$comment = stripcslashes($_POST['message']);
	$subject = stripcslashes($_POST['subject']);	
	$contactMessage =  
		"                Message:
		$comment 

		Namn: $name
		E-mail: $emailAddr

		IP: $_SERVER[REMOTE_ADDR]
		Sending Script: $_SERVER[HTTP_HOST]$_SERVER[PHP_SELF]";
		
		//send the email 
		mail('bjorn.ax@mailkonto.nu', $subject, $contactMessage);
		echo('success'); //return success callback
?>