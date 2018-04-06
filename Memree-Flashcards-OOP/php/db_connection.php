 <?php
	/*
	// Database values
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "memree_flashcards";

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);

	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	*/
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
			$this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
			if ($this->conn->connect_error) {
				die("Connection failed: " . $this->conn->connect_error);
			}
		}
		
		public function getConnection() {
			return $this->conn;
		}
		
		public function runQuery($query, $types, $parameters){
			$stmt = $this->conn->prepare($query);
			
			if ($types != NULL){
				$stmt->bind_param($types, $parameters);
			}
			$stmt->execute(); // Run query
			$result = $stmt->get_result(); // Get the results of running the query
			$stmt->close();
			
			return $result;
		}		
		
		public function sendQueryWithBlob($query, $types, $parameters, $image){
			$stmt = $this->conn->prepare($query);
			
			if ($types != NULL){
				$stmt->bind_param($types, $parameters);
			}
			$stmt->send_long_data(2, file_get_contents($image)); // Send blob of image
			$result = $stmt->execute(); // Run query
			$stmt->close();
			
			return $result;
			
		}
	}
 ?>