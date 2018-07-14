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
                            FROM missing
                            LIMIT $start, $limit
                            ");

        if ($sql->num_rows > 0) {

            $response = "";

			while ($row = $sql->fetch_array()) {

                //Date Calculation
						 $curDate = date('M d, Y');
						 $dDate = $row['dDate'];

						 //Creating Date Time Objects
						 $curDate = new DateTime($curDate);
						 $dDate = new DateTime($dDate);

						 $days = $curDate->diff($dDate);


            
                $response .= " 
                <div class='MissingBottomDivideLine'>

					<div class='MissinglistLeftCol'>
					  <h3>
					     <b>Last Name</b>&nbsp".$row['last']."<br>
					     <b>First Name:</b>&nbsp".$row['first']."<br>
						 <b>Company:</b>&nbsp".$row['business']."<br>
						 <b>Phone:</b>&nbsp".$row['phone']."<br>
						 <b>Email:</b>&nbsp".$row['email']."<br><br>
						 <b>Checked-out:</b>&nbsp".date('M d, Y', strtotime($row['date'])) ."<br>
						 <b>Return Date:</b>&nbsp".date('M d, Y', strtotime($row['dDate'])) ."<br>


				    <b>Missing:</b>&nbsp" . $days->days . " day(s)
					  </h3>
 					</div>

					<div class='MissinglistImageCol'>
				
						<b>Sku:</b>&nbsp<span class='MissingSku'>".$row['item']."</span><br>
					
						<img class='MissingItemImageWidth' src=".$row['pic']."><br><br>
				
				    </div>

				    <div class='MissinglistRightCol'>
				   	   
					  <br>
					  <a id='ChangeUrl' onclick='notifyA()' href='MissingAvailable.php?item=".$row['item']."'>
					  <button type='button' class='MissingReturnButtonPosition btnsm'>Return Available</button>
					  </a>
					  <br>

					  <a id='ChangeUrl' onclick='notifyC()' href='MissingCleaning.php?item=".$row['item']."'>
					  <button type='button' class='MissingReturnButtonPosition btnsm'>Return Cleaning</button>
					  </a>
					  <br>

					  <a id='ChangeUrl' onclick='notifyM()' href='MissingMending.php?item=".$row['item']."'>
					  <button type='button' class='MissingReturnButtonPosition btnsm'>Return Mending</button>
					  </a>
					  <br>

					  <a id='ChangeUrl' onclick='notifyD()' href='MissingDelete.php?item=".$row['item']."'>
					  <button type='button' class='MissingReturnButtonPosition btnsmDelete'>Delete</button>
					  </a>

					  </div>
					</div>

                ";//End response text
            }//End while
                exit($response);
        } else {
            exit('');
        }


    }// End if getData Post


?>





