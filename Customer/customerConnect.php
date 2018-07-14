<?php 
ob_start();
		//Database Connection
		session_start();//Starting session here for costume added confirmation message
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

$_SESSION['message'] = $first . ' ' . $last;//Storing item name just entered into message session variable for item added message

$business = $_POST['business'] ?? '';
$address = $_POST['address'] ?? '';
$address_2 = $_POST['address_2'] ?? '';
$city = $_POST['city'] ?? '';

$state = $_POST['state'] ?? '';
$zip = $_POST['zip'] ?? '';
$phone = $_POST['phone'] ?? '';
$altPhone = $_POST['altPhone'] ?? '';
$email = $_POST['email'] ?? '';

$query = "INSERT INTO customer (first, last, address, address_2, city, state, zip, phone, altPhone, email, business)" .
"VALUES ('$first', '$last', '$address', '$address_2', '$city', '$state', '$zip', '$phone', '$altPhone', '$email', '$business')";

mysqli_query($conn, $query)
or die('Error querying database');

//Grab auto generated id from above insert
$query = "SELECT id
		 FROM customer
		 WHERE first = '$first'
		 AND   last = '$last'
		 AND   email = '$email'
		 ";

	$result = mysqli_query($conn, $query)
			  or die('Error getting customer ID');

	//Looping through object to get paticular id cell and value
	while ($row = mysqli_fetch_array($result)) {
		$id = $row['id'];
		}


//If the save button was clicked, return to search results
//If the save and continue button was clicked, return to form for another submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Save was pressed return to customer search
    header("Location: ../Customer/CustomerSearchForm.php");
    if (isset($_POST['save'])) {
        // btnDelete
    } else {
		//assume Save and select was pressed
		$_SESSION['message'] = $first . ' ' . $last . ' has been added to the database';
        header("Location: ../Orders/OrderForm.php?id=".$id."");
    }
}


mysqli_close($conn);
ob_end_flush();

?>