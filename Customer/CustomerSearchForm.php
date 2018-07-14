<?php //Database Connection
		session_start();
		if(isset($_SESSION['u_id']) || isset($_SESSION['u_email'])){
			//If someone is logged in Do nothing here/Continue
		} else {
				header("Location: ../login.php?login=error");//Otherwise return to login screen
				exit();
		}
		include '../db_connect.php';
		
		//Clear search related session variables when returning to this page
		$_SESSION['search'] = '';
		$_SESSION['id'] = '';
?>

<!DOCTYPE html>
<html lang="en-us" id="CustomerPageBG">
<html>
<head>
    <title>Customer Search</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../style.css">

<script>
function notify() {//This function does the delete confirmation dialog
	var $check = confirm('Are you sure you want to delete this Customer? \n Warning: Once you press OK you cannot undo this action...');
	
	if ($check == true) {
		//alert('Customer Deleted'); Do Nothing
	} else {
		document.getElementById('ChangeUrl').href = "CustomerSearchForm.php";
	}
}
</script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
    $(".confirmation").fadeOut(10000);
    });
</script>

</head>
<body>

<div class="float-panel">
<form class="formfix" action="customerSearch.php" method="POST">
    <div class="searchBGcolor">
      <input class="searchbarshift" type="text" name="search" size="60px" placeholder="Customer Search">   
	  	</div> 
	<button class="btnSearchPlace btnSearch" type="submit" name="submit-search">Search</button> <!--Search Button-->    
 
	<button class="btnResetPlace btnReset" name="reset-search" formaction="reset.php" >Reset</button> <!--Reset Search Button--> 
</form>

<div class="buttonline">
<a href="../OpeningPage/OpenPage.php"><div title="Back" class="iconback"></div></a>
<a href="../OpeningPage/OpenPage.php"><div title="Home" class="iconhome"></div></a>
<!--Add Customer Button Note that this button is just a link and must remain outside the form -->
<a href="../Customer/customer_input.php"><button class="mediumbtn addButtonplace">Add Customer</button></a>

<?php
//If there is a name stored in the message variable - output it temporarily here to confirm it was added
if(!empty($_SESSION['message'])) {
	$message = $_SESSION['message'];
	echo "
	  <div class='confirmation'>
		Customer " . $message . " has been added
	  </div>
	";
}
?>

</div>
</div>

<!-- ======================= 
         Results Window 
================================ -->
<div class="border lighten">
	
<!--//////////////////////////////////// AJAX response container /////////////////////////////////////////////////////////////-->
<span class="results"></span> <!--This is in the data.php file -->
<!--//////////////////////////////////// AJAX response container /////////////////////////////////////////////////////////////-->

</div><!-- Close Border Lighten -->


<!-- JQuery/AJAX script for infinite scroll -->
<script>
		//Global variables
		var start = 0;
		var limit = 25;
		var reachedMax = false;

		$(window).scroll(function() {
			if ($(window).scrollTop() == $(document).height() - $(window).height())
				getData();
		});

			$(document).ready(function() { //Waiting for page to load first
				getData();
			});

			function getData() {
				//If we are at the end of the data - stop performing ajax call
				if(reachedMax)
					return;

					//Perform ajax call
					$.ajax({
						url: 'data.php',
						method: 'POST',
						dataType: 'text',
						data: {
							getData: 1,
							start: start,
							limit: limit
						},
						 success: function(response) {
							 if (response == "reachedMax")
							 	 reachedMax = true;
								  else {
									  start += limit;
									  $(".results").append(response);
								  }
						 }
					});
			}//End function getData
		</script>


</body>

<?php
//Clear session variable to stop multiple outputs
$_SESSION['message'] = '';
?>

</html>