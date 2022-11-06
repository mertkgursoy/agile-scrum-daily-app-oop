<?php 
    // Header
    include 'templates/header.php';
?>

<?php  
    // (1) Start Session     
    session_start();  
    // (2)
    // Add PDO Config
    require_once "pdo.php";
    require_once "class.php";
    $dailyScrumFormSubmit = new dailyScrumFormSubmit();
    $dailyScrumFormSubmit->dailyScrumFormSubmitRun();
?>


<div class="container-the100">
		        <div class="wrap-the100">
                    <form method="post" class="the100-form validate-form">
                        <span class="the100-form-title">
                            <?php
                                if(!empty($userData)) {
                                    $userDataName = $userData['name'];
                                    $queryUserNameAndTeamName = $this->authRun()->prepare('SELECT users.name, teams.team_name FROM users JOIN teams ON users.team_id = teams.team_id  WHERE users.name = ?');
                                    $queryUserNameAndTeamName->execute(array($userDataName));
                                    $resultsQueryUserNameAndTeamName = $queryUserNameAndTeamName->fetch(PDO::FETCH_ASSOC);
                                    echo '<br> <br><h1 style="color: dark-gray;">Create A Daily Note</h1>';
                                    echo '<br><h3 style="color: dark-gray;">Hello '.$resultsQueryUserNameAndTeamName["name"]. '<br><span>  [ '. $resultsQueryUserNameAndTeamName['team_name'] . ' - Scrum Team ]</span> </h3>';  
                                } 
                            ?>
                        </span>
                        <!--<p>Name:<input type="text" name="name" size="40"></p>-->
                        <!--<p>Date:<input type="text" name="daily_created_date"></p>-->
                        <div class="wrap-input100 validate-input">
                            <input class="input100" placeholder="Yesterday: What did you do yesterday?" required data-validate = "This field is required" type="text" name="wdydy">
                            <span class="focus-input100"></span>
                        </div>
                        <div class="wrap-input100 validate-input">
                            <input class="input100" placeholder="Today: What will you do today?" required data-validate = "This field is required" type="text" name="wwydt">
                            <span class="focus-input100"></span>
                        </div>
                        <div class="wrap-input100 validate-input">     
                            <input class="input100" placeholder="Impediment: Is there any impediment?" required data-validate = "This field is required" type="text" name="itai">
                            <span class="focus-input100"></span>
                        </div>
                        <div style=" display:none;  padding-right: 20px;    padding-left: 20px;" class="wrap-input100 validate-input">
                            <select style="    padding-left: 20px; padding-right: 20px;     height: 60px; border: none;" class="input100" placeholder="Active" required data-validate = "This field is required"  name="is_note_active">
                                <option  selected value="1"></option>
                            </select>
                            <span class="focus-input100"></span>
                        </div>
                        <div style="padding-top: 25px; padding-bottom:15px" class="container-the100-form-btn">
                            <button type="submit" value="Add New" class="the100-form-btn">Create</button>
                            <span>
						    </span>
                        </div>                
                    
                    
                    </form>
                

                    <p style="padding:0px; padding-bottom: 5px;" class="container-the100-form-btn">
            or
        </p>
        <div style="padding:0px;" class="container-the100-form-btn">

             <a href="daily-scrum.php">Daily Notes</a>
       
             <p style="color: transparent;">--<span style="color: #d4d4e2;font-weight: bold;">|</span>--</p>

             <a href="users.php">Scrum Team</a>
       
        </div>
                
                
                </div></div>


        
  


   


<?php
include "templates/footer.php";
?>