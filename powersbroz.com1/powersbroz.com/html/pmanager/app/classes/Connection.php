<?php
/**
 * @package Entity Connection Class
 * @version 1.0
 * @date 21 October 2014
 * @author Travis Coats, Zen Perfect Design
 * @location app/classes/
 *
 */

class Connection {

    public  $gate;						// The actual MYSQLI Resource
    private $host 		= DB_HOST;		// Leave localhost in most cases
    private $username	= DB_USERNAME;	// Database Username
    private $password 	= DB_PASS;		// Database Password
    private $database 	= DB_NAME;		// Database created in PHP myadmin

    public function __construct() {
        mysqli_report(MYSQLI_REPORT_STRICT);

        try {
            $this->gate = new mysqli($this->host, $this->username, $this->password, $this->database);
        } catch (Exception $e) {
            include_once ROOT_PATH.'app/views/no.connection.html.php';
            //echo 'No connection';
            exit;
        }
    }

    public function secure($string) {
        $string = trim($string);
        // $string = addslashes($string);
        $string = mysqli_real_escape_string($this->gate, $string);
        return $string;
    }

    public function insert_id() {
        return mysqli_insert_id($this->gate);
    }

    public function affected_rows() {
        return mysqli_affected_rows($this->gate);
    }

    public function num_rows($result_set) {
        return mysqli_num_rows($result_set);
    }

}

// Instantiate

$con = new Connection();