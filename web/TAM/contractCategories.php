<?php
require '../db.php';
session_start();
$employeesFetched = false;

global $categories;
$categories = array(0=>"Gold", 1=>"Premium", 2=>"Silver",3=>"Diamond");

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
function showCategories()
{
    $categories = $GLOBALS['categories'];

    echo "<form action='contractCategories.php' method='POST'>";
    echo "<select name='category'>";
    foreach ($categories as $key => $value) {
        echo "<option value='$value'>$value</option>";
    }
    echo "</select>";
    echo "<input type='submit' value='get Contracts' name='postCategory'  class='btn btn-success btn-sm'>";
    echo "</form>";
}

function fetchContracts($typeOfContract)
{
    $mysqli = $GLOBALS['mysqli'];
    $sql = "SELECT * FROM Contract, Company, LineOfBusiness WHERE typeOfContract = '$typeOfContract' AND Contract.companyId = Company.companyId AND Contract.LineOfBusinessId = LineOfBusiness.lineOfBusinessId";
    if ($result = $mysqli->query($sql)) {
        return $result;
    } else {
        die($mysqli->error);
    }
}

function showContracts($result)
{
    echo "<table>";
    echo "<tr>";
    echo "<th>Contract ID</th>";
    echo "<th>Name</th>";
    echo "<th>Initial Amount</th>";
    echo "<th>ACV</th>";
    echo "<th>Type Of Service</th>";
    echo "<th>Type Of Contract</th>";
    echo "<th>Line Of Business</th>";
    echo "<th>Start Date</th>";
    echo "<th>Satisfaction</th>";
    echo "<tr>";

    if (mysqli_num_rows($result) > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['contractId'] . "</td>";
            echo "<td>" . ucwords($row['companyName']) . "</td>";
            echo "<td>" . ucwords($row['initialAmount']) . "</td>";
            echo "<td>" . ucwords($row['ACV']) . "</td>";
            echo "<td>" . ucwords($row['typeOfService']) . "</td>";
            echo "<td>" . ucwords($row['typeOfContract']) . "</td>";
            echo "<td>" . ucwords($row['businessTypeName']) . "</td>";
            echo "<td>" . $row['startDate'] . "</td>";
            echo "<td>" . $row['satisfaction'] . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr>";
        echo "<td colspan='3'>No Contracts</td>";
        echo "</tr>";
    }

    echo "</table>";
}



if (isset($_POST['postCategory'])) {
    if (isset($_POST['category'])) {
        $value = $_POST['category'];
        $result = fetchContracts($value);
        $GLOBALS['contractSelected'] =  $value;
        $GLOBALS['employeesFetched'] = true;
    }
}

?>

<!DOCTYPE html>
    <html lang="en">

    <head>
    <meta charset="UTF-8">
    <title>CMS - Contracts </title>
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
    <div class="form">
        <br />
        <h2 class="text"> Contracts per Category </h2>
        <br />
        <?php 
            if (isset($employeesFetched) && $employeesFetched == true) {
                $value = $GLOBALS['contractSelected'];
                echo "<h4 class='text'>Category: $value</h4><pre></pre>";
            }
        ?>
            <?=showCategories();?>
        <?php
            if (isset($employeesFetched) && $employeesFetched == true) {
                showContracts($result);
        }
        ?>
    </div>
</div>
</body>
</html>
