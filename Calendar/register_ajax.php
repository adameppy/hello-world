<?php
// register_ajax.php
 
header("Content-Type: application/json"); // Since we are sending a JSON response here (not an HTML document), set the MIME Type to application/json

//$username = $mysqli->real_escape_string($_POST['username']);
//$password = $mysqli->real_escape_string($_POST['password']);
$username = (string)$_POST['username'];
$password = (string)$_POST['password'];

require 'database.php';

$stmt = $mysqli->prepare("select username from users where username = ?");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}
$stmt->bind_param('s', $username);
$stmt->execute();
//$stmt->close();
$stmt->bind_result($othername);
$stmt->fetch();
$stmt->close();

if($username == $othername){
	printf("Username already Exists");
	exit;
}

$stmt = $mysqli->prepare("insert into users (username, password) values (?,?)");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}
 
$stmt->bind_param('ss', $username, crypt($password));
$stmt->execute();
 
$stmt->close();

ini_set("session.cookie_httponly", 1);
session_start();
$_SESSION['username'] = $username;
$_SESSION['token'] = substr(md5(rand()), 0, 10);
 
echo json_encode(array(
	"success" => true
));
exit;

?>