<?php include 'deck.php'; ?>

<?php // Displaying decks

$userID = $_SESSION['userID']; // Get user ID

$stmt = $conn->prepare('SELECT * FROM deck WHERE userID=?');
$stmt->bind_param('i', $userID);

$stmt->execute(); // Run query
$result = $stmt->get_result(); // Get the results of running the query

if ($result->num_rows == 0) { // If user does not have any decks
	echo "<h3>You have no decks yet.</h3>";
}
else { // User has 1 or more decks
	while($row = $result->fetch_assoc()) { // Go through each row and fetch deck data
	$title = $row['title']; // Retrieve deck title
	$description = $row['description']; // Retrieve deck description
	$deckID = $row['deckID']; // Retrieve deck ID
	
	$imageBlob = $row['image']; // Retrieve deck image blob
	
	$deck = new Deck($deckID, $title, $description, $imageBlob, $userID, "0");
	$deck->displayDeck();
}
}

$stmt->close();
?>