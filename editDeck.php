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
		// This is called when the "Update" button for the deck is clicked
		if (isset($_POST['updateDeck'])) {
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
			
			// Deck values
			$userID = $_SESSION['userID']; // Get user ID from session
			$deckID = $_POST['deckID']; // Get deck ID from hidden input
			$title = $_POST['deckTitle']; // Get deck title from textfield
			$description = $_POST['deckDescription']; // Get deck description from textfield
			$imageName = $_FILES["imageFile"]["tmp_name"]; // Get the path of image file
			
			if (!$imageName) { // No image selected
				$sql = "UPDATE deck SET title='$title', description='$description' WHERE userID='$userID' and deckID='$deckID'";
			} 
			else {
				$imageBlob = addslashes(file_get_contents($imageName)); // Converting to a blob
				// Query to update the deck title, description, and image
				$sql = "UPDATE deck SET title='$title', description='$description', image='$imageBlob' WHERE userID='$userID' and deckID='$deckID'";
			}
			
			$result = mysqli_query($conn, $sql); // Run query
			
			if ($result) { // If query was 
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
			
			mysqli_close($conn);
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
			  <a class="nav-link" href="home.php">Public Decks</a>
			</li>
		</ul>
		
		<!-- Logged in as / Logout Button -->
		<form class="form-inline ml-auto" action="index.php?logout='1'">
			<span class="navbar-text mr-1">
			</span>
			<button class="btn btn-primary" type="submit">Logout</button>
		</form>

		</nav>
		
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
		
		$userID = $_SESSION['userID']; // Get user ID
		$deckID = $_POST['deckID']; // Get Deck ID from previous page
		$sql = "SELECT * FROM deck WHERE userID='$userID' and deckID='$deckID'"; // Get all fields from selected deck
		$result = mysqli_query($conn, $sql); // Run query
		
		if ($result) { // If query was successful
			$row = $result->fetch_assoc(); // Get the row
			$title = $row['title']; // Retrieve title from row
			$description = $row['description']; // Retrieve descriptoin from row
			
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
		
		mysqli_close($conn);
		
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
						<input type="file" id="imageFile" name="imageFile" value="Choose Image" onchange="displayChosenImage(this)">
						<div id="imageDiv"></div>
						<input type="submit" name="updateDeck" value="Update">
						<input name="deckID" value="<?php echo $deckID;?>" hidden="true">
					</form>
				</div>
			</div>
		</div>
		
		<script>
		function displayChosenImage(input) {
			var reader = new FileReader();
			
			reader.onload = function(e) {
				
				var fileSize = input.files[0].size; // Get size of file
				var sizeString = (fileSize/1024/1024).toFixed(2) + " MB"; // Convert size to MB
				if (fileSize/1024/1024 < 1) { // If MB conversion results in a value lower than 1.0, than it should be displayed in KB
					sizeString = (fileSize/1024).toFixed(2) + " KB";
				}
				document.getElementById("imageDiv").innerHTML = "Size: " + sizeString; // Display size of file
				document.getElementById("deckImage").src = e.target.result; // Change image of deck
			}
			
			reader.readAsDataURL(input.files[0]); // Read file
			
		}
		</script>
		
		
		
		<hr class="mt-5"/>
		
		<div class="container pb-3">
			<h1>Cards</h1>
			<div class="card" style="width: 18rem;">
			  <img class="card-img-top" src="icon.png" alt="Card image cap">
			  <div class="card-body">
				<input class="form-control form-control-lg mb-2" type="text" placeholder="Question">
				<input class="form-control form-control-lg" type="text" placeholder="Answer">
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