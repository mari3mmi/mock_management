<?php
	require '../db.php';
	session_start();

	$username = $_SESSION['username'];

	$sql_contract = "SELECT * FROM Contract";
	$contract_query = $mysqli->query($sql_contract);
	
	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		if(isset($_POST['contractSelected'])) {
			$contractId = $_POST['contractSelected'];
			$sql_remove = "DELETE FROM Contract WHERE contractId = $contractId";
			if ($mysqli->query($sql_remove) === true) {
				echo "<script type='text/javascript'>alert('Operation SuccessFul!');</script>";
                echo "<meta http-equiv='refresh' content=0>";
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
	<meta charset="UTF-8" />
	<title> CMS - <?= $username ?> Profile </title>

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
			<p class="form-title"> Welcome <?= $username ?></p>
			<br />
            <a href="removeContract.php">
                <i class="fa fa-2x fa-book text-primary sr-icons"></i>
                    Remove Contract</a> <br />
            <a href="updateContractPage.php">
                <i class="fa fa-2x fa-tasks text-primary sr-icons"></i>
					Update Contract</a> <br /> <br />
            <a href="../logout.php">
                <i class="fa fa-2x fa-power-off text-primary sr-icons"></i>
                    Log out</a> <br />
		</div>
		<br />
	<h2 class="text"> All Contracts </h2>
		<br />
	<p> Choose the <strong> Contract </strong> to Remove: </p>
	<form action="removeContract.php" method="post" autocomplete="off">
			<table class="contractInfo">
			<tr>
			    <th> ContractID </th>
			   	<th> Initial Amount </th>
			    <th> ACV </th>
			    <th> Type Of Service </th>
			    <th> Type Of Contract </th>
			    <th> Line Of Business</th>
			    <th> Start Date </th>
			    <th> State </th>
			    <th> Satisfaction </th>
			    <th> </th>
		    </tr>
		    <?php
		    	$i = 0;
		    	while($contract = $contract_query->fetch_assoc()) {
					$LineOfBusinessId = $contract['LineOfBusinessId'];
					$sql_lineOfBusiness = "SELECT businessTypeName FROM LineOfBusiness WHERE LineOfBusinessId = $LineOfBusinessId";
					$businessTypeName_query = $mysqli->query($sql_lineOfBusiness);
					$businessTypeName[] = $businessTypeName_query->fetch_assoc();
					echo "<tr class='clickable_row'>";
					echo "<td>" . $contract["contractId"] . "</td>";
				    echo "<td>" . $contract["initialAmount"] . "</td>";
				    echo "<td>" . $contract["ACV"] . "</td>";
				    echo "<td>" . $contract["typeOfService"] . "</td>";
				    echo "<td>" . $contract["typeOfContract"] . "</td>";
				    echo "<td>" . $businessTypeName[$i++]['businessTypeName'] . "</td>";
				    echo "<td>" . $contract["startDate"] . "</td>";
				    echo "<td>" . $contract["state"] . "</td>";
				    echo "<td>" . $contract["satisfaction"] . "</td>";
				   	echo "<td><button name='contractSelected' value='" . $contract["contractId"] ."' type='submit' class='btn btn-success btn-sm'>Remove</button></td>";
				    echo "</tr>";
				}
		    ?>
			</table>
	</form>
</div>	
</body>
</html>