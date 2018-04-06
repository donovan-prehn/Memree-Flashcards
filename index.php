<?php
	session_start(); 
	include 'php/registerUser.php';
	include 'php/authenticateUser.php';
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Memree Flashcards</title>

    <!-- Bootstrap core CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	
    <!-- Custom styles for this template -->
    <link href="css/signin.css" rel="stylesheet">
  </head>

  <body class="text-center">
	
	<!-- Login Form -->
    <form class="form-signin" method="post" action="">
      <img class="mb-4" src="https://getbootstrap.com/assets/brand/bootstrap-solid.svg" alt="" width="72" height="72">
      <h1 class="h3 mb-3 font-weight-normal">Please sign in</h1>
	  
      <label for="inputEmail" class="sr-only">Username</label>
      <input name="username" type="text" id="inputEmail" class="form-control" placeholder="Username" required autofocus>
      
	  <label for="inputPassword" class="sr-only">Password</label>
      <input name="password" type="password" id="inputPassword" class="form-control" placeholder="Password" required>
	  
	  <button style="margin-top:10px;" name="login_user" class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
	  <button class="btn btn-lg btn-secondary btn-block" type="button" data-toggle="modal" data-target="#exampleModal">Register</button>
      <br>
	<?php include('errors.php'); ?>
    </form>
  
	<!-- Registration Modal -->
	<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	  <div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="exampleModalLongTitle">User Registration</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
			  <div class="modal-body">
				<form class="form-signin" method="post" action="">
					
					<label for="registerUsername" class="sr-only">Username</label>
					<input id="registerUsername" name="username" type="text"  class="form-control" placeholder="Username" required autofocus>
					
					<label for="registerPass1" class="sr-only">Password</label>
					<input id="registerPass1" name="password_1" type="password" class="form-control" placeholder="Password" required>
					
					<label for="registerPass2" class="sr-only">Confirm Password</label>
					<input id="registerPass2" name="password_2" type="password" class="form-control" placeholder="Confirm Password" required>
					
					<button style="margin-top:10px;" name="reg_user" type="submit" class="btn btn-primary float-right">Register</button>
				</form>
			  </div>
		  </div>
		</div>
	  </div>
	</div>

	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  </body>
</html>