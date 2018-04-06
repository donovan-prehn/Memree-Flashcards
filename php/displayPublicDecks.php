			<?php include 'php/deck.php'; ?>
			
			<?php // Displaying decks
			
			//$userID = $_SESSION['userID']; // Get user ID
			
			$stmt = $conn->prepare('SELECT * FROM deck WHERE public="1"');
			//$stmt->bind_param('i', $userID);
			
			$stmt->execute(); // Run query
			$result = $stmt->get_result(); // Get the results of running the query
			
			if ($result->num_rows == 0) { // If there are no public decks
				echo "<h3>There are no public decks yet.</h3>";
			}
			else { // 1 or more public decks
				while($row = $result->fetch_assoc()) { // Go through each row and fetch deck data
				$userID = $row['userID']; // Retrieve deck title
				$title = $row['title']; // Retrieve deck title
				$description = $row['description']; // Retrieve deck description
				$deckID = $row['deckID']; // Retrieve deck ID			
				$imageBlob = $row['image']; // Retrieve deck image blob
				
				
				$query = "SELECT * FROM users WHERE userID='$userID'";
				$usernameResult = $conn->query($query);

				$usernameRow = $usernameResult->fetch_assoc();
				$username = $usernameRow['username'];
			
				$deck = new Deck($deckID, $title, $description, $imageBlob, $userID, "1");
				$deck->displayPublicDeck($username);

			}
			}
			
			$stmt->close();
			?>