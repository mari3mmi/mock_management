<?php
$username = $_POST['username'];
$result = $mysqli->query("SELECT * FROM Users WHERE username='$username'");

if ($result->num_rows == 0) {
    // User doesn't exits
    $_SESSION['message'] = "This username doesn't exist!";
    header("location: error.php");
} else {
    $user = $result->fetch_assoc();
    $hash =  password_hash($user['password'], PASSWORD_DEFAULT);
    

    if (password_verify($_POST['password'], $hash)) {
        $_SESSION['loggedIn'] = true;
        $_SESSION['username'] = $user['username'];
        $_SESSION['userId'] = $user['userId'];
        $_SESSION['genericId'] = $user['genericId'];
        $_SESSION['role'] = $user['role'];

    // Gets user first name and last name and puts in $_SESSION variable
    $genericId = $_SESSION['genericId'];
    $sql = "SELECT * FROM Employee WHERE employeeId = $genericId";
    $result = $mysqli->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $_SESSION['firstName'] = $row["firstName"];
            $_SESSION['lastName'] = $row["lastName"];
        }
    }


        switch($user['role']):
            case 'company':
                header("location: ./Client/contractStatus.php");
                break;
            case 'developer':
                header("location: ./Developer/preferences.php");
                break;
            case 'sales associate':
                header("location: ./SA/createNewClient.php");
                break;
            case 'tam':
                header("location: ./TAM/employees.php");
                break;
            case 'manager':
                header("location: ./Manager/contracts.php");
                break;
            case 'admin':
                header("location: ./Admin/updateContractPage.php");
                break;
        endswitch;
    } else {
        $_SESSION['message'] = " You have entered wrong password, please try again.";
        header("location: error.php");
    }
}