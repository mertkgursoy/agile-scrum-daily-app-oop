
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
    $dailyScrumTableView = new dailyScrumTableView();
    
    if(isset($_SESSION["email"]))  {  
        // (3)
        // Check if session expired redirect it to login page
        $now = time(); 
        if ($now > $_SESSION['expire']) {
            session_destroy();
            echo "Your session has expired!";
            header("location:login.php");  
        } 
        else {
            // (4) 
            // Retrieve User Data     
            $userData = $dailyScrumTableView->dailyScrumTableViewUserData();
            // Check Scrum Table View User Data Exists
            $dailyScrumTableView->dailyScrumTableViewUserDataCheck($userData);
            // (5)     
            // Soft Delete USER can only Soft Delete its own rows AND if it's added today from daily //
            $dailyScrumTableView->dailyScrumTableViewDeleteRowData($userData);
            // (6) 
            // Diplay Daily Notes In HTML Table               
            $dailyScrumTableView->dailyScrumTableViewFetchAndDisplayData($userData);
        }
    }  
    else  {  
     header("location:login.php");  
    }  
 ?>  
<?php
include "templates/footer.php"; // Footer
?>