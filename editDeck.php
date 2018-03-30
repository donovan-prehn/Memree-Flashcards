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
			</span>
			<button class="btn btn-primary" type="submit">Logout</button>
		</form>

		</nav>
		  
		<div class="container">
			<h1>Deck</h1>
			<div class="row bg-light px-3 py-3">
				<div class="col-md-auto">
					<h4>Deck Image</h4>
					<img src="icon.png" alt="..." class="img-thumbnail">
				</div>
				<div class="col-lg-6">
					<form>
						<div class="form-group">
							<h4>Deck Title</h4>
							<input type="text" class="form-control" id="inputDeckTitle" placeholder="asdf">
						</div>
						<div class="form-group">
							<h4>Deck Description</h4>
							<input type="textarea" class="form-control" id="inputDeckTitle" placeholder="asdf">
						</div>
					</form>
				</div>
			</div>
		</div>
		
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