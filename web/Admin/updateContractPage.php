<?php
	require '../db.php';
	session_start();

	 $username = $_SESSION['username'];

	$sql_contract = "SELECT * FROM Contract";
	$contract_query = $mysqli->query($sql_contract);

	$sql_business = "SELECT businessTypeName FROM LineOfBusiness";
	$business_query = $mysqli->query($sql_business);
	function lineOfBusiness($business_query, $businessTypeName) {
		$business_query->data_seek(0);
		while($business = $business_query->fetch_assoc()) {
			if($business['businessTypeName'] !== $businessTypeName) {
				echo "<option value='" . $business['businessTypeName'] . "'>" . $business['businessTypeName'] . "</option>";

			}
		}
	}

	function typeOfContract($contract) {
		switch($contract){
			case "premium":
				echo "<option value='diamond'>Diamond</option>";
				echo "<option value='gold'>Gold</option>";
				echo "<option value='silver'>Silver</option>";
				break;
			case "diamond":
				echo "<option value='premium'>Premium</option>";
				echo "<option value='gold'>Gold</option>";
				echo "<option value='silver'>Silver</option>";
				break;
			case "gold":
				echo "<option value='diamon'>Diamond</option>";
				echo "<option value='premium'>Premium</option>";
				echo "<option value='silver'>Silver</option>";
				break;
			case "silver":
				echo "<option value='diamond'>Diamond</option>";
				echo "<option value='gold'>Gold</option>";
				echo "<option value='premium'>Premium</option>";
				break;
		}
	}

	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		if(isset($_POST['contractSelected'])) {
			$contractSelected = $_POST['contractSelected'];
			$contractId = (int)$contractSelected;
			$initialAmount = $_POST["IA" . $contractSelected];
			$ACV = $_POST["AV" . $contractSelected];
			$typeOfService = $mysqli->escape_string($_POST["typeOfService"][$contractId - 1]);
			$typeOfContract = $mysqli->escape_string($_POST["typeOfContract"][$contractId - 1]);
			$businessTypeName = $mysqli->escape_string($_POST["businessTypeName"][$contractId - 1]);

			$sql_businessId = "SELECT lineOfBusinessId FROM LineOfBusiness WHERE businessTypeName = '$businessTypeName'";
			$business_id = $mysqli->query($sql_businessId);
			$lineOfBusinessIdArray = $business_id->fetch_assoc();
			$lineOfBusinessId = $lineOfBusinessIdArray['lineOfBusinessId'];

			$sql_update = "UPDATE Contract SET initialAmount = $initialAmount, ACV = $ACV, typeOfService = '$typeOfService', typeOfContract = '$typeOfContract', LineOfBusinessId = $lineOfBusinessId WHERE contractId = $contractId";
			if ($mysqli->query($sql_update) === true) {
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
    <title>CMS - <?=$username?> </title>
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
			<p class="form-title"> Welcome <?=$username?></p>
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

	<p> Choose the <strong> Contract </strong> to Update: </p>
	<form action="updateContractPage.php" class="contracts" method="post" autocomplete="off">
			<table>
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
					$businessTypeName = $businessTypeName_query->fetch_assoc();

					echo "<tr class='clickable_row'>";
					echo "<td>" . $contract["contractId"] . "</td>";
				    echo "<td><input type='number' value='" . $contract["initialAmount"] . "' name='IA" . $contract["contractId"] . "'/></td>";
				    echo "<td><input type='number' value='" . $contract["ACV"] . "' name='AV" . $contract["contractId"] . "'/></td>";
				    if($contract["typeOfService"] === "cloud") {
				    	echo "<td><select name='typeOfService[]'>
				    			<option value='" . $contract["typeOfService"] . "' default>" . $contract["typeOfService"] . "</option>
				    			<option value='on-premises' default>on-premises</option>
				    		  <select></td>";
				    } else {
				    	echo "<td><select name='typeOfService[]'>
				    			<option value='" . $contract["typeOfService"] . "' default>" . $contract["typeOfService"] . "</option>
				    			<option value='cloud' default>cloud</option>
				    		  <select></td>";
				    }
				   	echo "<td><select name='typeOfContract[]'>";
				    echo "<option value='" . ucfirst($contract["typeOfContract"]) . "' default>" . ucfirst($contract["typeOfContract"]) . "</option>";
				    echo typeOfContract($contract["typeOfContract"]);
				    echo "<select></td>";

				    echo "<td><select name='businessTypeName[]'>";
				    echo "<option value='" . $businessTypeName['businessTypeName'] . "' default>" . $businessTypeName['businessTypeName'] . "</option>";
				    echo lineOfBusiness($business_query, $businessTypeName['businessTypeName']);
				    echo "<select></td>";

				    echo "<td>" . $contract["startDate"] . "</td>";
				    echo "<td>" . $contract["state"] . "</td>";
				    echo "<td>" . $contract["satisfaction"] . "</td>";
				   	echo "<td><button name='contractSelected' value='" . $contract["contractId"] ."' type='submit' class='btn btn-success btn-sm'>Update</button></td>";
				    echo "</tr>";
				}
		    ?>
			</table>
	</form>
</div>	
</body>

</html>