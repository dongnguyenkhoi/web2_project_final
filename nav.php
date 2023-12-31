<?php 
/*******w******** 
    
    Name: Khoi Dong
    Date: Nov 15
    Description: Final Project

****************/
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <title>Final Project</title>
</head>
<body>
<nav class="navbar navbar-expand-sm bg-body-tertiary mb-3">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">WINNIE CAR</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="index.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="make.php">Make</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="login.php">Login</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="logout.php">Logout</a>    
        </li>
        <li class="nav-item">
        <?php
            if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){ ?>
                <a class="nav-link" href="signup.php">Sign Up</a>    
            <?php } ?>
        </li>
      </ul>
      <form  action="index.php" method="get">
            <div class="input-group" style="max-width:20rem;">
                <input class="form-control" type="search" placeholder="search by make" name="make_search">
                <button class="btn btn-primary">Search</button>
            </div>
        </form>
    </div>
  </div>
</nav>
