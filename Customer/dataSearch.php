<!-- Calling JQuery Library -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<?php
     session_start();

  
    if(isset($_POST['getData'])) {

        include '../db_connect.php'; //Including database connection

        $start = $conn->real_escape_string($_POST['start']);

        $limit = $conn->real_escape_string($_POST['limit']);

        $search = $conn->real_escape_string($_POST['search']);
        //$search = $_SESSION['search'];

       

        function dateDisplay($rNum, $dateVal) {
             //Only display date if not null
					  if(empty($rNum)) {
                        return "<br>";
                    } else {
                        return date('d/M/Y', strtotime($dateVal)) . "<br>";
                    }
        }


        //Full Text Search
        $sql = $conn->query("SELECT * FROM customer
        WHERE MATCH(`first`,
                    `last`,
                    `address`,
                    `address_2`,
                    `city`,
                    `state`,
                    `zip`,
                    `phone`,
                    `altPhone`,
                    `email`,
                    `business`)
               AGAINST('$search')
               LIMIT $start, $limit
               ");


        //Find out the number of results stored in the database
        if ($sql->num_rows > 0) {

            $response = "";


            
            while ($row = $sql->fetch_array()) {
                
                
                $response .= "

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



                ";//End response text
            }//End while
                exit($response);
        } else {
            exit('');
        }


    }// End if getData Post


?>





