<!-- Calling JQuery Library -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<?php
     session_start();

  
    if(isset($_POST['getData'])) {

        include '../../db_connect.php'; //Including database connection

        $start = $conn->real_escape_string($_POST['start']);

        $limit = $conn->real_escape_string($_POST['limit']);

        $search = $conn->real_escape_string($_POST['search']);
        //$search = $_SESSION['search'];

        //Turning dropdown status into functions to enable placement within html and response without breaking response apart!
        //Returns respective dropdown selection by returning selected = 'selected' from function per appropriate value in database!


        function dateDisplay($rNum, $dateVal) {
             //Only display date if not null
					  if(empty($rNum)) {
                        return "<br>";
                    } else {
                        return date('d/M/Y', strtotime($dateVal)) . "<br>";
                    }
        }


        //Full Text Search
        $sql = $conn->query("SELECT * FROM props
        WHERE MATCH(`item_num`,
                    `item_name`,
                    `picture`,
                    `descr`,
                    `color`,
                    `loc`,
                    `status_word`)
              AGAINST('$search')
              LIMIT $start, $limit
              ");


        //Find out the number of results stored in the database
        if ($sql->num_rows > 0) {

            $response = "";


            
            while ($row = $sql->fetch_array()) {
                
                
                $response .= "

                <div class='BottomDivideLine'>
                        <div class='listLeftCol'>
                          <h3><b>Sku:</b>&nbsp".$row['item_num']."<br> <b>Type:</b>&nbsp".$row['item_name']."</h3>
                          <p> <b>Description:&nbsp</b> ".$row['descr']."<br>
                          <b>Color:&nbsp</b> ".$row['color']."<br>
                          <b>Location:&nbsp</b> ".$row['loc']."<br> <b>Price:&nbsp</b> ".$row['price']."</p>
                        </div>
    
                        <div class='listImageCol'> <img class='ItemImageWidth' src='../".$row['picture']."'></div> <!--Comment: Displaying Thumbnail image here-->
    
                       
                        <div class='listRightColPublic'>
                        
                        <div class='PublicListRightText'>
                        <h3><b>Status:</b><br>".$row['status_word']."</h3>
                            </div>

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





