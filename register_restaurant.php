<?php
	include_once 'header.php';

	if(!isset($_SESSION['user_id'])){
  		header("Location: index.php");
	}

	$userID = $_SESSION['user_id'];
	$regOK = 0;


	if(isset($_POST['RegisterRestaurant']))
	{		
		$name = $_POST['name'];    
		$bio = $_POST['bio'];
		$cityID = $_POST['city'];
		$delivery = $_POST['delivery'];


		$check = $db->prepare("SELECT * FROM restaurants WHERE (name = :name) AND (city_ID = :city_ID)");
		$check->execute(array(':name' => $name, ':city_ID' => $cityID));
		$count = $check->fetchColumn();

		if($count >= 1)
		{
			header("Location: index.php");
    		die();
		}

	  //echo $delivery;
	  //die();

		//skripta iz W3Schools
		$maxsize = 50000000000;
		$allowedExts = array("gif", "jpeg", "jpg", "png", "jfif");
		//razbije ime datoteke, ki jo naložiš - deli jo glede na "."
		$temp = explode(".", $_FILES["file"]["name"]);
		$extension = end($temp);
		if ((($_FILES["file"]["type"] == "image/gif")
		        || ($_FILES["file"]["type"] == "image/jpeg")
		        || ($_FILES["file"]["type"] == "image/jpg")
		        || ($_FILES["file"]["type"] == "image/pjpeg")
		        || ($_FILES["file"]["type"] == "image/x-png")
		        || ($_FILES["file"]["type"] == "image/png"))
		        && ($_FILES["file"]["size"] < $maxsize)
		        && in_array($extension, $allowedExts)) 
		{

		    $newName = date("Ymdhisu") . '-' . $_FILES["file"]["name"];
		    move_uploaded_file($_FILES["file"]["tmp_name"], "images/restaurants/" . $newName);
		    $picture = 'images/restaurants/' . $newName;
		    //uspešno smo naložili sliko
		    

		    //echo "name: " . $name;
		    //echo " bio: " . $bio;
		    //echo " city " . $cityID;
		    //echo " delivery: " . $deliveryNumeric;
		    //die();


		    //zapis v bazo
		    $statement = $db->prepare("INSERT INTO restaurants (name, bio, approved, delivery, city_ID) VALUES (:name, :bio, :approved, :delivery, :city_ID)");
		    $statement->execute([
		    	'name' => $name,
		        'bio' => $bio,
		        'delivery' => $delivery,
		        'city_ID' => $cityID,
		        'approved' => '0',
		    ]);

		    

		    //dobimo zapisan ID restavracije
		    $rest_ID = $db->prepare("SELECT * FROM restaurants WHERE (name = :name) AND (city_ID = :city_ID)");
			$rest_ID->execute(array(':name' => $name, ':city_ID' => $cityID));
			$restID = $rest_ID->fetch();

			//zapišemo sliko v bazo
		    $statement2 = $db->prepare("INSERT INTO pictures (url, restaurant_ID) VALUES (:url, :restaurant_ID)");
		    $statement2->execute([
		    	'url' => $picture,
		        'restaurant_ID' => $restID['ID'],
		    ]);

 
		    //zapišemo še lastnika
		    $statement3 = $db->prepare("INSERT INTO restaurant_owners (user_ID, restaurant_ID) VALUES (:userID, :restaurantID)");
		    $statement3->execute([
		    	'userID' => $userID,
		        'restaurantID' => $restID['ID'],
		    ]);
		}

		$regOK = 2;
	}
	else{
		$regOK = 0;
	}


	?> 

<!-- Register Restaurant -->
<div class="about-us section-padding" data-scroll-index='1'>
  <div class="container">
    <div class="row">
      <div class="col-md-12 section-title text-center">
        <h3>REGISTRIRAJ RESTAVRACIJO</h3>
        <span class="section-title-line"></span>
      </div>
      <div class="col-md-6 mb-50" style="margin-left:25%; text-align: center;">
        <div class="section-info">
          <?php
          if($regOK == 1)
          {
            ?><p style="color:red;">Registracija neuspešna!</p><?php
          }
          else if($regOK == 2)
          {
          	{
            ?><p style="color:green;">Registracija uspešna!</p><?php
          }
          }
          ?>

          <p> 
            <form method="POST" name="RegisterRestaurant" target="_self" enctype="multipart/form-data">

            	<input name="name" placeholder="Ime Restavracije" required="required" type="text" class="inputWhite" />
            	<br/>
            	<textarea name="bio" style="width: 100%; max-width: 100%; border: 1px solid #ccc;" rows="5"></textarea>
            	<br/>

            	<select id="" name="city">
	            	<?php
	                $stmt = $db->query("SELECT * FROM cities ORDER BY name ASC");
	                while ($row = $stmt->fetch())
	                {
	                  
	                  echo "<option value=" . $row['ID'] . ">" . $row['name'] . "</option>";
	                }
	              	?>
              	</select>

            	<br>
            	<div class="">
	            	Ponujamo dostavo:
	              
	            	<input type="radio" id="YES" name="delivery" value="1">
					<label for="YES">DA</label>
					<input type="radio" id="NO" name="delivery" value="0">
					<label for="NO">NE</label>
				</div>

				<input type="file" name="file" required="required"/></td><td style="width:100%;">

            	<input type="submit" name="RegisterRestaurant" value="Pošlji prošnjo za registracijo" class="submitGray">

            </form>
          </p>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- End Register Restaurant -->

<?php 

    include_once 'footer.php';
?>