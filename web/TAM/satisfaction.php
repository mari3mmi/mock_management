<?php
require '../db.php';
session_start();

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

function fetchTable($type)
{
    $mysqli = $GLOBALS['mysqli'];
    $sql = "SELECT name, companyName, satisfaction FROM Company NATURAL JOIN Contract NATURAL JOIN Address NATURAL JOIN City WHERE typeOfContract = '$type' and state = 'expired' GROUP BY name, companyName DESC";
    if ($result = $mysqli->query($sql)) {
        return $result;
    } else {
        die($mysqli->error);
    }
}


function showTable($result)
{
    echo "<table>";
    echo "<tr>";
    echo "<td>City</td>";
    echo "<th>Company Name</th>";
    echo "<th>Satisfaction</th>";
    echo "<tr>";

    if (mysqli_num_rows($result) > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . ucwords($row['name']) . "</td>";
            echo "<td>" . ucwords($row['companyName']) . "</td>";
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
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
    <meta charset="UTF-8">
    <title>CMS - Satisfaction</title>
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
        <h2 class="text">Satisfaction of Finished Contracts </h2>
        <br />
        <?php 
            echo "<h3 class='text'> Satisfaction of Gold Contracts </h3>";
            $gold = fetchTable('gold');
            showTable($gold);
            echo "<br>";
            echo "<h3 class='text'> Satisfaction of Silver Contracts </h3>";
            $silver = fetchTable('silver');
            showTable($silver);
            echo "<br>";
            echo "<h3 class='text'> Satisfaction of Premium Contracts </h3>";
            $premium = fetchTable('premium');
            showTable($premium);
            echo "<br>";
            echo "<h3 class='text'> Satisfaction of Diamond Contracts </h3>";
            $diamond = fetchTable('diamond');
            showTable($diamond);
        ?>
    </div>
</div>
</body>
</html>
