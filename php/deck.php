<?php
	class Deck{
		private $deckID;
		private $title;
		private $description;
		private $image;
		private $userID;
		private $publicValue;
		
		public function setProperties($dID, $t, $descrip, $im, $uID, $pub){
			$this->deckID = $dID;
			$this->title = $t;
			$this->decription = $descrip;
			$this->image = $im;
			$this->userID = $uID;
			$this->pub = $publicValue;
			
			// Process image from blob into jpg
			$imageBlob = imagecreatefromstring($im); 
			ob_start();
			imagejpeg($imageBlob, null, 80);
			$this->image = ob_get_contents();
			ob_end_clean();
		}
		
		public function getDeckID(){
			return $this->deckID;
		}
		
		public function getTitle(){
			return $this->title;
		}
		
		public function getDecription(){
			return $this->description;
		}
		public function getImage(){
			return $this->image;
		}
		
		public function getUserID(){
			return $this->userID;
		}
		
		public function getPublic(){
			return $this->publicValue;
		}
		
		public function displayPublicDeck($username){
			echo '	<div class="card mx-2 my-2" style="width: 18rem;display: inline-block;">
							<img class="card-img-top" height="277px" width="200px" src="data:image/jpg;base64,' .  base64_encode($this->image)  . '" alt="Card image cap">
							<div class="card-body">
							  <h5 class="deckTitle" style="color: black;">'.$this->title.'</h5>
							  <p class="cardDescription" style="color: black;">'.$this->description.'</p>
							</div>
							<div class="card-footer  text-center">	
								<form action="study.php" method="get">
									<h5 class="deckTitle" style="color: black;">Owner: '.$username.'</h5>
									<input name="deckID" value="'.$this->deckID.'" hidden="true"/>
									<input class="btn btn-primary" type="submit" value="Study Deck">
								</form>							  
							</div>
						</div>';
			
		}
		
		public function displayDeck(){
			echo '	<div class="card mx-2 my-2" style="width: 18rem;display: inline-block;">
							
							<button type="button" class="close" aria-label="Close" style="position:absolute; right:0px; top:-10px;" onclick="showConfirmDialog('.$this->deckID.')">
							  <span aria-hidden="true"><font color="red" size="8">&times;</font></span>
							</button>
							
							<img class="card-img-top" height="277px" width="200px" src="data:image/jpg;base64,' .  base64_encode($this->image)  . '" alt="Card image cap">
							<div class="card-body">
							  <h5 class="deckTitle" style="color: black;">'.$this->title.'</h5>
							  <p class="cardDescription" style="color: black;">'.$this->description.'</p>
							</div>
							<div class="card-footer  text-center">	
								<form action="study.php" method="get">
									<input name="deckID" value="'.$this->deckID.'" hidden="true"/>
									<input class="btn btn-primary" type="submit" value="Study Deck">
								</form>
								<p>
								<form action="editDeck.php" method="post" style="display: inline-block;">
									<input name="deckID" value="'.$this->deckID.'" hidden="true"/>
									<input class="btn btn-primary" type="submit" value="Edit Deck">
								</form>
								<form action="" method="post" style="display: inline-block;">
									<input name="deckID" value="'.$this->deckID.'" hidden="true"/>
									<input type="submit" id="deleteDeckSubmit'.$this->deckID.'" hidden="true">
								</form>
							  
							  
							</div>
						</div>';
			
			
		}
		
	}


?>