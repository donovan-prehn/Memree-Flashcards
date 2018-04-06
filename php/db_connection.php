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
		
		function __construct($servername, $username, $password, $dbname) {
			$this->servername = $servername;
			$this->username = $username;
			$this->password = $password;
			$this->dbname = $dbname;
		}
		
		function connect() {
			$this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
			if ($this->conn->connect_error) {
				die("Connection failed: " . $this->conn->connect_error);
			}
		}
		
		function getConnection() {
			return $this->conn;
		}
	}
 ?>