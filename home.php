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

<!DOCTYPE html>
<html>
<head>
	<title>Home</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
	 <!-- Bootstrap core CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>
	<div class="container">
	
		<?php include 'nav-bar.php'; ?>
	  
		<div class="content">
		
			<!-- notification message -->
			<?php if (isset($_SESSION['success'])) : ?>
			  <div class="error success" >
				<h3>
				  <?php 
					echo $_SESSION['success']; 
					unset($_SESSION['success']);
				  ?>
				</h3>
			  </div>
			<?php endif ?>
			
			<!-- createDeck -->
			<?php
				// This is called when user submits information to create a deck
				if (isset($_POST['createDeckButton'])) {
					
					include 'php/db_connection.php';
					
					// Deck values
					$userID = $_SESSION['userID']; // Get user ID from session
					$title = $_POST['deckTitle']; // Get deck title from textfield
					$description = $_POST['deckDescription']; // Get deck description from textfield
					$imageName = $_FILES["imageFile"]["tmp_name"]; // Get the path of image file
					$null = NULL; // bind_param() requires parameters
					
					if (!$imageName) { // No image selected, use default image
						$imageName = "icon.png";
					} 
					// Query to insert new deck
					$stmt = $conn->prepare('INSERT INTO deck (title, description, image, userID) VALUES (?, ?, ?, ?)');
					$stmt->bind_param('ssbi', $title, $description, $null, $userID);
					
					$stmt->send_long_data(2, file_get_contents($imageName)); // Send blob of image
					
					if ($stmt->execute()) { // If query was successful
						// Display alert box
						// Maybe find a nicer way to do this
						echo '<script language="javascript">';
						echo 'alert("New deck created.")';
						echo '</script>';
					}
					else {
						echo '<script language="javascript">';
						echo 'alert("Deck creation NOT successful")';
						echo '</script>';
					}
					
					$stmt->close();
					$conn->close();
				}
			?>
			
			<!-- deleteDeck -->
			<?php
				// This is called when the user wants to delete deck (either from home.php or editDeck.php)
				if (isset($_GET['deckID']) or isset($_POST['deckID'])) {

					include 'php/db_connection.php';
					
					$userID = $_SESSION['userID']; // Get user ID from session
					if (isset($_GET['deckID'])) {
						$deckID = $_GET['deckID']; // Get deck ID from $_GET (editDeck.php delete button)
					}
					else {
						$deckID = $_POST['deckID']; // Get deck ID from $_POST (home.php delete button)
					}
					
					
					$stmt = $conn->prepare('SELECT userID FROM deck WHERE deckID=?');
					$stmt->bind_param('i', $deckID);
					
					$stmt->execute(); // Run query
					$result = $stmt->get_result(); // Get the results of running the query
					
					if ($row = $result->fetch_assoc()) { // If there is a result from query, i.e., the deckID exists
						if ($row['userID'] == $userID) { // User ID matches (can only delete a deck you own)
							$stmt = $conn->prepare('DELETE FROM card WHERE deckID=?');
							$stmt->bind_param('i', $deckID);
							$stmt->execute();
							
							$stmt = $conn->prepare('DELETE FROM deck WHERE deckID=?');
							$stmt->bind_param('i', $deckID);
							$stmt->execute();
						}
						else { // User ID does not match, i.e., trying to delete someone else's deck
							echo "<script>";
							echo "window.alert('Error: Must be owner of deck to delete it.');";
							echo "</script>";
						}
					}
					else { // Deck doesn't exist
						echo "<script>";
						echo "window.alert('Error: Deck does not exist.');";
						echo "</script>";
					}
					
					$stmt->close();
					$conn->close();
				}
			?>
			
			<input type="button" value="Create Deck" class="btn btn-primary" data-target="#createDeckDialog" data-toggle="modal">
			
			<div class="row">
				<!-- display decks on home.php -->
				<?php include 'php/displayDecks.php'; ?>
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
	
	function createDeck() { // Called when user clicks "create deck" after entering information
		document.getElementById("createDeckButton").click(); // Simulate form submission
	}
	
	function showConfirmDialog(deckID) { // Called when user clicks delete on a deck
		document.getElementById("deleteDeckID").value = deckID; // Set the ID of the deck to delete
		$('#deleteDeckDialog').modal('show'); // Show the delete deck confirmation dialog
	}
	
	function deleteDeck() { // Called when user confirms they want to delete the deck
		var deckID = document.getElementById("deleteDeckID").value; // Get the ID of the deck to delete
		document.getElementById("deleteDeckSubmit"+deckID).click(); // Simulate form submission
	}
	
	</script>
	
	<!-------------------------------------------------------------------------------
	// * createDeckDialog Modal
	-------------------------------------------------------------------------------->
	<div class="modal fade" id="createDeckDialog" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="exampleModalLabel">Create Deck</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
		  <div class="modal-body">
		  <form method="post" enctype="multipart/form-data">
			<img id="deckImage" height="350" class="card-img-top" src="icon.png" alt="Card image cap">
			Image (Max 2 MB):
			<input type="file" id="imageFile" name="imageFile" onchange="displayChosenImage(this,true,'deckImage')">
			<div id="imageDiv"></div>
			<input class="form-control form-control-lg mb-2" type="text" name="deckTitle" placeholder="Title">
			<input class="form-control form-control-lg mb-2" type="text" name="deckDescription" placeholder="Description">
			<input type="submit" id="createDeckButton" name="createDeckButton" hidden="true">
		  </form>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
			<button type="button" class="btn btn-primary" onclick="createDeck()">Create Deck</button>
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
			<input id="deleteDeckID" hidden="true">
		  </div>
		</div>
	  </div>
	</div>
	
	<!-- Bootstrap -->
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>