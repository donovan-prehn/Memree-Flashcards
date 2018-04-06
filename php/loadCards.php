<style>
<?php include 'css/main.css'; ?>
</style>

<?php
	
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
		
		// Create new card
		$card = new Card($deck->getDeckID(), $cardID, $question, $answer, "icon.png", "icon.png");		
		
		if ($imageBlobQ != null) { // Image was selected for question
			$imageQ = imagecreatefromstring($imageBlobQ);  // Create an image object out of the blob
			// Process into jpg
			ob_start();
			imagejpeg($imageQ, null, 80);
			$data = ob_get_contents();
			ob_end_clean();
			
			$imageStringQ = "data:image/jpg;base64,".base64_encode($data); // String to put into <img src=...
			$card->setQuestionImage($imageStringQ);
		}
		if ($imageBlobA != null) { // Image was selected for answer
			$imageA = imagecreatefromstring($imageBlobA);  // Create an image object out of the blob
			// Process into jpg
			ob_start();
			imagejpeg($imageA, null, 80);
			$data = ob_get_contents();
			ob_end_clean();
			
			$imageStringA = "data:image/jpg;base64,".base64_encode($data); // String to put into <img src=...
			$card->setAnswerImage($imageStringA);
		}
		
		$deck->addCard($card); // Add card to deck		
					
	}

	$stmt->close();
				
?>