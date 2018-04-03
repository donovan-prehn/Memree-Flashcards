<?php 
  session_start(); 

  if (!isset($_SESSION['username'])) {
  	$_SESSION['msg'] = "You must log in first";
  	header('location: index.php');
  }
  if(isset($_GET['logout'])) {
  	session_destroy();
  	unset($_SESSION['username']);
  	header("location: index.php");
  }
  
?>
<!-- Get cards from database and store in JavaScript array -->
<?php
		if (isset($_GET['deckID'])) {
			$deckID = $_GET['deckID'];
			
			include 'php/db_connection.php';
			
			$userID = $_SESSION['userID'];
			$sql = "SELECT * FROM card WHERE deckID='$deckID'";
			$result = mysqli_query($conn, $sql);
			
			if (!$result) {
			echo '<script language="javascript">';
				echo 'alert("Unable to find deck.")';
				echo '</script>';
			}
			$cards = array();
			$index = 0;
			while($card = mysqli_fetch_assoc($result))
			{
				$cards[$index]['cardID'] = $card['cardID'];
				$cards[$index]['question'] = $card['question'];
				$cards[$index]['answer'] = $card['answer'];
				
				if($card["questionImage"]){
					$cards[$index]['questionImage'] = base64_encode($card["questionImage"]);
				}
				else { // If no card image, use default image
					$cards[$index]['questionImage'] = base64_encode(file_get_contents("icon.png"));
				}
				if($card["answerImage"]){
					$cards[$index]['answerImage'] = base64_encode($card["answerImage"]);
				}
				else { // If no card image, use default image
					$cards[$index]['answerImage'] = base64_encode(file_get_contents("icon.png"));
				}
			
				$index=$index+1;
			}
			mysqli_close($conn);
		}
?>
		
<!DOCTYPE html>
<html>
<head>
	<title>Home</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
	 <!-- Bootstrap core CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<script defer src="https://use.fontawesome.com/releases/v5.0.9/js/all.js" integrity="sha384-8iPTk2s/jMVj81dnzb/iFR2sdA7u06vHJyyLlAd4snFpCl/SnyUjRrbdJsw1pGIl" crossorigin="anonymous"></script>
	
</head>
<body>

	<div class="container">
	
		<?php include 'nav-bar.php'; ?>
		
		<div class="row align-items-center justify-content-md-center mt-5">
		
			<!-- Message that tells user they have no cards -->
			<div id="noCardsMessage">
				<h3>There are no cards in this deck yet.</h3>
			</div>
		
			<!-- Previous Button -->
			<div class="col-md-auto" id="previousButtonDiv">
					<button id="prevButton" style="background-color:transparent;" class="btn" type="button" data-toggle="collapse" data-target=".multi-collapse" aria-expanded="false" aria-controls="question answer">
						<span style="font-size:6em;color:blue;" class="fas fa-caret-left" aria-hidden="true"></span>
					</button>
			</div>
			
			<!-- Card -->
			<div class="col-lg-7 text-center border text-dark bg-light" id="cardDiv">
					
					<!-- Question -->
					<div class="pt-3 pb-3" id="question">
						<img id="questionImage" src="icon.png" class="rounded mx-auto d-block pb-3" height = '150' width = '150' alt="...">
						<p class = 'card' id="questionText" > This is a question. </p>
					</div>
					
					<!-- Answer -->
					<div style="display:none" class="pt-3 pb-3" id="answer">
						<img id="answerImage" src="icon.png" class="rounded mx-auto d-block pb-3" height = '150' width = '150' alt="...">
						<p class = 'card' id="answerText" >This is an answer. </p>
					</div>
			</div>
			
			<!-- Next Button -->
			<div class="col-md-auto" id="nextButtonDiv">
					<button id="nextButton" style="background-color:transparent;" class="btn" type="button" data-toggle="collapse" data-target=".multi-collapse" aria-expanded="false" aria-controls="question answer">
						<span style="font-size:6em;color:blue;" class="fas fa-caret-right" aria-hidden="true"></span>
					</button>
			</div>
			
		</div>
		
		<!-- Flip Button -->
		<div class="col-md-auto mt-3" id="flipButtonDiv">
			<div class="text-center">
				<button id="flipButton" class="btn btn-primary" onclick="$('#answer').toggle();$('#question').toggle();" >Flip Card</button> 
			</div>
		</div>

	</div>
	
	
	<!-- Bootstrap -->
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	
	<script type="text/javascript">
	
		function mod(n, m) {
			return ((n % m) + m) % m;
		}
		
		var index = 0;
		var cards = <?php echo json_encode($cards);?>;
	
		function updateImages(){
			if(cards[index]["questionImage"]){
				$("#questionImage").attr("src","data:image/jpg;base64," + cards[index]["questionImage"]);
			}else{
				$("#questionImage").attr("src","");
			}
			if(cards[index]["answerImage"]){
				$("#answerImage").attr("src","data:image/jpg;base64," + cards[index]["answerImage"]);
			}else{
				$("#answerImage").attr("src","");
			}
		}
		
		if (cards.length > 0) { // If there are cards, update image and Q/A
			$("#questionText").text(cards[index]["question"]);
			$("#answerText").text(cards[index]["answer"]);
			updateImages();
			
			// Hide "no cards" message
			$("#noCardsMessage").hide();
		} else {
			// Hide elements since no cards
			$("#cardDiv").hide();
			$("#previousButtonDiv").hide();
			$("#nextButtonDiv").hide();
			$("#flipButtonDiv").hide();
			
			// Display "no cards" message
			$("#noCardsMessage").show();
		}
		
		$("#nextButton").click(function(){
			index= mod((index+1),cards.length);
			$("#questionText").text(cards[index]["question"]);
			$("#answerText").text(cards[index]["answer"]);
			updateImages();
			// Make sure to show the question first when going to next card
			$('#question').show();
			$('#answer').hide();
		});
		
		
		$("#prevButton").click(function(){
			index= mod((index-1),cards.length);
			$("#questionText").text(cards[index]["question"]);
			$("#answerText").text(cards[index]["answer"]);
			updateImages();
			// Make sure to show the question first when going to previous card
			$('#question').show();
			$('#answer').hide();
		});

	</script>
			

</body>
</html>