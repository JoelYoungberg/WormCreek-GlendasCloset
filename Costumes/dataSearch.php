<!-- Calling JQuery Library -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<script>
function notify() {//This function does the delete confirmation dialog
	var $check = confirm('Are you sure you want to delete this item? \n Warning: Once you press OK you cannot undo this action...');
	
	if ($check == true) {
		//alert('Costume Deleted'); Do Nothing
	} else {
		document.getElementById('ChangeUrl').href = "CostumeSearchForm.php";
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

        //Turning dropdown status into functions to enable placement within html and response without breaking response apart!
        //Returns respective dropdown selection by returning selected = 'selected' from function per appropriate value in database!

        function statusAvail($status) {
            if($status == 0)	{return "selected = 'selected'";};
        } 

        function statusClean($status) {
            if($status == 1)	{return "selected = 'selected'";};
        } 

        function statusMend($status) {
            if($status == 2)	{return "selected = 'selected'";};
        } 

        function statusNot($status) {
            if($status == 3)	{return "selected = 'selected'";};
        } 

        function statusMiss($status) {
            if($status == 4)	{return "selected = 'selected'";};
        } 

        function statusColor($status) {
            switch($status){
                case 0:
                return "style='color: #022702'";//Green
                break;
                case 1:
                return "style='color: #ff9507'";//Orange
                break;
                case 2:
                return "style='color: #ff9507'";//Orange
                break;
                case 3:
                return "style='color: #760000'";//Red
                break;
                case 4:
                return "style='color: #760000'";//Red
                break;
                default:
                return "style='color: #022702'";//Green
            }//End Switch
        }

        function dateDisplay($rNum, $dateVal) {
             //Only display date if not null
					  if(empty($rNum)) {
                        return "<br>";
                    } else {
                        return date('d/M/Y', strtotime($dateVal)) . "<br>";
                    }
        }


        //Full Text Search
        $sql = $conn->query("SELECT * FROM costumes
        WHERE MATCH(`item_num`,
                    `item_name`,
                    `picture`,
                    `descr`,
                    `gender`,
                    `size`,
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
				      <h3 id='".$row['id']."'><b>Sku:</b>&nbsp".$row['item_num']."<br> <b>Type:</b>&nbsp".$row['item_name']."</h3>
                      <p> <b>Description:&nbsp</b> ".$row['descr']."<br>
                       <b>Gender:&nbsp</b> ".$row['gender']."<br> <b>Size:&nbsp</b> ".$row['size']."<br> <b>Color:&nbsp</b> ".$row['color']."<br>
					 					   <b>Location:&nbsp</b> ".$row['loc']."<br> <b>Price:&nbsp</b> ".$row['price']."</p>
					</div>

                    <div class='listImageCol'> <img class='ItemImageWidth' src='".$row['picture']."'></div> <!--Comment: Displaying Thumbnail image here-->
                    
                    <!--========================== Dropdown Edit and Delete Buttons ============================-->
					<div class='listRightCol'>
					<br>
	
					<form id='sForm' action='costumeDrop.php?item_num=".$row['item_num']."&item_name=".$row['item_name']."' method='POST'>
					<select ". statusColor($row['status']) ." id='statSelect' name='taskOption' onchange='{this.form.submit();}'><!--Note: a value attribute cannot be set here (security)-->
					
					<!--============= Break in echo - If Database pull = dropdown value add selected to attributes, otherwise continue echo ===========-->

					  <option class='available' ". statusAvail($row['status']) ." value='0'>Available</option>

					  <option class='cleaning' ". statusClean($row['status']) ." value='1'>Cleaning</option>

					  <option class='mending' ". statusMend($row['status']) ." value='2'>Mending</option>

					  <option class='notavailable' ". statusNot($row['status']) ." value='3'>Not Available</option>

					  <option class='missing' ". statusMiss($row['status']) ." value='4'>Missing</option>

					</select> 
                    </form>

                    <br>
					  <a href='costumeEdit.php?item_num=".$row['item_num']."&item_name=".$row['item_name']."'>
					    <button type='button' class='EditDeleteButtonPosition btnsm'>&nbsp&nbspEdit&nbsp&nbsp</button>
					  </a>
                        <br>
                        <button type='button' class='EditDeleteButtonPosition btnsm'>Duplicate</button>
                        <br>
					  <a id='ChangeUrl' onclick='notify()' href='costumeDelete.php?item_num=".$row['item_num']."&item_name=".$row['item_name']."'>
					  <button type='button' class='EditDeleteButtonPosition btnsmDelete'>Delete</button>
					  </a>
					  <p>
					  <b>Last date rented:</b><br>
                      ". dateDisplay($row['rent_num'], $row['last_rent']) ."
                      <b>#Times Rented:</b> ".$row['rent_num']."
                      <p id='ajaxTest'></p>
					</p>
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





