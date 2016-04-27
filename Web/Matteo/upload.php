<?php

require_once('includes/dbconnect.php');

if (ini_get('magic_quotes_gpc')) {
	foreach ($_GET as $k => $v) {
		$_GET[$k] = stripslashes($v);
	}
	foreach ($_POST as $k => $v) {
		$_POST[$k] = stripslashes($v);
	}
}
//
function sanitise_filename($filename) {
	return basename(preg_replace("/[^0-9a-z._-]/i", "", $filename));
}

if (isset($_POST['submit'])) {
		// Require Secret Key
		require_once('includes/secret.php');

		// Get Values
		$location = $_POST['location'];
		$count = $_POST['count'];
    // FabFive!
    $rawtimestamp = $_POST['rawtimestamp'];

  		// Prepare INSERT QUERY
		$sqlq = "INSERT INTO `data` (`timestamp`,`locationID`,`event`) VALUES (UNIX_TIMESTAMP(:rawtimestamp),:location,:count)";
		$q = $DBH->prepare($sqlq);
		$q->execute(array(':rawtimestamp' => $rawtimestamp, ':count' => $count, ':location' => $location));

		// Error Handling
		if (!$q) {
			echo "Error: can't insert into database. ".$q->errorCode();
			exit;
		}
		else {
			echo "Count: " .$count;
			echo "</br>";
			echo "Location: " . $location;
			echo "</br>";
			echo "Raw Timestamp: " . $rawtimestamp;
			exit;
		}
}
elseif (isset($_GET['get'])){
	if (isset($_GET['interval']) && intval($_GET['interval'])) {
		$interval = intval($_GET['interval']);
	}
	else {
		// Default interval of 1 day
		$interval = 86400;
	}
	/*-----------FabFive!--------------------*/
	// Read all the rows from 00.00 from the DB (to count people in)
  	$query_tot = "
		SELECT * FROM `data` 
		WHERE UNIX_TIMESTAMP(timestamp) >= UNIX_TIMESTAMP(CURDATE()+' 00:00:00')
		ORDER BY timestamp ASC
		";
	$get = $DBH->prepare($query_tot);
	$get->execute();

	if (!$get) {
		echo "Error: couldn't execute query for totals. ".$get->errorCode();
		exit;
	}

	if ($get->fetchColumn() == 0) {
		echo "[]";
		exit;
	}
	$rows = array();

	$totalin = 0; // Count how many people go IN (only positive events)
	$currenttotal = 0; // Count all people go IN and OUT (all the events til now)
	$flag = 0;

	while ($row = $get->fetch(PDO::FETCH_ASSOC)) {
		if ($row['event'] > 0) {
			$totalin += $row['event'];
		}
		$currenttotal = +$row['event'];
		if (($flag == 0) && ($row['event'] >= (time() - $interval))) {
			$partial_currenttotal = $currenttotal;
			$flag = 1;
		}
	}

	/*--------------Data for Chart----------------*/
	// Read all the rows from 00.00 from the DB (to count people in)
	if($interval == 86400) {
		$query = "
		SELECT sum(event) AS window_tot, substring(timestamp,12,2) AS window FROM `data` 
        WHERE UNIX_TIMESTAMP(timestamp) >= UNIX_TIMESTAMP(CURDATE()+' 00:00:00')
        GROUP BY substring(timestamp,12,2)
		";
	}
	else {
		$query = "
			SELECT sum(event) AS window_tot, substring(timestamp,15,1) AS window FROM `data` 
			WHERE UNIX_TIMESTAMP(timestamp) >= UNIX_TIMESTAMP()-3600
			GROUP BY substring(timestamp,15,1)
			";
	}

	$get = $DBH->prepare($query);
	$get->execute();

	if (!$get) {
		echo "Error: couldn't execute query for data chart generator. ".$get->errorCode();
		exit;
	}

	if ($get->fetchColumn() == 0) {
		echo "[]";
		exit;
	}
	$rows = array();

	if($interval == 86400) {
		$starttime = date("Y-m-d 00:00:00");
		while ($row = $get->fetch(PDO::FETCH_ASSOC)) {
			$partial_currenttotal += $row['window_tot'];
			$data_db = array("timekey" => $row['window'], "total" => $partial_currenttotal);
		}

		for($dt=strtotime($starttime), 
	}
/***************************************************************/

	//total == current total


	//date("Y-m-d H:i:s",$t);



	//echo json_encode($rows); // do we need it?
}
?>
