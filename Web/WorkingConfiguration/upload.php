<?php
header('Access-Control-Allow-Origin: *');

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
    $rawtimestamp = $_POST['rawtimestamp'];
    
    // Prepare INSERT QUERY
	$sqlq = "INSERT INTO `data` (`timestamp`,`locationID`,`event`) VALUES (UNIX_TIMESTAMP(:rawtimestamp),:location,:count)";  // FabFive!
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
elseif (isset($_GET['get'])) {
	// Get interval
	if (isset($_GET['interval']) && intval($_GET['interval'])) {
		$interval = intval($_GET['interval']);
	}
	else {
		// Default interval of 1 hour
		$interval = 3600;
	}
	// Get calendar start
	if (isset($_GET['start'])) {
		$start = $_GET['start'];
	}
	else {
		// Default start date (today)
		$start = date("Y-m-d");
	}
	// Get calendar end
	if (isset($_GET['end'])) {
		$end = $_GET['end'];
//		if(strtotime($end) < strtotime($start)) {
//			$end = $start;
//		}
	}
	else {
		// Default end date (today)
		$end = date("Y-m-d");
	}
	/***************** FabFive! **************************/
	/* We can modify just WHERE such that we produce a list of values on the days window that we choose */
	$query = "
				SELECT  FROM_UNIXTIME(FLOOR(UNIX_TIMESTAMP(timestamp)/".$interval.")*".$interval.") AS timekey,
				SUM(event) as movement,
				SUM(IF(event > 0, event, 0)) as peoplein,
				SUM(IF(event < 0, ABS(event), 0)) as peopleout
				FROM     data				
                WHERE DATE(timestamp) BETWEEN \"".$start."\" AND \"".$end."\"
				GROUP BY timekey
				ORDER BY timekey ASC
			";

	/* We can modify just WHERE such that we produce a list of values on the days and time window that we choose
	is sufficient to retrieve day and time intervals from data picker as the $interval and insert them in the query*/
	/*
	$query = "
				SELECT  FROM_UNIXTIME(FLOOR(UNIX_TIMESTAMP(timestamp)/600)*600) AS timekey,
				SUM(event) as movement,
				SUM(IF(event >0, event, 0)) as peoplein,
				SUM(IF(event < 0, ABS(event), 0)) as peopleout
				FROM     data
                WHERE (DATE(timestamp) BETWEEN "2016-04-26" AND "2016-04-27") AND
                (HOUR(timestamp) BETWEEN "13" AND "20")
				GROUP BY timekey
				ORDER BY timekey ASC
			";
	*/
	$get = $DBH->prepare($query);
	$get->execute();

	if (!$get) {
		echo "Error: couldn't execute query. ".$get->errorCode();
		exit;
	}

	$rows = array();
	$runningtotal = 0;
	$runningtotalin = 0;
	$rowCount = 0;
	$totalin = 0;

	while ($row = $get->fetch(PDO::FETCH_ASSOC)) {
//		echo "I'm fetching!!! #: ".$rowCount;
		$runningtotal += $row['movement'];
		$row['total'] = $runningtotal;
		$totalin += $row['peoplein'];
		$row['totalin'] = $totalin;
		$rows[$row['timekey']] = $row;
		if ($rowCount == 0) {
			$starttime = strtotime($row['timekey']);
		}
		$rowCount++;
	}
	$endtime = time() - (time() % $interval);

//	echo "starttime: ".$starttime;
//	echo "endtime: ".$endtime;

	if ($rowCount == 0) {
		echo "[]"; //no data in the DB (answer to query NULL)
		exit;
	}

	if(!($endtime >= $starttime)) {
		$endtime = $starttime; //check to avoid endtime < starttime and avoid fuck up the for loop
	}

	for ($t = $starttime; $t <= $endtime; $t += $interval) {
		$dt = date("Y-m-d H:i:s",$t);
		if (!isset($rows[$dt])) {
			$rows[$dt] = array("timekey" => $dt, "movement" => 0, "peoplein" => 0, "peopleout" => 0, "total" => $runningtotal, "totalin" => $runningtotalin);
		}
		else {
			$runningtotal = $rows[$dt]['total'];
			$runningtotalin = $rows[$dt]['totalin'];
		}
	}
	ksort($rows); // sort
	$rows = array_values($rows); // change back into indexed

	echo json_encode($rows);
}
?>
