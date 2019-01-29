<?php
	require '../db.php';
	session_start();

	$username = $_SESSION['username'];
	$genericId = $_SESSION['genericId'];
	global $firstName, $lastName;

	$sql = "SELECT * FROM Employee WHERE employeeId = $genericId";
$result = $mysqli->query($sql) or die($mysqli->error);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $firstName = $row["firstName"];
        $lastName = $row["lastName"];
    }
}

	global $employee;
	$sql_lineOfBusiness = "SELECT * FROM LineOfBusiness";
	$lineOfBusiness_query = $mysqli->query($sql_lineOfBusiness);

	function getLineOfBusiness($lineOfBusiness_query) {
		while($lineOfBusiness = $lineOfBusiness_query->fetch_assoc()) {
			$businessTypeName = ucfirst($lineOfBusiness['businessTypeName']);
			echo "<option value='" . $businessTypeName . "'>" . $businessTypeName . "</option>";
		}
	}

	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		if(isset($_POST['create'])) {
			$businessTypeName = strtolower($_POST['LineOfBusiness']);
			$sql_tamId = "SELECT tamId FROM LineOfBusiness WHERE businessTypeName = '$businessTypeName'";
			$tamId_query = $mysqli->query($sql_tamId);
			$tamId = $tamId_query->fetch_assoc();
			$id = $tamId['tamId'];

			$sql_employee = "SELECT * FROM Employee WHERE employeeId = $id";
			$employee_query = $mysqli->query($sql_employee);
			$employee = $employee_query->fetch_assoc();
		}
	}
	function result($employee) {
		echo "<table class='manager'>";
		echo "<tr>";
		echo "<th> First Name </th>";
		echo "<th> Last Name </th>";
		echo "<th> Role </th>";
		echo "<th> Location </th>";
		echo "<th> Insurance Plan </th>";
		echo "<th> Prefered Service </th>";
		echo "</tr>";

		echo "<tr class='clickable_row'>";
		echo "<td>" . $employee["firstName"] . "</td>";
		echo "<td>" . $employee["lastName"] . "</td>";
		echo "<td> Technical Account Manager </td>";
		if($employee["locationId"] === '1'):
			echo "<td> Vancouver </td>";
		elseif($employee["locationId"] === '2'):
			echo "<td> Montreal </td>";
		elseif($employee["locationId"] === '3'):
			echo "<td> Toronto </td>";
		endif;
		echo "<td>" . ucfirst($employee["insurancePlan"]) . "</td>";
		echo "<td>" . ucfirst($employee["preferedService"]) . "</td>";
		echo "</table>";
	}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8" />
	<title> CMS - <?= $firstName . " " . $lastName ?> Profile </title>
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
        <div class="sidenav">
			<p class="form-title"> Welcome <br /><?= $firstName . " " . $lastName ?></p>
			<br />
            <a href="createNewClient.php">
                    Create New Client</a> <br />
            <a href="createNewContract.php">
					Create New Contract</a> <br /> <br />
			<a href="getListManagers.php">
					Manager List</a> <br /> <br />
            <a href="../logout.php">
                    Log out</a> <br />
		</div>
		<br />
	<h2 class="text"> Get List Of Managers </h2>
	<br />
	<div class="manager">
		<form action="getListManagers.php" method="post" autocomplete="off">
			<label>
				Select a Line Of Business
			</label>
			<select name="LineOfBusiness">
			<?php getLineOfBusiness($lineOfBusiness_query) ?>
			</select>
			<input type="submit" value="get manager" class="btn btn-success btn-sm" name="create"/><br/>
		</form>
		<br />
		<br />
		<?php 

		result($employee);
		?>
		</div>
</div>	
</body>
</html>