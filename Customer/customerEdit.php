<?php //Note: This includes database connection code
    session_start();
    if(isset($_SESSION['u_id']) || isset($_SESSION['u_email'])){
        //If someone is logged in Do nothing here/Continue
    } else {
            header("Location: ../login.php?login=error");//Otherwise return to login screen
            exit();
    }
    include '../db_connect.php';
?>

<!DOCTYPE html>
<html lang="en-us" id="CustomerPageBG">

<head>
    <title>Edit Customer</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../style.css">
    <script src="phone.js"></script>

</head>
<body>

<div>
    <?php
        
        $id = mysqli_real_escape_string($conn, $_GET['id']);//Grabbing item by id

        //Getting automatically generated id from mysql database and plugging it into the mix!
           $query = "SELECT id
                        FROM customer
                        WHERE id = '$id'
                        ";

                $getInfo = mysqli_query($conn, $query)//Note: SQL Query returns object here
                or die('Error querying database for id');

                //Looping through object to get paticular id cell and value
                while ($row = mysqli_fetch_array($getInfo)) {
                $id = $row['id'];
                }

//Displaying list of costume results
        $sql = "SELECT * FROM customer WHERE id ='$id'";
		$result = mysqli_query($conn, $sql);
        $queryResults = mysqli_num_rows($result);

        
        if ($queryResults > 0) {
			while ($row = mysqli_fetch_assoc($result)){

                echo "
                
                <div>
                
                <!--Outputting edit form with pre-filled values for selected customer=================-->

<img class='TitleBarPic' src='../Images/Site/TitleCustomer.png'>


<div class='lighten'>
<br>

<form method='post' action='customerUpdate.php' enctype='multipart/form-data'>

<div class='float'><div class='first'><label for='first'>First Name: </label></div>
<input type='text' id='first' name='first'  size='43' height='20%' value='".$row['first']."'> </div>

<div class='float'><div class='last'><label for='last'>Last Name: </label></div>
<input type='text' id='last' name='last' size='43' height='20%' value='".$row['last']."'></div>

<br>

<div class='float'><div class='business'><label for='business'>Business: </label></div>
<input type='text' id='business' name='business' size='93' height='20%' value='".$row['business']."'></div>

<br>

<div class='float'><div class='address'><label for='address'>Address: </label></div>
<input type='text' id='address' name='address' size='66' height='20%' value='".$row['address']."'></div>

<div class='float'><div class='address_2'><label for='address_2'>Address 2: </label></div>
<input type='text' id='address_2' name='address_2' size='20' height='20%' value='".$row['address_2']."'> </div>

<br>

<div class='float'><div class='city'><label for='city'>City: </label></div>
<input type='text' id='city' name='city' size='40' height='20%' value='".$row['city']."'></div>

<div class='float'><div class='state'><label for='state'>State: </label></div>
<input type='text' id='state' name='state' size='15' height='20%' value='".$row['state']."'></div>

<div class='float'><div class='zip'><label for='zip'>Zip Code: </label></div>
<input class='zipBox' type='number' id='zip' name='zip' height='20%' value='".$row['zip']."'></div>

<br>

<div class='float'><div class='phone'><label for='phone'>Phone: </label></div>
<input type='text' id='phone' name='phone' placeholder='(123)456-7890' 
    onkeydown='javascript:backspacerDOWN(this,event);' 
    onkeyup='javascript:backspacerUP(this,event);'
    size='43' height='20%' value='".$row['phone']."'></div>

<div class='float'><div class='altPhone'><label for='altPhone'>Alternate Phone: </label></div>
<input type='text' id='altPhone' name='altPhone' placeholder='(123)456-7890' 
    onkeydown='javascript:backspacerDOWN(this,event);' 
    onkeyup='javascript:backspacerUP(this,event);'
    size='43' height='20%' value='".$row['phone']."'></div>    


<br>
<div class='float'><div class='email'><label for='email'>Email: </label></div>
<input type='text' id='email' name='email' size='93' height='20%' value='".$row['email']."'></div>

<input type ='hidden' name ='id' value ='".$id."'> <!-- Inserting id value into post array here for use on next page! -->

<br><br>

</div><!--Close Lighten-->

<br><br>
<input class='mediumbtn' type='submit' name='submit' value='Save'>

</form><!--Closing Form Tag-->

<a href='CustomerSearchForm.php'><button class='mediumbtn'>Cancel</button></a>

                ";//Close Echo
			}//Close While
        }//Close if

//Set id here in session storage for overwrite assurance to anchor to correct item upon page return

$_SESSION['id'] = $id;
	?><!--Close Php-->

</div><!--Close unstyled div?-->


</body>

</html>