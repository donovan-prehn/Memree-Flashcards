<style>
<?php include 'css/main.css'; ?>
</style>

<?php
	include 'php/db_connection.php';
	
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
					<button type='button' class='close' aria-label='Close' style='position:absolute; right:0px; top:-10px;' onclick='prepareDeleteCard($cardID)'>
							  <span aria-hidden='true'><font color='red' size='8'>&times;</font></span>
							</button>
					<table>
						<tr>
							<td width='50%'><img height='141' id='imageQ$cardID' src='$imageStringQ' alt='Card image cap'></td>
							<td width='50%'><img height='141' id='imageA$cardID' src='$imageStringA' alt='Card image cap'></td>
						</tr>
					</table>
						<div class='card-body'>
							<!--<input class='form-control form-control-lg mb-2' type='text' placeholder='$question'>-->
							<b>Q: </b>
							<p class='text' id='question$cardID'>$question</p>
							<hr>
							<!--<input class='form-control form-control-lg' type='text' placeholder='$answer'>-->
							<b>A: </b>
							<p class = 'text' id='answer$cardID'>$answer</p>
						</div>
						<div class='card-footer text-center'>
							<input type='button' class='btn btn-primary' value='Edit Card' onclick='prepareEditCard($cardID)'>
							<input type='button' class='btn btn-secondary' value='Delete Card' onclick='prepareDeleteCard($cardID)'>
						</div>
				</div>";
		
					
	}

	$stmt->close();
	$conn->close();
				
?>