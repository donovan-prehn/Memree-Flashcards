 <?php
 	// initializing variables
	$username = "";
	$errors = array(); 
	$alerts = array();

	$db = mysqli_connect('localhost', 'root', '', 'memree_flashcards');
	
	if (isset($_POST['reg_user'])) {
	  
	  //Prevent against SQL injection
	  $username = mysqli_real_escape_string($db, $_POST['username']);
	  $password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
	  $password_2 = mysqli_real_escape_string($db, $_POST['password_2']);
	  
	  //Validate input
	  if (empty($username)) { array_push($errors, "Username is required");}
	  if (empty($password_1)) { array_push($errors, "Password is required");}
	  if ($password_1 != $password_2) { array_push($errors, "The two passwords do not match");}

	  //Query DB for existing user
	  $user_check_query = "SELECT * FROM users WHERE username='$username'LIMIT 1";
	  $result = mysqli_query($db, $user_check_query);
	  $user = mysqli_fetch_assoc($result);
	  
	  if ($user) {
		if ($user['username'] === $username) {
		  array_push($errors, "Username already exists");
		}
	  }

	  if (count($errors) == 0) {
		
	    //Generate a random salt and use it to hash password
		$password = password_hash($password_1, PASSWORD_BCRYPT);
		
		//Insert hash/salt into DB.
		array_push($alerts, "Account successfully created!");
		$query = "INSERT INTO users (username, password) 
				  VALUES('$username', '$password')";
		mysqli_query($db, $query);
		
		$_SESSION['username'] = $username;
		$_SESSION['success'] = "You are now logged in";
		header('location: index.php');
	  }
	}
?>