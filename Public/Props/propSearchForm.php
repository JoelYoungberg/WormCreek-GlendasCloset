<?php //Database Connection
		session_start();
		
		include '../../db_connect.php';
		//Clear search related session variables when returning to this page
		$_SESSION['search'] = '';
		$_SESSION['id'] = '';
?>

<!DOCTYPE html>
<html lang="en-us" id="PropPageBG">
<html>
<head>
    <title>Prop Search</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../../style.css">

<script>
function notify() {//This function does the delete confirmation dialog
	var $check = confirm('Are you sure you want to delete this item? \n Warning: Once you press OK you cannot undo this action...');
	
	if ($check == true) {
		//alert('Costume Deleted'); Do Nothing
	} else {
		document.getElementById('ChangeUrl').href = "propSearchForm.php";
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
<form class="formfix" action="propSearch.php" method="POST">
    <div class="searchBGcolor">
      <input class="searchbarshift" type="text" name="search" size="60px" placeholder="Prop Search">   
	  	</div> 
	<button class="btnSearchPlace btnSearch" type="submit" name="submit-search">Search</button> <!--Search Button-->    
 
	<button class="btnResetPlace btnReset" name="reset-search" formaction="reset.php" >Reset</button> <!--Reset Search Button-->
</form>

<div class="buttonline">
<a href="../../index.php"><div title="Back" class="iconback"></div></a>
</div>
</div>

<?php
//If there is a name stored in the message variable - output it temporarily here to confirm it was added
if(!empty($_SESSION['message'])) {
	$message = $_SESSION['message'];
	echo "
	  <div class='confirmation'>
		Prop " . $message . " has been added
	  </div>
	";
}
?>
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