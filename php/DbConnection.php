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
		
		public function testQuery($query, $types, $parameters){
			$stmt = $this->conn->prepare($query);
			
			if ($types != NULL){
				//$stmt->bind_param($types, $parameters);
				if (is_array($parameters))
					call_user_func_array(array($stmt, "bind_param"), array_merge(array($types), $parameters));
				else
					$stmt->bind_param($types, $parameters);
			}
			$result = $stmt->execute(); // Run query
			//$result = $stmt->get_result(); // Get the results of running the query
			$stmt->close();
			
			return $result;
		}	
		
	}
	

 ?>