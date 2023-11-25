

<?php 
if($_SERVER["REQUEST_METHOD"]=="POST"){
    require('authenticate.php');
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
                <input type="text" class="form-control" id="inputUsername">
            </div>
            <label for="inputPassword" class="col-sm-4 col-form-label">Password</label>
            <div class="col-sm-8">
                <input type="password" class="form-control" id="inputPassword">
            </div>
            
            <button class="col-4 ms-auto mt-2 btn btn-primary" type="submit">Login</button>
        </div>
    </form>
    </div>
    <?php require("footer.php") ?>
</div>


  