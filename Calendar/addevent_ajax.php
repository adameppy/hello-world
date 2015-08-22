<?php
    
require 'database.php'; 

ini_set("session.cookie_httponly", 1);
session_start();
$username = $mysqli->real_escape_string($_SESSION['username']);
$eventname = $mysqli->real_escape_string($_POST['name']);
$month = $mysqli->real_escape_string($_POST['month']);;
$year = $mysqli->real_escape_string($_POST['year']);;
$day = $mysqli->real_escape_string($_POST['day']);
$time = $mysqli->real_escape_string($_POST['time']);
$duration = $mysqli->real_escape_string($_POST['duration']);

$stmt = $mysqli->prepare("insert into events (name, username, year, month, day, startTime, duration) values (?,?,?,?,?,?,?)");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}
$stmt->bind_param('ssiiisi', $eventname, $username, $year, $month, $day, $time, $duration, $category);
$stmt->execute();
$stmt->close();



	echo json_encode(array(
		"success" => true
	));
?>