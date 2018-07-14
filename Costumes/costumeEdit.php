<?php //Note: This includes database connection code
ob_start();
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
<html lang="en-us" id="CostumePageBG">

<head>
    <title>Edit Costume</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../style.css">

    <script type='text/javascript'>
                    function preview_image(event) 
                    {
                    var reader = new FileReader();
                    reader.onload = function()
                    {
                    var output = document.getElementById('output_image');
                    output.src = reader.result;
                    }
                    reader.readAsDataURL(event.target.files[0]);
                    }
                </script>
</head>
<body>

<div>
    <?php

        $item_num = mysqli_real_escape_string($conn, $_GET['item_num']);//Grabbing item by sku/item num

        //Getting automatically generated id from mysql database and plugging it into the mix!
           $query = "SELECT id
                        FROM costumes
                        WHERE item_num = '$item_num'
                        ";

                $getInfo = mysqli_query($conn, $query)//Note: SQL Query returns object here
                or die('Error querying database for id');

                //Looping through object to get paticular id cell and value
                while ($row = mysqli_fetch_array($getInfo)) {
                $id = $row['id'];
                }

//Displaying list of costume results
        $sql = "SELECT * FROM costumes WHERE item_num ='$item_num'";
		$result = mysqli_query($conn, $sql);
        $queryResults = mysqli_num_rows($result);

        
        if ($queryResults > 0) {
			while ($row = mysqli_fetch_assoc($result)){

                echo "
                
                <div>
                
                <!--Outputting edit form with pre-filled values for selected costume=================-->
				
                <img src='../Images/Site/EditCost.png'>

<br>


<div class='lighten'>
<br>

<form method='post' action='costumeUpdate.php' enctype='multipart/form-data'>

<div class='float'><div class='item_num'><label for='item_num'>Item #: </label></div>
<input type='text' id='item_num' name='item_num' size='20' value='".$row['item_num']."'></div>

<div class='float'><div class='item_name'><label for='item_name'>Item Name: </label></div>
<input type='text' id='item_name' name='item_name' size='60' value='".$row['item_name']."'></div> <br>

 

 <!--Image Uploader.............................................................-->
<div class='imgBackground'>

<div class='bar'>
  <input type='file' name='picture' accept='image/*' onchange='preview_image(event)'>
  
</div> <!--End Bar-->
<br>
<img id='output_image' src='".$row['picture']."'/>
<br>
<br>

</div> <!--End imgback -->
<!--End Image Uploader..........................................................-->



<br>

<div class='float'><div class='descr'><label for='descr'>Item Description: </label></div>
<input type='text' id='descr' name='descr' size='87' height='20%' value='".$row['descr']."'></div>
<br>

<div class='float'><div class='gender'><label for='gender'>Item Gender: </label></div>
<input type='text' id='gender' name='gender' size='24.3' value='".$row['gender']."'></div>

<div class='float'><div class='size'><label for='size'>Item Size: </label></div>
<input type='text' id='size' name='size' size='24.3' value='".$row['size']."'></div>

<div class='float'><div class='item_color'><label for='color'>Item Color: </label></div>
<input type='text' id='color' name='color' size='25' value='".$row['color']."'></div>
<br>

<div class='float'><div class='loc'><label for='loc'>Item Location: </label></div>
<input type='text' id='loc' name='loc' size='55' value='".$row['loc']."'></div>

<div class='float'><div class='price'><label for='price'>Item Price: </label></div>
<input type='number' id='price' name='price' size='25' value='".$row['price']."'></div>
<br><br>

<input type ='hidden' name ='id' value ='".$id."'> <!-- Inserting id value into post array here for use on next page! -->

</div><!--Close Lighten-->

<br><br>
<input class='mediumbtn' type='submit' name='submit' value='Save'>

</form><!--Closing Form Tag-->

<a href='CostumeSearchForm.php'><button class='mediumbtn'>Cancel</button></a>

                ";//Close Echo
			}//Close While
        }//Close if

//Set id here in session storage for overwrite assurance to anchor to correct item upon page return

$_SESSION['id'] = $id;

ob_end_flush();
	?><!--Close Php-->

</div><!--Close unstyled div?-->


</body>

</html>