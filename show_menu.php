<?php
  include_once 'header.php';

if(!isset($_SESSION['user_id'])){
  header("Location: index.php");
}

$restaurantID = $_GET['id'];

if(isset($_POST['updatetables']))
{
  $guests =  $_POST['guests'];
  $number = $_POST['number'];   
  $repeat = 0;

  while($repeat < $number)
  {
    $statement = $db->prepare("INSERT INTO tables (restaurant_ID, guests) VALUES (:restaurant_ID, :guests)");
    $statement->execute([
      'restaurant_ID' => $restaurantID,
      'guests' => $guests,
    ]); 

    $repeat++;
  } 
}
?> 

<div class="portfolio section-padding" data-scroll-index='3'>
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12 section-title text-center">
        <h3>Jedilnik</h3>
        <span class="section-title-line"></span>
      </div>

      <div class="gallery no-padding col-lg-12 col-sm-12">
        <div class="restDiv browse">
          <div id="searchDiv">

            <table id="foodListTable">
              <?php
              $findFood = $db->prepare("SELECT f.name AS name, f.price AS price, f.description AS description, ft.type AS type FROM food f INNER JOIN food_types ft ON ft.ID=f.type_ID INNER JOIN restaurants r ON r.ID=f.restaurant_ID WHERE (f.restaurant_ID = :restaurantID)");
              $findFood->execute(array(':restaurantID' => $restaurantID));
              while ($rowFood = $findFood->fetch())
              {
                ?> 
                <tr>
                  <td class="food1" >
                    <?php
                      echo "<p class='f1'>" . $rowFood['name'] . "</p>" . $rowFood['type'];
                    ?>

                  <td class="food2">
                    <?php
                      echo $rowFood['description'];                     
                    ?>
                  </td>
                  <td class="food3">
                    <?php
                      echo $rowFood['price'] . " EUR" ;
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
  </div>
</div>

<?php 
  include_once 'footer.php';
?>