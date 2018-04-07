<?php
	class Card {
		private $deckID;
		private $cardID;
		private $question;
		private $answer;
		private $questionImage;
		private $answerImage;
		
		function __construct($dID, $cID, $q, $a, $qImg, $aImg) {
			$this->deckID = $dID;
			$this->cardID = $cID;
			$this->question = htmlspecialchars($q); //to prevent XSS
			$this->answer = htmlspecialchars($a);
			$this->questionImage = $qImg;
			$this->answerImage = $aImg;
		}

		// Getters
		public function getDeckID(){
			return $this->deckID;
		}
		
		public function getCardID(){
			return $this->cardID;
		}
		
		public function getQuestion(){
			return $this->question;
		}
		public function getAnswer(){
			return $this->answer;
		}
		
		public function getQuestionImage(){
			return $this->questionImage;
		}
		
		public function getAnswerImage(){
			return $this->answerImage;
		}
		
		// Setters
		public function setQuestion($string){
			$this->question = $q;
		}
		
		public function setAnswer($q){
			$this->answer = $a;
		}
		
		public function setQuestionImage($qImg){
			$this->questionImage = $qImg;
		}
		
		public function setAnswerImage($aImg){
			$this->answerImage = $aImg;
		}
		//HTML used to display card. Contains question, answer, and an image for each
		public function displayCard(){
			echo "	<div class='card' style='width: 18rem; display: inline-block;'>
				<button type='button' class='close' aria-label='Close' style='position:absolute; right:0px; top:-10px;' onclick='prepareDeleteCard($this->cardID)'>
						  <span aria-hidden='true'><font color='red' size='8'>&times;</font></span>
						</button>
				<table>
					<tr>
						<td width='50%'><img height='141' id='imageQ$this->cardID' src='$this->questionImage' alt='Card image cap'></td>
						<td width='50%'><img height='141' id='imageA$this->cardID' src='$this->answerImage' alt='Card image cap'></td>
					</tr>
				</table>
					<div class='card-body'>
						<b>Q: </b>
						<p class='text' id='question$this->cardID'>$this->question</p>
						<hr>
						<b>A: </b>
						<p class = 'text' id='answer$this->cardID'>$this->answer</p>
					</div>
					<div class='card-footer text-center'>
						<input type='button' class='btn btn-primary' value='Edit Card' onclick='prepareEditCard($this->cardID)'>
					</div>
			</div>";
		}
	}

?>