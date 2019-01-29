<?php
	require '../db.php';
	session_start();

	$username = $_SESSION['username'];

	$sql_contract = "SELECT * FROM Contract WHERE companyId = (SELECT companyId FROM Company WHERE companyName = '$username')";
	$contract_query = $mysqli->query($sql_contract);
?>
<!DOCTYPE html>
<html>

<head>
    <title>CMS - <?=$username?> Profile </title>
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
            <a href="satisfaction.php">
                <i class="fa fa-2x fa-book text-primary sr-icons"></i>
                    Satisfaction</a> <br />
            <a href="contractStatus.php">
                <i class="fa fa-2x fa-tasks text-primary sr-icons"></i>
					Contract Status</a> <br /> <br />
			<a href="averageSatisfaction.php">
				<i class="fa fa-2x fa-tasks text-primary sr-icons"></i>
					Average Satisfaction</a> <br /> <br />
            <a href="../logout.php">
                <i class="fa fa-2x fa-power-off text-primary sr-icons"></i>
                    Log out</a> <br />
        </div>


        <div class="satisfaction"> 
			<br />
				<h2 class=text> All contracts signed under
				<span style="text-decoration: underline;"> <?= $username ?> </span> </h2>
				<br />
                    <table>
                        <tr>
						 <th> Contract ID </th>
						 <th> Initial Amount </th>
						 <th> ACV </th>
						 <th> Type Of Service </th>
						 <th> Type Of Contract </th>
						 <th> Line Of Business</th>
						 <th> Start Date </th>
						 <th> Satisfaction </th>
						 <th> <span style='color: green;'>State </span></th>
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
							echo "<td>" . $contract["satisfaction"] . "</td>";
							echo "<td><strong>" . $contract["state"] . "</strong></td>";
						
							echo "</tr>";
						}
					?>
                    </table>
		</div>
    </div>           
</body>

</html>