 <?php
 
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "memree_flashcards";

	class DbConnection {
		private $servername;
		private $username;
		private $password;
		private $dbname;
		public $conn;
		
		public function __construct($servername, $username, $password, $dbname) {
			$this->servername = $servername;
			$this->username = $username;
			$this->password = $password;
			$this->dbname = $dbname;
		}
		
		public function connect() {
			//connect to the database
			$this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
			if ($this->conn->connect_error) {
				die("Connection failed: " . $this->conn->connect_error);
			}
		}
		
		public function getConnection() {
			return $this->conn;
		}
		
		public function runQuery($query, $types, $parameters){	//function used to return records of running the query
			$stmt = $this->conn->prepare($query);	//setup query
			
			if ($types != NULL){
				if (is_array($parameters))
					call_user_func_array(array($stmt, "bind_param"), array_merge(array($types), $parameters));		//call the bind_param function of stmt, giving it the array ($types, $param[0], $pram[1], .. ) as a parameter
				else
					$stmt->bind_param($types, $parameters);	//if the input given wasn't an array then there was only a variable for the parameter
			}
			$stmt->execute(); // Run query
			$result = $stmt->get_result(); // Get the results of running the query
			$stmt->close();	//close the statement
			
			return $result;
		}		
		
		public function sendQueryWithBlob($query, $types, $parameters, $image, $location){		//run a query with a given image as a blob
			$stmt = $this->conn->prepare($query);		//setup connection
			
			if ($types != NULL){
				if (is_array($parameters))
					call_user_func_array(array($stmt, "bind_param"), array_merge(array($types), $parameters)); //call the bind_param function of stmt, giving it the array ($types, $param[0], $pram[1], .. ) as a parameter
				else
					$stmt->bind_param($types, $parameters); // if the input given wasn't an array then there was only a variable for the parameter
			}
			$stmt->send_long_data($location, file_get_contents($image)); // Send blob of image to the query variable at location $location
			$result = $stmt->execute(); // Run query
			$stmt->close();		//close statement
			
			return $result;
			
		}
		
		public function sendQueryWithTwoBlobs($query, $types, $parameters, $image, $imageTwo, $location, $locationTwo){	//function to run a query with two images sent as blobs
			$stmt = $this->conn->prepare($query);	//setup query
			
			if ($types != NULL){
				if (is_array($parameters))
					call_user_func_array(array($stmt, "bind_param"), array_merge(array($types), $parameters));		//call the bind_param function of stmt, giving it the array ($types, $param[0], $pram[1], .. ) as a parameter
				else
					$stmt->bind_param($types, $parameters);	// Send blob of image to the query variable at location $location
			}
			$stmt->send_long_data($location, file_get_contents($image)); // Send blob of the question image
			$stmt->send_long_data($locationTwo, file_get_contents($imageTwo)); // Send blob of answer image
			$result = $stmt->execute(); // Run query
			$stmt->close();	//close statement
			
			return $result;
			
		}
		
		public function testQuery($query, $types, $parameters){		//run a query and return true or false if the query was successful
			$stmt = $this->conn->prepare($query);		//prepare the query
			
			if ($types != NULL){
				if (is_array($parameters))
					call_user_func_array(array($stmt, "bind_param"), array_merge(array($types), $parameters));		//call the bind_param function of stmt, giving it the array ($types, $param[0], $pram[1], .. ) as a parameter
				else
					$stmt->bind_param($types, $parameters);// Send blob of answer image
			}
			$result = $stmt->execute(); // Run query
			$stmt->close();	//close the statement
			
			return $result;
		}	
		
	}
	

 ?>