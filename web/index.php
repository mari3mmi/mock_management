<?php
require 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require 'login.php';
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>CMS - Login</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="Amarisse Brito-Martins">
    
    <link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
    <link rel="stylesheet" type="text/css" href="css/style.min.css">
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    
</head>
<body>

        <div class="container">
                    <div class="col-md-12">
                        <div class="wrap">
                            <p class="form-title">
                            Welcome to CMS - Contract Management System</p>
                            <form class="login" action="index.php" method="post">
                            <input type="text" required name ="username" placeholder="Username" />
                            <input type="password" required name="password" placeholder="Password" />
                            <input type="submit" value="Sign In" name="login" class="btn btn-success btn-sm" />
                            </form>
                        </div>
                    </div>
            </div>
            
</body>