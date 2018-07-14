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
    <title>Costume Search</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../style.css">
    <!-- Calling JQuery Library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>




<body>


<div class="float-panel">
<form class="formfix" action="costumeSearch.php" method="POST">
    <div class="searchBGcolor">
      <input class="searchbarshift" type="text" name="search" size="60px" placeholder="Costume Search">   
	  	</div> 
	<button class="btnSearchPlace btnSearch" type="submit" name="submit-search">Search</button> <!--Search Button-->    
 
  <button class="btnResetPlace btnReset" name="reset-search" formaction="reset.php" >Reset</button> <!--Reset Search Button-->
</form>

<div class="buttonline">
<a href="../Inventory/Inventory.php"><div title="Back" class="iconback"></div></a>
<a href="../OpeningPage/OpenPage.php"><div title="Home" class="iconhome"></div></a>
<!--Add Costume Button Note that this button is just a link and must remain outside the form -->
<a href="../Costumes/costume_input.php"><button class="mediumbtn addButtonplace">Add Costume</button></a>
</div>
</div>

<div class="border lighten">
<div class="customer-container">

<?php
//fetching id and reloading page to get location of last edited item/status
$search = $_SESSION['search'] ?? '';
$id = $_SESSION['id'] ?? '';



//Security Check against mysql injection
if(isset($_POST['submit-search'])){
  $search = mysqli_real_escape_string($conn, $_POST['search']);
}

  //Setting search criteria whithin session array as search...
  $_SESSION['search'] = $search;

?>

  <!--//////////////////////////////////// AJAX response container /////////////////////////////////////////////////////////////-->
        <span class="results"></span> <!--This is in the data.php file -->
      <!--//////////////////////////////////// AJAX response container /////////////////////////////////////////////////////////////-->

</div><!--End Border Lighten-->

		<!-- JQuery/AJAX script for infinite scroll -->
		<script>
		//Global variables
		var start = 0;
		var limit = 25;
		var reachedMax = false;
    var search = '<?php echo $search ?>'

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
						url: 'dataSearch.php',
						method: 'POST',
						dataType: 'text',
						data: {
							getData: 1,
							start: start,
							limit: limit,
              search: search
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
</html>

<?php
 echo "
 <script type='text/javascript'>
   window.location.hash = " . $id . ";
 </script> ";
ob_end_flush();
?>