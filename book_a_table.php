<?php
include_once 'header.php';


if(isset($_SESSION['user_id'])){
  $userID = $_SESSION['user_id'];
}

$restaurantID = $_GET['id'];

$stmt = $db->prepare("SELECT r.name AS name, p.url AS url, r.bio AS bio, c.name AS city FROM pictures p INNER JOIN restaurants r ON p.restaurant_ID=r.ID INNER JOIN cities c ON c.ID=r.city_ID WHERE (r.ID = :restaurantID)");
$stmt->execute(array(':restaurantID' => $restaurantID));
$restaurant = $stmt->fetch();

if(isset($_POST['bookTable']))
{
  $guests = $_POST['guests'];

  $res1 = $_POST['res1'];
  $res2 = $_POST['res2'];
  $user = $_SESSION['user_id'];

  

  if($res2 == 0)
  {
    $res2 = new DateTime($res1);
    $res2->add(new DateInterval('PT2H0M0S'));
  }

  else if($res2 == 1)
  {
    $res2 = new DateTime($res1);
    $res2->add(new DateInterval('PT24H0M0S'));
  }

  $res2 = $res2->format('Y-m-d H:i:s');

  $gettableID = $db->prepare("SELECT t.ID as ID FROM tables t INNER JOIN restaurants r ON t.restaurant_ID=r.ID WHERE t.restaurant_ID = :restaurantID");
  $gettableID->execute(array(':restaurantID' => $restaurantID));
  while ($row = $gettableID->fetch())
  {
    $tableID = $row['ID'];
  }

  $statement = $db->prepare("INSERT INTO reservations (user_ID, table_ID, res_from, res_to, guests) VALUES (:user_ID, :table_ID, :res1, :res2, :guests)");
  $statement->execute([
    'user_ID' => $userID,
    'table_ID' => $tableID,
    'res1' => $res1,
    'res2' => $res2,
    'guests' => $guests,
  ]);
  
}

?>

<!-- Prikaz -->
<div class="portfolio section-padding" data-scroll-index='3'>
  <div class="container">
    <div class="row">
      <div class="col-lg-12 section-title text-center">
        <h3><?php echo $restaurant['name'] ?></h3>
        <span class="section-title-line"></span>
      </div>
      <div class="col-md-6 mb-50" style="margin-left: 25%;">
        
        <div class="section-info" style="border: 0px solid black; min-width: 400px !important;">
          <p> 
            <form method="POST" name="bookTable" target="_self">

              <div class="inputTitle" > Število oseb:</div> <input name="guests" placeholder="Število oseb" required="required" type="number" class="inputGray rez" style="width: 75%; display: inline-block;" />
            
              <div class="inputTitle"> Čas rezervacije:</div> <input name="res1" type="datetime-local" id="meeting-time" required="required" class="inputGray rez" style="width: 75%;"> 
             
              <div class="inputTitle"> Celodnevna rezervacija:</div>
              <div name="res2" required="required" class="inputGray" style="display:inline-block; background-color: white; width: 75%;">                  
                <input type="radio" id="YES" name="res2" value="1">
                <label for="YES">DA</label>
                <input type="radio" id="NO" name="res2" value="0">
                <label for="NO">NE</label>
              </div>
              

              <input type="submit" name="bookTable" value="Rezerviraj mizo" class="submitWhite"> 
            </form>
          </p>
        </div>

      </div>
    </div>
  </div>
</div>
<!-- End Prikaz -->

<?php 
  include_once 'footer.php';
?>
