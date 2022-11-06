

<?php 
 // Header 
 include 'templates/header.php';
?>




<?php  

     // (1) Start Session 
     session_start();  
     // (2)
     // Add PDO Config
     // Add PDO Config
     require_once "pdo.php";
     require_once "class.php";
     $usersTableView = new usersTableView();
    if(isset($_SESSION["email"]))  
    {  
        // (3)
        // Check if session expired redirect it to login page
            $now = time(); // Checking the time now when home page starts.
            if ($now > $_SESSION['expire']) {
                session_destroy();
                echo "Your session has expired!";
                header("location:login.php");  
            }
            else {
                // (4) 
                // Retrieve User Data     
                $userData = $usersTableView->usersTableViewViewUserData();
                // (4) 
                // Retrieve User Name By Session Email      
                $usersTableView->usersTableViewViewUserDataCheck($userData);
                // (5)        
                // Delete rows only User Can Delete its own row from daily //
                $usersTableView->usersTableViewViewDeleteRowData($userData);
                // (6) 
                // Diplay Users In HTML Table//
                // Diplay Users In HTML Table//
                $usersTableView->usersTableViewFetchAndDisplayData($userData);
        }
    }  
    else  {  
        header("location:login.php");  
    }  
include "templates/footer.php"; // Footer
?>