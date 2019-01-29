<?php
    require '../db.php';
    session_start();

    $username = $_SESSION['username'];
    $userId = $_SESSION['userId'];
    $genericId = $_SESSION['genericId'];
    $role = $_SESSION['role'];

    $sql = "SELECT * FROM Employee WHERE employeeId = $genericId";
    $result = $mysqli->query($sql) or die($mysqli->error());
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $firstName = $row["firstName"];
            $lastName = $row["lastName"];
            $preferedService = $row["preferedService"];
        }
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['updatePreference'])) {
            $selectedVal = $_POST['typeOfServices'];

            $sql = "UPDATE Employee SET preferedService = '$selectedVal' WHERE employeeId = '$genericId'";
            if ($mysqli->query($sql) === true) {
                echo "<script type='text/javascript'>alert('Operation SuccessFul!');</script>";
                echo "<meta http-equiv='refresh' content='0'>";
            } else {
                echo "Error updating record: " . $mysqli->error;
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>CMS - <?=$firstName . " " . $lastName?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="Amarisse Brito-Martins">

    <link rel="icon" type="image/png" href="../images/icons/favicon.ico"/>
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet">

</head>

<body>

    <div class="container">

        <div class="sidenav">
            <p class="form-title"> Welcome <br />  <?=$firstName . " " . $lastName?></p>
            <br />
            <a href="preferences.php" >
                    <i class="fa fa-2x fa-book text-primary sr-icons"></i>
                    Preferences</a> <br />
            <a href="tasks.php" >
                    <i class="fa fa-2x fa-tasks text-primary sr-icons"></i>
                    Tasks</a> <br /> <br />
            <a href="../logout.php" >
                <i class="fa fa-2x fa-power-off text-primary sr-icons"></i>
                    Log out</a> <br />
        </div>

        <div class="preferences">
            <br />
            <h2 class="text"> Prefered Type of Service: <?=$preferedService?></h2>
            <br />

            <form action="preferences.php" class="pref" method="post">
                <label>Update Contract Preference</label>
                    <select name="typeOfServices">
                        <option value="silver">Silver</option>
                        <option value="premium">Premium</option>
                        <option value="gold">Gold</option>
                        <option value="diamond">Diamond</option>
                        <option value="none">None</option>
                    </select>
                <input type="submit" value="Update Preference" name="updatePreference" class="btn btn-success btn-sm">
            </form>

        </div>
    </div>

</body>

</html>
