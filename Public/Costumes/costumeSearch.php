<?php //Note: This includes database connection code
  session_start();
 
  include '../../db_connect.php';
?>

<!DOCTYPE html>
<html lang="en-us" id="CostumePageBG">
<head>
    <title>Costume Search</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../../style.css">

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
<a href="../../index.php"><div title="Back" class="iconback"></div></a>
</div>
</div>

<div class="border lighten">
<div>
<?php

   //Security Check against mysql injection
     
        $search = mysqli_real_escape_string($conn, $_POST['search']);


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

