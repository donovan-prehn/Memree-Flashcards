			<?php // Displaying decks
			
			//$userID = $_SESSION['userID']; // Get user ID
			
			$stmt = $conn->prepare('SELECT * FROM deck WHERE public="1"');
			//$stmt->bind_param('i', $userID);
			
			$stmt->execute(); // Run query
			$result = $stmt->get_result(); // Get the results of running the query
			
			if ($result->num_rows == 0) { // If user does not have any decks
				echo "<h3>There are no public decks yet.</h3>";
			}
			else { // User has 1 or more decks
				while($row = $result->fetch_assoc()) { // Go through each row and fetch deck data
				$userID = $row['userID']; // Retrieve deck title
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
				
				
				//$username = $userID;
				$query = "SELECT * FROM users WHERE userID='$userID'";
				$usernameResult = $conn->query($query);

				$usernameRow = $usernameResult->fetch_assoc();
				$username = $usernameRow['username'];
			
				//$query->bind_param('i', $userID);
				//$query->execute(); // Run query
				//$usernameResult = $query->get_result(); // Get the results of running the query
				
				
				// Display the deck in a Bootstrap card class format
				echo '	<div class="card mx-2 my-2" style="width: 18rem;display: inline-block;">
							<img class="card-img-top" height="277px" width="200px" src="data:image/jpg;base64,' .  base64_encode($data)  . '" alt="Card image cap">
							<div class="card-body">
							  <h5 class="deckTitle" style="color: black;">'.$title.'</h5>
							  <p class="cardDescription" style="color: black;">'.$description.'</p>
							</div>
							<div class="card-footer  text-center">	
								<form action="study.php" method="get">
									<h5 class="deckTitle" style="color: black;">Owner: '.$username.'</h5>
									<input name="deckID" value="'.$deckID.'" hidden="true"/>
									<input class="btn btn-primary" type="submit" value="Study Deck">
								</form>
			
							  
							</div>
						</div>';
			}
			}
			
			$stmt->close();
			?>