<?php 
ob_start();
//Note: This includes database connection code
    session_start();
    if(isset($_SESSION['u_id']) || isset($_SESSION['u_email'])){
        //If someone is logged in Do nothing here/Continue
    } else {
            header("Location: ../login.php?login=error");//Otherwise return to login screen
            exit();
    }
    include '../db_connect.php';
?>

<?php
//This script injects all the field values into the mysql database!!

$first = $_POST['first'] ?? ''; //Note: Adding ?? '' for initialization
$last = $_POST['last'] ?? '';

$business = $_POST['business'] ?? '';

$address = $_POST['address'] ?? '';
$address_2 = $_POST['address_2'] ?? '';
$city = $_POST['city'] ?? '';
$state = $_POST['state'] ?? '';

$zip = $_POST['zip'] ?? '';
$phone = $_POST['phone'] ?? '';
$email = $_POST['email'] ?? '';

$id = $_POST['id'] ?? '';



$query = "UPDATE customer
          SET  first = '$first',
               last = '$last',
               address = '$address',
               address_2 = '$address_2',
               city = '$city',
               state = '$state',
               zip = '$zip',
               phone = '$phone',
               email = '$email',
               business = '$business'

          WHERE id = '$id'";


mysqli_query($conn, $query)
or die('Error querying database');

//echo 'New Costume Has Been Added';
header("Location: ../Customer/customerSearchSave.php");

mysqli_close($conn);
ob_end_flush();
?>