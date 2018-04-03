<?php
	// Database values
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "memree_flashcards";
	
	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	
	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	
	//$userID = $_SESSION['userID']; // Get user ID
	$deckID = $_POST['deckID']; // Get Deck ID from previous page
	
	$stmt = $conn->prepare('SELECT * FROM card WHERE deckID=?'); // Get all fields from selected card
	$stmt->bind_param('i', $deckID);
	
	$stmt->execute(); // Run query
	
	$result = $stmt->get_result(); // Get the results of running the query
	while($row = $result->fetch_assoc()) {
		$question = $row['question']; // Retrieve question from row
		$answer = $row['answer']; // Retrieve answer from row
		$cardID = $row['cardID']; // Retrieve cardID for editing
		
		$imageBlobQ = $row['questionImage']; // Retrieve image blob of question
		$imageBlobA = $row['answerImage']; // Retrieve image blob of answer
		
		$imageStringQ = "icon.png"; // Default value if no image was selected
		$imageStringA = "icon.png"; // Default value if no image was selected
		if ($imageBlobQ != null) { // Image was selected for question
			$imageQ = imagecreatefromstring($imageBlobQ);  // Create an image object out of the blob
			// Process into jpg
			ob_start();
			imagejpeg($imageQ, null, 80);
			$data = ob_get_contents();
			ob_end_clean();
			
			$imageStringQ = "data:image/jpg;base64,".base64_encode($data); // String to put into <img src=...
		}
		if ($imageBlobA != null) { // Image was selected for answer
			$imageA = imagecreatefromstring($imageBlobA);  // Create an image object out of the blob
			// Process into jpg
			ob_start();
			imagejpeg($imageA, null, 80);
			$data = ob_get_contents();
			ob_end_clean();
			
			$imageStringA = "data:image/jpg;base64,".base64_encode($data); // String to put into <img src=...
		}
		
		// Display each card
		echo "	<div class='card' style='width: 18rem; display: inline-block;'>
					<table>
						<tr>
							<td width='50%'><img id='imageQ$cardID' class='card-img-top' src='$imageStringQ' alt='Card image cap'></td>
							<td width='50%'><img id='imageA$cardID' class='card-img-top' src='$imageStringA' alt='Card image cap'></td>
						</tr>
					</table>
						<div class='card-body'>
							<!--<input class='form-control form-control-lg mb-2' type='text' placeholder='$question'>-->
							<b>Q: </b>
							<p class='card-text' id='question$cardID'>$question</p><p>
							<!--<input class='form-control form-control-lg' type='text' placeholder='$answer'>-->
							<b>A: </b>
							<p class='card-text' id='answer$cardID'>$answer</p>
						</div>
						<div class='card-footer text-center'>
							<input type='button' class='btn btn-primary' value='Edit Card' onclick='prepareEditCard($cardID)'>
							<input type='button' class='btn btn-secondary' value='Delete Card' onclick='prepareDeleteCard($cardID)'>
						</div>
				</div>";
		
					
	}
	// This is the card with 'Add New Card' button
	echo "	<div class='card' style='width: 18rem;display: inline-block;'>
				<div class='card-body'>
					<br><br><br><br><br><br><br><br><br>
				</div>
				<div class='card-footer text-center'>
					<input class='btn btn-primary' type='button' value='Add New Card' data-target='#addCardDialog' data-toggle='modal'/>
				</div>
			</div>";
	
	$stmt->close();
	$conn->close();
				
?>