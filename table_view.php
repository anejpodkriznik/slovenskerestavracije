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
        <h3>Uredi mize</h3>
        <span class="section-title-line"></span>
      </div>

      <div class="gallery no-padding col-lg-12 col-sm-12">
        <div class="restDiv browse">
          <div id="searchDiv">

            <table id="tableListTable">
              <?php
              $stmt = $db->prepare("SELECT COUNT(guests) AS gnumber, guests, t.ID AS tid FROM tables t INNER JOIN restaurants r ON t.restaurant_ID=r.ID WHERE(t.restaurant_ID = :restaurantID) GROUP BY guests;");
              $stmt->execute(array(':restaurantID' => $restaurantID));
              while ($row = $stmt->fetch())
              {
                ?> 
                <tr>
                  <td class="tableListTableCol1">
                    <?php
                      echo "Miza za " . $row['guests'] . " oseb";
                    ?>
                  </td>
                  <td class="tableListTableCol2">
                    <?php
                      echo "Število miz: " . $row['gnumber'];
                    ?>
                  </td>
                  <td class="tableListTableCol3">
                    <a href="edit_table.php?id=<?php echo $restaurantID; ?>&guests=<?php echo $row['guests'] ?>&value=1&tid=<?php echo $row['tid']; ?>"><img src='images/plus.png' class='tableEditImg'></a>
                    <a href="edit_table.php?id=<?php echo $restaurantID; ?>&guests=<?php echo $row['guests'] ?>&value=2&tid=<?php echo $row['tid']; ?>"><img src='images/minus.png' class='tableEditImg'></a>
                    <a href="edit_table.php?id=<?php echo $restaurantID; ?>&guests=<?php echo $row['guests'] ?>&value=3&tid=<?php echo $row['tid']; ?>"><img src='images/delete.png' class='tableEditImg'></a>
                  </td>
                </tr>
                <?php
              }
              ?>
              <tr>
                <form method="POST" name="updatetables" target="_self"> 
                  <td>
                    <input name="guests" placeholder="Število oseb" required="required" type="number" class="inputNumber" />
                  </td>
                  <td>
                    <input name="number" placeholder="Število miz" required="required" type="number" class="inputNumber" />
                  </td>
                  <td>
                    <input type="submit" name="updatetables" value="Dodaj mize" class="inputNumber"> 
                  </td>
                </form>
              </tr>
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