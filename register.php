

<?php 
 include 'templates/header.php';
?>


<?php  
    session_start();  
    require_once "pdo.php";
    require_once "class.php";
    $register = new register();
    $register->registerRun();

?>

<div class="container-the100">
		        <div class="wrap-the100">
                    <form method="post" class="the100-form validate-form">
                                <span class="the100-form-title">
                                    Create User
                                </span>
                                <div class="wrap-input100 validate-input">
                                    <input style="height: 60px;" class="input100" placeholder="Name" required data-validate = "This field is required" type="text" name="name">
                                    <span class="focus-input100"></span>
                                </div>

                                <div class="wrap-input100 validate-input">
                                    <input  style="height: 60px;" class="input100" placeholder="Email" required data-validate = "This field is required" type="email" name="email">
                                    <span class="focus-input100"></span>
                                </div>

                                <div class="wrap-input100 validate-input">
                                    <input style="height: 60px;" class="input100" placeholder="Password"  required data-validate = "This field is required" type="password" name="password">
                                    <span class="focus-input100"></span>
                                </div>

                                <div style="   padding-right: 20px;    padding-left: 20px;" class="wrap-input100 validate-input">
                                    <select style="    padding-left: 20px; padding-right: 20px;     height: 60px; border: none;" class="input100" placeholder="Member Type" required data-validate = "This field is required"  name="is_admin">
                                        <option  selected value="1"> Product Owner / Scrum Master</option>
                                        <option  value="0"> Developer</option>
                                    </select>
                                    <span class="focus-input100"></span>
                                </div>

                                <div style=" display:none;  padding-right: 20px;    padding-left: 20px;" class="wrap-input100 validate-input">
                                    <select style="    padding-left: 20px; padding-right: 20px;     height: 60px; border: none;" class="input100" placeholder="Active" required data-validate = "This field is required"  name="is_active">
                                        <option  selected value="1"></option>
                                    </select>
                                    <span class="focus-input100"></span>
                                </div>

                                <div class="wrap-input100 validate-input">
                                    <input  style="height: 60px; " class="input100" placeholder="Scrum Team Name" required data-validate = "This field is required" type="text" name="team_name">
                                    <span class="focus-input100"></span>
                                </div>

                                <div style="padding-top: 25px; padding-bottom:0px" class="container-the100-form-btn">
                                <button style="margin-right: 15px; margin-left:15px;" type="submit" value="Add New" class="the100-form-btn">Create</button>

                                    <span>
                                    </span>
                                </div> 

    </form>    <br/>
        <p style="padding:0px; padding-bottom: 5px;" class="container-the100-form-btn">
            or
        </p>
        <div style="padding:0px;" class="container-the100-form-btn">
            <a href="login.php">Log In</a>
        </div>

    </div></div>


    <?php
include "templates/footer.php"; 





?>


