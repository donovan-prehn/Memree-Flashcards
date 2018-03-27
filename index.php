<!DOCTYPE html>
<!-------------------------------------------------------------------------------
// * File Name: 
// * Author:    
// * ID:        
// * Date:      
// *
// * Tested on: Google Chrome
-------------------------------------------------------------------------------->
<html>
<head>
	<title>Memree Flashcards</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="css/jquery.mobile-1.4.5.min.css">
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,700">
	<script src="js/jquery.js"></script>
	
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	
	<script>
    $(document).bind('mobileinit',function(){
        $.mobile.changePage.defaults.changeHash = false;
        $.mobile.hashListeningEnabled = false;
        $.mobile.pushStateEnabled = false;
    });
	</script> 
	<script src="js/jquery.mobile-1.4.5.min.js"></script>
</head>

<body onload="">

<!-------------------------------------------------------------------------------
// * welcomePage
-------------------------------------------------------------------------------->
<div data-role="page" data-theme="b" id="welcomePage">

	<div data-role="header">
		<center><h1><font color="1b96fe">Memree Flashcards</font></h1></center>
	</div><!-- /header -->
	
	<div role="main" class="ui-content" >
		<div class="ui-field-contain" >
			<input type="text" data-clear-btn="true" name="username" id="username" placeholder="Username">
			<input type="password" data-clear-btn="true" name="password" id="password" autocomplete="off" placeholder="Password">
		</div><!-- /form -->
		
		<a href="" class="ui-shadow ui-btn ui-corner-all ui-btn-icon-left ui-icon-power ui-shadow-icon" data-rel="dialog" data-transition="pop" onclick="login()">Login</a>
		<a href="#createAccountPage" class="ui-shadow ui-btn ui-corner-all ui-btn-icon-left ui-icon-user ui-shadow-icon" data-rel="dialog" data-transition="pop">Create Account</a>
	
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
	
	$sql = "SELECT * FROM `deck` WHERE public='1'";
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

		echo '	<div class="card" style="width: 18rem;display: inline-block;">
					<img class="card-img-top" height="277px" width="200px" src="data:image/jpg;base64,' .  base64_encode($data)  . '" alt="Card image cap">
					<div class="card-body">
					  <h5 class="card-title" style="color: black;">'.$title.'</h5>
					  <p class="card-text" style="color: black;">'.$description.'</p>
					  <a href="#" class="ui-btn">View Deck</a>
					</div>
				</div>';
		
		$counter += 1;

		
		

	}
	
	mysqli_close($conn);
	?>
	</div><!-- /content -->
	
</div><!-- /welcomePage -->

<!-------------------------------------------------------------------------------
// * createAccountPage
-------------------------------------------------------------------------------->
<div data-role="page" id="createAccountPage" data-overlay-theme="b" data-close-btn="none">
	
	<div data-role="header" data-theme="b">
		<h1>Enter Information</h1>
		<div class="ui-btn-right ui-shadow ui-corner-all">
			<a href="index.php" data-role="button" data-icon="delete" data-iconpos="notext">Back</a>
		</div>
	</div><!-- /header -->
	
	<div role="main" class="ui-content">
		<div class="ui-field-contain">
		<form action="register.php">
			<input type="text" data-clear-btn="true" name="username" id="userCreate" placeholder="Username">
			<input type="password" data-clear-btn="true" name="password" id="passCreate" autocomplete="off" placeholder="Password">
			<input type="password" data-clear-btn="true" name="passwordConfirm" autocomplete="off" placeholder="Confirm Password">
			<input class="ui-btn ui-corner-all ui-shadow ui-btn-b i-btn-b ui-btn-icon-left ui-icon-user ui-shadow-icon ui-btn-inline" type="submit" value="Create Account">
		</form>
		</div> <!-- /form -->
	</div> <!-- /content -->
		
</div> <!-- /createAccountPage -->

<!-------------------------------------------------------------------------------
// * accountCreatedDialog
-------------------------------------------------------------------------------->
<div data-role="dialog" id="accountCreatedDialog" data-overlay-theme="b" data-theme="b" data-dismissible="false" data-close-btn="none">
	<div data-role="header">
		<h1>Account Created</h1>
	</div>
	
	<div role="main" class="ui-content">
		<h3 class="ui-title">Account has been successfully created.</h3>
		<p>Please login.</p>
		<a href="index.php" class="ui-btn ui-corner-all ui-shadow ui-btn-inline ui-btn-b" data-direction="reverse">Done</a>
	</div>
</div><!-- /createAccountMessage -->

<!-------------------------------------------------------------------------------
// * Bootstrap javascripts
-------------------------------------------------------------------------------->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

</body>
</html>
