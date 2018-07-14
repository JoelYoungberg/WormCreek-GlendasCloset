<?php
session_start();
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

<title>New Customer</title>
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

<form method="post" action="customerConnect.php" enctype="multipart/form-data">

<div class="float"><div class="first"><label for="first">First Name: </label></div>
<input type="text" id="first" name="first"  size="43" height="20%"> </div>

<div class="float"><div class="last"><label for="last">Last Name: </label></div>
<input type="text" id="last" name="last" size="43" height="20%"></div>

<br>

<div class="float"><div class="business"><label for="business">Business: </label></div>
<input type="text" id="business" name="business" size="93" height="20%"></div>

<br>

<div class="float"><div class="address"><label for="address">Address: </label></div>
<input type="text" id="address" name="address" size="66" height="20%"></div>

<div class="float"><div class="address_2"><label for="address_2">Address 2: </label></div>
<input type="text" id="address_2" name="address_2" size="20" height="20%"> </div>

<br>

<div class="float"><div class="city"><label for="city">City: </label></div>
<input type="text" id="city" name="city" size="40" height="20%"></div>

<div class="float"><div class="state"><label for="state">State: </label></div>
<input type="text" id="state" name="state" size="15" height="20%"></div>

<div class="float"><div class="zip"><label for="zip">Zip Code: </label></div>
<input class="zipBox" type="number" id="zip" name="zip" height="20%"></div>

<br>

<div class="float"><div class="phone"><label for="phone">Phone: </label></div>
<input type="text" id="phone" name="phone" placeholder="(123)456-7890" 
    onkeydown="javascript:backspacerDOWN(this,event);" 
    onkeyup="javascript:backspacerUP(this,event);"
    size="43" height="20%"></div>

<div class="float"><div class="altPhone"><label for="altPhone">Alternate Phone: </label></div>
<input type="text" id="altPhone" name="altPhone" placeholder="(123)456-7890" 
    onkeydown="javascript:backspacerDOWN(this,event);" 
    onkeyup="javascript:backspacerUP(this,event);"
    size="43" height="20%"></div>
	
<br>

<div class="float"><div class="email"><label for="email">Email: </label></div>
<input type="text" id="email" name="email" size="93" height="20%"></div>

<br><br>

</div><!--Close Lighten-->


<br><br>
<input class="mediumbtn SaveContinueCancelSpacing" type="submit" name="save" value="Save">
<input class="mediumbtn SaveContinueCancelSpacing" type="submit" name="saveSelect" value="Save and Select">
</form><!--Closing Form Tag-->

<a href="../Orders/OrderForm.php"><button class="mediumbtn">Cancel</button></a>

<?php
//If there is a name stored in the message variable - output it temporarily here to confirm it was added
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