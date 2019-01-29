<?php
	require '../db.php';
	session_start();
	
	$username = $_SESSION['username'];
	$genericId = $_SESSION['genericId'];
	global $firstName, $lastName;

	$sql = "SELECT * FROM Employee WHERE employeeId = $genericId";
$result = $mysqli->query($sql) or die($mysqli->error());
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $firstName = $row["firstName"];
        $lastName = $row["lastName"];
    }
}

	$companyName_query = $mysqli->query("SELECT companyName FROM Company");

	function getCompanyName($companyName_query) {
		while($companyName = $companyName_query->fetch_assoc()) {
			echo "<option value='" . $companyName['companyName'] . "'>" . $companyName['companyName'] . "</option>";
		}
	}

	$lineOfBusiness_query = $mysqli->query("SELECT businessTypeName FROM LineOfBusiness");

	function getLineOfBusiness($lineOfBusiness_query) {
		while($lineOfBusiness = $lineOfBusiness_query->fetch_assoc()) {
				echo "<option value='" . strtoupper($lineOfBusiness['businessTypeName']) . "'>" . strtoupper($lineOfBusiness['businessTypeName']) . "</option>";
		}
	}

	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		if(isset($_POST['create'])) {
			$companyName = $mysqli->escape_string($_POST["companyName"]);
			$initialAmount = $_POST["initialAmount"];
			$ACV = $_POST["ACV"];
			$typeOfService = $mysqli->escape_string($_POST["typeOfService"]);
			$typeOfContract = $mysqli->escape_string($_POST["typeOfContract"]);
			$lineOfBusiness = $mysqli->escape_string($_POST["lineOfBusiness"]);

			$companyId_query = $mysqli->query("SELECT companyId FROM Company WHERE companyName = '$companyName'");
			$companyId = $companyId_query->fetch_assoc();
			$final_companyId = $companyId['companyId'];

			$lineOfBusiness = strtolower($lineOfBusiness);

			$lineOfBusiness_query = $mysqli->query("SELECT lineOfBusinessId FROM LineOfBusiness WHERE businessTypeName = '$lineOfBusiness'");
			$lineOfBusinessId = $lineOfBusiness_query->fetch_assoc();
			$final_lineOfBusinessId = $lineOfBusinessId['lineOfBusinessId'];
			
			$sql = "INSERT INTO Contract(companyId, initialAmount, ACV, typeOfService, typeOfContract, LineOfBusinessId, startDate) 
					VALUES ($final_companyId, '$initialAmount', '$ACV', '$typeOfService', '$typeOfContract', $final_lineOfBusinessId, NOW())";

			if ($mysqli->query($sql) === true) {
				echo "<script type='text/javascript'>alert('Operation SuccessFul!');</script>";
                echo "<meta http-equiv='refresh' content='0'>";
            } else {
            	$_SESSION['message'] = "Error updating record: " . $mysqli->error;
        		header("location: ../error.php");
			}
		}
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title> CMS - <?= $firstName . " " . $lastName ?> Profile </title>
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet">
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
	<h2 class="text"> Create New Contract </h2>
<br />
	<form action="createNewContract.php" class="client" method="post" autocomplete="off">
	<div class="container">		
	<label>
				Please indicate a Company Name
			</label>
			<select name='companyName'>
			<?php getCompanyName($companyName_query) ?>
			</select><br/>
			<input type="text" placeholder="INITIAL AMOUNT" name="initialAmount" required/><br/>
	
			<input type="text" placeholder="ACV" name="ACV" required/><br/>
			<label>
				Please indicate a type of Contract
			</label>
				<select name="typeOfService">
		            <option value="Cloud">Cloud</option>
		            <option value="On-Premises">On-Premises</option>
		        </select> <br>
			<label>
				Please indicate a type of Contract
			</label>
				<select name="typeOfContract">
					<option value="Premium">Premium</option>
		            <option value="Diamond">Diamond</option>
		            <option value="Gold">Gold</option>
		            <option value="Silver">Silver</option>
				</select><br/>
			<label>
				Please indicate a line of Business
			</label>
			<select name='lineOfBusiness'>
				<?php getLineOfBusiness($lineOfBusiness_query) ?>
				</select><br/>

			<input  type="submit" value="Create" name="create" class="btn btn-success btn-sm"/>
				</div>
		</form>
	</div>
</body>
</html>