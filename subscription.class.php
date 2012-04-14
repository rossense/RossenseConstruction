<?php
/*
    Rossense Construction - Subscription
	Version : 1.0
    Author  : rossense
    Email   : developer.rossense@gmail.com
    Site	: http://www.rossense.com/
	GitHub  : http://www.github.com/rossense/RossenseConstruction
	License : MIT License

*/

/*
 * DATABASE CONFIGURATION
 */

//Define Database Constants

//Server e.g 127.0.0.1 <localhost> or remote server's domain name or ip
define("DB_HOST","localhost");

//Database name on the host
define("DB_NAME","subscription");

//Database User
define("DB_USER","root");

//Defined User's Password
define("DB_PASS","");

/*Subscription Class*/
class Subscription{

    private $db_host;
    private $db_name;
    private $db_user;
    private $db_pass;

    //Connection Link
    private $connection;

    //Result
    private $result;

    const ALREADY=2;
    const SUCCESS=1;
    const FAIL=0;
    const INVALID_EMAIL=0;

    public function __construct(){
        $this->db_host=DB_HOST;
        $this->db_name=DB_NAME;
        $this->db_user=DB_USER;
        $this->db_pass=DB_PASS;

        //open connection
        $this->connection = mysql_connect($this->db_host, $this->db_user, $this->db_pass);
        if (!$this->connection) {
            die("Cannot connect to the database " . mysql_error());
        }
        //select db
        mysql_select_db($this->db_name, $this->connection)||die("Database Not Found!");

        //set character set of the connection
    }
    /**
     * @param $query that will be executed
     */
    private function execute($query) {
        //execute the query
        $this->result = mysql_query($query, $this->connection) ;
    }

    /**
     * Subscribe
     * @param $email subscriber's email
     * @return int mysql_insert_id must be greater than 1
     */
    public function subscribe($email){
        //prevent sql injections
        $email=addslashes(stripslashes($email));

        $query="INSERT INTO email_list (id,email) VALUES(NULL,'$email')";
        mysql_query($query, $this->connection);
        return mysql_insert_id($this->connection);
    }

    /**
     * Check email exists or not
     * @param $email subscriber's email
     * @return bool
     * @throws Exception
     */
    public function existEmail($email){
        $query="SELECT * FROM email_list WHERE email='".$email."'";
        $result = mysql_query($query, $this->connection);
        if($result){
            $record= mysql_fetch_assoc($result);
            //fetch record from email_list
            if(isset($record["email"]))return true;
            else return false;
        }
        throw new Exception("Checking Email Exception");
    }
    /**
     * Check the email address is valid or not
     * @static
     * @param $email subscriber's email address
     * @return bool true if the email address is valid, otherwise false.
     */
    static function isValidEmail($email){
        if (preg_match("/^[+a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i", $email))
            return true;
        else return false;
    }

}
