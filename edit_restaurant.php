<?php
include_once 'header.php';

if(!isset($_SESSION['user_id'])){
  header("Location: index.php");
}

$restaurantID = $_GET['id'];

//poizvedba restavracije
$stmt = $db->prepare("SELECT r.name AS name, p.url AS url, r.bio AS bio, c.id AS city, r.approved AS approved, r.delivery AS delivery FROM pictures p INNER JOIN restaurants r ON p.restaurant_ID=r.ID INNER JOIN cities c ON c.ID=r.city_ID WHERE (r.ID = :restaurantID)");
$stmt->execute(array(':restaurantID' => $restaurantID));
$restaurant = $stmt->fetch();

//shranimo vrednosti poizvedbe
$name = $restaurant['name'];
$bio = $restaurant['bio'];
$approved = $restaurant['approved'];
$delivery = $restaurant['delivery'];
$cityID = $restaurant['city'];

//posodobitev info
if(isset($_POST['updateinfo']))
{
  $name =  $_POST['name'];
  $bio = $_POST['bio'];    
  //$cityID = $_POST['city'];   
  $delivery = $_POST['delivery'];

  $check = $db->prepare("SELECT * FROM restaurants WHERE (name = :name) AND (city_ID = :city_ID) AND (ID != :id)");
  $check->execute(array(':name' => $name, ':city_ID' => $cityID, ':id' => $restaurantID));
  $count = $check->fetchColumn();

  //če že obstaja javi napako
  if($count >= 1)
  {
    $ChangeStatus = 2;
  }
  else
  {
    if(!isset($_FILES['file']) || $_FILES['file']['error'] == UPLOAD_ERR_NO_FILE)
    {
        //zapis v bazo
        $statement = $db->prepare("UPDATE restaurants SET name = :name, bio = :bio, delivery = :delivery, city_ID = :city_ID WHERE(ID = :id)");
        $statement->execute([
            'id' => $restaurantID,
            'name' => $name,
            'bio' => $bio,
            'delivery' => $delivery,
            'city_ID' => $cityID,
        ]);
        $ChangeStatus = 1;
    }
    else
    {
        //skripta iz W3Schools
    $maxsize = 5000000000;
    $allowedExts = array("gif", "jpeg", "jpg", "png");
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


        //zapis v bazo
        $statement = $db->prepare("UPDATE restaurants SET name = :name, bio = :bio, delivery = :delivery, city_ID = :city_ID WHERE(ID = :id)");
        $statement->execute([
            'id' => $restaurantID,
            'name' => $name,
            'bio' => $bio,
            'delivery' => $delivery,
            'city_ID' => $cityID,
        ]);


        //dobimo zapisan ID restavracije
        $rest_ID = $db->prepare("SELECT * FROM restaurants WHERE (name = :name) AND (city_ID = :city_ID)");
        $rest_ID->execute(array(':name' => $name, ':city_ID' => $cityID));
        $restID = $rest_ID->fetch();

        $deletePic = $db->prepare("DELETE FROM pictures WHERE (restaurant_ID = :id)");
        $deletePic->execute([
            'id' => $restaurantID,
        ]);

      //zapišemo sliko v bazo
        $statement2 = $db->prepare("INSERT INTO pictures (url, restaurant_ID) VALUES (:url, :restaurant_ID)");
        $statement2->execute([
          'url' => $picture,
            'restaurant_ID' => $restID['ID'],
        ]);
      }
    $ChangeStatus = 1;
    }
    
  }
}

?>

<!-- Rezervacije -->
<div class="about-us section-padding bg-grey" data-scroll-index='1'>
  <div class="container">
    <div class="row">
      <div class="col-md-12 section-title text-center" style="" >
        <h3>Pregled rezervacij</h3>
        <span class="section-title-line"></span>
      </div>
      <?php
          if(isset($ChangeStatus))
            {
              if($ChangeStatus == 1)
              {
                echo "<div id='passSuccess'>Podatki posodobljeni</div><br>";
              }
              else if($ChangeStatus == 2)
              {
                echo "<div id='passErr'>Napaka</div><br>";
              }
            }
            ?>

      <div class="bookingView">
        
        <table id="bookingTable">
              <?php
              $stmt = $db->prepare("SELECT u.name as name, u.surname AS surname, r.guests AS guests, res_from AS res1, res_to AS res2 FROM reservations r INNER JOIN tables t ON t.ID=r.table_ID INNER JOIN users u ON u.ID=r.user_ID WHERE t.restaurant_ID = :restaurantID");
              $stmt->execute(array(':restaurantID' => $restaurantID));
              while ($row = $stmt->fetch())
              {
                ?> 
                <tr>
                  <td class="bookingTabletd1">
                    <?php
                      echo $row['name'] . " " . $row['surname'];
                    ?>
                  </td>
                  <td class="bookingTabletd2">
                    <?php
                      echo "Št. oseb: " . $row['guests'];
                    ?>
                  </td>
                  <td class="bookingTabletd3">
                    <?php
                      echo $row['res1'] . " - " . $row['res2'] ;
                    ?>
                  </td>
                </tr>
                <?php
              }
              ?>
            </table>
        
      </div>     
    </div>
  </div>
</div>
<!-- Rezervacije --> 


<!-- Edit restaurant -->
<div class="about-us section-padding" data-scroll-index='1'>
  <div class="container">
    <div class="row">
      <div class="col-md-12 section-title text-center" style="" >
        <h3>Urejanje restavracije</h3>
        <span class="section-title-line"></span>
      </div>
      <?php
          if(isset($ChangeStatus))
            {
              if($ChangeStatus == 1)
              {
                echo "<div id='passSuccess'>Podatki posodobljeni</div><br>";
              }
              else if($ChangeStatus == 2)
              {
                echo "<div id='passErr'>Napaka</div><br>";
              }
            }
            ?>

      <div class="col-md-6 mb-50" style="margin-left: 25%;">
        
        <div class="section-info" style="border: 0px solid black; min-width: 400px !important;">
          <p> 
            <form method="POST" name="updateinfo" target="_self" enctype="multipart/form-data">
              <div class="inputTitle" > Ime:</div> <input name="name" value="<?php echo $name; ?>" required="required" type="text" class="inputWhite" style="width: 75%; display: inline-block;"/>

            
              <div class="inputTitle" style="vertical-align: top;"> Opis:</div> <textarea name="bio" style="width: 75%; display: inline-block; border: 1px solid #ccc;" rows="10"><?php echo $bio; ?></textarea>
           
             <!-- <div class="inputTitle"> Mesto:</div>
              <select id="" name="city" style="width: 75%; display: inline-block;">
                <?php
                  $stmt = $db->query("SELECT * FROM cities ORDER BY name ASC");
                  while ($row = $stmt->fetch())
                  {
                    
                    //echo "<option value=" . $row['ID'] . ">" . $row['name'] . "</option>"; ?>
                    <option value="<?php echo $row['ID']; ?>" <?php if($row['ID']==$cityID){ echo "selected"; } ?>> <?php echo $row['name'];  ?></option>
                    <?php
                  }
                  ?>
                </select>-->
                <div class="inputTitle" class="inputTitle"> Dostava:</div>
                <div style="width: 75%; display: inline-block;">
                  <input type="radio" id="YES" name="delivery" value="1" <?php if($delivery == 1){ echo "checked"; }  ?>>
                    <label for="YES">DA</label>
                    <input type="radio" id="NO" name="delivery" value="0" <?php if($delivery == 0){ echo "checked"; }  ?>>
                    <label for="NO">NE</label>
                </div>
                <div><input type="file" name="file" /></td><td style="width: 100%;">
                </div>
                

              <input type="submit" name="updateinfo" value="Posodobi podatke" class="submitWhite"> 
            </form>
            <a href="table_view.php?id=<?php echo $restaurantID ?>" class="editHref">
              <button class="editButton"> Uredi mize </button>
            </a>
            <a href="edit_menu.php?id=<?php echo $restaurantID ?>" class="editHref">
              <button class="editButton"> Uredi meni </button>
            </a>
          </p>
        </div>
      </div>     
    </div>
  </div>
</div>
<!-- End edit restaurant --> 

<?php
    include_once 'footer.php';
?>