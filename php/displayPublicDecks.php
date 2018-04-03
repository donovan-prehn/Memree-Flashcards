			<?php // Displaying decks
			
			include 'php/db_connection.php';
			
			//$userID = $_SESSION['userID']; // Get user ID
			
			$stmt = $conn->prepare('SELECT * FROM deck WHERE public="1"');
			//$stmt->bind_param('i', $userID);
			
			$stmt->execute(); // Run query
			$result = $stmt->get_result(); // Get the results of running the query
			
			if ($result->num_rows == 0) { // If user does not have any decks
				echo "<h3>You have no decks yet.</h3>";
			}
			else { // User has 1 or more decks
				while($row = $result->fetch_assoc()) { // Go through each row and fetch deck data
				$title = $row['title']; // Retrieve deck title
				$description = $row['description']; // Retrieve deck description
				$deckID = $row['deckID']; // Retrieve deck ID
				
				$imageBlob = $row['image']; // Retrieve deck image blob
				$image = imagecreatefromstring($imageBlob); // Create an image object out of the blob
				// Process into jpg
				ob_start();
				imagejpeg($image, null, 80);
				$data = ob_get_contents();
				ob_end_clean();
				
				// Display the deck in a Bootstrap card class format
				echo '	<div class="card mx-2 my-2" style="width: 18rem;display: inline-block;">
							<img class="card-img-top" height="277px" width="200px" src="data:image/jpg;base64,' .  base64_encode($data)  . '" alt="Card image cap">
							<div class="card-body">
							  <h5 class="deckTitle" style="color: black;">'.$title.'</h5>
							  <p class="cardDescription" style="color: black;">'.$description.'</p>
							</div>
							<div class="card-footer  text-center">	
								<form action="study.php" method="get">
									<input name="deckID" value="'.$deckID.'" hidden="true"/>
									<input class="btn btn-primary" type="submit" value="Study Deck">
								</form>
								<p>
								<form action="editDeck.php" method="post" style="display: inline-block;">
									<input name="deckID" value="'.$deckID.'" hidden="true"/>
									<input class="btn btn-primary" type="submit" value="Edit Deck">
								</form>
								<form action="" method="post" style="display: inline-block;">
									<input name="deckID" value="'.$deckID.'" hidden="true"/>
									<input type="submit" id="deleteDeckSubmit'.$deckID.'" hidden="true">
									<input class="btn btn-secondary" type="button" onclick="showConfirmDialog('.$deckID.')" value="Delete Deck">
								</form>
							  
							  
							</div>
						</div>';
			}
			}
			
			$stmt->close();
			$conn->close();
			?>