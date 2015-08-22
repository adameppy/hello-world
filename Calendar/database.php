	<?php
		$mysqli = new mysqli('localhost', 'root', 'our3boyS!', 'Module5database');
		if($mysqli->connect_errno) {
		printf("Connection Failed: %s\n", $mysqli->connect_error);
		exit;
		}
	?>
