<?php

class login extends Auth {
    function loginRun() {
            if(isset($_SESSION["email"])){
                header("Location: daily-scrum.php");
            } else {
                // (3) 
                // Check Submit Button Clicked & Inputs Filled Correctly // 
                if ( !empty($_POST['email']) &&  isset($_POST['email']) && isset($_POST['password']) && !empty($_POST['password']) ) {
                    // (4) 
                    // Check User Exist or Not In Users Table - Try To Select User From Users Table // 
                    $selectUserSqlQuery = "SELECT name FROM users 
                        WHERE email = :em AND password = :pw AND is_active = 1";
                    // echo "<p>$selectUserSqlQuery</p>\n";
                    /* */
                    /* */
                    // Buradaki _pdo degisken aslında pdo aslında şu pdo.php içindeki 
                    //         $pdo = new PDO("mysql:host=$hostName;dbname=$databaseName",$userName,$password);
                    // buraya class içine çekiyoruz yeni class create edilince ve stmt için kullanıyor.
                    $stmt = $this->authRun()->prepare($selectUserSqlQuery);
                    /* */
                    /* */
                    $password = $_POST["password"];
                    $hash = md5($password);
                    $stmt->execute(array(
                        ':em' => $_POST['email'], 
                        ':pw' => $hash));
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    // var_dump($row);

                    // (5) 
                    // Check If User Exists Log In and Redirect To The Success Page or Display Error Message //
                    if ( $row === FALSE ) {
                        echo "<h1 style='color: #e4ff00;'>Login incorrect.</h1>\n";
                        $password = $_POST["password"];
                        $hash = md5($password);
                        // echo $hash;
                    } else { 
                        echo "<p>Login success.</p>\n";
                        
                        // (6)
                        // Set The User Email In Session And Go To DailyScrum Page // 
                        $_SESSION["email"] = $_POST["email"]; 
                        
                        // (7) 
                        // Retrieve User Name By Session Email     
                        $theUserSessionEmail = $_POST["email"];
                        /* */
                        /* */
                        // Buradaki _pdo degisken aslında pdo aslında şu pdo.php içindeki 
                        //         $pdo = new PDO("mysql:host=$hostName;dbname=$databaseName",$userName,$password);
                        // buraya class içine çekiyoruz yeni class create edilince ve stmt için kullanıyor.
                        $SqlQueryToRetrieveUserData = $this->authRun()->prepare('SELECT * FROM users WHERE email = ?');
                        /* */
                        /* */

                        $SqlQueryToRetrieveUserData->execute(array($theUserSessionEmail));
                        $userData = $SqlQueryToRetrieveUserData->fetch(PDO::FETCH_ASSOC);

                        // (8) 
                        // Add this user if we do not have any issue above. "empty error" means we passed the error conditions above. //
                        if(!empty($userData)) {

                            // (9)
                            // Set user name in Session
                            $_SESSION["name"] = $userData["name"];  

                            // (10)
                            // Taking now logged in time.
                            $_SESSION['start'] = time(); 

                            // (11) 
                            // Ending a session in 30 minutes from the starting time.
                            $_SESSION['expire'] = $_SESSION['start'] + (30 * 60);
                        }
                        header("Location: daily-scrum-form.php");
                    }
                    
            }

            // Register Form //
            if ( isset($_POST['register']) ) {
                header("Location: register.php");
            }

        }

    }

}

// ******************************************************************************************* //

class register extends Auth {


    function registerRun() {




    // Register Form Posted and Post Data Exists
    if ( isset($_POST['name']) && isset($_POST['email']) && isset($_POST['team_name'])  && isset($_POST['is_admin']) && isset($_POST['is_active']) && isset($_POST['password'])) {
        
        // Register Form User Email Value Check This is Email Format Is Correct
        $email = $_POST['email'];
        if (!filter_var ($email, FILTER_VALIDATE_EMAIL)) {
            $error = "<h1 style='color: #e4ff00;'> Invalid email format </h1>";
            echo "<h1 style='color: #e4ff00;'>  Invalid Email Address! </h1>";
        }
        // Check This User Exists
        $query = $this->authRun()->prepare("  SELECT * FROM users WHERE email = ? ");
        $query->execute([$email]);
        $result = $query->rowCount();
        if ($result > 0 ) {
            $error = "<h1 style='color: #e4ff0078;'> Email Already Exists!  </h1>";
            echo "<h1 style='color: #e4ff0078;'>  Email Already Exists!  </h1>";
        }



        // Register Form User Admin Status 
        // !!!! IF YOU GOT TROUBLE WITH REGISTERATION PRCEOSS YOU CAN REPLACE İS ADMIN STATEMENT BELOW WITH this => $is_admin = intval($_POST['is_admin']);  
        $is_admin = $_POST['is_admin'];
        // !!!!





        $team_name = $_POST['team_name'];

        // Check Team Name Exists
        $queryTeamName = $this->authRun()->prepare("  SELECT * FROM teams WHERE team_name = ? ");
        $queryTeamName->execute([$team_name]);
        $theValueResultCount = $queryTeamName->rowCount();
        $teamValRowResults = $queryTeamName->fetch(PDO::FETCH_ASSOC);

        // If User Selected Admin     
        if ($is_admin === "1") {

            
            if ($theValueResultCount > 0 ) {
                $error = "<h1 style='color: #e4ff00;'>  Team  Already Exists! Please try a different one. </h1>";
                echo "<h2 style='color: #e4ff00;'>   This Team & PO/SM  Already Exists!  <br> Please select 'Developer' option to join your team.  </h2>";
            }

        } 

        // If User Selected Default Member
        if ($is_admin === "0") {

            
            if ($theValueResultCount < 1 ) {
                $error = "<h1 style='color: #e4ff00;'> The team does not exist. Please add an exist team name. </h1>";
                echo "<h1 style='color: #e4ff00;'>  The team does not exist! </h1>";
            }


        } 

        

        // If There is no error above
        if (empty($error)) {

            // Password Hashed
            $password = $_POST["password"];
            $hash = md5($password);
            

           
            function  createSessionTimeout() {
                // Start Session
                $_SESSION["email"] = $_POST["email"]; 
                $_SESSION['start'] = time(); 
                $_SESSION['expire'] = $_SESSION['start'] + (30 * 60);
            };
            function redirectToDailyScrumFrom() {
                // Redirection
                header("Location: daily-scrum-form.php");
            };

            
            
            // If User is Admin
            if ($is_admin === "1") {
                
                // Team Query
                $insertTeamsSqlQuery = "INSERT INTO teams (team_name, team_created_date, admin_email, admin_name) 
                VALUES (:team_name, :team_created_date, :admin_email, :admin_name)";
                $stmtTheNewTeam = $this->authRun()->prepare($insertTeamsSqlQuery);
                // Insert Team
                $stmtTheNewTeam->execute(array(
                    ':team_name' => strtolower($_POST['team_name']),
                    ':team_created_date' => date("d-m-Y"),
                    ':admin_email' => $_POST['email'],
                    ':admin_name' => $_POST['name']
                )); 

                // Check Inserted Team Data Exist
                $checkInsertedDataQuery = $this->authRun()->prepare("  SELECT * FROM teams WHERE team_name = ? ");
                $checkInsertedDataQuery->execute([$team_name]);
                $justInsertedTeamValuesResults = $checkInsertedDataQuery->fetch(PDO::FETCH_ASSOC);


                if ($justInsertedTeamValuesResults['team_id']) {

                  // Insert User
                  $insertUserSqlQuery = "INSERT INTO users (name, email, team_name, is_admin, is_active, password, team_id) 
                  VALUES (:name, :email, :team_name, :is_admin, :is_active, :password, :team_id)";
                  $stmtTheNewUser = $this->authRun()->prepare($insertUserSqlQuery);
                  $stmtTheNewUser->execute(array(
                      ':name' => $_POST['name'],
                      ':email' => $_POST['email'],
                      ':team_name' => strtolower($_POST['team_name']),
                      ':is_admin' => $_POST['is_admin'],
                      ':is_active' => $_POST['is_active'],
                      ':password' => $hash,
                      ':team_id' => $justInsertedTeamValuesResults['team_id']
                  ));
  
                  createSessionTimeout();
                  redirectToDailyScrumFrom();
              
            
                }
                


            }

            // If User Default Member
            if ($is_admin === "0") {

                
                // Insert User
                $insertUserSqlQuery = "INSERT INTO users (name, email, team_name, is_admin, is_active, password, team_id) 
                VALUES (:name, :email, :team_name, :is_admin, :is_active, :password, :team_id)";
                $stmtTheNewUser = $this->authRun()->prepare($insertUserSqlQuery);
                $stmtTheNewUser->execute(array(
                    ':name' => $_POST['name'],
                    ':email' => $_POST['email'],
                    ':team_name' => strtolower($_POST['team_name']),
                    ':is_admin' => $_POST['is_admin'],
                    ':is_active' => $_POST['is_active'],
                    ':password' => $hash,
                    ':team_id' => $teamValRowResults['team_id']
                ));
                createSessionTimeout();
                redirectToDailyScrumFrom();

            }



            




        }






    }
    if ( isset($_POST['loginForm']) ) {
        header("Location: login.php");
    }





    }


}

// ******************************************************************************************* //

class dailyScrumFormSubmit extends Auth {

    function dailyScrumFormSubmitRun() {

        if(isset($_SESSION["email"]))  
        {  

            // (3)
            // Check if session expired redirect it to login page
            $now = time(); // Checking the time now when home page starts.
            if ($now > $_SESSION['expire']) {
                session_destroy();
                echo "Your session has expired! <a href='http://localhost/somefolder/login.php'>Login here</a>";
                header("location:login.php");  
            }
            else {

                // (4) 
                // Retrieve User Name By Session Email      
                $theUserSessionEmail = $_SESSION['email'];
                $SqlQueryToRetrieveUserData = $this->authRun()->prepare('SELECT * FROM users WHERE email = ?');
                $SqlQueryToRetrieveUserData->execute(array($theUserSessionEmail));

                $userData = $SqlQueryToRetrieveUserData->fetch(PDO::FETCH_ASSOC);
                if(!empty($userData)) {
                    
                
                    // (5)
                    // Add DailyNotes Into daily //     
                    if ( !empty($_POST['wdydy']) &&  isset($_POST['wdydy']) && !empty($_POST['wwydt'])  &&  isset($_POST['wwydt']) && !empty($_POST['itai']) && isset($_POST['itai']) && !empty($_SESSION['email']) && isset($_SESSION['email']) && isset($_POST['is_note_active'])   )  {
                        
                        
                        
                        
                        
                        $selectDailyNoteSqlQuery = "INSERT INTO daily (wdydy, wwydt, itai, is_note_active, daily_created_date, team_id, user_id)      
                                    VALUES (:wdydy, :wwydt, :itai, :is_note_active, :daily_created_date, :team_id, :user_id)";          
                        

                        
                        
                        
                            
                            
                            
                            
                            $stmtDailyNotes = $this->authRun()->prepare($selectDailyNoteSqlQuery);     
                            $stmtDailyNotes->execute(array(  
                                ':wdydy' => $_POST['wdydy'],
                                ':wwydt' => $_POST['wwydt'],
                                ':itai' => $_POST['itai'],
                                ':is_note_active' => $_POST['is_note_active'],
                                ':daily_created_date' => date("d-m-Y"), /* $_POST['daily_created_date'], */
                                ':team_id' =>  $userData['team_id'] ,
                                ':user_id' => $userData['user_id']
                            ));
                            header("Location: daily-scrum.php");     
                        }

                }    
            }
        }  
        else  {  
        header("location:login.php");  
        } 

    } 

}

// ******************************************************************************************* //

class dailyScrumTableView extends Auth {
    public function dailyScrumTableViewUserData() {
        $theUserSessionEmail = $_SESSION['email'];
        $SqlQueryToRetrieveUserData = $this->authRun()->prepare('SELECT * FROM users WHERE email = ?');
        $SqlQueryToRetrieveUserData->execute(array($theUserSessionEmail));
        $userData = $SqlQueryToRetrieveUserData->fetch(PDO::FETCH_ASSOC);
        return $userData;
    }
    public function dailyScrumTableViewUserDataCheck($userData) {

        if(!empty($userData)) {
            $userDataName = $userData['name'];
            $queryUserNameAndTeamName = $this->authRun()->prepare('SELECT users.name, teams.team_name FROM users JOIN teams ON users.team_id = teams.team_id  WHERE users.name = ?');
            $queryUserNameAndTeamName->execute(array($userDataName));
            $resultsQueryUserNameAndTeamName = $queryUserNameAndTeamName->fetch(PDO::FETCH_ASSOC);
            echo '<br> <br><h1 style="color: white;"> Daily Scrum Standup Notes </h1>';
            echo '<br><h3 style="color: white;">Hello '.$resultsQueryUserNameAndTeamName["name"]. '<br><span>  [ '. $resultsQueryUserNameAndTeamName['team_name'] . ' - Scrum Team ]</span> </h3>';  
        } else {
            echo "Error: dailyScrumTableViewUserDataCheck";
        }
    }
    public function dailyScrumTableViewDeleteRowData($userData) {
    
        
        if(!empty($userData)) {

            if (isset($_POST['delete']) && isset($_POST['daily_id']) && isset($_POST["email"]) ) {

                if ($userData['is_admin'] === "0") {

                    if ( $_POST['email'] == $_SESSION['email'] ) {

                        $selectUserSqlQuery = "UPDATE daily SET is_note_active = 0 WHERE (daily_id = :daily_id AND daily_created_date = :daily_created_date)";
                            $stmtUser = $this->authRun()->prepare($selectUserSqlQuery);
                            $stmtUser->execute(array(
                                ":daily_id" => $_POST["daily_id"],
                                ":daily_created_date" => date("d-m-Y")
                        ));

                        header("Refresh:0");

                    }


                } else {

                    
                    // Only Today Version // 
                    /* 
                    $selectUserSqlQuery = "UPDATE daily SET is_note_active = 0 WHERE (daily_id = :daily_id  AND daily_created_date = :daily_created_date)";
                    $stmtUser = $this->authRun()->prepare($selectUserSqlQuery);
                    $stmtUser->execute(array(
                        ":daily_id" => $_POST["daily_id"],
                        ":daily_created_date" => date("d-m-Y") // Only Today's notes can be deleted by the Admin User
                    ));
                    */

                    $selectUserSqlQuery = "UPDATE daily SET is_note_active = 0 WHERE (daily_id = :daily_id )";
                    $stmtUser = $this->authRun()->prepare($selectUserSqlQuery);
                    $stmtUser->execute(array(
                        ":daily_id" => $_POST["daily_id"]
                        // ":daily_created_date" => date("d-m-Y") // Only Today's notes can be deleted by the Admin User
                    ));

                    header("Refresh:0");

                }

            }
        } else  { 
            echo "User Data Error: dailyScrumTableViewDeleteRowData";

        }
    }
    public function dailyScrumTableViewFetchAndDisplayData($userData) {




        if(!empty($userData)) {


            $stmtDailyNotes = $this->authRun()->query("SELECT daily_id, daily_created_date, wdydy, wwydt, itai, is_note_active, user_id, team_id FROM daily");
            $rows = $stmtDailyNotes->fetchAll(PDO::FETCH_ASSOC);


            echo 
            '
                <div class="container-the100">
                <div  class="wrap-the100">
                <div class="table-wrap">
                <table border="1" class="table table-striped" style=" width: 100%;">
                <thead>
                    <tr>
                        <th> User Name</th>
                        <th> Date </th>
                        <th> Yesterday </th>
                        <th> Today </th>
                        <th> Impediment </th>
                        <th> </th>
                    </tr>
                </thead>
                <tbody>
            '
            ;

                foreach ( $rows as $row ) {

                    // Retrieve Row User Data by Email
                    $theRowUserId = $row['user_id'];
                    $SqlQueryToRetrieveRowUserData = $this->authRun()->prepare('SELECT * FROM users WHERE user_id = ?');
                    $SqlQueryToRetrieveRowUserData->execute(array($theRowUserId));
                    $RowUserData = $SqlQueryToRetrieveRowUserData->fetch(PDO::FETCH_ASSOC);

                    if ($row['team_id'] === $userData["team_id"]) {

                        if ($row['is_note_active'] === "1") {
                            if ($RowUserData['team_name'] === $userData["team_name"]) {
                                if ($userData['is_admin'] === "0") {
                                        echo "<tr><td>";
                                        echo($RowUserData['name']);
                                        echo("</td><td>");     
                                        echo($row['daily_created_date']);
                                        echo("</td><td>");
                                        echo($row['wdydy']);
                                        echo("</td><td>");
                                        echo($row['wwydt']);
                                        echo("</td><td>");
                                        echo($row['itai']);
                                        echo("</td><td>");
                                            echo('<form method="post"><input type="hidden" ');
                                                    echo('name="email" value="'.$RowUserData['email'].'">'."\n");
                                                echo('<input type="hidden" ');
                                                    echo('name="daily_id" value="'.$row['daily_id'].'">'."\n");
                                                if ($_SESSION["email"] === $RowUserData["email"] && $row["daily_created_date"] ===  date("d-m-Y")) {
                                                    echo('<input style="background: none; color: #a10000;     font-weight: bold;" type="submit" value="Remove" name="delete">');
                                                }
                                            echo("\n</form>\n");
                                        echo("</td></tr>\n");
                            } else {
                                echo "<tr><td>";
                                        echo($RowUserData['name']);
                                        echo("</td><td>");     
                                        echo($row['daily_created_date']);
                                        echo("</td><td>");
                                        echo($row['wdydy']);
                                        echo("</td><td>");
                                        echo($row['wwydt']);
                                        echo("</td><td>");
                                        echo($row['itai']);
                                        echo("</td><td>");
                                        echo('<form method="post"><input type="hidden" ');
                                        echo('name="email" value="'.$RowUserData['email'].'">'."\n");
                                        echo('<input type="hidden" ');
                                        echo('name="daily_id" value="'.$row['daily_id'].'">'."\n");
                                        echo('<input style="background: none; color: #a10000;     font-weight: bold;" type="submit" value="Remove" name="delete">');
                                        
                                }
                            } 
                        } 
                    } 
                }
                                    
            echo 
                '
                    </tbody>
                    </table>
                    </div>
                    <p style="padding:0px; padding-bottom: 5px;" class="container-the100-form-btn">
                        <span style="color: transparent;font-weight: bold;"></span>or
                    </p>
                    <div style="padding:0px;" class="container-the100-form-btn">    
                        <a href="daily-scrum-form.php">Create Note</a>
                        <p style="color: transparent;">----<span style="color: #d4d4e2;font-weight: bold;">|</span>----</p>
                        <a href="users.php">Scrum Team</a>
                    </div>
                    </div>
                    </div>

                '
                ;
                
            


        } else  { 
            echo "User Data Error: dailyScrumTableViewDeleteRowData";

        }



    }
}

// ******************************************************************************************* //

class usersTableView extends Auth {
    public function usersTableViewViewUserData() {
        $theUserSessionEmail = $_SESSION['email'];
        $SqlQueryToRetrieveUserData = $this->authRun()->prepare('SELECT * FROM users WHERE email = ?');
        $SqlQueryToRetrieveUserData->execute(array($theUserSessionEmail));
        $userData = $SqlQueryToRetrieveUserData->fetch(PDO::FETCH_ASSOC);
        return $userData;
    }
    public function usersTableViewViewUserDataCheck($userData) {

        if(!empty($userData)) {
            $userDataName = $userData['name'];
            $queryUserNameAndTeamName = $this->authRun()->prepare('SELECT users.name, teams.team_name FROM users JOIN teams ON users.team_id = teams.team_id  WHERE users.name = ?');
            $queryUserNameAndTeamName->execute(array($userDataName));
            $resultsQueryUserNameAndTeamName = $queryUserNameAndTeamName->fetch(PDO::FETCH_ASSOC);
            echo '<br> <br><h1 style="color: white;"> Scrum Team Users </h1>';
            echo '<br><h3 style="color: white;">Hello '.$resultsQueryUserNameAndTeamName["name"]. '<br><span>  [ '. $resultsQueryUserNameAndTeamName['team_name'] . ' - Scrum Team ]</span> </h3>';  
        } else {
            echo "Error: dailyScrumTableViewUserDataCheck";
        }
    }









    public function usersTableViewViewDeleteRowData($userData) {
    
        
        if(!empty($userData)) {
        // (5)        
        // Delete rows only User Can Delete its own row from daily //
            if ( isset($_POST['delete']) && isset($_POST['user_id']) && isset($_POST['theEmail']) ) {   
                if ($userData['is_admin'] === "0") {
                    if ( $_POST['theEmail'] == $_SESSION['email'] ) {
                        $selectUserSqlQuery = "UPDATE users SET is_active = 0 WHERE (user_id = :user_id AND email = :email)";
                        $stmtUser = $this->authRun()->prepare($selectUserSqlQuery);
                        $stmtUser->execute(array(
                            ':user_id' => $_POST['user_id'],
                            ':email' => $_POST['theEmail']
                        
                        ));
                        header("location:logout.php");
                    }
                } else { 

                    $selectUserSqlQuery = "UPDATE users SET is_active = 0 WHERE (user_id = :user_id AND email = :email)";
                    $stmtUser = $this->authRun()->prepare($selectUserSqlQuery);
                    $stmtUser->execute(array(
                        ':user_id' => $_POST['user_id'],
                        ':email' => $_POST['theEmail']
                    
                    ));
                    header("Refresh:0");
                }

            }
            } else  { 
                echo "User Data Error: dailyScrumTableViewDeleteRowData";

            }
    }
    public function usersTableViewFetchAndDisplayData($userData) {




        if(!empty($userData)) {


            $stmtUser = $this->authRun()->query("SELECT name, email, password, user_id, team_name, is_admin, is_active FROM users");
            $rowsUser = $stmtUser->fetchAll(PDO::FETCH_ASSOC);


            echo 
            '
            <div class="container-the100">
                <div  class="wrap-the100">
                        <div class="table-wrap">
                            <table border="1"  class="table table-striped" style=" width: 100%;">
                                <thead>
                                <tr>
                                    <th> User Name</th>
                                    <th> Email </th>
                                    <th> Password </th>
                                    <th> Role </th>
                                    <th> Action </th>
                                </tr>
                            </thead> 
                            <tbody>

            '
            ;

            foreach ( $rowsUser as $row ) {


                    if ($row['is_active'] === "1") {
    
                        if ($row['team_name'] === $userData["team_name"]) {
    
    
                                if ($userData['is_admin'] === "0") {
                            
                                    echo "<tr><td>";
                                    echo($row['name']);
                                    echo("</td><td>");
                                    echo($row['email']);
                                    echo("</td><td class='hidetext'>");
                                    echo('****');
                               
    
    
                                    echo("</td><td>");
                                    if ($row["is_admin"] === "0" ) {
                                        echo('Developer');
                                    } else {
                                        echo(' PO / SM');
    
                                    }
    
    
    
    
                                    echo("</td><td>");
                                    echo('<form method="post"><input type="hidden" ');
                                    echo('name="user_id" value="'.$row['user_id'].'">'."\n");
                                    echo('<input type="hidden"');
                                    echo('name="theEmail" value="'.$row['email'].'">'."\n");
    
                                    /*
                                    if ($_SESSION["email"] === $row["email"]) {
                                        echo('<input style="background: none; color: #a10000;     font-weight: bold;"type="submit" value="Remove" name="delete">');
                                    } 
                                    */
    
    
                                    echo("\n</form>\n");
                                    echo("</td></tr>\n");
    
    
                                } else {
    
    
                                    echo "<tr><td>";
                                    echo($row['name']);
                                    echo("</td><td>");
                                    echo($row['email']);
                                    echo("</td><td class='hidetext'>");
                                    echo('****');
    
    
                                    echo("</td><td>");
                                    if ($row["is_admin"] === "0" ) {
                                        echo('Developer');
                                    } else {
                                        echo(' PO / SM');
    
                                    }
    
    
    
    
                                    echo("</td><td>");
                                    echo('<form method="post"><input type="hidden" ');
                                    echo('name="user_id" value="'.$row['user_id'].'">'."\n");
                                    echo('<input type="hidden"');
                                    echo('name="theEmail" value="'.$row['email'].'">'."\n");
    
                                    
                                    
                                    
                                    if ($_SESSION["email"] != $row["email"]) {
                                        echo('<input style="background: none; color: #a10000;     font-weight: bold;"type="submit" value="Remove" name="delete">');
                                    } else {
                                        echo('<span style="background: none; color: Green;     font-weight: bold;">  </span>');
    
                                    }
                                    
                                    
    
    
                                    echo("\n</form>\n");
                                    echo("</td></tr>\n");
    
    
                                    
                                }
    
    
















                                
    
                        } else {
                            // the users team names does not match therefore they are not in the same team.
                        } 
                        
                    } else {
                        // the users does not active
                    } 
        
            }
    
    
    
    
    
           
           
           
           
           
                          
            echo 
                '
                            </tbody>
                            </table>   
                        </div>
                            <p style="padding:0px; padding-bottom: 5px;" class="container-the100-form-btn">
                            <span style="color: transparent;font-weight: bold;">-----</span>or
                             </p>
                        <div style="padding:0px;" class="container-the100-form-btn">     
                            <a href="daily-scrum.php">Daily Notes</a>
                            <p style="color: transparent;">----<span style="color: #d4d4e2;font-weight: bold;">|</span>----</p>
                            <a href="logout.php">Log Out</a>
                        </div>
                    </div>
                </div>
                '
                ;
                
            















                

        } else  { 
            echo "User Data Error: dailyScrumTableViewDeleteRowData";

        }



    }
}





/*
echo "<pre>";
echo "_POST:";
print_r($_POST);
echo "</pre>";
*/



?>