<!-- Calling JQuery Library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<?php


  
    if(isset($_POST['getData'])) {

        include '../db_connect.php'; //Including database connection

        $start = $conn->real_escape_string($_POST['start']);

        $limit = $conn->real_escape_string($_POST['limit']);


        function dateDisplay($dateVal) {
             //Only display date if not null
					  if($dateVal==0000-00-00) {
                        return "<br>";
                    } else {
                        return date('Y-m-d', strtotime($dateVal)) . "<br>";
                    }
        }

        $sql = $conn->query("SELECT *
                            FROM orders
                            LIMIT $start, $limit
                            ");

        if ($sql->num_rows > 0) {

            $response = "";

			while ($row = $sql->fetch_array()) {
            
                $response .= " 
                <div class='BottomDivideLine'>
				    <div class='CustomerlistLeftCol'>
					  <h3><b>Order Number:</b> ".$row['id']."<br>
					      <b>Last Name:</b>&nbsp".$row['last']."<br>
						  <b>First Name:</b>&nbsp".$row['first']."<br>
						  <b>Checkout Date:</b>&nbsp".$row['date']."<br>
					      <b>Return Date:</b>&nbsp".dateDisplay($row['dDate'])."
					  </h3>
					</div>

					
			
						<br>
					  <a href='../Returns/ReturnForm.php?id=".$row['id']."&return=true'>
						<button name='return' type='button' class='EditDeleteButtonPosition btnsm'>Return</button>
					  </a>
						<br>

					  <a id='ChangeUrl' onclick='notify()' href='OpenOrdersDelete.php?id=".$row['id']."'>
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





