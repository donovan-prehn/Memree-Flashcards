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
 ?>