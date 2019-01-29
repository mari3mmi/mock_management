<?php
/* Displays all error messages */
session_start();
?>
<!DOCTYPE html>
<html>
<head>
  <title>CMS - Error</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
    <link rel="stylesheet" type="text/css" href="css/style.min.css">
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    
</head>
<body>
        <div class="container">
                    <div class="col-md-12">
                        <div class="wrap">
                            <p class="form-title">
                            Error: 
                            <?php
                            if (isset($_SESSION['message']) and !empty($_SESSION['message'])):
                                echo $_SESSION['message'];
                            else:
                                header("location: index.php");
                            endif; ?> 
                            </p>
                            <form class="login">
                            <a href="index.php" class="btn btn-success btn-sm"/> Home </a>
                            </form>
                        </div>
                    </div>
            </div>
</body>
</html>
