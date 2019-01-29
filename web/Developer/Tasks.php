<?php
require '../db.php';
session_start();
$username = $_SESSION['username'];
$userId = $_SESSION['userId'];
$genericId = $_SESSION['genericId'];
$role = $_SESSION['role'];

$sql = "SELECT * FROM Employee WHERE employeeId = '$genericId'";
$result = $mysqli->query($sql) or die($mysqli->error);
if ($result->num_rows > 0) {
    $employee = $result->fetch_assoc();
    $firstName = $employee["firstName"];
    $lastName = $employee["lastName"];
    $preferedService = $employee["preferedService"];
}
$sql = "SELECT * FROM WorksOn WHERE employeeId = '$genericId'";
$tableRows = $mysqli->query($sql) or die($mysqli->error);

$sql = "SELECT contractId FROM WorksOn WHERE employeeId = '$genericId'";
$contractIds = $mysqli->query($sql) or die($mysqli->error);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['updateHours'])) {
        $selectedContractId = $_POST['contractId'];
        $numberOfMinutes = $_POST['numberOfMinutes'];
        // minutes to hour
        $sql = "SELECT hours FROM WorksOn WHERE employeeId = '$genericId' AND contractId = '$selectedContractId'";
        // current time in hours
        $result = $mysqli->query($sql) or die($mysqli->error);
        $row = $result->fetch_assoc();

        $currentTime = $row['hours'];
        // transform hours to minutes
        $currentTime = $currentTime * 60;
        $currentTime += $numberOfMinutes;

        $currentTime /= 60;        // echo "Minutes: $currentTime";
        $currentTime = number_format((float) $currentTime, 2);

        $sql = "UPDATE WorksOn SET hours = '$currentTime' WHERE employeeId = '$genericId' AND contractId = '$selectedContractId'";

        // echo "contractId: " . $selectedContractId . " minutes: " . $numberOfMinutes;
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
<html>

<head>
        <title>Welcome
        <?=$firstName . " " . $lastName?>
            </title>
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
            <p class="form-title"> Welcome <br /> <?=$firstName . " " . $lastName?></p>
            <br />
            <a href="preferences.php">
                    <i class="fa fa-2x fa-book text-primary sr-icons"></i>
                    Preferences</a> <br />
            <a href="tasks.php">
                    <i class="fa fa-2x fa-tasks text-primary sr-icons"></i>
                    Tasks</a> <br /> <br />
            <a href="../logout.php">
                <i class="fa fa-2x fa-power-off text-primary sr-icons"></i>
                    Log out</a> <br />
        </div>


        <div class="tasks"> 
            <br />
                <h2 class="text">Update hours</h2>
                <br />
                    <table>
                        <tr>
                            <th>Contract ID</th>
                            <th>Task</th>
                            <th>Hours</th>
                        </tr>
                        <!--<?php
                            while ($row = $tableRows->fetch_assoc()) { 
                                echo "--><tr class='clickable_row'><!--";
                                echo "--><td>" . $row["contractId"] . "</td><!--";
                                echo "--><td>" . $row["task"] . "</td><!--";
                                echo "--><td>" . number_format((float) $row["hours"], 2) . "</td><!--";
                                echo "--></tr><!--";
                            }
                        ?>-->

                    </table>
            <div class="tasks">
            <form action="tasks.php" class="task" method="post">
                <label>contractId to update: </label>
                <select name="contractId" class="block">
                    <?php
                        while ($row = $contractIds->fetch_assoc()) {
                            echo "<option value=" .$row['contractId'] .">" . $row['contractId'] . "</option>";
                        }
                    ?>
                </select>
                <label>Number of minutes to add: </label>
                <input type="number" name="numberOfMinutes" min="1" required class="block">
                <input type="submit" value="Update Hours" name="updateHours"  class="btn btn-success btn-sm block">
            </div>
        </div>
    </div>           
</body>

</html>