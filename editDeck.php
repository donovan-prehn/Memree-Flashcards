<?php 
  session_start(); 

  if (!isset($_SESSION['username'])) {
  	$_SESSION['msg'] = "You must log in first";
  	header('location: index.php');
  }
  if (isset($_GET['logout'])) {
  	session_destroy();
  	unset($_SESSION['username']);
  	header("location: index.php");
  }
?>

<?php
	include 'php/db_connection.php';
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "memree_flashcards";
	
	$db = new DbConnection($servername, $username, $password, $dbname);
	$db->connect();
	$conn = $db->getConnection();
?>

<?php
	if (isset($_POST['deleteCardButton'])) {
		
		$cardID = $_POST['deleteCardID']; // Get card ID from hidden input
		
		$stmt = $conn->prepare('DELETE FROM card WHERE cardID=?');
		$stmt->bind_param('i', $cardID);
		
		if ($stmt->execute()) { // If query was successful
			// Display alert box
			// Maybe find a nicer way to do this
			echo '<script language="javascript">';
			echo 'alert("Card deleted successfully")';
			echo '</script>';
		}
		else {
			echo '<script language="javascript">';
			echo 'alert("Delete card failed")';
			echo '</script>';
		}
		
		$stmt->close();

	}
?>

<?php
	// This is called when the "Save Changes" button is clicked on edit card dialog
	if (isset($_POST['editCardButton'])) {
		
		// Card values
		//$userID = $_SESSION['userID']; // Get user ID from session
		//$deckID = $_POST['deckID']; // Get deck ID from hidden input
		$cardID = $_POST['cardID']; // Get card ID from hidden input
		$question = $_POST['editCardQ']; // Get card question from textfield
		$answer = $_POST['editCardA']; // Get card answer from textfield
		$imageNameQ = $_FILES["editImageFileQ"]["tmp_name"]; // Get the path of image file for question
		$imageNameA = $_FILES["editImageFileA"]["tmp_name"]; // Get the path of image file for answer
		
		$null = NULL; // bind_param() requires parameters
		
		if (!$imageNameQ or !$imageNameA) { // No image for question/answer selected
			if ($imageNameQ) { // Image selected for question
				$stmt = $conn->prepare('UPDATE card SET question=?, answer=?, questionImage=? WHERE cardID=?');
				$stmt->bind_param('ssbi', $question, $answer, $null, $cardID);
			
				$stmt->send_long_data(2, file_get_contents($imageNameQ)); // Send blob of question image
			}
			elseif ($imageNameA) { // Image selected for answer
				$stmt = $conn->prepare('UPDATE card SET question=?, answer=?, answerImage=? WHERE cardID=?');
				$stmt->bind_param('ssbi', $question, $answer, $null, $cardID);
			
				$stmt->send_long_data(2, file_get_contents($imageNameA)); // Send blob of question image
			}
			else { // No images selected
				$stmt = $conn->prepare('UPDATE card SET question=?, answer=? WHERE cardID=?');
				$stmt->bind_param('ssi', $question, $answer, $cardID);
			}
		} 
		else { // Images selected for both Q/A
			$stmt = $conn->prepare('UPDATE card SET question=?, answer=?, questionImage=?, answerImage=? WHERE cardID=?');
			$stmt->bind_param('ssbbi', $question, $answer, $null, $null, $cardID);
			
			$stmt->send_long_data(2, file_get_contents($imageNameQ)); // Send blob of question image
			$stmt->send_long_data(3, file_get_contents($imageNameA)); // Send blob of answer image
		}
		
		if ($stmt->execute()) { // If query was successful
			// Display alert box
			// Maybe find a nicer way to do this
			echo '<script language="javascript">';
			echo 'alert("Card edited successfully")';
			echo '</script>';
		}
		else {
			echo '<script language="javascript">';
			echo 'alert("Edit card failed")';
			echo '</script>';
		}
		
		$stmt->close();
	}

?>

<?php
	// This is called when the "Add Card" button is clicked
	if (isset($_POST['addCardButton'])) {
		
		include 'php/db_connection.php';
		
		// Card values
		//$userID = $_SESSION['userID']; // Get user ID from session
		$deckID = $_POST['deckID']; // Get deck ID from hidden input
		$question = $_POST['newCardQ']; // Get card question from textfield
		$answer = $_POST['newCardA']; // Get card answer from textfield
		$imageNameQ = $_FILES["imageFileQ"]["tmp_name"]; // Get the path of image file for question
		$imageNameA = $_FILES["imageFileA"]["tmp_name"]; // Get the path of image file for answer
		
		$null = NULL; // bind_param() requires parameters
		
		if (!$imageNameQ or !$imageNameA) { // No image for question/answer selected
			if ($imageNameQ) { // Image selected for question
				$stmt = $conn->prepare("INSERT INTO card (deckID, question, answer, questionImage) VALUES (?, ?, ?, ?)");
				$stmt->bind_param('issb', $deckID, $question, $answer, $null);
			
				$stmt->send_long_data(3, file_get_contents($imageNameQ)); // Send blob of question image
			}
			elseif ($imageNameA) { // Image selected for answer
				$stmt = $conn->prepare("INSERT INTO card (deckID, question, answer, answerImage) VALUES (?, ?, ?, ?)");
				$stmt->bind_param('issb', $deckID, $question, $answer, $null);
			
				$stmt->send_long_data(3, file_get_contents($imageNameA)); // Send blob of question image
			}
			else { // No images selected
				$stmt = $conn->prepare("INSERT INTO card (deckID, question, answer) VALUES (?, ?, ?)");
				$stmt->bind_param('iss', $deckID, $question, $answer);
			}
		} 
		else { // Images selected for both Q/A
			
			
			$stmt = $conn->prepare("INSERT INTO card (deckID, question, answer, questionImage, answerImage) VALUES (?, ?, ?, ?, ?)");
			$stmt->bind_param('issbb', $deckID, $question, $answer, $null, $null);
			
			$stmt->send_long_data(3, file_get_contents($imageNameQ)); // Send blob of question image
			$stmt->send_long_data(4, file_get_contents($imageNameA)); // Send blob of answer image
		}
		
		if ($stmt->execute()) { // If query was successful
			// Display alert box
			// Maybe find a nicer way to do this
			echo '<script language="javascript">';
			echo 'alert("New card added")';
			echo '</script>';
		}
		else {
			echo '<script language="javascript">';
			echo 'alert("Error occurred when adding card")';
			echo '</script>';
		}
		
		$stmt->close();
	}
?>
		
<?php
	// This is called when the "Update" button for the deck is clicked
	if (isset($_POST['updateDeck'])) {

		// Deck values
		$userID = $_SESSION['userID']; // Get user ID from session
		$deckID = $_POST['deckID']; // Get deck ID from hidden input
		$title = $_POST['deckTitle']; // Get deck title from textfield
		$description = $_POST['deckDescription']; // Get deck description from textfield
		$imageName = $_FILES["imageFile"]["tmp_name"]; // Get the path of image file
		
		$madePublic = isset($_POST['publicCheck']); // Check to see value for the checkbox 
		
		if($madePublic)
			$isPublic = 1;
		else
			$isPublic = 0;
		
		if (!$imageName) { // No image selected, update title/description only
			// Query to update the deck title and description only
			$stmt = $conn->prepare('UPDATE deck SET title=?, description=?, public=? WHERE userID=? and deckID=?');
			$stmt->bind_param('ssiii', $title, $description, $isPublic, $userID, $deckID);
		} 
		else {
			$null = NULL; // bind_param() requires parameters
			// Query to update the deck title, description, and image
			$stmt = $conn->prepare('UPDATE deck SET title=?, description=?, image=?, public=?  WHERE userID=? and deckID=?');
			$stmt->bind_param('ssbiii', $title, $description, $null, $isPublic, $userID, $deckID);
			
			$stmt->send_long_data(2, file_get_contents($imageName)); // Send blob of image
		}
		
		if ($stmt->execute()) { // If query was successful
			// Display alert box
			// Maybe find a nicer way to do this
			echo '<script language="javascript">';
			echo 'alert("Deck updated successfully")';
			echo '</script>';
		}
		else {
			echo '<script language="javascript">';
			echo 'alert("Deck update NOT successful")';
			echo '</script>';
		}
		
		$stmt->close();
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Home</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	 <!-- Bootstrap core CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>
	<div class="container">
	<?php include 'php/nav-bar.php'; ?>
		
		<?php
		
		$userID = $_SESSION['userID']; // Get user ID
		$deckID = $_POST['deckID']; // Get Deck ID from previous page
		$sql = "SELECT * FROM deck WHERE userID='$userID' and deckID='$deckID'"; // Get all fields from selected deck
		$result = mysqli_query($conn, $sql); // Run query
		
		if ($result) { // If query was successful
			$row = $result->fetch_assoc(); // Get the row
			$title = $row['title']; // Retrieve title from row
			$description = $row['description']; // Retrieve descriptoin from row
			$madePublic = $row['public'];	//retreive the value in the public row
			
			$imageBlob = $row['image']; // Retrieve image blob from row
			$image = imagecreatefromstring($imageBlob);  // Create an image object out of the blob
			
			// Process into jpg
			ob_start();
			imagejpeg($image, null, 80);
			$data = ob_get_contents();
			ob_end_clean();			
		}
		else {
			$title = "error";
			$description = "error";
		}
		

		
		?>
		  
		<div class="container">
			<div class="row bg-light px-3 py-3">
				<div class="col-md-auto">
					<h4>Deck Image</h4>
					<img id="deckImage" src=<?php echo "'data:image/jpg;base64,".base64_encode($data)."' "; ?> alt="..." class="img-thumbnail" width="192" height="192">
				</div>
				<div class="col-lg-6">
					<form method="post" enctype="multipart/form-data">
						<div class="form-group">
							<h4>Deck Title</h4>
							<input type="text" name="deckTitle" class="form-control" id="inputDeckTitle" value="<?php echo $title;?>">
						</div>
						<div class="form-group">
							<h4>Deck Description</h4>
							<input type="textarea" name="deckDescription" class="form-control" id="inputDeckTitle" value="<?php echo $description;?>">
						</div>
						Image (Max 2 MB):
						<input type="file" id="imageFile" name="imageFile" onchange="displayChosenImage(this,true,'deckImage')">
						<div id="imageDiv"></div>
						<input id="publicCheck" name="publicCheck" type="checkbox" <?php if($madePublic) echo 'checked'; ?>>
						<label for="publicCheck">Public</label>
						<br>
						<input type="button" id="updateDeck" name="updateDeck" value="Update" class="btn btn-primary" data-target="#updateDeckDialog" data-toggle="modal">
						<input type="button" id="deleteDeck" name="deleteDeck" value="Delete" class="btn btn-secondary" data-target="#deleteDeckDialog" data-toggle="modal">
						<input id="deckID" name="deckID" value="<?php echo $deckID;?>" hidden="true">
					</form>
				</div>
			</div>
		</div>
		
		<script>
		function displayChosenImage(input, displaySize, imgSrcId) {
			var reader = new FileReader();
			
			reader.readAsDataURL(input.files[0]); // Read file
			
			reader.onload = function(e) {
				
				if (displaySize == true) {
					var fileSize = input.files[0].size; // Get size of file
					var sizeString = (fileSize/1024/1024).toFixed(2) + " MB"; // Convert size to MB
					if (fileSize/1024/1024 < 1) { // If MB conversion results in a value lower than 1.0, than it should be displayed in KB
						sizeString = (fileSize/1024).toFixed(2) + " KB";
					}
					document.getElementById("imageDiv").innerHTML = "Size: " + sizeString; // Display size of file
				}
				
				document.getElementById(imgSrcId).src = e.target.result; // Change image of deck
			}
			
		}		
		
		
		// Called when user clicks update deck button
		function updateDeck() {
			document.getElementById("updateDeck").type = "submit"; // Change the form button into submit type for PHP isset requirement
			document.getElementById("updateDeck").click(); // Simulate form submission
		}
		
		// Called when user clicks delete deck button
		function deleteDeck() {
			window.location = "home.php?deckID=" + document.getElementById("deckID").value;
		}
		
		// Called when user clicks add card button on dialog box
		function addCard() {
			document.getElementById("addCardButton").click(); // Simulate form submission
		}
		
		// Called when user clicks "save changes" on the edit card dialog box
		function updateCard() {
			document.getElementById("editCardButton").click(); // Simulate form submission
		}
		
		// Called when user clicks "edit card" button
		function prepareEditCard(cardID) {
			var question = document.getElementById("question"+cardID).innerHTML; // Get question of card to edit
			var answer = document.getElementById("answer"+cardID).innerHTML; // Get answer of card to edit
			var imageQ = document.getElementById("imageQ"+cardID).src; // Get image of question for card
			var imageA = document.getElementById("imageA"+cardID).src; // Get image of answer for card
			
			document.getElementById("editCardQ").value = question;
			document.getElementById("editCardA").value = answer;
			document.getElementById("editCardImageQ").src = imageQ;
			document.getElementById("editCardImageA").src = imageA;
			document.getElementById("cardID").value = cardID;
			$('#editCardDialog').modal('show');
		}
		
		// Called when user clicks "Delete Card" button
		function prepareDeleteCard(cardID) {
			document.getElementById("deleteCardID").value = cardID;
			$('#deleteCardDialog').modal('show');
		}
		
		// Called when "Yes" is clicked on delete card dialog
		function deleteCard() {
			document.getElementById("deleteCardButton").click(); // Simulate form submission
		}
		</script>
		
		
		<!-- HR between deck and cards -->
		<hr class="mt-5"/>
		
		<!-- Card list container -->
		<div class="container pb-3">
			<h1>Cards
				<!-- Button to add a new card -->
				<input class='btn btn-primary' type='button' value='Add New Card' data-target='#addCardDialog' data-toggle='modal'/>
			</h1>

			<!-- Call PHP file that displays the list of cards from the deck -->
			<?php include "php/displayCards.php"; ?>
		</div>
		
	</div>
	
	<!-------------------------------------------------------------------------------
	// * updateDeckDialog Modal
	-------------------------------------------------------------------------------->
	<div class="modal fade" id="updateDeckDialog" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="exampleModalLabel">Update Deck?</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
		  <div class="modal-body">
			Are you sure you want to update the deck information?
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
			<button type="button" class="btn btn-primary" onclick="updateDeck()">Save changes</button>
		  </div>
		</div>
	  </div>
	</div>
	
	<!-------------------------------------------------------------------------------
	// * deleteDeckDialog Modal
	-------------------------------------------------------------------------------->
	<div class="modal fade" id="deleteDeckDialog" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="exampleModalLabel">Delete Deck?</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
		  <div class="modal-body">
			Are you sure you want to delete this deck?
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
			<button type="button" class="btn btn-primary" onclick="deleteDeck()">Delete</button>
		  </div>
		</div>
	  </div>
	</div>
	
	<!-------------------------------------------------------------------------------
	// * addCardDialog Modal
	-------------------------------------------------------------------------------->
	<div class="modal fade" id="addCardDialog" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="exampleModalLabel">Add New Card</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
		  <div class="modal-body">
		  <form method="post" enctype="multipart/form-data">
			<table>
				<tr>
					<td width="50%"><img name="newCardImageQ" id="newCardImageQ" class="card-img-top" src="icon.png" alt="Card image cap"></td>
					<td width="50%"><img name="newCardImageA" id="newCardImageA" class="card-img-top" src="icon.png" alt="Card image cap"></td>
				</tr>
			</table>
			Question: <input name="imageFileQ" type="file" onchange="displayChosenImage(this,false,'newCardImageQ')"/>
			<p>
			Answer:&nbsp;&nbsp;&nbsp;&nbsp;<input name="imageFileA" type="file" onchange="displayChosenImage(this,false,'newCardImageA')"/>
			<input class="form-control form-control-lg mb-2" type="text" name="newCardQ" placeholder="Question">
			<input class="form-control form-control-lg mb-2" type="text" name="newCardA" placeholder="Answer">
			<input type="submit" id="addCardButton" name="addCardButton" hidden="true">
			<input name="deckID" value="<?php echo $deckID;?>" hidden="true">
		  </form>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
			<button type="button" class="btn btn-primary" onclick="addCard()">Add Card</button>
		  </div>
		</div>
	  </div>
	</div>
	
	<!-------------------------------------------------------------------------------
	// * editCardDialog Modal
	-------------------------------------------------------------------------------->
	<div class="modal fade" id="editCardDialog" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="exampleModalLabel">Edit Card</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
		  <div class="modal-body">
		  <form method="post" enctype="multipart/form-data">
			<table>
				<tr>
					<td width="50%"><img name="editCardImageQ" id="editCardImageQ" class="card-img-top" src="icon.png" alt="Card image cap"></td>
					<td width="50%"><img name="editCardImageA" id="editCardImageA" class="card-img-top" src="icon.png" alt="Card image cap"></td>
				</tr>
			</table>
			Question: <input name="editImageFileQ" type="file" onchange="displayChosenImage(this,false,'editCardImageQ')"/>
			<p>
			Answer:&nbsp;&nbsp;&nbsp;&nbsp;<input name="editImageFileA" type="file" onchange="displayChosenImage(this,false,'editCardImageA')"/>
			<input class="form-control form-control-lg mb-2" type="text" name="editCardQ" id="editCardQ" >
			<input class="form-control form-control-lg mb-2" type="text" name="editCardA" id="editCardA">
			<input type="submit" id="editCardButton" name="editCardButton" hidden="true">
			<input name="deckID" value="<?php echo $deckID;?>" hidden="true">
			<input name="cardID" id="cardID" hidden="true">
		  </form>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
			<button type="button" class="btn btn-primary" onclick="updateCard()">Save Changes</button>
		  </div>
		</div>
	  </div>
	</div>
	
	<!-------------------------------------------------------------------------------
	// * deleteCardDialog Modal
	-------------------------------------------------------------------------------->
	<div class="modal fade" id="deleteCardDialog" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="exampleModalLabel">Delete Card?</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
		  <div class="modal-body">
			Are you sure you want to delete this card?
			<form method="post">
				<input name="deckID" value="<?php echo $deckID;?>" hidden="true">
				<input id="deleteCardID" name="deleteCardID" hidden="true"/>
				<input type="submit" id="deleteCardButton" name="deleteCardButton" hidden="true"/>
			</form>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
			<button type="button" class="btn btn-primary" onclick="deleteCard()">Yes</button>
		  </div>
		</div>
	  </div>
	</div>
	
	<?php
		$conn->close();
	?>
	
	<!-- Bootstrap -->
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>