<?php
require '../db.php';
session_start();

global $selectedContract, $selectedTypeOfContract, $locationId;
$selectedContract = $_SESSION['selectedContract'];
$selectedTypeOfContract = $_SESSION['selectedTypeOfContract'];
$locationId = $_SESSION['locationId'];

$username = $_SESSION['username'];
$userId = $_SESSION['userId'];
$genericId = $_SESSION['genericId'];

$sql = "SELECT * FROM Employee WHERE employeeId = $genericId";
$result = $mysqli->query($sql) or die($mysqli->error());
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $firstName = $row["firstName"];
        $lastName = $row["lastName"];
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

function getEmployeesInContract()
{
    $mysqli = $GLOBALS['mysqli'];
    $selectedContract = $GLOBALS['selectedContract'];
    $selectedTypeOfContract = $GLOBALS['selectedTypeOfContract'];

    $sql = "SELECT Employee.* FROM WorksOn, Employee WHERE WorksOn.contractId = '$selectedContract' AND Employee.employeeId = WorksOn.employeeId";
    if ($result = $mysqli->query($sql)) {
        // return $result;
        return $result;
    } else {
        echo $mysqli->error;
        return;
    }
}

function getEmployeesNotInContract()
{
    $mysqli = $GLOBALS['mysqli'];
    $selectedContract = $GLOBALS['selectedContract'];
    $selectedTypeOfContract = $GLOBALS['selectedTypeOfContract'];
    $locationId = $GLOBALS['locationId'];

    $sql = "SELECT DISTINCT Employee.* FROM Employee WHERE Employee.employeeId NOT IN (SELECT t2.employeeId FROM WorksOn t2 WHERE contractId = '$selectedContract') AND preferedService = '$selectedTypeOfContract'";
    if ($result = $mysqli->query($sql)) {
        return $result;
    } else {
        echo $mysqli->error;
    }
}

function showEmployeesInContract()
{
    $result = getEmployeesInContract();
    echo "<form action='allocate.php' method='POST'>";
    echo "<table>";
    echo "<tr>";
    echo "<th>Id</th>";
    echo "<th>First Name</th>";
    echo "<th>Last Name</th>";
    echo "<tr>";

    if (mysqli_num_rows($result) > 0) {
        while ($row = $result->fetch_assoc()) {
            $employeeId = $row['employeeId'];
            echo "<tr>";
            echo "<td>" . $row['employeeId'] . "</td>";
            echo "<td>" . $row['firstName'] . "</td>";
            echo "<td>" . $row['lastName'] . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr>";
        echo "<td colspan='3'>No employees in this contract </td>";
        echo "</tr>";
    }

    echo "</table>";
    echo "</form>";
}

function showEmployeesNotInContract()
{
    $result = getEmployeesNotInContract();

    echo "<form action='allocate.php' method='post'>";
    echo "<table>";
    echo "<tr>";
    echo "<th>Id</th>";
    echo "<th>First Name</th>";
    echo "<th>Last Name</th>";
    echo "<tr>";

    if (mysqli_num_rows($result) > 0) {
        while ($row = $result->fetch_assoc()) {
            $employeeId = $row['employeeId'];
            echo "<tr>";
            echo "<td>" . $row['employeeId'] . "</td>";
            echo "<td>" . $row['firstName'] . "</td>";
            echo "<td>" . $row['lastName'] . "</td>";
            echo "<td><button name='selectedEmployee' value='$employeeId' type='submit' class='btn btn-success btn-sm'>Add</button></td>";
            echo "</tr>";
        }
    } else {
        echo "<tr>";
        echo "<td colspan='3'>All employees are in this contract </td>";
        echo "</tr>";
    }

    echo "</table>";
    echo "</form>";
}

if (isset($_POST['selectedEmployee'])) {
    $value = $_POST['selectedEmployee'];
    if (addEmployeeToWorkOn($value)) {
        echo "<script type='text/javascript'>alert('Operation SuccessFul!');</script>";
    echo "<meta http-equiv='refresh' content='0'>";
    } else {
         $_SESSION['message'] = "Error updating record: " . $mysqli->error;
        header("location: ../error.php");
    }
}

function addEmployeeToWorkOn($employeeId)
{
    $mysqli = $GLOBALS['mysqli'];
    $contractId = $GLOBALS['selectedContract'];
    $sql = "INSERT INTO WorksOn (employeeId, contractId) VALUE ($employeeId, $contractId)";
    if ($result = $mysqli->query($sql)) {
        if ($mysqli->affected_rows > 0) {
            return true;
        }
        return false;
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
        <br />
        <h2 class="text">
            Contract ID: <?=$selectedContract?>
        </h2>
        <br />
        <p>Employees working on this contract</p>


        <?=showEmployeesInContract();?>

            <p>Employees not working on this contract, prefer to work on
                <b>
                    <?=ucfirst($selectedTypeOfContract)?>
                </b> contracts.</p>
            <?=showEmployeesNotInContract();?>

    </div>
    </body>

    </html>