<!-- Calling JQuery Library -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<script>
function notify() {//This function does the delete confirmation dialog
	var $check = confirm('Are you sure you want to delete this item? \n Warning: Once you press OK you cannot undo this action...');
	
	if ($check == true) {
		//alert('Costume Deleted'); Do Nothing
	} else {
		document.getElementById('ChangeUrl').href = "OrderHistorySearchForm.php";
	}
}
</script>

<?php
     session_start();

  
    if(isset($_POST['getData'])) {

        include '../db_connect.php'; //Including database connection

        $start = $conn->real_escape_string($_POST['start']);

        $limit = $conn->real_escape_string($_POST['limit']);

        $search = $conn->real_escape_string($_POST['search']);
        //$search = $_SESSION['search'];

       

        function dateDisplay($dateVal) {
             //Only display date if not null
					  if($dateVal==0000-00-00) {
                        return "<br>";
                    } else {
                        return date('d/M/Y', strtotime($dateVal)) . "<br>";
                    }
        }


        //Full Text Search
        $sql = $conn->query("SELECT * FROM returns
        WHERE orderID LIKE '%$search%'
        OR date LIKE '%$search%'
        OR last LIKE '%$search%'
        OR first LIKE '%$search%'
        OR dDate LIKE '%$search%'
        ORDER BY orderID
        LIMIT $start, $limit
        ");


        //Find out the number of results stored in the database
        if ($sql->num_rows > 0) {

            $response = "";


            
            while ($row = $sql->fetch_array()) {
                
                
                $response .= "

                <div class='BottomDivideLine'>
                  <div class='CustomerlistLeftCol'>
                  <h3> <b>Order Number:</b>&nbsp".$row['orderID']."<br>
                    <b>Last Name:</b>&nbsp".$row['last']."<br>
                    <b>First Name:</b>&nbsp".$row['first']."<br>
                    <b>Checked-out:</b>&nbsp".$row['date']."<br>
                    <b>Return Date:</b>&nbsp".$row['dDate']."<br>
                    <b>Checked-in:</b>&nbsp".$row['inDate']."
                    </h3>
                  </div>
        
                  
              
                   
						<br>
                        <a href='../Returns/ReturnForm.php?id=".$row['id']."'>
                          <button type='button' class='EditDeleteButtonPosition btnsm'>Select</button>
                        </a>
                          <br>
  
                        <a id='ChangeUrl' onclick='notify()' href='OrderHistoryDelete.php?id=".$row['id']."'>
                        <button type='button' class='EditDeleteButtonPosition btnsmDelete'>Delete</button>
                        </a>
                      </div>




                ";//End response text
            }//End while
                exit($response);
        } else {
            exit('');
        }


    }// End if getData Post


?>





