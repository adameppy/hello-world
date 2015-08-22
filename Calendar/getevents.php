<?php
	header("Content-Type: application/json");
	
	ini_set("session.cookie_httponly", 1);
	session_start();
	$username = $_SESSION['username'];
	$month = $_POST['month'];
	$year = $_POST['year'];
	require 'database.php';
	//$stmt = $mysqli->prepare("select * from events");
	$stmt = $mysqli->prepare("select name, startTime, day, duration, event_id from events where username=? and month=? and year=?");
	if (!$stmt){
		printf("Finding events failed. Please try again later");
		exit;
	}
	$stmt->bind_param('sss', $username, $month, $year);
	$stmt->execute();
	
	$stmt->bind_result($eventname, $time, $day, $duration, $id);
	

	$jsonstring = '';
	$i = 0;
	
	//doing same thing as json_encode, just making string ourselves
	while($stmt->fetch()){
		if ($i==0){
			$jsonstring = '"event'.$i.'": {"name": "'.$eventname.'", "day": "'.$day.'", "year": "'.$year.'", "time": "'.$time.'", "duration": "'.$duration.'", "event_id": "'.$id.'"}';
			//$jsonstring = '{"name": "'.$eventname.'", "day": "'.$day.'", "year": "'.$year.'", "time": "'.$time.'"}';
		} else {
		$jsonstring = $jsonstring. ', "event'.$i.'": {"name": "'.$eventname.'", "day": "'.$day.'", "year": "'.$year.'", "time": "'.$time.'", "duration": "'.$duration.'", "event_id": "'.$id.'"}';
		//$jsonstring = $jsonstring. ', {"name": "'.$eventname.'", "day": "'.$day.'", "year": "'.$year.'", "time": "'.$time.'"}, ';
		}
		$i = $i+1;
	};
	$jsonstring = '{ "numberOfEvents": "'.$i.'", ' . $jsonstring. '}';
	
	//$jsonstring = '['.$jsonstring.']';
	echo ($jsonstring);

	$stmt->close();
	exit;
	/*
	$i = 0;
	$arr = "";
	
	//http://uk3.php.net/array_values <-Good Reference for making an array of arrays for JSON
	
	$string_to_return = "";
	while($stmt->fetch()){
		if ($i==0){
			$string_to_return = $string_to_return.json_encode(array('name' => $eventname, 'day' => $day, 'year' => $year, 'time' => $time));
		} else {
			$string_to_return = $string_to_return.json_encode(array('name' => $eventname, 'day' => $day, 'year' => $year, 'time' => $time)).',';
		}
		$i=$i+1;
		$string_to_return = $string_to_return.json_encode(array('name' => $eventname, 'day' => $day, 'year' => $year, 'time' => $time)).',';
		//$arr = $arr.array('name' => $eventname, 'day' => $day, 'year' => $year, 'time' => $time);
	}
	$string_to_return = json_encode(array_values($string_to_return));
	echo $string_to_return;
	//echo json_encode($arr);
	
	$stmt->close();
	exit;
	*/
	/*
	[
		{
			nameOfEvent0:
			etc
		}
		{
			nameOfEvent1;
		}
	]
	*/
?>