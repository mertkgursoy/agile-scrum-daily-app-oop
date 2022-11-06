
<?php 
 // Header
 include 'templates/header.php';
?>


<?php
    // (1)
    // Start Session
    session_start();  

    // (2)
    /* */
    /* */
    require_once "pdo.php";
    require_once "class.php";
    $login = new login();
    $login->loginRun();
    /* */   
    /* */

    // p' OR '1' = '1
  
?>





<div class="container-the100">
		        <div class="wrap-the100">
                    <form method="post" class="the100-form validate-form">
                    <span class="the100-form-title"> 
                            Daily Scrum Tool
                        </span>
                        <div class="wrap-input100 validate-input">
                            <input style="height: 60px;" class="input100" placeholder="Email"  required data-validate = "This field is required" type="email" name="email">
                            <span class="focus-input100"></span>
                        </div>
                        <div class="wrap-input100 validate-input">
                            <input style="height: 60px;" class="input100" placeholder="Password"  required  data-validate = "This field is required" type="password" name="password">
                            <span class="focus-input100"></span>
                        </div>
                        <div style="padding-top: 25px; padding-bottom:15px" class="container-the100-form-btn">
                            <button style="margin-right: 15px; margin-left:15px;"    type="submit" value="Login" class="the100-form-btn">Log In</button>
                            <span>
						    </span>
                            <!--
                            button style="margin-right: 15px; margin-left:15px;" class="the100-form-btn">
                                <a href="<?php echo($_SERVER['PHP_SELF']);?>">Refresh</a>
                            </button>
                            -->
                        </div> 
</form>


<p style="padding:0px; padding-bottom: 5px;" class="container-the100-form-btn">
            or
        </p>
        <div style="padding:0px;" class="container-the100-form-btn">
            <a href="register.php">Create A New User</a>
        </div>



</div></div>



<?php
    include "templates/footer.php"; // Footer
?>