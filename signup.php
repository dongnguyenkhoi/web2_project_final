<?php 

require('connect.php');
session_start();
$username = $username_err = "";
$password = $password_err = "";

if($_SERVER['REQUEST_METHOD'] == "POST"){
    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "* Please enter a username.";
    } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))){
        $username_err = "Username can only contain letters, numbers, and underscores.";
    } else{
        // Prepare a select statement
        $query = "SELECT user_id FROM user WHERE user_name = :username";
        
        if($statement = $db->prepare($query)){
            // Set parameters
            $param_username = trim($_POST["username"]);

            // Bind variables to the prepared statement as parameters
            $statement->bindParam(":username", $param_username);
            
            // Attempt to execute the prepared statement
            if($statement->execute()){
                if($statement->rowCount() == 1){
                    $username_err = " * This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
    }
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = " * Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = " * Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    
   
    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err)){
        
        // Prepare an insert statement
        $query = "INSERT INTO user (user_name, password) VALUES (:username, :password)";
         
        if($statement = $db->prepare($query)){
            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            
            // Bind variables to the prepared statement as parameters
            $statement->bindParam(":username", $param_username);
            $statement->bindParam(":password", $param_password);
            
            
            // Attempt to execute the prepared statement
            if($statement->execute()){
                // Redirect to login page
                header("location: login.php");
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
    }
}
?>


<div class="container">
    <?php require("nav.php") ?>
    <div class="card p-3" style="max-width: 400px;">
    <h5>Use username or email for sign up</h5>
    <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

        <div class="mb-3 row">
            <label for="inputUsername" class="col-sm-4 col-form-label">Username or Email</label>
            <div class="col-sm-8">
                <input type="text" name="username" class="form-control <?= (!empty($username_err)) ? 'is-invalid' : ''; ?>" id="inputUsername">
                <span class=""><?= $username_err; ?></span>
            </div>
            <label for="inputPassword" class="col-sm-4 col-form-label">Password</label>
            <div class="col-sm-8">
                <input type="password" name="password" class="form-control <?= (!empty($password_err)) ? 'is-invalid' : ''; ?>" id="inputPassword" >
                <span class=""><?= $password_err; ?></span>
            </div>
            
            <button class="col-4 ms-auto mt-2 btn btn-primary" type="submit">Sign Up</button>
        </div>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </form>
    </div>
    <?php require("footer.php") ?>
</div>