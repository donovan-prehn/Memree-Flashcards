<nav class="navbar navbar-expand-lg navbar-dark bg-primary mt-3 mb-3">
	<a class="navbar-brand" href="#">Memree Flashcards</a>
	
	<ul class="navbar-nav">
		<li class="nav-item">
		  <a class="nav-link" href="home.php">My Decks</a>
		</li>
		<a class="nav-link" href="public.php">Public Decks</a>
	</ul>
	
	<!-- Logged in as / Logout Button -->
	<form class="form-inline ml-auto" action="index.php?logout='1'">
		<span class="navbar-text mr-1">
				<?php if (isset($_SESSION['username'])) : ?>
				<?php echo $_SESSION['username']; ?>
				<?php endif ?>
		</span>
		<button class="btn btn-primary" type="submit">Logout</button>
	</form>
</nav>