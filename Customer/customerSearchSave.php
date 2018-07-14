<?php //Note: This includes database connection code
    session_start();
    if(isset($_SESSION['u_id']) || isset($_SESSION['u_email'])){
      //If someone is logged in Do nothing here/Continue
  } else {
          header("Location: ../login.php?login=error");//Otherwise return to login screen
          exit();
  }
    include '../db_connect.php';
    //Obtaining search terms from session storage!!!
    
?>

<!DOCTYPE html>
<html lang="en-us" id="CustomerPageBG">
<head>
    <title>Customer Search</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../style.css">
</head>




<body onload='load()'>

<div class="float-panel">
<form class="formfix" action="customerSearch.php" method="POST">
    <div class="searchBGcolor">
      <input class="searchbarshift" type="text" name="search" size="60px" placeholder="Customer Search">   
	  	</div> 
	<button class="btnSearchPlace btnSearch" type="submit" name="submit-search">Search</button> <!--Search Button-->    
 
  <button class="btnResetPlace btnReset" name="reset-search" formaction="reset.php" >Reset</button> <!--Reset Search Button-->  
</form>

<div class="buttonline">
<a href="../Orders/OrderForm.php"><div title="Back" class="iconback"></div></a>
<a href="../OpeningPage/OpenPage.php"><div title="Home" class="iconhome"></div></a>
<!--Add Customer Button Note that this button is just a link and must remain outside the form -->
<a href="../Customer/customer_input.php"><button class="mediumbtn addButtonplace">Add Customer</button></a>
</div>
</div>


<div class="border lighten">
<div class="customer-container">

<?php

//fetching id and reloading page to get location of last edited item/status
$search = $_SESSION['search'] ?? '';
$id = $_SESSION['id'] ?? '';
//echo "<p>$search</p>";//Search term test

//Querying database with SQL Select statement & LIKE Keyword (every field)
$sql = "SELECT * FROM customer 
WHERE first LIKE '%$search%' 
OR last LIKE '%$search%'
OR address LIKE '%$search%'
OR address_2 LIKE '%$search%'
OR city LIKE '%$search%'
OR state LIKE '%$search%'
OR zip LIKE '%$search%'
OR phone LIKE '%$search%' 
OR email LIKE '%$search%'
OR business LIKE '%$search%'
";

$result = mysqli_query($conn, $sql);
        $queryResult = mysqli_num_rows($result);
    
    
            if ($queryResult > 0) {//Output Results only if greater than zero rows in database - otherwise do nothing
                while ($row = mysqli_fetch_assoc($result)){ //Note: row is the array prefix number or row number //Note: To get CSS in the results window requires single quotes on the class declaration
            
                  echo "
                  <div class='BottomDivideLine'>
                    <div class='CustomerlistLeftCol'>
                    <h3 id='".$row['id']."'>
                    <b>Last Name:</b>&nbsp".$row['last']."<br>
                    <b>First Name:</b>&nbsp".$row['first']."<br>
                    <b>Business Name:</b>&nbsp".$row['business']."<br>
                    </h3>
                    
                              <p> <b>Address:&nbsp</b> ".$row['address']."<br>
                     <b>Address 2:&nbsp</b> ".$row['address_2']."<br> 
                     <b>City:&nbsp</b> ".$row['city']." &nbsp&nbsp<b>State:&nbsp</b> ".$row['state']." &nbsp&nbsp<b>Zip:&nbsp</b> ".$row['zip']."<br>
                     <b>Phone:&nbsp</b> ".$row['phone']." &nbsp&nbsp <b>Alternate Phone:&nbsp</b> ".$row['altPhone']."<br>
                     <b>Email:&nbsp</b> ".$row['email']."</p>
                  </div>
        
                  
              
                    <br>
                    <a href='../Orders/OrderForm.php?id=".$row['id']."'>
						          <button type='button' class='EditDeleteButtonPosition btnsm'>Select</button>
					          </a>
             
                    <br>

                    <a href='customerEdit.php?id=".$row['id']."'>
                      <button type='button' class='EditDeleteButtonPosition btnsm'>&nbsp&nbspEdit&nbsp&nbsp</button>
                    </a>
                      <br>
                    <a id='ChangeUrl' onclick='notify()' href='customerDelete.php?id=".$row['id']."'>
                    <button type='button' class='EditDeleteButtonPosition btnsmDelete'>Delete</button>
                    </a>
                  </div>
              
                ";//End Echo
                }
            }

         echo "
         <script type='text/javascript'>
           window.location.hash = " . $id . ";
         </script> ";

?>

</body>


      
</html>