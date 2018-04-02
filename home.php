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
	 <!-- Bootstrap core CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>
	<div class="container">
	<nav class="navbar navbar-expand-lg navbar-dark bg-primary mt-3 mb-3">
	<a class="navbar-brand" href="#">Memree Flashcards</a>
	
	<ul class="navbar-nav">
		<li class="nav-item">
		  <a class="nav-link" href="#">Manage Decks</a>
		</li>
		<li class="nav-item">
		  <a class="nav-link" href="#">Public Decks</a>
		</li>
	</ul>
	
	<!-- Logged in as / Logout Button -->
	<form class="form-inline ml-auto" action="index.php?logout='1'">
		<span class="navbar-text mr-1">
				<?php  if (isset($_SESSION['username'])) : ?>
				<?php echo $_SESSION['username']; ?>
		</span>
		<button class="btn btn-primary" type="submit">Logout</button>
	</form>

	</nav>
	  
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
		
		<?php
			// This is called when user submits information to create a deck
			if (isset($_POST['createDeckButton'])) {
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
		
		<?php
			// This is called when the user wants to delete deck (either from home.php or editDeck.php)
			if (isset($_GET['deckID']) or isset($_POST['deckID'])) {
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
		<P>

		<?php
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
		
		$userID = $_SESSION['userID'];
		$sql = "SELECT * FROM deck WHERE userID='$userID'";
		$result = mysqli_query($conn, $sql);
		
		while($row = $result->fetch_assoc()) {
			$title = $row['title'];
			$description = $row['description'];
			$deckID = $row['deckID'];
			
			$imageBlob = $row['image'];
			$image = imagecreatefromstring($imageBlob); 
			ob_start();
			imagejpeg($image, null, 80);
			$data = ob_get_contents();
			ob_end_clean();
			echo '	<div class="card" style="width: 18rem;display: inline-block;">
						<img class="card-img-top" height="277px" width="200px" src="data:image/jpg;base64,' .  base64_encode($data)  . '" alt="Card image cap">
						<div class="card-body">
						  <h5 class="card-title" style="color: black;">'.$title.'</h5>
						  <p class="card-text" style="color: black;">'.$description.'</p>
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
								<input class="btn btn-secondary" type="submit" value="Delete Deck">
							</form>
						  
						  
						</div>
					</div>';
		}
		mysqli_close($conn);
		?>
		<?php endif ?>
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
	
	function createDeck() {
		document.getElementById("createDeckButton").click(); // Simulate form submission
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
			<img id="deckImage" class="card-img-top" src="icon.png" alt="Card image cap">
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
	
	<!-- Bootstrap -->
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>