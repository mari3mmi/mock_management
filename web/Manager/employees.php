<?php
require '../db.php';
session_start();

// Verify user has logged in
if (!($_SESSION['loggedIn'])) {
    $_SESSION['message'] = "Login first!";
    header("location: ../error.php");
}
$username = $_SESSION['username'];
$userId = $_SESSION['userId'];
$genericId = $_SESSION['genericId'];

$sql = "SELECT * FROM Employee WHERE employeeId = $genericId";
$result = $mysqli->query($sql) or die($mysqli->error());
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $firstName = $row["firstName"];
        $lastName = $row["lastName"];
        $preferedService = $row["preferedService"];
    }
}
global $locationId, $departmentId;

$sql = "SELECT locationId, departmentId FROM Manager WHERE managerId = '$genericId'";
$result = $mysqli->query($sql) or die($mysqli->error);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $locationId = $row['locationId'];
    $departmentId = $row['departmentId'];
}


// Get Location Name
$sql = "SELECT name FROM Location WHERE locationId = '$locationId'";
$result = $mysqli->query($sql) or die($mysqli->error);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $locationName = $row['name'];
}

// Get Department Name
$sql = "SELECT * FROM Department WHERE departmentId = '$departmentId'";
$result = $mysqli->query($sql) or die($mysqli->error);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $departmentName = $row['name'];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['employeeSelected'])) {
        $value = $_POST['employeeSelected'];
        $_SESSION['selectedEmployeeId'] = $value;
        header("location: details.php");
    }
}

function getEmployees($mysqli, $locationId){
    // Get all employees from a given location
$sql = "SELECT * FROM Employee WHERE locationId = '$locationId' AND role = 'developer'";
$employees = $mysqli->query($sql) or die($mysqli->error);
    while($row = $employees->fetch_assoc()){
        echo "<tr>";
        $id = $row['employeeId'];
        echo "<td>" . $id ."</td>";
        echo "<td>" . $row['firstName'] . " " . $row["lastName"] ."</td>";
        echo "<td>" . ucfirst($row['role']) ."</td>";
        echo "<td>" . ucfirst($row['insurancePlan']) ."</td>";
        echo "<td>" . ucfirst($row['preferedService']) ."</td>";
        //echo "<input type='hidden' name='employeeSelected' value='$id'>"."</td>";
        //echo "<td><input value='Select' type='submit' class='btn btn-success btn-sm'></td>";
        echo "<td><button name='employeeSelected' value='$id' type='submit' class='btn btn-success btn-sm'>Select</button></td>";
        echo "</tr>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manager Dashboard</title>
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
        <p class="form-title"> Welcome <br /><?=$firstName . " " . $lastName?></p>
    <br />
            <p class="text">Department:  <?=ucfirst($departmentName);?> </p>
            <p class="text">Location: <?= ucfirst($locationName);?> </p>
            <br />
            <a href="contracts.php">
                    <i class="fa fa-2x fa-book text-primary sr-icons"></i>
                    Contracts</a> <br />
            <a href="employees.php">
                    <i class="fa fa-2x fa-tasks text-primary sr-icons"></i>
                    Employees</a> <br /> <br />
            <a href="../logout.php">
                <i class="fa fa-2x fa-power-off text-primary sr-icons"></i>
                    Log out</a> <br />
        </div> 
    <body>
        <br />
        <h2 class="text">Select an employee to update details</h2>
        <br />

        <form action="employees.php" method="POST">
            <table>
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Role</th>
                    <th>Insurance Plan</th>
                    <th>Prefered Service</th>
                    <th></th>
                </tr>
                <?php getEmployees($mysqli, $locationId);?>
            </table>
        </form>
    </body>
</div>

    </html>