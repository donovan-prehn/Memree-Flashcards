<?php
	// Database values
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "memree_flashcards";
	
	// Create connection
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	
	// Check connection
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
	}
	
	//$userID = $_SESSION['userID']; // Get user ID
	$deckID = $_POST['deckID']; // Get Deck ID from previous page
	$sql = "SELECT * FROM card WHERE deckID='$deckID'"; // Get all fields from selected card
	$result = mysqli_query($conn, $sql); // Run query
	
	while($row = $result->fetch_assoc()) {
		$question = $row['question']; // Retrieve question from row
		$answer = $row['answer']; // Retrieve answer from row
		
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
					<img class='card-img-top' src='$imageStringQ' alt='Card image cap'>
					<img class='card-img-top' src='$imageStringA' alt='Card image cap'>
						<div class='card-body'>
							<input class='form-control form-control-lg mb-2' type='text' placeholder='$question'>
							<input class='form-control form-control-lg' type='text' placeholder='$answer'>
						</div>
				</div>";
		
					
	}
	// This is the card with 'Add New Card' button
	echo "	<div class='card' style='width: 18rem;display: inline-block;'>
				<div class='card-body' align='center'>
					<input class='btn btn-primary' type='button' value='Add New Card' data-target='#addCardDialog' data-toggle='modal'/>
				</div>
			</div>";
	
	mysqli_close($conn);
				
?>