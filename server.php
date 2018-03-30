<?php
session_start();

// initializing variables
$username = "";
$errors = array(); 
$alerts = array();

// connect to the database
$db = mysqli_connect('localhost', 'root', '', 'memree_flashcards');

// REGISTER USER
if (isset($_POST['reg_user'])) {
  // receive all input values from the form
  $username = mysqli_real_escape_string($db, $_POST['username']);
  $password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
  $password_2 = mysqli_real_escape_string($db, $_POST['password_2']);

  // form validation: ensure that the form is correctly filled ...
  // by adding (array_push()) corresponding error unto $errors array
  if (empty($username)) { array_push($errors, "Username is required"); }
  if (empty($password_1)) { array_push($errors, "Password is required"); }
  if ($password_1 != $password_2) {
	array_push($errors, "The two passwords do not match");
  }

  // first check the database to make sure 
  // a user does not already exist with the same username and/or email
  $user_check_query = "SELECT * FROM users WHERE username='$username'LIMIT 1";
  $result = mysqli_query($db, $user_check_query);
  $user = mysqli_fetch_assoc($result);
  
  if ($user) { // if user exists
    if ($user['username'] === $username) {
      array_push($errors, "Username already exists");
    }
  }
  // Finally, register user if there are no errors in the form
  if (count($errors) == 0) {
	  
	$password = password_hash($password_1, PASSWORD_BCRYPT);
	array_push($alerts, "Account successfully created!");
  	$query = "INSERT INTO users (username, password) 
  			  VALUES('$username', '$password')";
  	mysqli_query($db, $query);
  	$_SESSION['username'] = $username;
  	$_SESSION['success'] = "You are now logged in";
  	header('location: index.php');
  }
}

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