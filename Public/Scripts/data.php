<!-- Calling JQuery Library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<?php


  
    if(isset($_POST['getData'])) {

        include '../../db_connect.php'; //Including database connection

        $start = $conn->real_escape_string($_POST['start']);

        $limit = $conn->real_escape_string($_POST['limit']);

        //Turning dropdown status into functions to enable placement within html and response without breaking response apart!
        //Returns respective dropdown selection by returning selected = 'selected' from function per appropriate value in database!


        function dateDisplay($rNum, $dateVal) {
             //Only display date if not null
					  if(empty($rNum)) {
                        return "<br>";
                    } else {
                        return date('Y-m-d', strtotime($dateVal)) . "<br>";
                    }
        }

        $sql = $conn->query("SELECT *
                            FROM scripts
                            LIMIT $start, $limit
                            ");

        if ($sql->num_rows > 0) {

            $response = "";

			while ($row = $sql->fetch_array()) {
            
                $response .= " 
                <div class='BottomDivideLine'>
				    <div class='listLeftCol'>
				      <h3 id='".$row['id']."'><b>Sku:</b>&nbsp".$row['item_num']."<br> <b>Type:</b>&nbsp".$row['item_name']."</h3>
                      <p> <b>Description:&nbsp</b> ".$row['descr']."<br>
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





