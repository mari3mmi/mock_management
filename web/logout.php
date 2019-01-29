<?php
/* Log out process, unsets and destroys session variables */
session_start();
session_unset();
session_destroy(); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>CMS - Logout</title>
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
                            <p class="form-title"> <?= 'You have been logged out!'; ?> </p>
                            <form class="login">
                            
                            <a href="index.php" class="btn btn-success btn-sm">Home</a>
                            </form>
                        </div>
                    </div>
            </div>
            
</body>