<?php
  // DB parameters for JoomlaHosting
	$db_name = "uc6weaoq_cSHou";
  $db_user = "uc6weaoq_cSHou";
	$user_passwd= "pCKmyi31";
	$db_host = "mysqlhost";
  // --------------------------------
	try {
		$DBH = new PDO('mysql:host='.$db_host.';dbname='.$db_name.,$db_user,$user_passwd);
	} catch (PDOException $e) {
		echo $e->getMessage();
		exit;
	}
?>