<?php
	header("Content-Type: application/json");
	session_start();
	$event_Id = (int)$_POST['event_id'];
	require 'database.php';
	
	$stmt = $mysqli->prepare("select month, day, year, name, time from events where event_id=?");
	if (!$stmt){
		printf("Finding stories failed. Please try again later");
		exit;
	}
	$stmt->bind_param('i', $event_id);
	$stmt->execute();
	
	$stmt->bind_result($month, $day, $year, $eventname, $time);
	
	$jsonstring = "";
	//doing same thing as json_encode, just making string ourselves
	while($stmt->fetch()){
		$jsonstring = '{"name": "'.$eventname.'", "day": "'.$day.'", "month": "'.$month.'", "year": "'.$year.'", "time": "'.$time.'"}';
		$i = $i+1;
	}
	
	
	echo $jsonstring;
?>