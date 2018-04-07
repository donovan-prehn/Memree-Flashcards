 <?php
	/*
	// Database values
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "memree_flashcards";

	// Create DbConnection
	$conn = new mysqli($servername, $username, $password, $dbname);

	// Check DbConnection
	if ($conn->connect_error) {
		die("DbConnection failed: " . $conn->connect_error);
	}
	*/
	class DbDbConnection {
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
			$this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
			if ($this->conn->connect_error) {
				die("DbConnection failed: " . $this->conn->connect_error);
			}
		}
		
		public function getDbConnection() {
			return $this->conn;
		}
		
		public function runQuery($query, $types, $parameters){
			$stmt = $this->conn->prepare($query);
			
			if ($types != NULL){
				//$stmt->bind_param($types, $parameters);
				if (is_array($parameters))
					call_user_func_array(array($stmt, "bind_param"), array_merge(array($types), $parameters));
				else
					$stmt->bind_param($types, $parameters);
			}
			$stmt->execute(); // Run query
			$result = $stmt->get_result(); // Get the results of running the query
			$stmt->close();
			
			return $result;
		}		
		
		public function sendQueryWithBlob($query, $types, $parameters, $image, $location){
			$stmt = $this->conn->prepare($query);
			
			if ($types != NULL){
				//$stmt->bind_param($types, $parameters);
				if (is_array($parameters))
					call_user_func_array(array($stmt, "bind_param"), array_merge(array($types), $parameters));
				else
					$stmt->bind_param($types, $parameters);
			}
			$stmt->send_long_data($location, file_get_contents($image)); // Send blob of image
			$result = $stmt->execute(); // Run query
			$stmt->close();
			
			return $result;
			
		}
		
		public function sendQueryWithTwoBlobs($query, $types, $parameters, $image, $imageTwo, $location, $locationTwo){
			$stmt = $this->conn->prepare($query);
			
			if ($types != NULL){
				if (is_array($parameters))
					call_user_func_array(array($stmt, "bind_param"), array_merge(array($types), $parameters));
				else
					$stmt->bind_param($types, $parameters);
			}
			$stmt->send_long_data($location, file_get_contents($image)); // Send blob of image
			$stmt->send_long_data($locationTwo, file_get_contents($imageTwo)); // Send blob of answer image
			$result = $stmt->execute(); // Run query
			$stmt->close();
			
			return $result;
			
		}
		
	}
 ?>