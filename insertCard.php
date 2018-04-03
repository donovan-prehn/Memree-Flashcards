<?php
	if(isset($_POST["submit"])) {
		$servername = "localhost";
		$username = "root";
		$password = "";
		$dbname = "memree_flashcards";
		$inputQuestion = $_POST["inputQuestion"];
		$inputAnswer = $_POST["inputAnswer"];
		
		// Create connection
		$conn = mysqli_connect($servername, $username, $password, $dbname);
		
		// Check connection
		if (!$conn) {
			die("Connection failed: " . mysqli_connect_error());
		}
	}
?>

<!DOCTYPE html>
<head> <title>PHP Script</title>
</head>
<body>
<?php
if(isset($_POST["submit"]))
echo "<h1>Incorrect Username and/or password!</h1>";
?>
