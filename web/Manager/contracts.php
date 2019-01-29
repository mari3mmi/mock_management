<?php
require '../db.php';
session_start();

// Verify user has logged in
if (!($_SESSION['loggedIn'])) {
    $_SESSION['message'] = "You must login first!";
    header("location: ../error.php");
}
global $genericId;
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

function getLocation()
{
    $mysqli = $GLOBALS['mysqli'];
    $genericId = $GLOBALS['genericId'];
    $sql = "SELECT locationId, departmentId FROM Manager WHERE managerId = $genericId";
    if ($result = $mysqli->query($sql)) {
        $manager = $result->fetch_assoc();
        global $locationId, $departmentId;
        $locationId = $manager['locationId'];
        $departmentId = $manager['departmentId'];
        $_SESSION['locationId'] = $locationId;
        $_SESSION['departmentId'] = $departmentId;
        unset($result);
        unset($manager);
    }
}

getLocation();


function getContracts()
{
    $mysqli = $GLOBALS['mysqli'];
    $sql = "SELECT contractId, companyName, initialAmount, ACV, typeOfService, typeOfContract FROM Contract LEFT JOIN Company ON Contract.companyId = Company.companyId";
    if ($result = $mysqli->query($sql)) {
        return $result;
    }
}

function showContracts($array)
{
    echo "<form action='contracts.php' method='post'>";
    echo "<table>";
    echo "<tr>";
    echo "<th>Company Name</th>";
    echo "<th>Initial Amount</th>";
    echo "<th>ACV</th>";
    echo "<th>Type Of Service</th>";
    echo "<th>Type Of Contract</th>";
    echo "<th></th>";
    echo "<tr>";

    while ($row = $array->fetch_assoc()) {
        $contractId = $row['contractId'];
        $typeOfContract = $row['typeOfContract'];
        echo "<tr>";
        echo "<td>" . $row['companyName'] . "</td>";
        echo "<td>" . $row['initialAmount'] . "</td>";
        echo "<td>" . $row['ACV'] . "</td>";
        echo "<td>" . $row['typeOfService'] . "</td>";
        echo "<td>" . $typeOfContract ."<input type='hidden' name='typeOfContract' value='$typeOfContract'>"."</td>";
        echo "<input type='hidden' name='selectedContract' value='$contractId'>"."</td>";
        echo "<td><input type='submit' value='Select' class='btn btn-success btn-sm'></td>";
        echo "</tr>";
    }

    echo "</table>";
    echo "</form>";
}

// Post results
if (isset($_POST['selectedContract'])) {
    $selectedContract = $_POST['selectedContract'];
    $selectedTypeOfContract = $_POST['typeOfContract'];
    $_SESSION['selectedContract'] = $selectedContract;
    $_SESSION['selectedTypeOfContract'] = $selectedTypeOfContract;
    header("location: allocate.php");
}

$contracts = getContracts($mysqli);
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
        <h2 class="text">Allocation</h2>
        <br />
        <p>Select the contract you wish to allocate a developer to.</p>
        <!-- Contracts Table -->
        <?=showContracts($contracts);?>
        </div>
 </body>


    </html>