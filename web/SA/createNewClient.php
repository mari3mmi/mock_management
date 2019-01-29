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


	$province_query = $mysqli->query("SELECT * FROM Province");
	
	function getProvince($province_query) {
		while($province = $province_query->fetch_assoc()) {
			$name = ucfirst($province['name']);
				echo "<option value='" . $name . "'>" . $name . "</option>";
		}
	}


	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		if(isset($_POST)) {
			$xhttpPOST = $_POST;
			$province_array = array_keys($xhttpPOST);
			$province_na = $province_array[0];
			$province_string = preg_replace("/[^A-Za-z0-9 ]/", ' ', $province_na);

			$provinceId_query = $mysqli->query("SELECT provinceId FROM Province WHERE name = '$province_string'");
			$province = $provinceId_query->fetch_assoc();
			$provinceId = $province['provinceId'];

			$city_query = $mysqli->query("SELECT * FROM City WHERE provinceId = $provinceId");
			if($city_query) {
				while ($data = $city_query->fetch_assoc()) {
					$city[] = $data["name"];
				}
			}
			$jsonCity = json_encode($city);

			echo $jsonCity;
		}
		if(isset($_POST['create'])) {
			$companyName = $mysqli->escape_string($_POST["companyName"]);
			$contactfirstName = $mysqli->escape_string($_POST["contactfirstName"]);
			$contactLastName = $mysqli->escape_string($_POST["contactLastName"]);
			$contactEmail = $mysqli->escape_string($_POST["contactEmail"]);
			$contactNumber = (int)$_POST["contactNumber"];
			$streetAddress = $mysqli->escape_string($_POST["streetAddress"]);
			$postalCode = $mysqli->escape_string($_POST["postalCode"]);
			$city = $mysqli->escape_string($_POST["city"]);

			$city = strtolower($city);
			$replaced = preg_replace("/ /", "-", $city);

			$sql_cityId = $mysqli->query("SELECT cityId FROM City WHERE name = '$replaced'");
			$citId = $sql_cityId->fetch_assoc();
			$final_cityId = (int)$citId['cityId'];

			$sql_address = "INSERT INTO Address(streetAddress, cityId, postalCode)
							VALUES ('$streetAddress', $final_cityId, '$postalCode')";
			if ($mysqli->query($sql_address) === true) {
				$_SESSION['message'] = "Operation SuccessFul";
                //echo "Operation SuccessFul!";
            } else {
            	$_SESSION['message'] = "Error updating record: " . $mysqli->error;
        		header("location: ../error.php");
            }

			$sql = "INSERT INTO Company(companyName, contactfirstName, contactLastName, contactEmail, contactNumber, streetAddress, cityId) 
					VALUES ('$companyName', '$contactfirstName', '$contactLastName', '$contactEmail', $contactNumber, '$streetAddress', $final_cityId)";

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
	<script type='text/javascript'>
		function getCity() {
			var province = document.getElementById("province");
			var value = province.options[province.selectedIndex].value;
			var xhttp = new XMLHttpRequest();
			xhttp.open('POST', 'createNewClient.php', true);
			xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
			xhttp.onload = function () {
			    var city = document.getElementById("city");
				var data = this.responseText;
				var dataStriped = data.replace(/[^A-Za-z0-9-]/g, '  ');
				var splitArray = dataStriped.split("DOCTYPE");
				var cityData = splitArray[0].split(" ");
				cityData = cityData.filter(Boolean);
				//console.log(cityData);
				city.style.visibility = "visible";
				for(var i = 0; i < cityData.length; i++) {
					cityData[i] = cityData[i].replace("-", " ");
					cityData[i] = cityData[i].replace("-", " ");
					cityData[i] = cityData[i].replace("-", " ");
					cityData[i] = toTitleCase(cityData[i]);
					city.options[i] = new Option(cityData[i]);
				} 
			};
			xhttp.send(value);
		}
		function toTitleCase(str) {
		    return str.replace(/\w\S*/g, function(txt){
		        return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
    	});
	}
	</script>
	    

</head>
<body>
<div class="container">
        <div class="sidenav">
			<p class="form-title"> Welcome <br /><?= $firstName . " " . $lastName ?></p>
			<br />
            <a href="createNewClient.php">
                <i class="fa fa-2x fa-book text-primary sr-icons"></i>
                    Create New Client</a> <br />
            <a href="createNewContract.php">
                <i class="fa fa-2x fa-tasks text-primary sr-icons"></i>
					Create New Contract</a> <br /> <br />
			<a href="getListManagers.php">
                <i class="fa fa-2x fa-tasks text-primary sr-icons"></i>
					Manager List</a> <br /> <br />
            <a href="../logout.php">
                <i class="fa fa-2x fa-power-off text-primary sr-icons"></i>
                    Log out</a> <br />
		</div>
		<br />
	<h2 class="text"> Create New Client </h2>
	<br />

	<form action="createNewClient.php" class="client" method="post" autocomplete="off">
		<div class="container">
			<input type="text" placeholder="COMPANY NAME" name="companyName" required/><br/>
			<input type="text" placeholder="FIRST NAME" name="contactfirstName" required/><br/>
			<input type="text" placeholder="LAST NAME" name="contactLastName" required/><br/>
			<input type="text" placeholder="EMAIL" name="contactEmail" required/><br/>
			<input type="text" placeholder="STREET ADDRESS" name="streetAddress" required/><br/>
			<label> Please indicate a Province </label>
				<select id='province' name='province' onchange = 'getCity()'>
					<?php getProvince($province_query);?>
				</select>
				<label> Please indicate a City </label>
				<select id="city" name="city">
				</select><br/>
			<input type="text" placeholder="POSTAL CODE" name="postalCode" required/><br/>
			<input type="number" placeholder="PHONE NUMBER" name="contactNumber" required/><br/>
			<input  type="submit" value="Create" name="create" class="btn btn-success btn-sm"/>
		</div>
	</form>
</div>	
</body>
</html>
