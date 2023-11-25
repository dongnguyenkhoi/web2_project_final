<?php 

require('connect.php');
session_start();
$username = $username_err = "";
$password = $password_err = "";
$captcha_err = "";
$confirm_password = $confirm_password_err = "";


if($_SERVER['REQUEST_METHOD'] == "POST"){
    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))){
        $username_err = "Username can only contain letters, numbers, and underscores.";
    } else{
        // Prepare a select statement
        $query = "SELECT user_id FROM users WHERE user_name = :username";
        
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
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = " * Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = " * Password did not match.";
        }
    }
   
    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){
        
        // Prepare an insert statement
        $query = "INSERT INTO users (user_name, password) VALUES (:username, :password)";
         
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
    <h3>Login</h3>
    <form action="login.php" method="post">

        <div class="mb-3 row">
            <label for="inputUsername" class="col-sm-4 col-form-label">Username or Email</label>
            <div class="col-sm-8">
                <input type="text" name="username" class="form-control" id="inputUsername">
            </div>
            <label for="inputPassword" class="col-sm-4 col-form-label">Password</label>
            <div class="col-sm-8">
                <input type="password" name="password" class="form-control" id="inputPassword">
            </div>
            
            <button class="col-4 ms-auto mt-2 btn btn-primary" type="submit">Sign Up</button>
        </div>
    </form>
    </div>
    <?php require("footer.php") ?>
</div>