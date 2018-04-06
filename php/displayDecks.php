<?php include 'php/deck.php'; ?>
<?php // Displaying decks
			
			$db = new DbConnection($servername, $username, $password, $dbname);
			$db->connect();
			$conn = $db->getConnection();
			
			$userID = $_SESSION['userID']; // Get user ID
			
			$query = 'SELECT * FROM deck WHERE userID=?';
			$types = 'i';
			$parameters = $userID;
			$result = $db->runQuery($query, $types, $parameters);
			
			if ($result->num_rows == 0) { // If user does not have any decks
				echo "<h3>You have no decks yet.</h3>";
			}
			else { // User has 1 or more decks
				while($row = $result->fetch_assoc()) { // Go through each row and fetch deck data
					$title = $row['title']; // Retrieve deck title
					$description = $row['description']; // Retrieve deck description
					$deckID = $row['deckID']; // Retrieve deck ID
					
					$imageBlob = $row['image']; // Retrieve deck image blob
					$isPublic  = $row['public'];
					
					$deck = new Deck($deckID, $title, $description, $imageBlob, $userID, $isPublic);
					$deck->displayDeck();
				}
			}
			?>