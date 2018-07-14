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
<html lang="en-us" id="PropPageBG">
<head>
    <title>Prop Search</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../style.css">
</head>

<body onload='load()'>

<div class="float-panel">
<form class="formfix" action="propSearch.php" method="POST">
    <div class="searchBGcolor">
      <input class="searchbarshift" type="text" name="search" size="60px" placeholder="Prop Search">
	  	</div> 
	<button class="btnSearchPlace btnSearch" type="submit" name="submit-search">Search</button> <!--Search Button-->    
 
  <button class="btnResetPlace btnReset" name="reset-search" formaction="reset.php" >Reset</button> <!--Reset Search Button-->
</form>

<div class="buttonline">
<a href="../Inventory/Inventory.php"><div title="Back" class="iconback"></div></a>
<a href="../OpeningPage/OpenPage.php"><div title="Home" class="iconhome"></div></a>
<!--Add Prop Button Note that this button is just a link and must remain outside the form -->
<a href="../Props/prop_input.php"><button class="mediumbtn addButtonplace">Add Prop</button></a>
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
$sql = "SELECT * FROM props
WHERE item_num LIKE '%$search%' 
OR item_name LIKE '%$search%'
OR picture LIKE '%$search%'
OR descr LIKE '%$search%'
OR color LIKE '%$search%'
OR loc LIKE '%$search%' 
OR status_word LIKE '%$search%'
";

$result = mysqli_query($conn, $sql);
        $queryResult = mysqli_num_rows($result);
    
    
            if ($queryResult > 0) {//Output Results only if greater than zero rows in database - otherwise do nothing
                while ($row = mysqli_fetch_assoc($result)){ //Note: row is the array prefix number or row number //Note: To get CSS in the results window requires single quotes on the class declaration
            
                    echo " 

                        <div class='BottomDivideLine'>
                        <div class='listLeftCol'>
                          <h3 id='".$row['id']."'><b>Sku:</b>&nbsp".$row['item_num']."<br> <b>Type:</b>&nbsp".$row['item_name']."</h3>
                          <p> <b>Description:&nbsp</b> ".$row['descr']."<br>
                          <b>Color:&nbsp</b> ".$row['color']."<br>
                          <b>Location:&nbsp</b> ".$row['loc']."<br> <b>Price:&nbsp</b> ".$row['price']."</p>
                        </div>
    
                        <div class='listImageCol'> <img class='ItemImageWidth' src='".$row['picture']."'></div> <!--Comment: Displaying Thumbnail image here-->
    
                        <!--========================== Dropdown Edit and Delete Buttons ============================-->
                        <div class='listRightCol'>
                        <br>
        
                        
                        <form method='post' action='propDropSearch.php?item_num=".$row['item_num']."&item_name=".$row['item_name']."'>
					              <select ";	switch($row['status']){
						          	case 0:
						          	echo "style='color: #022702'";//Green
						          	break;
						          	case 1:
						          	echo "style='color: #ff9507'";//Orange
					          		break;
				          			case 2:
					          		echo "style='color: #ff9507'";//Orange
					          		break;
					          		case 3:
					          		echo "style='color: #760000'";//Red
                        break;
                        case 4:
					          		echo "style='color: #760000'";//Red
					          		break;
				          			default:
				          			echo "style='color: #022702'";//Green
				            		}//End Switch

					
					              echo"
                        
                        id='statSelect' name='taskOption' onchange='{this.form.submit();}'><!--Note: a value attribute cannot be set here (security)-->
                        
                        <!--============= Break in echo - If Database pull = dropdown value add selected to attributes, otherwise continue echo ===========-->
    
                          <option class='available'      ";		if($row['status'] == 0)	{echo "selected = 'selected'";} echo" value='0'>Available</option>
    
                          <option class='cleaning'          ";		if($row['status'] == 1)	{echo "selected = 'selected'";} echo" value='1'>Cleaning</option>
    
                          <option class='mending'        ";		if($row['status'] == 2)	{echo "selected = 'selected'";} echo" value='2'>Mending</option>
    
                          <option class='notavailable'  ";		if($row['status'] == 3)	{echo "selected = 'selected'";} echo" value='3'>Not Available</option>

                          <option class='missing'  ";		if($row['status'] == 4)	{echo "selected = 'selected'";} echo" value='4'>Missing</option>
    
                        </select> 
                        </form>
    
    
                            <br>
                          <a href='propEdit.php?item_num=".$row['item_num']."&item_name=".$row['item_name']."'>
                            <button type='button' class='EditDeleteButtonPosition btnsm'>&nbsp&nbspEdit&nbsp&nbsp</button>
                          </a>
                            <br>
                            <button type='button' class='EditDeleteButtonPosition btnsm'>Duplicate</button>
                            <br>
                          <a id='ChangeUrl' onclick='notify()' href='propDelete.php?item_num=".$row['item_num']."&item_name=".$row['item_name']."'>
                          <button type='button' class='EditDeleteButtonPosition btnsmDelete'>Delete</button>
                          </a>
                          <p>
                          <b>Last date rented:</b><br>";//end echo

			              		  //Only display date if not null
			              		  if(empty($row['last_rent'])) {
			              			  echo "<br>";
			              		  } else {
			              			  echo date('d/M/Y', strtotime($row['last_rent'])) . "<br>";
			              		  }
			              		   echo"
			              		  <b>#Times Rented:</b> ".$row['rent_num']."
			              		</p>
			              	  </div>
			              	</div>
			               ";//End Echo
                }//Close while
            }//Close if

         echo "
         <script type='text/javascript'>
           window.location.hash = " . $id . ";
         </script> ";
?>

</body>


      
</html>