<?php
  // DB parameters for JoomlaHosting
	$dbname = "uc6weaoq_cSHou";
	$passkey= "pCKmyi31";
	$host = "mysqlhost";
  // --------------------------------
	try {
		$DBH = new PDO('mysql:host=mysqlhost;dbname=uc6weaoq_cSHou',$dbname,$passkey);
	} catch (PDOException $e) {
		echo $e->getMessage();
		exit;
	}
?>