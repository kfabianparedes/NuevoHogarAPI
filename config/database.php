<?php
    error_reporting(E_ALL ^ E_WARNING);
    date_default_timezone_set('America/Lima');

    class Database{
    
        //Database credentials
        private $host ="212.1.208.51"; // "212.1.208.51";
        private $db_name ="u741735946_capacitacion"; // "u741735946_capacitacion";
        private $username ="u741735946_team"; // "u741735946_team";
        private $password ="Team123_"; // "Team123_";
        public $conn;

        //Constructor
        public function __construct()
        {
            try{
                // Create connection
                $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db_name);          
                $this->conn->set_charset("utf8");
            }catch(Throwable  $e){
                echo "Connection error: " . $e->getMessage();        
            }        
        }
    
        //Get the database connection
        public function getConnection(){    
            return $this->conn;
        }        
    }
?>