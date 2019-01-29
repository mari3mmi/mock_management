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

$selectedEmployee = $_SESSION['selectedEmployeeId'];
// Get all the records where the selected employee works on
$sql = "SELECT * FROM WorksOn WHERE employeeId = '$selectedEmployee'";
$employees = $mysqli->query($sql) or die($mysqli->error);

// Get all the records where the selected employee works on
$sql = "SELECT contractId FROM WorksOn WHERE employeeId = '$selectedEmployee'";
$contractIds = $mysqli->query($sql) or die($mysqli->error);




// $sql = "SELECT name FROM WorksOn WHERE employeeId = $selectedEmployee";
// $contractIds = $mysqli->query($sql) or die($mysqli->error());
$tasks = array('Set up infrastructure for client', 'Provisioning of resources', 'Assigning tasks to resources', 'Allocating a dedicated point of contact', 'Development');
// $sql = "SELECT * FROM WorksOn WHERE employeeId = $selectedEmployee";
// $result = $mysqli->query() or die($mysqli->error());
    if (isset($_POST['select_button'])) {
        $value = $_POST['select_button'];
        echo "<script>console.log($value)</script>";
    } 
    elseif (isset($_POST['remove_button'])) {
        $selectedContract = $_POST['remove_button'];
        $sql = "DELETE FROM WorksOn WHERE employeeId = '$selectedEmployee' AND contractId = '$selectedContract'";
        $result = $mysqli->query($sql) or die($mysqli->error);
        echo "<meta http-equiv='refresh' content='0'>";
    }

    if (isset($_POST['updateTask'])) {
        $selectedContract = $_POST['contractId'];
        $value = $_POST['newTask'];
        $sql = "UPDATE WorksOn SET task = '$value' WHERE employeeId = '$selectedEmployee' AND contractId = '$selectedContract'";
        if ($result = $mysqli->query($sql)) {
            echo "<script type='text/javascript'>alert('Operation SuccessFul!');</script>";
			echo "<meta http-equiv='refresh' content='0'>";
        } else {
            die($mysqli->error);    
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
            Employee ID: <?=$selectedEmployee?>
        </h2>
        <br />
        <form action="details.php" class="details" method="post">
            <table>
                <tr>
                    <th>Contract Id</th>
                    <th>Task</th>
                    <th>Hour</th>
                    <th></th>
                    <th></th>
                </tr>
                <?php
            while ($row = $employees->fetch_assoc()) {
                echo "<tr>";
                $contractId =  $row['contractId'];
                echo "<td>" . $contractId ."</td>";
                echo "<td>" . $row["task"] ."</td>";
                echo "<td>" . number_format((float) $row["hours"], 2) ."</td>";
                //echo "<input type='hidden' name='Select' value='$contractId'>"."</td>";
                //echo "<td><input value='Select' type='submit' class='btn btn-success btn-sm'></td>";
                echo "<input type='hidden' name='Remove' value='$contractId'>"."</td>";
                echo "<td><input value='Remove' type='submit' class='btn btn-success btn-sm'></td>";
                echo "</tr>";
            }
            ?>
            </table>
        </form>

        <form action="details.php" class="details" method="post">
            <p>Targeted contract: </p>
            <label>Select the
                <b>Contract Id</b> to update: </label>
            <select name="contractId">
                <?php
                     while ($row = $contractIds->fetch_assoc()) {
                         echo "<option value=" .$row['contractId'] .">" . $row['contractId'] . "</option>";
                     }
                ?>
            </select>
            <br>
            <label>Change task to: </label>
            <select name="newTask">
                <?php
                     foreach ($tasks as $task) {
                         echo "<option value='$task'>$task</option>";
                     }
                ?>
            </select>
            <input type="submit" value="Update Task" name="updateTask" class='btn btn-success btn-sm'>
    </div>
</body>

</html>