<?php
	header("Content-Type: application/json");
	
	ini_set("session.cookie_httponly", 1);
	session_start();
	$username = $_SESSION['username'];
	require 'database.php';
	$other = $mysqli->real_escape_string($_POST['otheruser']);
	
	$stmt = $mysqli->prepare("select day, month, year, name, startTime, duration from events where username = ?");
	if (!$stmt){
		printf("Finding stories failed. Please try again later");
		exit;
	}
	$stmt->bind_param('s', $username);
	$stmt->execute();
	
	$stmt->bind_result($day, $month, $year, $eventname, $time, $duration);
	
	$i = 0;
	$aday = array();
	$amonth = array();
	$ayear = array();
	$aeventname = array();
	$atime = array();
	$aduration = array();
	
	while($stmt->fetch()){
		$aday[$i] = $day;
		$amonth[$i] = $month;
		$ayear[$i] = $year;
		$aeventname[$i] = $eventname;
		$atime[$i] = $time;
		$aduration[$i] = $duration;
		$i = $i+1;
	}
	$stmt->close();
	foreach($aday as $i => $value) {
		$bday = $aday[$i];
		$bmonth = $amonth[$i];
		$byear = $ayear[$i];
		$beventname = $aeventname[$i];
		$btime = $atime[$i];
		$bduration = $aduration[$i];
		$stmt2 = $mysqli->prepare("insert into events (username, day, month, year, name, startTime, duration) values (?,?,?,?,?,?,?)");
		if (!$stmt2){
			printf("Finding stories failed. Please try again later");
			exit;
		}
		$stmt2->bind_param('siiisss', $other, $bday, $bmonth, $byear, $beventname, $btime, $bduration);
		$stmt2->execute();
		$stmt2->close();
	}
	

	echo json_encode(array(
		"success" => true
	));

?>