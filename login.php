<?php
/*************** 
    Name: 
    Date: 
    Description: 

****************/
require('connect.php');

session_start();

if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true){
    header("Location: index.php");
    exit;
}

$username = $username_err = "";
$password = $password_err = "";
$login_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    print_r("server request method is POST");
 
    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = " * Please enter username or email.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = " * Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate credentials
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $query = "SELECT * FROM user WHERE user_name = :username";
        
        if($statement = $db->prepare($query)){
            // Set parameters
            $param_username = trim($_POST["username"]);
            
            // Bind variables to the prepared statement as parameters
            $statement->bindParam(":username", $param_username);             
            
            // Attempt to execute the prepared statement
            if($statement->execute()){
                // Check if username exists, if yes then verify password
                if($statement->rowCount() == 1){
                    
                    if($user = $statement->fetch()){
                        $id = $user["user_id"];
                        $username = $user["user_name"];
                        $password = $user["password"];
                        if($password === $_POST["password"]) {
                            print_r("password is correct");
                            // Password is correct, so start a new session
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;         
                            
                            // Redirect user to welcome page
                            header("Location: index.php");
                            exit;
                        } 
                        else{
                            // Password is not valid, display a generic error message
                            $login_err = " * Invalid password.";
                        }
                    }
                } 
                else{
                    // Username doesn't exist, display a generic error message
                    $login_err = " * Invalid username";
                }
            } 
            else{
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
        <p>
            <?php 
                if(!empty($login_err)){
                    echo $login_err;
                }        
            ?>
        </p>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

        <div class="mb-3 row">
            <label for="username" class="col-sm-4 col-form-label">Username or Email</label>
            <div class="col-sm-8">
                <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>"  value="<?php echo $username; ?>">
                <span class=""><?php echo $username_err; ?></span>
            </div>
            <label for="password" class="col-sm-4 col-form-label">Password</label>
            <div class="col-sm-8">
                <input type="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" name="password" value="<?php echo $password; ?>">
                <span class=""><?php echo $password_err; ?></span>
            </div>
            
            <button class="col-4 ms-auto mt-2 btn btn-primary" type="submit">Login</button>
        </div>
    </form>
    </div>
    <?php require("footer.php") ?>
</div>


  