<?php
session_start();//If there is a name stored in the message variable - output it temporarily here to confirm it was added
if(isset($_SESSION['u_id']) || isset($_SESSION['u_email'])){
    //If someone is logged in Do nothing here/Continue
} else {
        header("Location: ../login.php?login=error");//Otherwise return to login screen
        exit();
}
?>

<!DOCTYPE html>
<html lang="en-us" id="CustomerPageBG">

<head>

<title>New User</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="../style.css">
<script src="phone.js"></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
    $(".confirmation2").fadeOut(10000);
    });
</script>

</head>


<body>
   
<img class="TitleBarPic" src="../Images/Site/TitleCustomer.png">


<div class="lighten">
<br>

<form method="post" action="createUser.php" enctype="multipart/form-data">

<div class="float"><div class="first"><label for="first">First Name: </label></div>
<input type="text" id="first" name="first"  size="43" height="20%"> </div>

<div class="float"><div class="last"><label for="last">Last Name: </label></div>
<input type="text" id="last" name="last" size="43" height="20%"></div>

<br>

<div class="float"><div class="email"><label for="email">Email: </label></div>
<input type="text" id="email" name="email" size="93" height="20%"></div>

<br>

<div class="float"><div class="first"><label for="first">User Id: </label></div>
<input type="text" id="uid" name="uid" size="93" height="20%"></div>

<br>

<div class="float"><div class="first"><label for="pwd">Password: </label></div>
<input type="password" id="pwd" name="pwd" size="93" height="20%"></div>

<br><br>

</div><!--Close Lighten-->


<br><br>
<input class="mediumbtn SaveContinueCancelSpacing" type="submit" name="submit" value="Create User">
</form><!--Closing Form Tag-->

<a href="../Orders/OrderForm.php"><button class="mediumbtn">Cancel</button></a>

<?php
if(!empty($_SESSION['message'])) {
	$message = $_SESSION['message'];
	echo "
	  <div class='confirmation2'>
		Customer " . $message . " has been added
	  </div>
	";
}
?>
<?php
//Clear session variable to stop multiple outputs
$_SESSION['message'] = '';
?>

</body>

</html>