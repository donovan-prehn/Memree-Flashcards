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
		
		$counter = 0;
		while($row = $result->fetch_assoc()) {
			$title = $row['title'];
			$description = $row['description'];
			
			$imageBlob = $row['image'];
			$image = imagecreatefromstring($imageBlob); 
			ob_start();
			imagejpeg($image, null, 80);
			$data = ob_get_contents();
			ob_end_clean();
			echo '	<form action = "card.php">
						<div class="card" style="width: 18rem;display: inline-block;">
						<img class="card-img-top" height="277px" width="200px" src="data:image/jpg;base64,' .  base64_encode($data)  . '" alt="Card image cap">
						<div class="card-body">
						  <h5 class="card-title" style="color: black;">'.$title.'</h5>
						  <p class="card-text" style="color: black;">'.$description.'</p>
						  <a href="card.php" class="ui-btn" type="submit">View Deck</a>
						</div>
						</form>
					</div>';
			
			$counter += 1;
		}
		mysqli_close($conn);
		?>
		<?php endif ?>
	</div>
	</div>
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>