<?php
    ini_set("session.cookie_httponly", 1);
    if(session_status()==PHP_SESSION_ACTIVE){
        $username = $mysqli->real_escape_string($_POST['username']);
        echo json_encode(
            array(
                "success" => true,
                "username" => "$username"
            )
        );
	exit;
    } else {
        echo json_encode(
            array(
		"success" => false,
		"message" => "Not Logged In"
            )
        );
        exit;
    };
?>