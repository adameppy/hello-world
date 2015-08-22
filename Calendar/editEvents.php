
		<?php
			ini_set("session.cookie_httponly", 1);
			session_start();
			require "database.php";
			$event_ID = (int)$_POST['eventId'];
			$newtime = $mysqli->real_escape_string($_POST['newtime']);
			$newevent = $mysqli->real_escape_string($_POST['newevent']);
			$editdelete = $mysqli->real_escape_string($_POST['editdelete']);
			if ($editdelete == "edit"){
				$stmt = $mysqli->prepare("update events set eventname = ?, time = ? where event_ID = ?");
				if (!$stmt){
					echo "Deleting the comments associated with this story results in an error.";
				}
				$stmt->bind_param('si', $newcomment, $newtime $comment_ID);
				$stmt->execute();
				$stmt->close();
				
				
			}
			else if ($editdelete == "delete"){
			//delete from comments where story_id = this Story
			$stmt = $mysqli->prepare("DELETE FROM events WHERE event_ID=?");
			if (!$stmt){
				echo "Deleting the comments associated with this story results in an error.";
			}
			$stmt->bind_param('i', $event_ID);
			$stmt->execute();
			$stmt->close();
			//delete from stories where story_id = this story
			
			else{
				echo("failed");
			}
			
		?>