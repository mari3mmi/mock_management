<?php
require '../db.php';
session_start();

// Verify user has logged in
if (!($_SESSION['loggedIn'])) {
    $_SESSION['message'] = "Login first!";
    header("location: ../error.php");
}

$username =$_SESSION['username'];
$userId = $_SESSION['userId'];
$genericId = $_SESSION['genericId'];
$role = $_SESSION['role'];

$sql = "SELECT * FROM Employee WHERE employeeId = $genericId";
$result = $mysqli->query($sql) or die($mysqli->error);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $firstName = $row["firstName"];
        $lastName = $row["lastName"];
    }
}
function getLatestContracts(){
    $mysqli = $GLOBALS['mysqli'];
    $sql = "SELECT * FROM Contract A1, Company A2, LineOfBusiness A3 WHERE startDate >= DATE_ADD(CURDATE(), INTERVAL -10 DAY) AND A1.companyId = A2.companyId AND A1.LineOfBusinessId = A3.LineOfBusinessId;";
    if ($result = $mysqli->query($sql)) {
        return $result;
    } else {
        die($mysqli->error);
    }
}

function showLatestContracts(){
    $result = getLatestContracts();

    echo "<table>";
    echo "<tr>";
    echo "<th>Company Id</th>";
    echo "<th>Company Name</th>";
    echo "<th>Type Of Service</th>";
    echo "<th>Type Of Contract</th>";
    echo "<th>Start Date</th>";
    echo "<th>Initial Amount</th>";
    echo "<th>ACV</th>";
    echo "<th>State</th>";
    echo "<th>Satisfaction</th>";
    echo "<th>Line Of Business</th>";
    echo "<tr>";

    if (mysqli_num_rows($result) > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['companyId'] . "</td>";
            echo "<td>" . ucwords($row['companyName']) . "</td>";
            echo "<td>" . ucwords($row['typeOfService']) . "</td>";
            echo "<td>" . ucwords($row['typeOfContract']) . "</td>";
            echo "<td>" . $row['startDate'] . "</td>";
            echo "<td>" . $row['initialAmount'] . "</td>";
            echo "<td>" . $row['ACV'] . "</td>";
            echo "<td>" . $row['state'] . "</td>";
            echo "<td>" . $row['satisfaction'] . "</td>";
            echo "<td>" . $row['businessTypeName'] . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr>";
        echo "<td colspan='3'>No Contracts</td>";
        echo "</tr>";
    }

    echo "</table>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CMS - Contracts</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
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
    <a href="employees.php" >
            <i class="fa fa-2x fa-book text-primary sr-icons"></i>
            Employees</a> <br />
    <a href="latestContracts.php" >
            <i class="fa fa-2x fa-tasks text-primary sr-icons"></i>
            Latest Contracts</a> <br /> <br />
    <a href="contractCategories.php" >
            <i class="fa fa-2x fa-tasks text-primary sr-icons"></i>
            Contract Categories</a> <br /> <br />  
    <a href="reportLineOfBusiness.php" >
            <i class="fa fa-2x fa-tasks text-primary sr-icons"></i>
            Report Line Of Business</a> <br /> <br />
    <a href="satisfaction.php" >
            <i class="fa fa-2x fa-tasks text-primary sr-icons"></i>
            Satisfaction</a> <br /> <br />  
    <a href="../logout.php" >
        <i class="fa fa-2x fa-power-off text-primary sr-icons"></i>
            Log out</a> <br />
</div>
        <br />
        <h2 class="text">Last 10 days contracts</h2>
        <br />
        <?=showLatestContracts()?>

        </div>
</body>

</html>
