<?php
require '../db.php';
session_start();
$employeesFetched = false;

// Verify user has logged in
if (!($_SESSION['loggedIn'])) {
    $_SESSION['message'] = "Login first!";
    header("location: ../error.php");
}
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
    }
}
function fetchBranches()
{
    $mysqli = $GLOBALS['mysqli'];
    $sql = "SELECT * FROM Location";
    if ($result = $mysqli->query($sql)) {
        return $result;
    } else {
        die($mysqli->error);
    }
}

function showBranches()
{
    $result = fetchBranches();
    echo "<form action='employees.php' class='pref' method='POST'>";
    echo "<select name='branch'>";
    if (mysqli_num_rows($result) > 0) {
        while ($row = $result->fetch_assoc()) {
            $locationId = $row['locationId'];
            $branchName= ucwords($row['name']);
            $GLOBALS['locationName'] = $branchName;
            echo "<option value='$locationId'>$branchName</option>";
        }
    }
    echo "</select>";
    echo "<input name='postBranch' value='Get Employess' type='submit' class='btn btn-success btn-sm block'>";
    echo "</form>";
}
function fetchEmployees($locationId)
{
    $mysqli = $GLOBALS['mysqli'];
    $sql = "SELECT Department.name, Employee.*, Location.name AS locationName FROM Employee, Department, Location  WHERE Employee.locationId = '$locationId' AND Employee.departmentId = Department.departmentId AND Employee.locationId = Location.locationId";
    if ($result = $mysqli->query($sql)) {
        return $result;
    } else {
        die($mysqli->error);
    }
}

function showEmployees($result)
{
    echo "<table>";
    echo "<tr>";
    echo "<th>Name</th>";
    echo "<th>Department</th>";
    echo "<th>Role</th>";
    echo "<th>Insurance Plan</th>";
    echo "<th>Prefered Service</th>";
    echo "<th>Location</th>";
    echo "<tr>";

    if (mysqli_num_rows($result) > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['firstName'] . ' ' . $row['lastName'] . "</td>";
            echo "<td>" . ucwords($row['name']) . "</td>";
            echo "<td>" . ucwords($row['role']) . "</td>";
            echo "<td>" . ucwords($row['insurancePlan']) . "</td>";
            echo "<td>" . ucwords($row['preferedService']) . "</td>";
            echo "<td>" . ucwords($row['locationName']) . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr>";
        echo "<td colspan='3'>No Contracts</td>";
        echo "</tr>";
    }

    echo "</table>";
}



if (isset($_POST['postBranch'])) {
    if (isset($_POST['branch'])) {
        $locationId = $_POST['branch'];
        $result = fetchEmployees($locationId);
        $GLOBALS['employeesFetched'] = true;
    }
}

?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
    <meta charset="UTF-8">
    <title>CMS - Employees</title>
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
        <h2 class="text">Employees in branch </h2>
        <br />
        <?=showBranches();?>
        <?php
            if (isset($employeesFetched) && $employeesFetched == true) {
                showEmployees($result);
            }
        ?>
        </div>
    </body>

    </html>
