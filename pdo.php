<?php
//  Encapsulation | OOP
class Auth {

    //  Access Modifier |  OOP
    private $hostName = "localhost";
    private $databaseName = "agile_scrum_database";
    private $userName = "theUserName";
    private $password = "thePass";
        
    //  Constructor |  OOP
    function __construct() {
        try {
            $pdoConn = new PDO("mysql:host=$this->hostName;dbname=$this->databaseName",$this->userName,$this->password);
            $pdoConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
        $this->pdo = $pdoConn;

    }

    //  Access Modifier | OOP
    protected function authRun() {

        //  Getter | OOP
        $pdo = $this->pdo;
        return $pdo;
    }

}
?>
