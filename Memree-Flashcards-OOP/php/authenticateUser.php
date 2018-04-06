<?php
	// initializing variables
	$username = "";
	$errors = array(); 
	$alerts = array();

	// connect to the database
	$db = mysqli_connect('localhost', 'root', '', 'memree_flashcards');
	
	// LOGIN USER
	if (isset($_POST['login_user'])) {
	  $username = mysqli_real_escape_string($db, $_POST['username']);
	  $password = mysqli_real_escape_string($db, $_POST['password']);

	  if (empty($username)) {
		array_push($errors, "Username is required");
	  }
	  if (empty($password)) {
		array_push($errors, "Password is required");
	  }

	  if (count($errors) == 0) {
		$query = "SELECT password, userID FROM users WHERE username='$username'";
		$results = mysqli_query($db, $query);
		$row = mysqli_fetch_array($results, MYSQLI_NUM);
		$storedPassword = $row[0];
		$userID = $row[1];
		mysqli_free_result($results);

		if(password_verify($password, $storedPassword)){
			  $_SESSION['username'] = $username;
			  $_SESSION['userID'] = $userID;
			  $_SESSION['success'] = "You are now logged in";
			  header('location: home.php');
		}else{
			array_push($errors, "Wrong username/password combination");
		}
		
	  }
	}
?>